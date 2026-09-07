@extends('admin.layout')

@section('title', 'Pengaturan Website')

@section('content')
<div class="max-w-3xl mx-auto">
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-bold text-slate-800">Konfigurasi Pesanan & Bisnis</h3>
        </div>

        <form action="/admin/pengaturan" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- WA Number -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor WhatsApp Admin (Penerima Pesanan)</label>
                    <p class="text-xs text-slate-500 mb-2">Gunakan format 62 di depan (Contoh: 6281234567890)</p>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.384 0 12.031c0 2.115.548 4.184 1.59 6.002L.003 24l6.104-1.603a12.001 12.001 0 005.924 1.564h.005c6.645 0 12.029-5.385 12.029-12.032S18.676 0 12.031 0zm0 21.968h-.003a9.98 9.98 0 01-5.086-1.385l-.365-.217-3.78 1.002.993-3.69-.238-.38A9.975 9.975 0 012.024 12.03C2.024 6.505 6.508 2.02 12.034 2.02c2.68 0 5.197 1.042 7.091 2.937A9.972 9.972 0 0122.062 12.03c0 5.525-4.484 10.01-10.005 10.01l-.026-.072zm5.495-7.51c-.301-.151-1.782-.879-2.058-.98-.276-.101-.478-.151-.679.151-.201.302-.78 1.006-.957 1.208-.176.202-.353.226-.654.075-.301-.151-1.274-.47-2.428-1.5-1.026-.906-1.72-2.023-1.92-2.325-.202-.302-.022-.466.128-.616.136-.135.302-.352.453-.529.151-.176.201-.301.301-.503.1-.201.05-.377-.025-.528-.075-.151-.679-1.635-.93-2.239-.244-.59-.493-.51-.678-.52h-.58c-.201 0-.528.075-.804.377-.276.302-1.055 1.03-1.055 2.514 0 1.484 1.08 2.918 1.23 3.12.152.202 2.128 3.248 5.155 4.553 2.091.901 2.87.844 3.397.743.593-.114 1.782-.729 2.033-1.433.251-.704.251-1.307.176-1.433-.075-.126-.276-.201-.577-.352z"/></svg>
                        </div>
                        <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '6281234567890' }}" class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Harga Softcover -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Dasar Softcover</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-500 text-sm">Rp</span>
                        </div>
                        <input type="number" name="book_price_softcover" value="{{ $settings['book_price_softcover'] ?? '150000' }}" class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Harga Hardcover -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Dasar Hardcover</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-500 text-sm">Rp</span>
                        </div>
                        <input type="number" name="book_price_hardcover" value="{{ $settings['book_price_hardcover'] ?? '250000' }}" class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Info Rekening -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Info Rekening Pembayaran</label>
                    <textarea name="bank_account" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: BCA 123456789 a.n. PT CeritaKu Memory">{{ $settings['bank_account'] ?? '' }}</textarea>
                </div>

                <!-- Gambar Flipbook -->
                <div class="col-span-1 md:col-span-2 border-t border-slate-200 pt-6">
                    <label class="block text-lg font-bold text-slate-800 mb-2">Gambar Contoh Memory Book (3D Flipbook)</label>
                    <p class="text-sm text-slate-500 mb-6">Ubah gambar per halaman tanpa menghilangkan gambar lain. Upload gambar baru pada kotak halaman yang ingin Anda ganti.</p>
                    
                    @php
                        $existingSetting = $settings['flipbook_images'] ?? '';
                        $images = [];
                        if (!empty($existingSetting)) {
                            $images = explode("\n", str_replace("\r", "", $existingSetting));
                        }
                        // Pastikan ada 10 elemen
                        for($i=0; $i<10; $i++) {
                            if (!isset($images[$i]) || empty(trim($images[$i]))) {
                                $images[$i] = 'https://images.unsplash.com/photo-1528543606781-2f6e6857f318?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                            }
                        }
                    @endphp

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @for($i = 0; $i < 10; $i++)
                            @php
                                $label = "Halaman " . $i;
                                if ($i === 0) $label = "Cover Depan";
                                if ($i === 9) $label = "Cover Belakang";
                            @endphp
                            <div class="border border-slate-200 rounded-lg p-3 bg-slate-50 flex flex-col items-center">
                                <span class="text-xs font-bold text-slate-700 mb-2">{{ $label }}</span>
                                <div class="w-full aspect-[1/1.4] mb-3 relative rounded overflow-hidden border border-slate-300 shadow-sm bg-white group">
                                    <img id="preview_image_{{ $i }}" src="{{ trim($images[$i]) }}" class="w-full h-full object-cover">
                                    <!-- Hover Overlay -->
                                    <label for="flipbook_input_{{ $i }}" class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                        <svg class="w-6 h-6 text-white mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                        <span class="text-white text-[10px] font-medium">Ganti</span>
                                    </label>
                                </div>
                                <label for="flipbook_input_{{ $i }}" class="w-full text-center py-1.5 px-2 bg-white border border-slate-300 rounded text-[10px] font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer transition mb-1">
                                    Pilih Foto
                                </label>
                                <input id="flipbook_input_{{ $i }}" type="file" name="flipbook_page_{{ $i }}" accept="image/*" class="hidden" onchange="if(this.files[0]) { document.getElementById('preview_image_{{ $i }}').src = URL.createObjectURL(this.files[0]); }">
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
