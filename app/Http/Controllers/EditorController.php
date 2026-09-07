<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\Design;
use App\Models\DesignPage;

class EditorController extends Controller
{
    public function index(Request $request)
    {
        $designCode = $request->query('design_code');
        
        if (!$designCode) {

            // Generate new draft
            $lastDesign = Design::orderBy('id', 'desc')->first();
            $nextId = $lastDesign ? $lastDesign->id + 1 : 1;
            $newCode = 'MB-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            
            $template = \App\Models\Template::find($request->query('template_id'));
            
            $design = Design::create([
                'design_code' => $newCode,
                'template_id' => $request->query('template_id'),
                'book_size' => $template ? $template->book_size : 'A5',
                'status' => 'draft',
                'total_pages' => 1
            ]);
            
            $template = \App\Models\Template::find($request->query('template_id'));
            
            // COVER PAGE
            $design->pages()->create([
                'page_number' => 0,
                'layout_type' => 'cover',
                'photos' => $template ? $template->layout_structure : null
            ]);
            
            // ISI PAGE 1
            $design->pages()->create([
                'page_number' => 1,
                'layout_type' => '1_foto',
                'photos' => null
            ]);
            
            return redirect('/editor?design_code=' . $newCode);
        }

        $design = Design::with(['pages' => function($q) {
            $q->orderBy('page_number');
        }, 'template'])->where('design_code', $designCode)->firstOrFail();
        
        $gallery = $design->photos->map(function($p) { return url('/media/' . $p->stored_path); })->toArray();
        
        return view('editor', compact('design', 'gallery'));
    }
    
    public function preview(Request $request)
    {
        $designCode = $request->query('design_code');
        
        if (!$designCode) {
            return redirect('/');
        }

        $design = Design::with(['pages' => function($q) {
            $q->orderBy('page_number');
        }, 'template'])->where('design_code', $designCode)->firstOrFail();
        
        return view('preview', compact('design'));
    }

    public function serveMedia($type, $filename)
    {
        $path = $type . '/' . $filename;
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return response()->file(storage_path('app/public/' . $path));
        }
        abort(404);
    }
    
    public function uploadAsset(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'design_code' => 'required'
        ]);
        
        $design = Design::where('design_code', $request->design_code)->firstOrFail();
        
        $file = $request->file('image');
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_.\-]/', '', $file->getClientOriginalName());
        
        // Save to storage/app/public/memorybook
        $path = $file->storeAs('memorybook', $filename, 'public');
        
        $photo = $design->photos()->create([
            'original_filename' => $filename,
            'stored_path' => $path,
            'size' => $file->getSize(),
        ]);
        
        return response()->json([
            'url' => url('/media/' . $path)
        ]);
    }
    
    public function saveDesign(Request $request)
    {
        $design = Design::where('design_code', $request->design_code)->firstOrFail();
        
        if ($request->has('book_size')) {
            $design->book_size = $request->book_size;
        }
        
        if ($request->has('pages')) {
            $pagesData = $request->pages; // array of pages
            $design->total_pages = count($pagesData);
            $design->save();
            
            $pageIds = collect($pagesData)->pluck('id')->toArray();
            // Delete pages not in the list
            $design->pages()->whereNotIn('page_number', $pageIds)->delete();
            
            foreach ($pagesData as $page) {
                $design->pages()->updateOrCreate(
                    ['page_number' => $page['id']],
                    [
                        'layout_type' => $page['layout'],
                        'photos' => $page['json']
                    ]
                );
            }
        } else {
            $design->save();
        }
        
        return response()->json(['status' => 'success']);
    }
    
    public function submitOrder(Request $request)
    {
        $request->validate([
            'design_code' => 'required',
            'customer_name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'required|string',
            'book_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $design = Design::with(['pages', 'photos'])->where('design_code', $request->design_code)->firstOrFail();
        
        // Generate Order Code
        $lastOrder = \App\Models\Order::orderBy('id', 'desc')->first();
        $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
        $orderCode = 'ORD-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        // Create a snapshot of the design for this specific order
        // This ensures if the user edits and orders again, the previous order's design is not overwritten
        $snapshotDesign = $design->replicate();
        $snapshotDesign->design_code = $design->design_code . '-S' . $nextId;
        $snapshotDesign->status = 'Ordered';
        $snapshotDesign->save();

        foreach($design->pages as $page) {
            $snapPage = $page->replicate();
            $snapPage->design_id = $snapshotDesign->id;
            $snapPage->save();
        }

        foreach($design->photos as $photo) {
            $snapPhoto = $photo->replicate();
            $snapPhoto->design_id = $snapshotDesign->id;
            $snapPhoto->save();
        }

        $order = \App\Models\Order::create([
            'order_code' => $orderCode,
            'design_id' => $snapshotDesign->id,
            'customer_name' => $request->customer_name,
            'customer_wa' => $request->whatsapp,
            'email' => $request->email,
            'customer_address' => $request->address,
            'book_size' => $snapshotDesign->book_size ?? 'A5',
            'book_type' => $request->book_type,
            'quantity' => $request->quantity,
            'notes' => $request->note,
            'total_pages' => $snapshotDesign->pages->count(),
            'status' => 'Pesanan Baru'
        ]);

        $design->update(['status' => 'Ordered']);

        // Generate WhatsApp Message
        $settingWa = \App\Models\Setting::where('key', 'whatsapp_number')->first();
        $adminPhone = $settingWa ? $settingWa->value : '6281234567890';

        $pagesCount = $design->pages->count();
        
        $message = "Halo Admin CeritaKu,\n\n";
        $message .= "Saya ingin memesan Memory Book.\n\n";
        $message .= "Nama:\n{$order->customer_name}\n\n";
        $message .= "Nomor:\n{$order->whatsapp}\n\n";
        $message .= "Kode Desain:\n{$snapshotDesign->design_code}\n\n";
        $message .= "Kode Order:\n{$order->order_code}\n\n";
        $message .= "Ukuran Buku:\nCustom (20x28 cm)\n\n"; // Assume a standard size or fetch from design
        $message .= "Jumlah Halaman:\n{$pagesCount} Halaman\n\n";
        $message .= "Jenis Buku:\n{$order->book_type}\n\n";
        $message .= "Jumlah Buku:\n{$order->quantity}\n\n";
        if($order->note) {
            $message .= "Catatan:\n{$order->note}\n\n";
        }
        $message .= "Mohon informasi pembayaran.";

        $waUrl = 'https://wa.me/' . $adminPhone . '?text=' . urlencode($message);

        return response()->json(['wa_url' => $waUrl]);
    }

    public function trackOrder(Request $request) {
        $orderCode = $request->query('order_code');
        $whatsapp = $request->query('whatsapp');
        
        $order = null;
        $error = null;
        
        if ($orderCode && $whatsapp) {
            // Bersihkan format WA (opsional, tapi disarankan)
            $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
            
            $order = \App\Models\Order::where('order_code', $orderCode)
                        ->where('customer_wa', 'like', "%{$whatsapp}%")
                        ->first();
            
            if (!$order) {
                $error = "Pesanan tidak ditemukan. Pastikan Kode Pesanan dan Nomor WhatsApp sudah benar.";
            }
        }
        
        return view('track', compact('order', 'orderCode', 'whatsapp', 'error'));
    }
}
