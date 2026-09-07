@extends('admin.layout')

@section('title', 'Manajemen Template Cover')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center" x-data="{ openModal: false }">
        <h3 class="text-lg font-bold text-slate-800">Daftar Template Cover</h3>
        <button @click="openModal = true" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Template
        </button>

        <!-- Modal Tambah Template -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-cloak>
            <div class="bg-white rounded-2xl p-8 w-[600px] shadow-2xl" @click.away="openModal = false">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Pilih Cara Buat Template</h2>
                <p class="text-slate-500 mb-6 text-sm">Pilih antara unggah gambar jadi (tidak bisa diubah teksnya oleh pelanggan) atau buat template dinamis lewat Kanvas.</p>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- Opsi Statis -->
                    <a href="/admin/template/create" class="flex flex-col p-6 bg-slate-50 border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600 mb-4 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-800 text-lg mb-1">Upload Statis</h4>
                        <p class="text-xs text-slate-500">Unggah foto cover jadi. Pelanggan hanya bisa melihat, tidak bisa mengedit teks/warna sampul ini.</p>
                    </a>

                    <!-- Opsi Dinamis -->
                    <div x-data="{ showDynamicForm: false }">
                        <button x-show="!showDynamicForm" @click="showDynamicForm = true" class="flex flex-col p-6 bg-slate-50 border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group text-left w-full h-full">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600 mb-4 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-lg mb-1">Buat Dinamis</h4>
                            <p class="text-xs text-slate-500">Desain sampul di dalam Kanvas. Anda bisa menaruh teks yang nanti bisa diganti-ganti oleh pelanggan.</p>
                        </button>
                        
                        <form x-show="showDynamicForm" action="/admin/template/dynamic" method="POST" class="p-4 bg-blue-50 border border-blue-200 rounded-xl h-full flex flex-col justify-center">
                            @csrf
                            <input type="text" name="name" placeholder="Nama Template" required class="w-full mb-3 px-3 py-2 text-sm border border-blue-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <select name="category_id" required class="w-full mb-3 px-3 py-2 text-sm border border-blue-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(\App\Models\TemplateCategory::all() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <select name="book_size" required class="w-full mb-4 px-3 py-2 text-sm border border-blue-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="A5">A5 (14,8 x 21 cm)</option>
                                <option value="A4">A4 (21 x 29,7 cm)</option>
                                <option value="B5">B5 (17,6 x 25 cm)</option>
                                <option value="Persegi">Persegi (20 x 20 cm)</option>
                                <option value="Mini">Mini Pocket (10 x 15 cm)</option>
                            </select>
                            <button type="submit" class="w-full py-2 bg-blue-600 text-white font-bold text-sm rounded hover:bg-blue-700 transition">Buka Kanvas</button>
                        </form>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button @click="openModal = false" class="px-4 py-2 text-slate-600 font-medium hover:bg-slate-100 rounded-lg">Batal</button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-6 py-4 font-semibold">Preview Cover</th>
                    <th class="px-6 py-4 font-semibold">Nama Template</th>
                    <th class="px-6 py-4 font-semibold">Kategori</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($templates as $template)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" class="w-16 h-20 object-cover object-right rounded shadow border border-slate-200">
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $template->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">{{ $template->category->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($template->is_active)
                                <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-200">Publik</span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-full border border-slate-300">Pribadi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2" x-data="{ openEditInfoModal: false }">
                                <!-- Tombol Edit Info -->
                                <button @click="openEditInfoModal = true" class="text-emerald-600 hover:text-emerald-700 p-2 border border-emerald-200 rounded-lg bg-emerald-50 hover:bg-emerald-100 transition flex items-center gap-1 text-xs font-semibold" title="Edit Info">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit Info
                                </button>
                                
                                <!-- Tombol Edit Kanvas -->
                                <a href="/admin/template/{{ $template->id }}/editor" class="text-blue-500 hover:text-blue-700 p-2 border border-blue-200 rounded-lg bg-blue-50 hover:bg-blue-100 transition flex items-center gap-1 text-xs font-semibold">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit Kanvas
                                </a>

                                <!-- Tombol Hapus -->
                                <form action="/admin/template/{{ $template->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus template ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>

                                <!-- Modal Edit Info Template -->
                                <div x-show="openEditInfoModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-cloak>
                                    <div class="bg-white rounded-2xl p-6 w-[400px] shadow-2xl text-left" @click.away="openEditInfoModal = false">
                                        <div class="flex justify-between items-center mb-4">
                                            <h2 class="text-xl font-bold text-slate-900">Edit Info Template</h2>
                                            <button @click="openEditInfoModal = false" class="text-slate-400 hover:text-slate-600">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        
                                        <form action="/admin/template/{{ $template->id }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Template</label>
                                                <input type="text" name="name" value="{{ $template->name }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                                                <select name="category_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    @foreach(\App\Models\TemplateCategory::orderBy('name')->get() as $cat)
                                                        <option value="{{ $cat->id }}" {{ $template->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Ukuran Buku</label>
                                                <select name="book_size" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    <option value="A5" {{ $template->book_size == 'A5' ? 'selected' : '' }}>A5 (14,8 x 21 cm)</option>
                                                    <option value="A4" {{ $template->book_size == 'A4' ? 'selected' : '' }}>A4 (21 x 29,7 cm)</option>
                                                    <option value="B5" {{ $template->book_size == 'B5' ? 'selected' : '' }}>B5 (17,6 x 25 cm)</option>
                                                    <option value="Persegi" {{ $template->book_size == 'Persegi' ? 'selected' : '' }}>Persegi (20 x 20 cm)</option>
                                                    <option value="Mini" {{ $template->book_size == 'Mini' ? 'selected' : '' }}>Mini Pocket (10 x 15 cm)</option>
                                                </select>
                                            </div>

                                            <div class="mb-6">
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Status Publikasi</label>
                                                <select name="is_active" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    <option value="1" {{ $template->is_active ? 'selected' : '' }}>Publik (Tampil di Halaman Depan)</option>
                                                    <option value="0" {{ !$template->is_active ? 'selected' : '' }}>Pribadi (Disembunyikan)</option>
                                                </select>
                                            </div>
                                            
                                            <div class="flex justify-end gap-3">
                                                <button type="button" @click="openEditInfoModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition">Batal</button>
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            Belum ada template. Silakan klik "Tambah Template" untuk mengunggah.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
