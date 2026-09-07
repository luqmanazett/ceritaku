<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CeritaKu - Jangan Biarkan Kenanganmu Hilang di Galeri</title>
    <meta name="description" content="Buat Memory Book premium langsung dari browser. Desain Sendiri, Kami Cetakkan.">
    <!-- Google Fonts: Plus Jakarta Sans for premium, modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for Mobile Menu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff', // Light Blue
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb', // Primary Blue
                            900: '#1e3a8a',
                        },
                        neutral: {
                            50: '#f8fafc', // Light Gray
                            100: '#f1f5f9',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="font-sans antialiased text-neutral-800 bg-neutral-50 selection:bg-brand-100 selection:text-brand-900" x-data="{ showExampleModal: false }">

    <!-- Navigation -->
    <nav x-data="{ open: false }" class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-xl">
                        C
                    </div>
                    <a href="#" class="text-2xl font-bold tracking-tight text-neutral-900">Cerita<span class="text-brand-600">Ku</span></a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#beranda" class="text-sm font-medium text-neutral-600 hover:text-brand-600 transition-colors">Beranda</a>
                    <a href="/template-cover" class="text-sm font-medium text-neutral-600 hover:text-brand-600 transition-colors">Template</a>
                    <a href="#cara-kerja" class="text-sm font-medium text-neutral-600 hover:text-brand-600 transition-colors">Cara Kerja</a>
                    <a href="#harga" class="text-sm font-medium text-neutral-600 hover:text-brand-600 transition-colors">Harga</a>
                    <a href="/lacak" class="text-sm font-bold text-blue-600 flex items-center gap-1 hover:text-blue-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                        Lacak Pesanan
                    </a>
                    <a href="/template-cover" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 rounded-full hover:bg-brand-500 shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-0.5">Mulai Membuat</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="text-neutral-600 hover:text-brand-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" class="md:hidden bg-white border-b border-neutral-100 px-4 pt-2 pb-6 space-y-2 shadow-xl" style="display: none;">
            <a href="#beranda" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-800 hover:text-brand-600 hover:bg-brand-50">Beranda</a>
            <a href="/template-cover" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-800 hover:text-brand-600 hover:bg-brand-50">Template Cover</a>
            <a href="#cara-kerja" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-800 hover:text-brand-600 hover:bg-brand-50">Cara Kerja</a>
            <a href="#harga" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-800 hover:text-brand-600 hover:bg-brand-50">Harga</a>
            <a href="/lacak" class="block px-3 py-2 rounded-md text-base font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 mt-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                Lacak Pesanan
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-brand-100/50 via-white to-white"></div>
        <div class="absolute right-0 top-1/4 -z-10 w-96 h-96 bg-brand-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse"></div>
        <div class="absolute left-10 bottom-0 -z-10 w-72 h-72 bg-blue-100/50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-4xl mx-auto">
                <span class="inline-block py-1.5 px-4 rounded-full bg-brand-50 text-brand-600 text-sm font-semibold tracking-wide mb-6 border border-brand-100 shadow-sm">
                    ✨ Desain Sendiri, Kami Cetakkan
                </span>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-neutral-900 mb-8 leading-tight">
                    Jangan Biarkan Kenanganmu <br class="hidden md:block" /> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-blue-400">Hilang di Galeri</span>
                </h1>
                <p class="mt-6 text-xl text-neutral-600 max-w-2xl mx-auto leading-relaxed">
                    Buat Memory Book premium langsung dari browser. Tanpa aplikasi tambahan, desain sesuai selera, dan langsung pesan melalui WhatsApp.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="/template-cover" class="w-full sm:w-auto px-8 py-4 text-base font-semibold text-white bg-brand-600 rounded-full hover:bg-brand-500 shadow-xl shadow-brand-500/30 transition-all hover:-translate-y-1">
                        Mulai Membuat
                    </a>
                    <button @click="showExampleModal = true; initFlipbook()" class="w-full sm:w-auto px-8 py-4 text-base font-semibold text-neutral-700 bg-white border-2 border-neutral-200 rounded-full hover:border-brand-300 hover:bg-brand-50 hover:text-brand-600 transition-all">
                        Lihat Contoh
                    </button>
                </div>
            </div>

            <!-- Hero Image/Mockup Mock -->
            <div class="mt-20 relative mx-auto max-w-5xl">
                <div class="rounded-2xl bg-neutral-900/5 p-2 md:p-4 backdrop-blur-sm border border-white/50 shadow-2xl">
                    <div class="rounded-xl overflow-hidden bg-white aspect-video relative flex items-center justify-center shadow-inner">
                        <!-- Mockup Image representing the App -->
                        <img src="https://images.unsplash.com/photo-1544396821-4dd40b938ad3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Memory Book Editor Preview" class="w-full h-full object-cover opacity-90">
                        
                        <!-- Overlay UI element to make it look like an editor -->
                        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/40 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 flex justify-between items-end">
                            <div class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-lg shadow-lg flex gap-3 items-center">
                                <div class="w-8 h-8 rounded bg-brand-100 flex items-center justify-center text-brand-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-neutral-800">Layout Selesai</p>
                                    <p class="text-[10px] text-neutral-500">Hal 12 - Liburan Bali</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Kerja Section -->
    <section id="cara-kerja" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Cara Kerja yang Mudah</h2>
                <p class="text-lg text-neutral-600">Hanya butuh 3 langkah sederhana untuk mencetak memori Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                <!-- Line connector -->
                <div class="hidden md:block absolute top-1/4 left-[15%] right-[15%] h-0.5 bg-brand-100 -z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center text-center group">
                    <div class="w-20 h-20 rounded-2xl bg-white border-2 border-brand-100 shadow-xl shadow-brand-50/50 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:border-brand-500 transition-all duration-300">
                        <svg class="w-10 h-10 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">1. Pilih Template</h3>
                    <p class="text-neutral-600 leading-relaxed">Pilih desain cover dan layout halaman dari koleksi premium kami yang terus di-update.</p>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center text-center group">
                    <div class="w-20 h-20 rounded-2xl bg-brand-600 text-white shadow-xl shadow-brand-500/40 flex items-center justify-center mb-6 group-hover:scale-110 transition-all duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">2. Upload Foto</h3>
                    <p class="text-neutral-600 leading-relaxed">Susun foto-foto terbaikmu langsung dari browser HP atau Laptop tanpa perlu install aplikasi.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center text-center group">
                    <div class="w-20 h-20 rounded-2xl bg-white border-2 border-brand-100 shadow-xl shadow-brand-50/50 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:border-brand-500 transition-all duration-300">
                        <svg class="w-10 h-10 text-[#25D366]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">3. Kami Cetak & Kirim</h3>
                    <p class="text-neutral-600 leading-relaxed">Selesaikan pesanan via WhatsApp. Kami akan mencetak buku fisik premium dan mengirimkannya langsung ke alamat Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kategori Cover Section -->
    <section id="template" class="py-24 bg-neutral-50 border-t border-neutral-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Kategori Cover</h2>
                    <p class="text-lg text-neutral-600">Desain sampul eksklusif untuk setiap momen berharga Anda.</p>
                </div>
                <a href="/template-cover" class="hidden md:flex text-brand-600 font-medium items-center hover:text-brand-700">
                    Lihat Semua <svg class="w-5 h-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    @php $template = $category->templates->first(); @endphp
                    @if($template)
                    <a href="/template-cover?category={{ $category->id }}" class="group cursor-pointer block">
                        <div class="relative overflow-hidden rounded-2xl aspect-[70/99] shadow-md mb-4 bg-white">
                            <img src="{{ $template->thumbnail }}" alt="{{ $category->name }}" class="w-full h-full object-cover object-right transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                            <div class="absolute top-4 right-4 bg-neutral-900/80 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm border border-white/20">
                                <p class="text-[10px] font-bold text-white uppercase tracking-wider">{{ $template->book_size ?? 'A5' }}</p>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-neutral-900">{{ $category->name }}</h3>
                        <p class="text-sm text-neutral-500 mt-1">{{ $template->name }}</p>
                    </a>
                    @endif
                @endforeach
            </div>
            
            <div class="mt-8 text-center md:hidden">
                <a href="/template-cover" class="inline-flex text-brand-600 font-medium items-center hover:text-brand-700">
                    Lihat Semua Template <svg class="w-5 h-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Cerita Mereka Bersama Kami</h2>
                <p class="text-lg text-neutral-600">Ribuan memori telah tercetak dengan kualitas terbaik.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-neutral-50 p-8 rounded-3xl border border-neutral-100 relative">
                    <div class="text-brand-400 mb-4">
                        <svg class="w-10 h-10 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    </div>
                    <p class="text-neutral-700 italic mb-6">"Kualitas cetaknya luar biasa! Bindingnya kuat dan warnanya sangat tajam. Proses desainnya juga sangat mudah tanpa perlu repot download aplikasi."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/150?img=47" alt="Sarah" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-neutral-900">Sarah A.</h4>
                            <p class="text-sm text-neutral-500">Jakarta Selatan</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-brand-600 p-8 rounded-3xl relative text-white shadow-xl shadow-brand-500/20 transform md:-translate-y-4">
                    <div class="text-brand-300 mb-4">
                        <svg class="w-10 h-10 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    </div>
                    <p class="italic mb-6">"Saya buat buku memori untuk hadiah ulang tahun pernikahan orang tua. Admin WhatsApp sangat responsif membantu. Pengiriman cepat dan aman."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/150?img=11" alt="Budi" class="w-12 h-12 rounded-full object-cover border-2 border-brand-400">
                        <div>
                            <h4 class="font-bold">Budi Pratama</h4>
                            <p class="text-sm text-brand-200">Bandung</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-neutral-50 p-8 rounded-3xl border border-neutral-100 relative">
                    <div class="text-brand-400 mb-4">
                        <svg class="w-10 h-10 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    </div>
                    <p class="text-neutral-700 italic mb-6">"Layout-nya kekinian banget, persis seperti feed estetik di sosmed. Cocok banget untuk mengabadikan momen liburan bareng teman-teman."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/150?img=5" alt="Nadia" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-neutral-900">Nadia Putri</h4>
                            <p class="text-sm text-neutral-500">Surabaya</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-neutral-50 border-t border-neutral-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Tanya Jawab (FAQ)</h2>
                <p class="text-lg text-neutral-600">Jawaban untuk pertanyaan yang sering diajukan oleh pelanggan kami.</p>
            </div>

            <div class="space-y-4" x-data="{ activeAccordion: 1 }">
                <!-- FAQ 1 -->
                <div class="bg-white border border-neutral-200 rounded-2xl overflow-hidden">
                    <button @click="activeAccordion = activeAccordion === 1 ? null : 1" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-neutral-900">Apakah buku ini dicetak fisik atau hanya digital?</span>
                        <svg class="w-5 h-5 text-neutral-500 transform transition-transform duration-300" :class="{'rotate-180': activeAccordion === 1}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="activeAccordion === 1" x-collapse>
                        <div class="px-6 pb-4 text-neutral-600">
                            CeritaKu mencetak **buku fisik (Photobook) premium** berkualitas tinggi. Setelah Anda selesai mendesain di website kami, kami akan mencetak, menjilid, dan mengirimkan buku tersebut langsung ke alamat Anda.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white border border-neutral-200 rounded-2xl overflow-hidden">
                    <button @click="activeAccordion = activeAccordion === 2 ? null : 2" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-neutral-900">Berapa lama proses pembuatan dan pengiriman?</span>
                        <svg class="w-5 h-5 text-neutral-500 transform transition-transform duration-300" :class="{'rotate-180': activeAccordion === 2}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="activeAccordion === 2" x-collapse>
                        <div class="px-6 pb-4 text-neutral-600">
                            Proses produksi cetak membutuhkan waktu sekitar **2-4 hari kerja** setelah pembayaran dikonfirmasi. Waktu pengiriman bergantung pada ekspedisi yang Anda pilih (biasanya 1-3 hari untuk Jabodetabek).
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white border border-neutral-200 rounded-2xl overflow-hidden">
                    <button @click="activeAccordion = activeAccordion === 3 ? null : 3" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-neutral-900">Apakah saya perlu install aplikasi?</span>
                        <svg class="w-5 h-5 text-neutral-500 transform transition-transform duration-300" :class="{'rotate-180': activeAccordion === 3}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="activeAccordion === 3" x-collapse>
                        <div class="px-6 pb-4 text-neutral-600">
                            **Sama sekali tidak!** Anda bisa langsung mendesain melalui browser di Laptop, Tablet, maupun HP Anda. Kami sangat menyarankan menggunakan Laptop/PC untuk pengalaman mendesain yang paling maksimal dan leluasa.
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-white border border-neutral-200 rounded-2xl overflow-hidden">
                    <button @click="activeAccordion = activeAccordion === 4 ? null : 4" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-neutral-900">Apakah foto saya aman?</span>
                        <svg class="w-5 h-5 text-neutral-500 transform transition-transform duration-300" :class="{'rotate-180': activeAccordion === 4}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="activeAccordion === 4" x-collapse>
                        <div class="px-6 pb-4 text-neutral-600">
                            Tentu saja. Kami menjamin privasi foto Anda. Semua file foto dan desain yang belum terbayar akan otomatis **dihapus permanen dari server kami setelah 3 hari** (sistem Garbage Collector otomatis).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-brand-50 border-t border-brand-100">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-neutral-900 mb-6">Siap Mencetak Memori Anda?</h2>
            <p class="text-lg text-neutral-600 mb-8">Pilih desainnya, upload fotonya, dan kami akan urus sisanya.</p>
            <a href="/template-cover" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-brand-600 rounded-full hover:bg-brand-500 shadow-xl shadow-brand-500/30 transition-all hover:-translate-y-1">
                Mulai Desain Sekarang
                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="tentang-kami" class="bg-white border-t border-neutral-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-xl">
                            C
                        </div>
                        <span class="text-2xl font-bold tracking-tight text-neutral-900">Cerita<span class="text-brand-600">Ku</span></span>
                    </div>
                    <p class="text-neutral-500 max-w-sm mb-6">Platform cetak Memory Book online premium. Mengubah galeri fotomu menjadi buku nyata yang penuh makna dengan desain elegan.</p>
                    <div class="flex gap-4">
                        <!-- Socials -->
                        <a href="#" class="w-10 h-10 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-600 hover:bg-brand-100 hover:text-brand-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-600 hover:bg-brand-100 hover:text-brand-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold text-neutral-900 mb-4">Produk</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-neutral-500 hover:text-brand-600 transition-colors">Template Cover</a></li>
                        <li><a href="#" class="text-neutral-500 hover:text-brand-600 transition-colors">Hardcover Book</a></li>
                        <li><a href="#" class="text-neutral-500 hover:text-brand-600 transition-colors">Softcover Book</a></li>
                        <li><a href="#harga" class="text-neutral-500 hover:text-brand-600 transition-colors">Daftar Harga</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-neutral-900 mb-4">Bantuan</h4>
                    <ul class="space-y-3">
                        <li><a href="/bantuan#pemesanan" class="text-neutral-500 hover:text-brand-600 transition-colors">Cara Pemesanan</a></li>
                        <li><a href="/bantuan#faq" class="text-neutral-500 hover:text-brand-600 transition-colors">FAQ</a></li>
                        <li><a href="/bantuan#pengiriman" class="text-neutral-500 hover:text-brand-600 transition-colors">Pengiriman</a></li>
                        <li><a href="/bantuan#kontak" class="text-neutral-500 hover:text-brand-600 transition-colors">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-neutral-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-neutral-500">© 2026 CeritaKu. Hak cipta dilindungi undang-undang.</p>
                <div class="flex gap-4 text-sm text-neutral-500">
                    <a href="/bantuan#syarat" class="hover:text-brand-600 transition-colors">Syarat & Ketentuan</a>
                    <a href="/bantuan#privasi" class="hover:text-brand-600 transition-colors">Kebijakan Privasi</a>
                    <a href="/admin/login" class="hover:text-brand-600 text-neutral-300 transition-colors ml-2" title="Admin Login">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    </footer>

    <!-- 3D Flipbook Modal -->
    <div x-show="showExampleModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-neutral-900/90 backdrop-blur-sm" style="display: none;" x-cloak>
        <div class="relative w-full h-full flex flex-col items-center justify-center p-4 sm:p-8">
            <!-- Close Button -->
            <button @click="showExampleModal = false" class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors z-10 backdrop-blur-md">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <div class="text-center mb-6 z-10">
                <h3 class="text-2xl font-bold text-white mb-2">Contoh Memory Book</h3>
                <p class="text-neutral-300 text-sm">Geser/tarik ujung halaman untuk membalik buku</p>
            </div>

            <!-- Flipbook Container -->
            <div class="relative w-[90vw] max-w-4xl aspect-[2/1.3] max-h-[70vh] flex justify-center items-center mt-2 mx-auto">
                <div id="flipbook-container" class="shadow-2xl mx-auto w-full h-full">
                    <!-- Pages will be injected by JS -->
                </div>
            </div>
            
            <!-- Controls -->
            <div class="flex gap-4 mt-6 z-10">
                <button id="btn-prev" class="px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-full font-medium transition-colors backdrop-blur-md border border-white/20">
                    &larr; Sebelumnya
                </button>
                <button id="btn-next" class="px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-full font-medium transition-colors backdrop-blur-md border border-white/20">
                    Selanjutnya &rarr;
                </button>
            </div>
        </div>
    </div>

    <!-- StPageFlip Library -->
    <script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.min.js"></script>
    <script>
        let pageFlip = null;

        function initFlipbook() {
            // Wait for modal to show
            setTimeout(() => {
                const container = document.getElementById('flipbook-container');
                if (!container) return;

                // Clear previous instance
                if (pageFlip) {
                    pageFlip.destroy();
                    container.innerHTML = '';
                }

                // Determine base page size (aspect ratio)
                let width = 400;
                let height = 560;
                
                if (window.innerWidth < 768) {
                    width = 300;
                    height = 420;
                }

                // Mengambil gambar dari pengaturan database (Admin -> Pengaturan)
                @php
                    $flipbookSetting = \App\Models\Setting::where('key', 'flipbook_images')->first();
                    $imageArray = [];
                    if ($flipbookSetting && !empty($flipbookSetting->value)) {
                        $imageUrls = explode("\n", str_replace("\r", "", $flipbookSetting->value));
                        foreach($imageUrls as $url) {
                            if (trim($url) !== '') {
                                $imageArray[] = trim($url);
                            }
                        }
                    }
                    
                    if (count($imageArray) < 10) {
                        // Fallback default images (10 images total)
                        $imageArray = [
                            'https://images.unsplash.com/photo-1528543606781-2f6e6857f318?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Cover Depan
                            'https://images.unsplash.com/photo-1507608616759-54f48f0af0ee?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Hal 1
                            'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Hal 2
                            'https://images.unsplash.com/photo-1511895426328-dc8714191300?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Hal 3
                            'https://images.unsplash.com/photo-1516589178581-6cd7833ae3b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Hal 4
                            'https://images.unsplash.com/photo-1518605368461-1ee7c664deaf?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Hal 5
                            'https://images.unsplash.com/photo-1528543606781-2f6e6857f318?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Hal 6
                            'https://images.unsplash.com/photo-1507608616759-54f48f0af0ee?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Hal 7
                            'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Hal 8
                            'https://images.unsplash.com/photo-1511895426328-dc8714191300?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', // Cover Belakang
                        ];
                    }
                @endphp
                
                const images = {!! json_encode($imageArray) !!};

                images.forEach((img, idx) => {
                    const pageDiv = document.createElement('div');
                    pageDiv.className = 'page relative bg-white border border-neutral-200 overflow-hidden';
                    
                    // Menentukan label teks di bawah gambar
                    let pageLabel = `Halaman ${idx}`;
                    if (idx === 0) pageLabel = "Cover Depan";
                    if (idx === images.length - 1) pageLabel = "Cover Belakang";
                    
                    // Menentukan apakah halamannya di kiri atau kanan (untuk efek lipatan)
                    // Cover depan selalu di kanan, halaman setelahnya selang-seling
                    const isRightPage = idx % 2 === 0;

                    // Simple page styling
                    pageDiv.innerHTML = `
                        <div class="absolute inset-0 p-3 sm:p-5 flex flex-col bg-white">
                            <div class="flex-1 rounded-lg overflow-hidden relative shadow-inner">
                                <img src="${img}" class="w-full h-full object-cover">
                            </div>
                            <div class="text-center pt-2 pb-1">
                                <span class="text-[10px] sm:text-xs font-bold text-neutral-400">${pageLabel}</span>
                            </div>
                        </div>
                        <!-- Book Fold Gradient -->
                        <div class="absolute top-0 bottom-0 ${isRightPage ? 'left-0 w-4 sm:w-8 bg-gradient-to-r' : 'right-0 w-4 sm:w-8 bg-gradient-to-l'} from-black/10 to-transparent pointer-events-none"></div>
                    `;
                    container.appendChild(pageDiv);
                });

                // Initialize PageFlip with "stretch" mode
                pageFlip = new St.PageFlip(container, {
                    width: width, // base width
                    height: height, // base height
                    size: "stretch", // Stretches to fill container
                    minWidth: 200,
                    maxWidth: 1000,
                    minHeight: 280,
                    maxHeight: 1200,
                    maxShadowOpacity: 0.5,
                    showCover: true,
                    mobileScrollSupport: false
                });

                pageFlip.loadFromHTML(container.querySelectorAll('.page'));

                // Bind controls
                document.getElementById('btn-prev').onclick = () => pageFlip.flipPrev();
                document.getElementById('btn-next').onclick = () => pageFlip.flipNext();
            }, 500); // 500ms delay for modal animation and DOM readiness
        }
    </script>
</body>
</html>
