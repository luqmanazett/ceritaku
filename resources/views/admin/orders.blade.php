@extends('admin.layout')

@section('title', 'Daftar Pesanan')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Daftar Pesanan</h1>
        <form action="/admin/pesanan" method="GET" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesanan, pelanggan, kode..." class="px-4 py-2 border border-slate-300 rounded-lg text-sm w-64 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm hover:bg-slate-700 transition">Cari</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Order</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold">Kode Desain</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $order->order_code }}</p>
                                <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $order->book_type }} (x{{ $order->quantity }})</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800">{{ $order->customer_name }}</p>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_wa) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.384 0 12.031c0 2.115.548 4.184 1.59 6.002L.003 24l6.104-1.603a12.001 12.001 0 005.924 1.564h.005c6.645 0 12.029-5.385 12.029-12.032S18.676 0 12.031 0zm0 21.968h-.003a9.98 9.98 0 01-5.086-1.385l-.365-.217-3.78 1.002.993-3.69-.238-.38A9.975 9.975 0 012.024 12.03C2.024 6.505 6.508 2.02 12.034 2.02c2.68 0 5.197 1.042 7.091 2.937A9.972 9.972 0 0122.062 12.03c0 5.525-4.484 10.01-10.005 10.01l-.026-.072zm5.495-7.51c-.301-.151-1.782-.879-2.058-.98-.276-.101-.478-.151-.679.151-.201.302-.78 1.006-.957 1.208-.176.202-.353.226-.654.075-.301-.151-1.274-.47-2.428-1.5-1.026-.906-1.72-2.023-1.92-2.325-.202-.302-.022-.466.128-.616.136-.135.302-.352.453-.529.151-.176.201-.301.301-.503.1-.201.05-.377-.025-.528-.075-.151-.679-1.635-.93-2.239-.244-.59-.493-.51-.678-.52h-.58c-.201 0-.528.075-.804.377-.276.302-1.055 1.03-1.055 2.514 0 1.484 1.08 2.918 1.23 3.12.152.202 2.128 3.248 5.155 4.553 2.091.901 2.87.844 3.397.743.593-.114 1.782-.729 2.033-1.433.251-.704.251-1.307.176-1.433-.075-.126-.276-.201-.577-.352z"/></svg>
                                    {{ $order->customer_wa }}
                                </a>
                                @if($order->notes)
                                    <p class="text-xs text-slate-500 mt-1 italic">"{{ $order->notes }}"</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($order->design)
                                    <p class="font-bold text-slate-800">{{ $order->design->design_code }}</p>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        <a href="/editor?design_code={{ $order->design->design_code }}" target="_blank" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded flex items-center gap-1 transition">
                                            Editor
                                        </a>
                                        <a href="/preview?design_code={{ $order->design->design_code }}" target="_blank" class="px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 text-[10px] font-bold rounded flex items-center gap-1 transition">
                                            Preview
                                        </a>
                                        <a href="/admin/pesanan/{{ $order->design->design_code }}/produksi" class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 text-[10px] font-bold rounded flex items-center gap-1 transition">
                                            Produksi
                                        </a>
                                    </div>
                                @else
                                    <span class="text-red-500 text-xs font-semibold">Desain Tidak Ditemukan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="/admin/pesanan/{{ $order->id }}/status" method="POST" class="flex flex-col gap-2">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-xs px-2 py-1 border border-slate-300 rounded font-semibold focus:outline-none focus:ring-1 focus:ring-blue-500
                                        @if($order->status === 'Pesanan Baru') bg-amber-50 text-amber-700
                                        @elseif($order->status === 'Selesai') bg-green-50 text-green-700
                                        @elseif($order->status === 'Menunggu Pembayaran') bg-red-50 text-red-700
                                        @else bg-blue-50 text-blue-700 @endif
                                    ">
                                        <option value="Pesanan Baru" {{ $order->status === 'Pesanan Baru' ? 'selected' : '' }}>Pesanan Baru</option>
                                        <option value="Menunggu Pembayaran" {{ $order->status === 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                        <option value="Diproses" {{ $order->status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Dicetak" {{ $order->status === 'Dicetak' ? 'selected' : '' }}>Dicetak</option>
                                        <option value="Dikirim" {{ $order->status === 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="Selesai" {{ $order->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </form>
                                
                                <div class="mt-2 flex flex-col gap-1">
                                    @php
                                        $waMsg = "Halo Kak {$order->customer_name}, pesanan CeritaKu dengan nomor {$order->order_code} sudah kami terima. Kami tunggu konfirmasi pembayarannya maksimal hari ini pukul 23:59 WIB ya kak. Jika ada kendala, silakan balas chat ini.";
                                        $waLink = "https://wa.me/" . preg_replace('/[^0-9]/', '', $order->customer_wa) . "?text=" . urlencode($waMsg);
                                    @endphp
                                    <a href="{{ $waLink }}" target="_blank" class="px-2 py-1 bg-green-50 hover:bg-green-100 text-green-700 text-[10px] font-bold rounded flex items-center justify-center gap-1 transition w-full">
                                        Follow-up WA
                                    </a>
                                    
                                    <form action="/admin/pesanan/{{ $order->id }}/cancel" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini dan MENGHAPUS SEMUA DATA PESANAN secara permanen? Data yang dihapus tidak dapat dikembalikan.')">
                                        @csrf
                                        <button type="submit" class="w-full px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 text-[10px] font-bold rounded flex items-center justify-center gap-1 transition">
                                            Hapus Pesanan
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="alert('Detail alamat: \n\n{{ str_replace(array("\r", "\n"), '', addslashes($order->customer_address)) }}')" class="px-3 py-1 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-md hover:bg-slate-50 transition">
                                    Alamat
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
