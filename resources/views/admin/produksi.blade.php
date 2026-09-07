@extends('admin.layout')

@section('title', 'Produksi: ' . $design->design_code)

@section('content')
<div x-data="produksiApp()" class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <a href="/admin/pesanan" class="text-sm text-slate-500 hover:text-slate-800 flex items-center gap-1 font-medium">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Pesanan
        </a>
    </div>

    <!-- Production Actions -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Aksi Produksi</h3>
        <p class="text-slate-500 text-sm mb-6">Pilih format output untuk diproses ke mesin cetak. Format PDF akan dibuat secara langsung di browser Anda sesuai desain asli pelanggan tanpa mengubah resolusi desain.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Generate HD Images (ZIP) -->
            <button @click="generateImagesZip()" :disabled="isGeneratingImages" class="flex flex-col items-center justify-center p-6 border-2 border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group disabled:opacity-50 disabled:cursor-not-allowed">
                <div x-show="!isGeneratingImages">
                    <svg class="w-10 h-10 text-slate-400 group-hover:text-blue-500 mb-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                </div>
                <div x-show="isGeneratingImages" class="w-10 h-10 mb-3 flex items-center justify-center">
                    <svg class="animate-spin w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <span class="font-bold text-slate-700 group-hover:text-blue-700" x-text="isGeneratingImages ? 'Memproses ZIP...' : 'Download Desain HD (ZIP)'"></span>
                <span class="text-xs text-slate-500 mt-1 text-center" x-text="statusTextImages">Gambar HD untuk dikirim ke pelanggan.</span>
            </button>

            <!-- Download JSON -->
            <a href="/admin/pesanan/{{ $design->design_code }}/download-json" class="flex flex-col items-center justify-center p-6 border-2 border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group">
                <svg class="w-10 h-10 text-slate-400 group-hover:text-blue-500 mb-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                <span class="font-bold text-slate-700 group-hover:text-blue-700">Download Layout (JSON)</span>
                <span class="text-xs text-slate-500 mt-1 text-center">Data posisi dan struktur buku.</span>
            </a>

            <!-- Generate Final PDF -->
            <button @click="generatePDF()" :disabled="isGeneratingPDF" class="flex flex-col items-center justify-center p-6 border-2 border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group disabled:opacity-50 disabled:cursor-not-allowed">
                <div x-show="!isGeneratingPDF">
                    <svg class="w-10 h-10 text-slate-400 group-hover:text-blue-500 mb-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <div x-show="isGeneratingPDF" class="w-10 h-10 mb-3 flex items-center justify-center">
                    <svg class="animate-spin w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <span class="font-bold text-slate-700 group-hover:text-blue-700" x-text="isGeneratingPDF ? 'Memproses PDF...' : 'Generate PDF Cetak'"></span>
                <span class="text-xs text-slate-500 mt-1 text-center" x-text="statusTextPDF">Sesuai desain pelanggan (Identik 100%).</span>
            </button>
        </div>
    </div>

    <!-- Hidden Canvas for processing -->
    <div style="display: none;" wire:ignore>
        <canvas id="pdf-canvas" width="500" height="700"></canvas>
    </div>

</div>

<!-- Fabric.js, jsPDF, JSZip, FileSaver -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('produksiApp', () => ({
            isGeneratingPDF: false,
            statusTextPDF: 'Sesuai desain pelanggan (Identik 100%).',
            isGeneratingImages: false,
            statusTextImages: 'Gambar HD untuk dikirim ke pelanggan.',
            
            designCode: '{{ $design->design_code }}',
            bookSize: '{{ $design->book_size ?? "A5" }}',
            canvasWidth: 1000,
            canvasHeight: 709,
            singleWidth: 500,
            
            // Layout Data
            coverFrontUrl: '{{ $design->template ? ($design->template->cover_front ?? $design->template->thumbnail) : "" }}',
            coverBackUrl: '{!! $design->template ? (is_array($design->template->layout_structure) && isset($design->template->layout_structure["cover_back_url"]) ? $design->template->layout_structure["cover_back_url"] : ($design->template->cover_back ?? $design->template->thumbnail)) : "" !!}',
            pages: [
                @foreach($design->pages->sortBy('page_number') as $page)
                    { id: {{ $page->page_number }}, json: {!! json_encode($page->photos) !!} },
                @endforeach
            ],

            init() {
                let w = 500; let h = 709;
                if (this.bookSize === 'Persegi') { w = 600; h = 600; }
                else if (this.bookSize === 'Mini') { w = 500; h = 750; }
                this.singleWidth = w;
                this.canvasWidth = w * 2; // Spread
                this.canvasHeight = h;
                
                const canvasEl = document.getElementById('pdf-canvas');
                canvasEl.width = this.canvasWidth;
                canvasEl.height = this.canvasHeight;
            },

            // HELPER: Convert Image URL to Base64
            urlToDataUrl(url, isSpread = false) {
                return new Promise((resolve, reject) => {
                    if(!url) return resolve(null);
                    const img = new Image();
                    img.crossOrigin = 'Anonymous';
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        canvas.width = isSpread ? this.canvasWidth : this.singleWidth;
                        canvas.height = this.canvasHeight;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        resolve(canvas.toDataURL('image/jpeg', 1.0));
                    };
                    img.onerror = () => resolve(null);
                    img.src = url;
                });
            },

            applyCoverBackgrounds(canvas) {
                return new Promise(resolve => {
                    let loadedCount = 0;
                    const checkDone = () => { loadedCount++; if(loadedCount === 2) resolve(); };
                    
                    if (this.coverFrontUrl) {
                        fabric.Image.fromURL(this.coverFrontUrl, (img) => {
                            if(img) {
                                const scale = Math.max(this.singleWidth / img.width, this.canvasHeight / img.height);
                                img.set({ scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: this.singleWidth });
                                canvas.add(img);
                                canvas.sendToBack(img);
                            }
                            checkDone();
                        }, { crossOrigin: 'anonymous' });
                    } else { checkDone(); }

                    if (this.coverBackUrl) {
                        fabric.Image.fromURL(this.coverBackUrl, (img) => {
                            if(img) {
                                const scale = Math.max(this.singleWidth / img.width, this.canvasHeight / img.height);
                                img.set({ scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: 0 });
                                canvas.add(img);
                                canvas.sendToBack(img);
                            }
                            checkDone();
                        }, { crossOrigin: 'anonymous' });
                    } else { checkDone(); }
                });
            },

            processAndCrop(canvas, pageJson, side, isCover = false) {
                return new Promise(resolve => {
                    canvas.clear();
                    canvas.backgroundColor = '#ffffff';
                    
                    const doCrop = () => {
                        canvas.renderAll();
                        const cropLeft = side === 'left' ? 0 : this.singleWidth;
                        const dataUrl = canvas.toDataURL({
                            format: 'jpeg', quality: 1, multiplier: 5,
                            left: cropLeft, top: 0, width: this.singleWidth, height: this.canvasHeight
                        });
                        resolve(dataUrl);
                    };

                    if (!pageJson) {
                        if (isCover) this.applyCoverBackgrounds(canvas).then(doCrop);
                        else doCrop();
                        return;
                    }
                    
                    canvas.loadFromJSON(pageJson, () => {
                        if (isCover) {
                            this.applyCoverBackgrounds(canvas).then(doCrop);
                        } else {
                            doCrop();
                        }
                    });
                });
            },

            async generateImagesZip() {
                this.isGeneratingImages = true;
                this.statusTextImages = 'Menyiapkan file ZIP...';
                
                try {
                    const zip = new JSZip();
                    const imgFolder = zip.folder("Foto_Pelanggan");
                    const pad = (num) => String(num).padStart(3, '0');

                    const tempCanvas = new fabric.StaticCanvas('pdf-canvas', {
                        width: this.canvasWidth, 
                        height: this.canvasHeight,
                        backgroundColor: '#ffffff'
                    });
                    fabric.Textbox.prototype.fontFamily = "'Plus Jakarta Sans', sans-serif";

                    // Helper to download and resize raw image to match book size exactly
                    const fetchAndResizeImage = async (url) => {
                        if(!url) return null;
                        try {
                            const response = await fetch(url);
                            const blob = await response.blob();
                            return new Promise((resolve, reject) => {
                                const reader = new FileReader();
                                reader.onloadend = () => {
                                    const img = new Image();
                                    img.onload = () => {
                                        // Create a canvas exactly the size of a single page
                                        const c = document.createElement('canvas');
                                        // Using 2x multiplier for HD quality
                                        c.width = this.singleWidth * 2;
                                        c.height = this.canvasHeight * 2;
                                        const ctx = c.getContext('2d');
                                        
                                        // object-fit: cover logic
                                        const scale = Math.max(c.width / img.width, c.height / img.height);
                                        const w = img.width * scale;
                                        const h = img.height * scale;
                                        const left = (c.width - w) / 2;
                                        const top = (c.height - h) / 2;
                                        
                                        ctx.drawImage(img, left, top, w, h);
                                        resolve(c.toDataURL('image/jpeg', 1.0).split(',')[1]);
                                    };
                                    img.onerror = reject;
                                    img.src = reader.result;
                                };
                                reader.onerror = reject;
                                reader.readAsDataURL(blob);
                            });
                        } catch (e) {
                            console.error("Fetch image error: ", e);
                            return null;
                        }
                    };

                    const coverPage = this.pages.find(p => p.id === 0);
                    const innerPages = this.pages.filter(p => p.id !== 0).sort((a,b) => a.id - b.id);
                    
                    const parsePageJson = (jsonStr) => {
                        if(!jsonStr) return null;
                        try {
                            return typeof jsonStr === 'string' ? JSON.parse(jsonStr) : jsonStr;
                        } catch(e) { return null; }
                    };

                    // 1. Cover (Rendered exactly like PDF to keep backgrounds and text)
                    this.statusTextImages = 'Memproses Cover...';
                    let coverDepanData = await this.processAndCrop(tempCanvas, coverPage ? coverPage.json : null, 'right', true);
                    if(coverDepanData) imgFolder.file(`000_Cover_Depan.jpg`, coverDepanData.split(',')[1], {base64: true});
                    
                    let coverBelakangData = await this.processAndCrop(tempCanvas, coverPage ? coverPage.json : null, 'left', true);
                    if(coverBelakangData) imgFolder.file(`999_Cover_Belakang.jpg`, coverBelakangData.split(',')[1], {base64: true});

                    // 2. Inner Pages (Extract photos and resize to book size without white borders)
                    let physicalPageCounter = 1;
                    for(let i=0; i<innerPages.length; i++) {
                        const page = innerPages[i];
                        this.statusTextImages = `Memproses Halaman ${(i*2)+1} & ${(i*2)+2}...`;
                        
                        const parsedPage = parsePageJson(page.json);
                        if (parsedPage && parsedPage.objects) {
                            const leftObjects = [];
                            const rightObjects = [];
                            
                            parsedPage.objects.forEach(obj => {
                                if(obj.type === 'image' && obj.src && !obj.excludeFromExport) {
                                    if(obj.left < this.singleWidth) leftObjects.push(obj);
                                    else rightObjects.push(obj);
                                }
                            });
                            
                            // Left Side
                            let leftCount = 1;
                            for(const obj of leftObjects) {
                                const base64Data = await fetchAndResizeImage(obj.src);
                                if(base64Data) {
                                    const suffix = leftObjects.length > 1 ? `_${leftCount}` : '';
                                    imgFolder.file(`Hal_${pad(physicalPageCounter)}${suffix}.jpg`, base64Data, {base64: true});
                                    leftCount++;
                                }
                            }
                            physicalPageCounter++;
                            
                            // Right Side
                            let rightCount = 1;
                            for(const obj of rightObjects) {
                                const base64Data = await fetchAndResizeImage(obj.src);
                                if(base64Data) {
                                    const suffix = rightObjects.length > 1 ? `_${rightCount}` : '';
                                    imgFolder.file(`Hal_${pad(physicalPageCounter)}${suffix}.jpg`, base64Data, {base64: true});
                                    rightCount++;
                                }
                            }
                            physicalPageCounter++;
                        } else {
                            physicalPageCounter += 2;
                        }
                    }

                    this.statusTextImages = 'Menyimpan ZIP...';
                    const content = await zip.generateAsync({type:"blob"});
                    saveAs(content, `FotoAsli_${this.designCode}.zip`);
                    this.statusTextImages = 'Selesai!';
                } catch (error) {
                    console.error("ZIP Error: ", error);
                    alert("Gagal memproses gambar. Pastikan koneksi stabil.");
                    this.statusTextImages = 'Terjadi kesalahan.';
                } finally {
                    setTimeout(() => {
                        this.isGeneratingImages = false;
                        this.statusTextImages = 'Gambar HD untuk dikirim ke pelanggan.';
                    }, 2000);
                }
            },

            async generatePDF() {
                this.isGeneratingPDF = true;
                this.statusTextPDF = 'Menyiapkan dokumen...';
                
                try {
                    const { jsPDF } = window.jspdf;
                    const dynamicDoc = new jsPDF({ unit: "px" });
                    dynamicDoc.deletePage(1);

                    const addDynamicImageToDoc = (dataUrl, w, h) => {
                        dynamicDoc.addPage([w, h], w > h ? "landscape" : "portrait");
                        dynamicDoc.addImage(dataUrl, 'JPEG', 0, 0, w, h);
                    }

                    const tempCanvas = new fabric.StaticCanvas('pdf-canvas', {
                        width: this.canvasWidth, 
                        height: this.canvasHeight,
                        backgroundColor: '#ffffff'
                    });
                    fabric.Textbox.prototype.fontFamily = "'Plus Jakarta Sans', sans-serif";

                    const coverPage = this.pages.find(p => p.id === 0);
                    const innerPages = this.pages.filter(p => p.id !== 0).sort((a,b) => a.id - b.id);

                    // 1. Cover Depan
                    this.statusTextPDF = 'Memproses Cover Depan...';
                    let coverDepanData = await this.processAndCrop(tempCanvas, coverPage ? coverPage.json : null, 'right', true);
                    addDynamicImageToDoc(coverDepanData, this.singleWidth, this.canvasHeight);

                    // 2. Inner Pages
                    for(let i=0; i<innerPages.length; i++) {
                        const page = innerPages[i];
                        this.statusTextPDF = `Memproses Halaman ${(i*2)+1} & ${(i*2)+2}...`;
                        
                        const leftData = await this.processAndCrop(tempCanvas, page.json, 'left', false);
                        addDynamicImageToDoc(leftData, this.singleWidth, this.canvasHeight);
                        
                        const rightData = await this.processAndCrop(tempCanvas, page.json, 'right', false);
                        addDynamicImageToDoc(rightData, this.singleWidth, this.canvasHeight);
                    }

                    // 3. Cover Belakang
                    this.statusTextPDF = 'Memproses Cover Belakang...';
                    let coverBelakangData = await this.processAndCrop(tempCanvas, coverPage ? coverPage.json : null, 'left', true);
                    addDynamicImageToDoc(coverBelakangData, this.singleWidth, this.canvasHeight);

                    this.statusTextPDF = 'Menyimpan PDF...';
                    dynamicDoc.save(`CeritaKu_${this.designCode}_Cetak.pdf`);
                    this.statusTextPDF = 'Selesai!';
                } catch (error) {
                    console.error("PDF Error: ", error);
                    alert("Gagal memproses PDF.");
                    this.statusTextPDF = 'Terjadi kesalahan.';
                } finally {
                    setTimeout(() => {
                        this.isGeneratingPDF = false;
                        this.statusTextPDF = 'Sesuai desain pelanggan (1 Hal/Gambar).';
                    }, 2000);
                }
            }
        }));
    });
</script>
@endsection
