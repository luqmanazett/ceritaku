@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Template</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalTemplate }}</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Pesanan</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalPesanan }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Pesanan Baru</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $pesananBaru }}</h3>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">5 Pesanan Terakhir</h3>
            <a href="/admin/pesanan" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Kode Order</th>
                        <th class="px-6 py-3 font-semibold">Pelanggan</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-medium text-blue-600">{{ $order->order_code }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800">{{ $order->customer_name }}</p>
                                <p class="text-slate-500 text-xs">{{ $order->whatsapp }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($order->status === 'Pesanan Baru') bg-amber-100 text-amber-700
                                    @elseif($order->status === 'Selesai') bg-green-100 text-green-700
                                    @elseif($order->status === 'Menunggu Pembayaran') bg-red-100 text-red-700
                                    @else bg-blue-100 text-blue-700 @endif
                                ">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada pesanan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
