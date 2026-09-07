<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalTemplate = Template::count();
        $totalPesanan = Order::count();
        $pesananBaru = Order::where('status', 'Pesanan Baru')->count();
        
        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalTemplate', 'totalPesanan', 'pesananBaru', 'recentOrders'));
    }

    public function orders(Request $request)
    {
        $query = Order::with('design')->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_wa', 'like', "%{$search}%")
                  ->orWhereHas('design', function($q) use ($search) {
                      $q->where('design_code', 'like', "%{$search}%");
                  });
        }

        $orders = $query->paginate(20)->appends(['search' => $request->search]);
        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pesanan Baru,Menunggu Pembayaran,Diproses,Dicetak,Dikirim,Selesai'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function cancelOrder($id)
    {
        $order = Order::with('design')->findOrFail($id);

        if ($order->design) {
            // Hapus file fisik foto pelanggan untuk menghemat storage
            $photos = $order->design->photos;
            if ($photos) {
                foreach ($photos as $photo) {
                    $filePath = 'public/' . $photo->stored_path;
                    if (\Illuminate\Support\Facades\Storage::exists($filePath)) {
                        \Illuminate\Support\Facades\Storage::delete($filePath);
                    }
                    $photo->delete(); // Hapus record dari database
                }
            }
            
            // Hapus json halaman desain untuk menghemat database
            $order->design->pages()->delete();
            // Hapus record desain
            $order->design->delete();
        }

        // Hapus pesanan dari database
        $order->delete();

        return redirect()->back()->with('success', 'Pesanan beserta seluruh file foto pelanggan telah dihapus secara permanen dari sistem.');
    }

    public function produksiView($designCode)
    {
        $design = \App\Models\Design::with(['pages' => function($q) {
            $q->orderBy('page_number');
        }, 'template'])->where('design_code', $designCode)->firstOrFail();
        
        return view('admin.produksi', compact('design'));
    }

    public function downloadPhotos($designCode)
    {
        $design = \App\Models\Design::where('design_code', $designCode)->firstOrFail();
        $assets = $design->assets;
        
        if ($assets->isEmpty()) {
            return back()->with('error', 'Tidak ada foto yang diunggah oleh pelanggan.');
        }
        
        $zipFileName = 'Foto_' . $designCode . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
        
        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($assets as $asset) {
                $filePath = storage_path('app/public/' . $asset->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $asset->file_name);
                }
            }
            $zip->close();
        }
        
        if (!file_exists($zipPath)) {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }
        
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function downloadJson($designCode)
    {
        $design = \App\Models\Design::with(['pages' => function($q) {
            $q->orderBy('page_number');
        }])->where('design_code', $designCode)->firstOrFail();
        
        $data = [
            'design_code' => $design->design_code,
            'book_size' => $design->book_size,
            'pages' => $design->pages->map(function($p) {
                return [
                    'page_number' => $p->page_number,
                    'layout_type' => $p->layout_type,
                    'json' => json_decode($p->photos)
                ];
            })
        ];
        
        $jsonString = json_encode($data, JSON_PRETTY_PRINT);
        
        return response($jsonString)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="Layout_'.$designCode.'.json"');
    }

    public function templateIndex()
    {
        if (\App\Models\TemplateCategory::count() === 0) {
            $now = now();
            \App\Models\TemplateCategory::insert([
                ['name' => 'Minimalis', 'slug' => 'minimalis', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Vintage & Klasik', 'slug' => 'vintage-klasik', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Pernikahan', 'slug' => 'pernikahan', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Kelahiran & Bayi', 'slug' => 'kelahiran-bayi', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Travel', 'slug' => 'travel', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
        
        $templates = \App\Models\Template::with('category')->orderBy('created_at', 'desc')->get();
        return view('admin.templates', compact('templates'));
    }

    public function templateCreate()
    {
        $categories = \App\Models\TemplateCategory::all();
        return view('admin.template-create', compact('categories'));
    }

    public function templateStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:template_categories,id',
            'book_size' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cover_back' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $file = $request->file('thumbnail');
        $filename = time() . '_front_' . preg_replace('/[^A-Za-z0-9_.\-]/', '', $file->getClientOriginalName());
        $path = $file->storeAs('templates', $filename, 'public');

        $fileBack = $request->file('cover_back');
        $filenameBack = time() . '_back_' . preg_replace('/[^A-Za-z0-9_.\-]/', '', $fileBack->getClientOriginalName());
        $pathBack = $fileBack->storeAs('templates', $filenameBack, 'public');
        
        $layoutStructure = [
            'is_static' => true,
            'cover_back_url' => url('/media/' . $pathBack)
        ];

        \App\Models\Template::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'book_size' => $request->book_size,
            'thumbnail' => url('/media/' . $path),
            'layout_structure' => $layoutStructure,
            'is_active' => true
        ]);

        return redirect('/admin/template')->with('success', 'Template statis berhasil ditambahkan.');
    }

    public function templateStoreDynamic(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:template_categories,id',
            'book_size' => 'required|string',
        ]);

        $template = \App\Models\Template::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'book_size' => $request->book_size,
            'thumbnail' => '', // Will be generated from canvas
            'is_active' => true
        ]);

        return redirect('/admin/template/' . $template->id . '/editor');
    }

    public function templateEditor($id)
    {
        $template = \App\Models\Template::findOrFail($id);
        
        // "Upload Statis" templates have a thumbnail image but its filename doesn't contain 'dynamic_'
        $isStaticTemplate = !empty($template->thumbnail) && !str_contains($template->thumbnail, 'dynamic_');
        
        return view('admin.template-editor', compact('template', 'isStaticTemplate'));
    }

    public function templateSaveLayout(Request $request, $id)
    {
        $template = \App\Models\Template::findOrFail($id);
        
        $layoutData = json_decode($request->json, true);

        // Extract base64 images from layoutData and save them as files to prevent MySQL packet errors
        if (isset($layoutData['objects']) && is_array($layoutData['objects'])) {
            foreach ($layoutData['objects'] as &$object) {
                if (isset($object['src']) && preg_match('/^data:image\/(\w+);base64,/', $object['src'], $type)) {
                    $data = substr($object['src'], strpos($object['src'], ',') + 1);
                    $typeStr = strtolower($type[1]);
                    if (in_array($typeStr, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                        $data = base64_decode($data);
                        if ($data !== false) {
                            $filename = 'templates/assets_' . $template->id . '_' . uniqid() . '.' . $typeStr;
                            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $data);
                            $object['src'] = url('/media/' . $filename);
                        }
                    }
                }
            }
        }

        // Extract base64 background image if present
        if (isset($layoutData['backgroundImage']) && isset($layoutData['backgroundImage']['src']) && preg_match('/^data:image\/(\w+);base64,/', $layoutData['backgroundImage']['src'], $type)) {
            $data = substr($layoutData['backgroundImage']['src'], strpos($layoutData['backgroundImage']['src'], ',') + 1);
            $typeStr = strtolower($type[1]);
            if (in_array($typeStr, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                $data = base64_decode($data);
                if ($data !== false) {
                    $filename = 'templates/assets_bg_' . $template->id . '_' . uniqid() . '.' . $typeStr;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $data);
                    $layoutData['backgroundImage']['src'] = url('/media/' . $filename);
                }
            }
        }

        // Preserve static metadata if it exists
        if (is_array($template->layout_structure) && isset($template->layout_structure['is_static'])) {
            $layoutData['is_static'] = $template->layout_structure['is_static'];
            if (isset($template->layout_structure['cover_back_url'])) {
                $layoutData['cover_back_url'] = $template->layout_structure['cover_back_url'];
            }
        }

        $template->layout_structure = $layoutData;
        
        if ($request->has('thumbnail_data_url') && !empty($request->thumbnail_data_url)) {
            // Convert base64 to image file
            $data = $request->thumbnail_data_url;
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $typeStr = strtolower($type[1]); // jpg, png, gif
                if (!in_array($typeStr, [ 'jpg', 'jpeg', 'gif', 'png', 'webp' ])) {
                    throw new \Exception('invalid image type');
                }
                $data = base64_decode($data);
                if ($data === false) {
                    throw new \Exception('base64_decode failed');
                }
                $filename = 'templates/dynamic_' . $template->id . '_' . time() . '.' . $typeStr;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $data);
                $template->thumbnail = url('/media/' . $filename);
            }
        }
        
        $template->save();

        return response()->json(['status' => 'success']);
    }

    public function templateDelete($id)
    {
        $template = \App\Models\Template::findOrFail($id);
        $template->delete();
        return redirect()->back()->with('success', 'Template berhasil dihapus.');
    }

    public function templateUpdateInfo(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:template_categories,id',
            'book_size' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $template = \App\Models\Template::findOrFail($id);
        $template->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'book_size' => $request->book_size,
            'is_active' => $request->is_active
        ]);

        return redirect()->back()->with('success', 'Informasi template berhasil diperbarui.');
    }

    public function showLogin()
    {
        if (\App\Models\AdminUser::count() === 0) {
            \App\Models\AdminUser::create([
                'name' => 'Administrator',
                'email' => 'admin@ceritaku.com',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123')
            ]);
        }
        return view('admin.login');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = \App\Models\AdminUser::where('email', $request->email)->first();

        if ($admin && \Illuminate\Support\Facades\Hash::check($request->password, $admin->password)) {
            session(['admin_logged_in' => true, 'admin_name' => $admin->name]);
            return redirect('/admin');
        }

        return back()->with('error', 'Email atau Password salah!');
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_name']);
        return redirect('/admin/login');
    }

    public function settingsIndex()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $keys = ['whatsapp_number', 'bank_account', 'book_price_hardcover', 'book_price_softcover'];
        
        foreach ($keys as $key) {
            if ($request->has($key)) {
                \App\Models\Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->$key]
                );
            }
        }

        // Mengelola unggahan gambar satuan (0 s/d 9)
        $existingSetting = \App\Models\Setting::where('key', 'flipbook_images')->first();
        $existingImages = [];
        if ($existingSetting && !empty($existingSetting->value)) {
            $existingImages = explode("\n", str_replace("\r", "", $existingSetting->value));
        }

        // Pastikan array selalu berukuran minimal 10 elemen (1 Cover, 8 Halaman, 1 Cover Belakang)
        for ($i = 0; $i < 10; $i++) {
            if (!isset($existingImages[$i]) || empty(trim($existingImages[$i]))) {
                $existingImages[$i] = 'https://images.unsplash.com/photo-1528543606781-2f6e6857f318?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'; // fallback
            }
        }

        $isUpdated = false;
        for ($i = 0; $i < 10; $i++) {
            $inputName = "flipbook_page_{$i}";
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $filename = time() . "_page_{$i}_" . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('flipbook', $filename, 'public');
                $existingImages[$i] = url('/media/' . $path);
                $isUpdated = true;
            }
        }

        if ($isUpdated) {
            \App\Models\Setting::updateOrCreate(
                ['key' => 'flipbook_images'],
                ['value' => implode("\n", $existingImages)]
            );
        }
        
        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function categoryIndex()
    {
        $categories = \App\Models\TemplateCategory::orderBy('name')->get();
        return view('admin.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:template_categories,name',
        ]);

        \App\Models\TemplateCategory::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:template_categories,name,' . $id,
        ]);

        $category = \App\Models\TemplateCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function categoryDelete($id)
    {
        $category = \App\Models\TemplateCategory::findOrFail($id);
        
        // Prevent deleting if it has templates
        if (\App\Models\Template::where('category_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki template.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
