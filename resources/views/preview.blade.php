<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Preview Desain - CeritaKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #1e293b; }
        .canvas-container { margin: 0 auto; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
    </style>
</head>
<body class="h-screen flex flex-col overflow-hidden text-white" x-data="previewApp()">

    <!-- Header -->
    <header class="h-16 bg-slate-900 border-b border-slate-700 flex items-center justify-between px-4 md:px-6 flex-shrink-0 z-10">
        <div class="flex items-center gap-2 md:gap-4">
            <a :href="'/editor?design_code=' + designCode" class="text-sm font-medium text-slate-300 hover:text-white flex items-center gap-2 transition" title="Kembali">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                <span class="hidden sm:inline">Kembali ke Editor</span>
            </a>
            <div class="h-6 w-px bg-slate-700 mx-1 md:mx-2 hidden sm:block"></div>
            <h1 class="text-sm md:text-lg font-bold truncate max-w-[120px] sm:max-w-[200px] md:max-w-none" x-text="title"></h1>
        </div>
        <div class="flex items-center gap-2 md:gap-3">
            <span class="hidden md:inline text-sm text-slate-400 mr-4">Mode Preview (Halaman <span x-text="rawPages[currentIndex]?.id === 0 ? 'Cover' : rawPages[currentIndex]?.id"></span> dari <span x-text="rawPages.length - 1"></span>)</span>
            <span class="md:hidden text-xs text-slate-400 mr-2"><span x-text="rawPages[currentIndex]?.id === 0 ? 'Cov' : rawPages[currentIndex]?.id"></span>/<span x-text="rawPages.length - 1"></span></span>
            <button @click="openOrderModal = true" class="px-3 md:px-5 py-1.5 md:py-2 text-xs md:text-sm font-bold text-white bg-blue-600 rounded-md hover:bg-blue-700 transition shadow-lg shadow-blue-900/50 whitespace-nowrap">
                <span class="hidden sm:inline">Pesan Buku Sekarang</span>
                <span class="sm:hidden">Pesan</span>
            </button>
        </div>
    </header>

    <!-- Main Canvas Area -->
    <div class="flex-1 flex items-center justify-center relative p-4 md:p-10 bg-[url('https://www.transparenttextures.com/patterns/wood-pattern.png')] bg-amber-900 shadow-inner overflow-hidden" id="flipbook-wrapper">
        
        <!-- Loading State -->
        <div x-show="isLoadingFlipbook" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-slate-900 bg-opacity-90 text-white transition-opacity duration-500">
            <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
            <p class="text-lg font-bold" x-text="loadingText"></p>
            <p class="text-sm text-slate-400 mt-2">Mohon tunggu sebentar, menyiapkan animasi 3D...</p>
        </div>

        <!-- Flipbook Container -->
        <div id="flipbook-container" class="transition-transform duration-300 relative drop-shadow-2xl shadow-black z-10">
            <!-- Pages will be generated here -->
        </div>

        <!-- Watermark Overlay (Anti-Maling) -->
        <div class="absolute inset-0 z-[15] pointer-events-none overflow-hidden flex flex-wrap content-start justify-center opacity-[0.05]" style="transform: rotate(-15deg) scale(1.5);">
            <template x-for="i in 100">
                <span class="text-3xl md:text-5xl font-black text-white m-4 md:m-8 whitespace-nowrap uppercase drop-shadow-md">CeritaKu</span>
            </template>
        </div>

        <!-- Prev/Next Overlay Buttons -->
        <button @click="turnPrev()" x-show="!isLoadingFlipbook" 
                class="absolute left-2 md:left-10 p-3 md:p-4 bg-white/10 hover:bg-white/30 backdrop-blur-sm rounded-full text-white transition z-20 shadow-lg">
            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button @click="turnNext()" x-show="!isLoadingFlipbook" 
                class="absolute right-2 md:right-10 p-3 md:p-4 bg-white/10 hover:bg-white/30 backdrop-blur-sm rounded-full text-white transition z-20 shadow-lg">
            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
        </button>
    </div>

    <!-- Order Modal -->
    <div x-show="openOrderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70" style="display: none;" x-cloak>
        <div class="bg-white rounded-2xl p-6 w-[600px] max-w-full shadow-2xl max-h-[90vh] overflow-y-auto text-slate-800" @click.away="openOrderModal = false">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Pesan Memory Book</h2>
            <p class="text-gray-500 mb-6 text-sm">Lengkapi data untuk memesan buku kenangan Anda.</p>
            
            <form @submit.prevent="submitOrderForm" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" x-model="orderForm.customer_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-slate-800">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp *</label>
                        <input type="text" x-model="orderForm.whatsapp" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-slate-800" placeholder="081234567890">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                    <input type="email" x-model="orderForm.email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                    <textarea x-model="orderForm.address" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-slate-800"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Buku *</label>
                        <select x-model="orderForm.book_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-slate-800">
                            <option value="Hardcover">Hardcover Premium</option>
                            <option value="Softcover">Softcover Classic</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Buku *</label>
                        <input type="number" x-model="orderForm.quantity" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-slate-800">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea x-model="orderForm.note" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-slate-800"></textarea>
                </div>
                
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                    <button type="button" @click="openOrderModal = false" class="px-4 py-2 text-gray-600 font-medium hover:bg-gray-100 rounded-md">Batal</button>
                    <button type="submit" :disabled="isSubmittingOrder" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <span x-show="isSubmittingOrder" class="animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                        Pesan via WhatsApp
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay for Flipbook -->
    <div x-show="isLoadingFlipbook" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-gray-900 bg-opacity-95 backdrop-blur-sm">
        <div class="bg-white p-8 rounded-3xl shadow-2xl max-w-sm w-full text-center">
            <div class="mb-6 flex justify-center">
                <div class="relative w-24 h-24">
                    <svg class="animate-spin w-full h-full text-blue-100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8"></circle>
                    </svg>
                    <svg class="absolute top-0 left-0 w-full h-full text-blue-600" viewBox="0 0 100 100" style="transform: rotate(-90deg);">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" :stroke-dasharray="283" :stroke-dashoffset="283 - (283 * loadingProgress / 100)" class="transition-all duration-300 ease-out"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center font-black text-2xl text-blue-600" x-text="Math.round(loadingProgress) + '%'"></div>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-gray-800 mb-2">Membangun Buku 3D</h3>
            <p class="text-gray-500 font-medium text-sm mb-6" x-text="loadingText">Menyiapkan aset visual...</p>
            
            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-300 ease-out" :style="`width: ${loadingProgress}%`"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('previewApp', () => ({
                designCode: '{{ $design->design_code }}',
                title: '{{ $design->title }}',
                bookSize: '{{ $design->book_size ?? "A5" }}',
                canvasWidth: 500,
                canvasHeight: 709,
                singleWidth: 250,
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                currentIndex: 0,
                openOrderModal: false,
                isSubmittingOrder: false,
                rawPages: [],
                coverUrl: '{{ isset($design) && $design->template ? $design->template->thumbnail : "" }}',
                coverBackUrl: '{!! isset($design) && $design->template ? (is_array($design->template->layout_structure) && isset($design->template->layout_structure["cover_back_url"]) ? $design->template->layout_structure["cover_back_url"] : $design->template->thumbnail) : "" !!}',
                
                isLoadingFlipbook: true,
                loadingProgress: 0,
                loadingText: 'Menyiapkan canvas...',
                pageFlip: null,

                orderForm: {
                    customer_name: '',
                    whatsapp: '',
                    email: '',
                    address: '',
                    book_type: 'Hardcover',
                    quantity: 1,
                    note: ''
                },

                initCanvasSize() {
                    let w = 500; let h = 709; // Default A5/A4/B5 ratio
                    if (this.bookSize === 'Persegi') { w = 600; h = 600; }
                    else if (this.bookSize === 'Mini') { w = 500; h = 750; }
                    this.singleWidth = w;
                    this.canvasWidth = w * 2;
                    this.canvasHeight = h;
                },

                async init() {
                    this.initCanvasSize();
                    
                    @foreach($design->pages->sortBy('page_number') as $page)
                        this.rawPages.push({ id: {{ $page->page_number }}, json: {!! json_encode($page->photos) !!} });
                    @endforeach
                    
                    if(this.rawPages.length === 0) {
                        this.loadingText = "Tidak ada halaman";
                        return;
                    }

                    setTimeout(() => {
                        this.buildFlipbook();
                    }, 100);
                },

                async buildFlipbook() {
                    const flipbookContainer = document.getElementById('flipbook-container');
                    flipbookContainer.innerHTML = '';
                    
                    let flipbookHtml = '';
                    let backCoverHtml = '';
                    
                    const offscreenCanvas = new fabric.StaticCanvas(null, {
                        width: this.canvasWidth,
                        height: this.canvasHeight,
                        backgroundColor: '#ffffff'
                    });

                    // Set default font
                    fabric.Textbox.prototype.fontFamily = "'Plus Jakarta Sans', sans-serif";

                    for (let i = 0; i < this.rawPages.length; i++) {
                        this.loadingProgress = (i / this.rawPages.length) * 100;
                        this.loadingText = `Merender halaman ${i+1} dari ${this.rawPages.length}...`;
                        const page = this.rawPages[i];
                        
                        offscreenCanvas.clear();
                        
                        const applyStaticCovers = async (id) => {
                            if (id === 0) {
                                offscreenCanvas.backgroundColor = '#ffffff';
                                if (this.coverUrl) {
                                    await new Promise(resolve => {
                                        fabric.Image.fromURL(this.coverUrl, async (img) => {
                                            if (!img) return resolve();
                                            const isPortrait = img.width <= img.height;
                                            const targetWidth = isPortrait ? this.singleWidth : this.canvasWidth;
                                            const leftPos = isPortrait ? this.singleWidth : 0;
                                            const scale = Math.max(targetWidth / img.width, this.canvasHeight / img.height);
                                            
                                            if (isPortrait) {
                                                img.set({ scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: leftPos });
                                                offscreenCanvas.add(img);
                                                offscreenCanvas.sendToBack(img);
                                                
                                                if (this.coverBackUrl && this.coverBackUrl !== this.coverUrl) {
                                                    await new Promise(res => {
                                                        fabric.Image.fromURL(this.coverBackUrl, (backImg) => {
                                                            if (!backImg) return res();
                                                            const backScale = Math.max(targetWidth / backImg.width, this.canvasHeight / backImg.height);
                                                            backImg.set({ scaleX: backScale, scaleY: backScale, originX: 'left', originY: 'top', left: 0 });
                                                            offscreenCanvas.add(backImg);
                                                            offscreenCanvas.sendToBack(backImg);
                                                            res();
                                                        }, { crossOrigin: 'anonymous' });
                                                    });
                                                }
                                                offscreenCanvas.renderAll();
                                                resolve();
                                            } else {
                                                offscreenCanvas.setBackgroundImage(img, () => {
                                                    offscreenCanvas.renderAll();
                                                    resolve();
                                                }, { scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: 0 });
                                            }
                                        }, { crossOrigin: 'anonymous' });
                                    });
                                }
                            } else if (id === 9) {
                                offscreenCanvas.backgroundColor = '#ffffff';
                                if (this.coverBackUrl) {
                                    await new Promise(resolve => {
                                        fabric.Image.fromURL(this.coverBackUrl, (img) => {
                                            if (!img) return resolve();
                                            const isPortrait = img.width <= img.height;
                                            const targetWidth = isPortrait ? this.singleWidth : this.canvasWidth;
                                            const scale = Math.max(targetWidth / img.width, this.canvasHeight / img.height);
                                            offscreenCanvas.setBackgroundImage(img, () => {
                                                offscreenCanvas.renderAll();
                                                resolve();
                                            }, { scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: 0 });
                                        }, { crossOrigin: 'anonymous' });
                                    });
                                }
                            }
                        };
                        
                        if (page.json) {
                            await new Promise(resolve => {
                                offscreenCanvas.loadFromJSON(page.json, async () => {
                                    if (page.id === 0 || page.id === 9) await applyStaticCovers(page.id);
                                    else offscreenCanvas.renderAll();
                                    resolve();
                                });
                            });
                        } else {
                            offscreenCanvas.backgroundColor = '#ffffff';
                            offscreenCanvas.backgroundImage = null;
                            if (page.id === 0 || page.id === 9) {
                                await applyStaticCovers(page.id);
                            } else {
                                offscreenCanvas.renderAll();
                            }
                        }
                        
                        // Extract Left and Right halves (quality adjusted for preview speed vs clarity)
                        const leftData = offscreenCanvas.toDataURL({ format: 'jpeg', quality: 0.8, left: 0, top: 0, width: this.singleWidth, height: this.canvasHeight });
                        const rightData = offscreenCanvas.toDataURL({ format: 'jpeg', quality: 0.8, left: this.singleWidth, top: 0, width: this.singleWidth, height: this.canvasHeight });
                        
                        if (i === 0) {
                            // Cover is the first spread. Right side is the actual front cover.
                            flipbookHtml += `<div class="page" data-density="hard"><img src="${rightData}" class="w-full h-full object-cover"></div>`;
                            // Halaman balik cover depan (putih kosong)
                            flipbookHtml += `<div class="page" data-density="hard" style="background-color: white;"></div>`; 
                            
                            // Simpan cover belakang untuk dimasukkan di paling akhir
                            backCoverHtml = `<div class="page" data-density="hard"><img src="${leftData}" class="w-full h-full object-cover"></div>`;
                        } else {
                            // Halaman isi (Halaman 1, 2, dst)
                            flipbookHtml += `<div class="page"><img src="${leftData}" class="w-full h-full object-cover"></div>`;
                            flipbookHtml += `<div class="page"><img src="${rightData}" class="w-full h-full object-cover"></div>`;
                        }
                    }

                    // Tambahkan balik cover belakang (putih kosong) lalu cover belakang luar
                    flipbookHtml += `<div class="page" data-density="hard" style="background-color: white;"></div>`;
                    flipbookHtml += backCoverHtml;

                    flipbookContainer.innerHTML = flipbookHtml;

                    this.loadingProgress = 100;
                    this.loadingText = "Menyatukan halaman buku 3D...";

                    // Get screen size to determine initial scale
                    const wrapper = document.getElementById('flipbook-wrapper');
                    const isMobile = wrapper.clientWidth < 768;
                    const paddingW = isMobile ? 40 : 120;
                    
                    const availableWidth = wrapper.clientWidth - paddingW;
                    const availableHeight = wrapper.clientHeight - 80;
                    
                    // We need to scale the StPageFlip container since it uses fixed logic by default
                    const targetSpreadW = this.singleWidth * 2;
                    const scaleX = availableWidth / targetSpreadW;
                    const scaleY = availableHeight / this.canvasHeight;
                    
                    let scale = Math.min(scaleX, scaleY);
                    if (scale > 1) scale = 1;
                    
                    flipbookContainer.style.transform = `scale(${scale})`;

                    this.pageFlip = new St.PageFlip(flipbookContainer, {
                        width: this.singleWidth,
                        height: this.canvasHeight,
                        size: "fixed",
                        minWidth: this.singleWidth,
                        maxWidth: this.singleWidth,
                        minHeight: this.canvasHeight,
                        maxHeight: this.canvasHeight,
                        maxShadowOpacity: 0.5,
                        showCover: true,
                        mobileScrollSupport: false
                    });

                    this.pageFlip.loadFromHTML(document.querySelectorAll('.page'));
                    
                    this.pageFlip.on('flip', (e) => {
                        this.currentIndex = Math.floor(e.data / 2);
                    });

                    this.isLoadingFlipbook = false;
                    
                    // Handle window resize dynamically via scale
                    window.addEventListener('resize', () => {
                        const newAvailableWidth = wrapper.clientWidth - (wrapper.clientWidth < 768 ? 40 : 120);
                        const newAvailableHeight = wrapper.clientHeight - 80;
                        const newScale = Math.min(newAvailableWidth / targetSpreadW, newAvailableHeight / this.canvasHeight, 1);
                        flipbookContainer.style.transform = `scale(${newScale})`;
                    });
                },

                turnPrev() {
                    if (this.pageFlip) this.pageFlip.flipPrev();
                },

                turnNext() {
                    if (this.pageFlip) this.pageFlip.flipNext();
                },

                async submitOrderForm() {
                    this.isSubmittingOrder = true;
                    try {
                        const payload = {
                            design_code: this.designCode,
                            ...this.orderForm
                        };
                        
                        const response = await fetch('/editor/order', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify(payload)
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.wa_url) {
                            this.openOrderModal = false;
                            window.open(data.wa_url, '_blank');
                        } else {
                            alert('Gagal membuat pesanan. Silakan coba lagi.');
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan koneksi.');
                    } finally {
                        this.isSubmittingOrder = false;
                    }
                }
            }));
        });
    </script>
</body>
</html>
