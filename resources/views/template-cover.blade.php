<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Cover - CeritaKu</title>
    <!-- Google Fonts: Plus Jakarta Sans for premium, modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
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
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            900: '#1e3a8a',
                        },
                        neutral: {
                            50: '#f8fafc',
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
<body class="font-sans antialiased text-neutral-800 bg-neutral-50 selection:bg-brand-100 selection:text-brand-900">

    <!-- Navigation -->
    <nav class="w-full z-50 glass-nav border-b border-neutral-200 sticky top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-xl">
                        C
                    </div>
                    <a href="/" class="text-2xl font-bold tracking-tight text-neutral-900">Cerita<span class="text-brand-600">Ku</span></a>
                </div>
                
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="/" class="text-sm font-medium text-neutral-600 hover:text-brand-600 transition-colors">Beranda</a>
                    <a href="/template-cover" class="text-sm font-medium text-brand-600 transition-colors">Template Cover</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="py-16 bg-white border-b border-neutral-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-neutral-900 mb-6">
                Eksplorasi <span class="text-brand-600">Template Cover</span>
            </h1>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Pilih desain sampul eksklusif yang dirancang oleh tim profesional kami. Dari perjalanan hingga pernikahan, temukan yang paling cocok untuk kenangan Anda.
            </p>
        </div>
    </section>

    <!-- Content Section with Alpine.js filtering -->
    <section class="py-16 bg-neutral-50" x-data="{ 
        activeCategory: {{ request('category') ? request('category') : "'all'" }},
        templates: {{ json_encode($templates->map(function($t) { return ['id' => $t->id, 'name' => $t->name, 'category_id' => $t->category_id, 'category_name' => $t->category->name, 'book_size' => $t->book_size ?? 'A5', 'thumbnail' => $t->thumbnail]; })) }}
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                <button @click="activeCategory = 'all'" 
                        :class="activeCategory === 'all' ? 'bg-brand-600 text-white shadow-md' : 'bg-white text-neutral-600 hover:bg-brand-50 border border-neutral-200'"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all">
                    Semua Kategori
                </button>
                @foreach($categories as $category)
                <button @click="activeCategory = {{ $category->id }}" 
                        :class="activeCategory === {{ $category->id }} ? 'bg-brand-600 text-white shadow-md' : 'bg-white text-neutral-600 hover:bg-brand-50 border border-neutral-200'"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>

            <!-- Templates Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <template x-for="template in templates" :key="template.id">
                    <div x-show="activeCategory === 'all' || activeCategory === template.category_id" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-neutral-100 overflow-hidden transition-all duration-300 flex flex-col">
                        
                        <!-- Preview Image -->
                        <div class="relative overflow-hidden aspect-[70/99] bg-neutral-100">
                            <img :src="template.thumbnail" :alt="template.name" class="w-full h-full object-cover object-right transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6 pointer-events-none">
                                <a :href="'/editor?template_id=' + template.id" class="px-6 py-2.5 bg-white/90 backdrop-blur-sm text-neutral-900 text-sm font-bold rounded-full hover:bg-white transition-colors transform translate-y-4 group-hover:translate-y-0 duration-300 shadow-lg pointer-events-auto">
                                    Gunakan Template
                                </a>
                            </div>
                            <!-- Badge Category -->
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-sm">
                                <p class="text-xs font-bold text-brand-600" x-text="template.category_name"></p>
                            </div>
                            <!-- Badge Ukuran -->
                            <div class="absolute top-4 right-4 bg-neutral-900/80 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm border border-white/20">
                                <p class="text-[10px] font-bold text-white uppercase tracking-wider" x-text="template.book_size"></p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-neutral-900 mb-1" x-text="template.name"></h3>
                            <p class="text-sm text-neutral-500 mb-4" x-text="'Kategori: ' + template.category_name"></p>
                            <div class="mt-auto pt-4 border-t border-neutral-100">
                                <a :href="'/editor?template_id=' + template.id" class="w-full py-3 bg-brand-50 text-brand-600 font-semibold rounded-xl hover:bg-brand-600 hover:text-white transition-colors block text-center">
                                    Gunakan Template
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Empty State -->
            <div x-show="templates.filter(t => activeCategory === 'all' || t.category_id === activeCategory).length === 0" style="display: none;" class="text-center py-20">
                <div class="w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 class="text-xl font-bold text-neutral-900 mb-2">Belum ada template</h3>
                <p class="text-neutral-500">Template untuk kategori ini sedang disiapkan.</p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-neutral-100 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm text-neutral-500">© 2026 CeritaKu. Hak cipta dilindungi undang-undang.</p>
        </div>
    </footer>

</body>
</html>
