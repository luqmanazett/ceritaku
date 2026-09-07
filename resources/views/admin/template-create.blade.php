@extends('admin.layout')

@section('title', 'Tambah Template Cover')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Upload Template Baru</h3>
        <a href="/admin/template" class="text-sm font-medium text-slate-500 hover:text-slate-800">Batal</a>
    </div>

    <form action="/admin/template" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Template</label>
            <input type="text" name="name" required placeholder="Contoh: Klasik Hitam Putih" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                <select name="category_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Ukuran Buku</label>
                <select name="book_size" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="A5">A5 (14,8 x 21 cm)</option>
                    <option value="A4">A4 (21 x 29,7 cm)</option>
                    <option value="B5">B5 (17,6 x 25 cm)</option>
                    <option value="Persegi">Persegi (20 x 20 cm)</option>
                    <option value="Mini">Mini Pocket (10 x 15 cm)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Cover Depan (Thumbnail)</label>
                <div class="border-2 border-dashed border-slate-300 rounded-lg p-8 text-center bg-slate-50 hover:bg-slate-100 transition relative">
                    <input type="file" name="thumbnail" required accept="image/jpeg, image/png, image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event, 'front')">
                    
                    <div id="upload-prompt-front" class="flex flex-col items-center pointer-events-none">
                        <svg class="w-10 h-10 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span class="text-sm text-slate-600 font-medium">Upload Cover Depan</span>
                    </div>

                    <div id="image-preview-container-front" class="hidden flex-col items-center pointer-events-none">
                        <img id="image-preview-front" src="#" alt="Preview" class="h-32 object-contain rounded mb-2 shadow-sm">
                        <span class="text-sm font-semibold text-blue-600">Ganti Gambar</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Cover Belakang</label>
                <div class="border-2 border-dashed border-slate-300 rounded-lg p-8 text-center bg-slate-50 hover:bg-slate-100 transition relative">
                    <input type="file" name="cover_back" required accept="image/jpeg, image/png, image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event, 'back')">
                    
                    <div id="upload-prompt-back" class="flex flex-col items-center pointer-events-none">
                        <svg class="w-10 h-10 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span class="text-sm text-slate-600 font-medium">Upload Cover Belakang</span>
                    </div>

                    <div id="image-preview-container-back" class="hidden flex-col items-center pointer-events-none">
                        <img id="image-preview-back" src="#" alt="Preview" class="h-32 object-contain rounded mb-2 shadow-sm">
                        <span class="text-sm font-semibold text-blue-600">Ganti Gambar</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200">
            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                Simpan & Publish Template
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(event, type) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('upload-prompt-' + type).classList.add('hidden');
                document.getElementById('image-preview-container-' + type).classList.remove('hidden');
                document.getElementById('image-preview-container-' + type).classList.add('flex');
                document.getElementById('image-preview-' + type).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
