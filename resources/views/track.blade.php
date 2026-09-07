<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan | CeritaKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    
    <nav class="bg-white border-b border-slate-200 py-4">
        <div class="max-w-4xl mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-xl font-black text-blue-600 tracking-tighter">CeritaKu<span class="text-slate-800">.</span></a>
            <a href="/" class="text-sm font-medium text-slate-500 hover:text-slate-800">Kembali ke Beranda</a>
        </div>
    </nav>

    <div class="max-w-xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-6 md:p-8 border border-slate-100">
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-800">Lacak Pesanan Anda</h1>
                <p class="text-slate-500 mt-2 text-sm">Masukkan Kode Pesanan dan Nomor WhatsApp Anda untuk melihat status cetak dan pengiriman.</p>
            </div>

            <form method="GET" action="/lacak" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Kode Pesanan (Order ID) <span class="text-red-500">*</span></label>
                    <input type="text" name="order_code" value="{{ request('order_code') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition text-slate-800 placeholder-slate-400 font-medium" placeholder="Contoh: ORD-000001" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="whatsapp" value="{{ request('whatsapp') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition text-slate-800 placeholder-slate-400 font-medium" placeholder="Contoh: 08123456789" required>
                    <p class="text-xs text-slate-400 mt-1">Gunakan nomor yang sama saat melakukan pemesanan.</p>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-600/30 transition transform hover:-translate-y-0.5">
                    Lacak Sekarang
                </button>
            </form>

            @if(isset($error))
                <div class="mt-6 p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <p class="text-sm text-red-800 font-medium">{{ $error }}</p>
                </div>
            @endif

            @if(isset($order))
                <div class="mt-10 pt-8 border-t border-slate-200">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Untuk</p>
                            <h3 class="text-lg font-black text-blue-600">{{ $order->order_code }}</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pemesan</p>
                            <p class="text-sm font-bold text-slate-700">{{ $order->customer_name }}</p>
                        </div>
                    </div>
                    
                    <div class="relative space-y-6 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                        
                        @php
                            $statuses = ['Pesanan Baru', 'Diproses', 'Dicetak', 'Dikirim'];
                            $currentStatus = $order->status;
                            
                            // Map existing status to timeline
                            $currentIndex = array_search($currentStatus, $statuses);
                            if ($currentIndex === false) {
                                if ($currentStatus === 'Selesai' || $currentStatus === 'Dikirim') $currentIndex = 3;
                                else $currentIndex = 0;
                            }
                        @endphp

                        @foreach($statuses as $index => $status)
                            @php
                                $isCompleted = $index <= $currentIndex;
                                $isActive = $index === $currentIndex;
                                
                                $icons = [
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />', // Pesanan Baru
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />', // Diproses
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />', // Dicetak
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />' // Dikirim
                                ];
                            @endphp
                            
                            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 shrink-0 transition-colors {{ $isCompleted ? 'bg-blue-600 border-white text-white shadow-lg shadow-blue-200' : 'bg-slate-100 border-white text-slate-300' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        {!! $icons[$index] !!}
                                    </svg>
                                </div>
                                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-xl transition-all {{ $isActive ? 'bg-blue-50 border border-blue-100 shadow-sm transform scale-105' : ($isCompleted ? 'bg-white border border-slate-200' : 'opacity-50') }}">
                                    <div class="font-bold {{ $isActive ? 'text-blue-700' : ($isCompleted ? 'text-slate-800' : 'text-slate-400') }}">{{ $status }}</div>
                                    <p class="text-xs {{ $isActive ? 'text-blue-500' : 'text-slate-400' }} mt-0.5">
                                        @if($index === 0) Menunggu validasi pembayaran
                                        @elseif($index === 1) Desain sedang diperiksa
                                        @elseif($index === 2) Buku masuk antrian cetak
                                        @elseif($index === 3) Buku dalam perjalanan
                                        @endif
                                    </p>
                                    @if($status === 'Dikirim' && $order->status === 'Dikirim' && $order->notes)
                                        <div class="mt-3 pt-3 border-t border-slate-200/50">
                                            <p class="text-xs text-slate-500 mb-1">Nomor Resi Pengiriman:</p>
                                            <div class="bg-white border border-slate-200 rounded px-3 py-2 font-mono font-bold text-slate-800 text-sm inline-block">
                                                {{ $order->notes }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
