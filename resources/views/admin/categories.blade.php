@extends('admin.layout')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center" x-data="{ openAddModal: false }">
        <h3 class="text-lg font-bold text-slate-800">Daftar Kategori Template</h3>
        <button @click="openAddModal = true" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </button>

        <!-- Modal Tambah Kategori -->
        <div x-show="openAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-cloak>
            <div class="bg-white rounded-2xl p-6 w-[400px] shadow-2xl" @click.away="openAddModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-slate-900">Tambah Kategori</h2>
                    <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form action="/admin/kategori" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori</label>
                        <input type="text" name="name" placeholder="Contoh: Travel, Wisuda, Family" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Feedback Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 rounded-lg border border-green-200 flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="p-4 bg-red-50 text-red-700 rounded-lg border border-red-200 flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="p-4 bg-red-50 text-red-700 rounded-lg border border-red-200 flex flex-col gap-1">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-900">
                    <tr>
                        <th class="px-6 py-4 font-semibold">No</th>
                        <th class="px-6 py-4 font-semibold">Nama Kategori</th>
                        <th class="px-6 py-4 font-semibold">Slug (URL)</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($categories as $idx => $category)
                    <tr class="hover:bg-slate-50 transition" x-data="{ openEditModal: false, openDeleteModal: false }">
                        <td class="px-6 py-4">{{ $idx + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <button @click="openEditModal = true" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button @click="openDeleteModal = true" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>

                            <!-- Modal Edit -->
                            <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-cloak>
                                <div class="bg-white rounded-2xl p-6 w-[400px] shadow-2xl text-left" @click.away="openEditModal = false">
                                    <div class="flex justify-between items-center mb-4">
                                        <h2 class="text-xl font-bold text-slate-900">Edit Kategori</h2>
                                        <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <form action="/admin/kategori/{{ $category->id }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori</label>
                                            <input type="text" name="name" value="{{ $category->name }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                        </div>
                                        <div class="flex justify-end gap-3 mt-6">
                                            <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition">Batal</button>
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div x-show="openDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-cloak>
                                <div class="bg-white rounded-2xl p-6 w-[400px] shadow-2xl text-center" @click.away="openDeleteModal = false">
                                    <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <h2 class="text-xl font-bold text-slate-900 mb-2">Hapus Kategori?</h2>
                                    <p class="text-slate-500 mb-6">Apakah Anda yakin ingin menghapus kategori <strong>{{ $category->name }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                    <form action="/admin/kategori/{{ $category->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="flex justify-center gap-3">
                                            <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition">Batal</button>
                                            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition shadow-lg shadow-red-500/30">Ya, Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <p>Belum ada kategori template.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
