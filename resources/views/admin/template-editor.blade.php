<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Template: {{ $template->name }} - Admin CeritaKu</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Fabric.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .canvas-container { margin: 0 auto; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="bg-slate-100 h-screen flex flex-col overflow-hidden" x-data="adminEditorApp()">

    <!-- Header -->
    <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 flex-shrink-0 z-10">
        <div class="flex items-center gap-4">
            <a href="/admin/template" class="text-slate-500 hover:text-slate-800">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-800">Edit Template: {{ $template->name }}</h1>
                <p class="text-xs text-slate-500">Tambahkan Teks Placeholder untuk pelanggan.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-slate-500 mr-2" x-text="saveStatus"></span>
            <button @click="saveTemplate()" class="px-6 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Simpan Desain Template
            </button>
        </div>
    </header>

    <!-- Main -->
    <div class="flex flex-1 overflow-hidden">
        
        <!-- Left Sidebar (Tools) -->
        <div class="w-80 bg-white border-r border-slate-200 flex flex-col z-10">
            <div class="p-6 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-800 text-lg">Alat Desain</h3>
                <p class="text-xs text-slate-500 mt-1">Tambahkan elemen yang nanti bisa diklik dan diedit oleh pelanggan.</p>
            </div>
            
            <div class="p-6 space-y-6 overflow-y-auto">
                <!-- Teks Tool -->
                <div>
                    <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 00-1-1H4a1 1 0 01-1-1V4a1 1 0 011-1h3a1 1 0 001-1v-1z"/></svg>
                        Teks Placeholder
                    </h4>
                    <div class="space-y-2">
                        <button @click="addText('[JUDUL UTAMA]', 48, 'bold')" class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-lg text-left hover:bg-blue-50 transition flex justify-between group">
                            <span class="font-bold text-xl text-slate-800">Judul Besar</span>
                            <span class="text-blue-600 opacity-0 group-hover:opacity-100">+</span>
                        </button>
                        <button @click="addText('[Nama Pasangan]', 32, 'normal')" class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-lg text-left hover:bg-blue-50 transition flex justify-between group">
                            <span class="font-semibold text-lg text-slate-800">Subjudul</span>
                            <span class="text-blue-600 opacity-0 group-hover:opacity-100">+</span>
                        </button>
                        <button @click="addText('19 Januari 2026', 18, 'normal')" class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-lg text-left hover:bg-blue-50 transition flex justify-between group">
                            <span class="text-sm text-slate-600">Teks Kecil (Tanggal)</span>
                            <span class="text-blue-600 opacity-0 group-hover:opacity-100">+</span>
                        </button>
                    </div>
                </div>

                @if(!$isStaticTemplate)
                <!-- Gambar Tool -->
                <div>
                    <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Ornamen & Background
                    </h4>
                    <div class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:bg-slate-50 transition cursor-pointer" @click="$refs.fileInput.click()">
                        <svg class="mx-auto h-8 w-8 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        <span class="text-sm text-slate-600 font-medium">Upload Gambar (PNG/JPG)</span>
                        <input type="file" x-ref="fileInput" class="hidden" accept="image/*" @change="uploadImage($event)">
                    </div>
                </div>

                <hr class="border-slate-200">
                @endif

                <!-- Stiker Tool -->
                <div>
                    <h4 class="text-sm font-bold text-slate-700 mb-3">Ornamen</h4>
                    <div class="grid grid-cols-4 gap-2 text-2xl text-center">
                        <button @click="addEmoji('❤️')" class="p-2 hover:bg-slate-100 rounded">❤️</button>
                        <button @click="addEmoji('✨')" class="p-2 hover:bg-slate-100 rounded">✨</button>
                        <button @click="addEmoji('🌟')" class="p-2 hover:bg-slate-100 rounded">🌟</button>
                        <button @click="addEmoji('💍')" class="p-2 hover:bg-slate-100 rounded">💍</button>
                    </div>
                </div>

                @if(!$isStaticTemplate)
                <!-- Latar (Background) Tool -->
                <div>
                    <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        Warna Latar
                    </h4>
                    <input type="color" x-model="bgColor" @input="changeBgColor()" class="w-full h-12 rounded cursor-pointer border-2 border-slate-200">
                </div>

                <hr class="border-slate-200">
                @else
                <div class="p-4 bg-amber-50 rounded-lg border border-amber-200 mt-4">
                    <p class="text-xs text-amber-700 font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        Template ini diunggah secara Statis. Anda tidak dapat mengubah gambar atau warna cover depan dan belakang dari editor ini.
                    </p>
                </div>
                <hr class="border-slate-200 mt-4">
                @endif

                <!-- Properties Tool (Visible when selected) -->
                <div x-show="isObjectSelected" class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100 shadow-sm">
                    <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Properti Objek</h4>
                    
                    <div class="mb-3">
                        <label class="text-xs text-slate-600 block mb-1">Warna</label>
                        <input type="color" x-model="selectedColor" @input="changeColor()" class="w-full h-8 rounded cursor-pointer">
                    </div>

                    <div class="mb-3" x-show="isTextSelected">
                        <label class="text-xs text-slate-600 block mb-1">Font Text</label>
                        <select x-model="selectedFont" @change="changeFont()" class="w-full px-2 py-1 text-sm border border-slate-300 rounded">
                            <option value="Plus Jakarta Sans">Plus Jakarta Sans</option>
                            <option value="'Londrina Solid'">Londrina Solid Black</option>
                            <option value="Arial">Arial</option>
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Courier New">Courier New</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Verdana">Verdana</option>
                        </select>
                    </div>
                    
                    <button @click="deleteSelected()" class="w-full mt-4 py-2 bg-red-100 text-red-700 font-bold rounded-lg hover:bg-red-200 transition text-sm">
                        Hapus Objek
                    </button>
                </div>
            </div>
        </div>

        <!-- Center Canvas -->
        <!-- Canvas Container Wrapper -->
        <div class="flex-1 bg-slate-200 flex items-center justify-center overflow-auto p-8 relative" id="canvas-wrapper">
            <div class="relative" :style="`width: ${canvasWidth * canvasScale}px; height: ${canvasHeight * canvasScale}px;`">
                <div class="bg-white shadow-2xl origin-top-left absolute top-0 left-0 z-0" :style="`width: ${canvasWidth}px; height: ${canvasHeight}px; transform: scale(${canvasScale}); transition: transform 0.2s ease-out;`">
                    <canvas id="template-canvas"></canvas>
                    
                    <!-- Center Fold Line Overlay -->
                    <div class="absolute inset-y-0 w-px bg-black/30" style="left: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.5); pointer-events: none; z-index: 50;"></div>
                </div>
                
                <!-- Spread Labels (Below Canvas) -->
                <div class="absolute -bottom-8 left-0 right-0 flex justify-between px-4 text-gray-500 font-bold text-sm w-full" style="pointer-events: none;">
                    <span class="w-1/2 text-center">Cover Belakang / Kiri</span>
                    <span class="w-1/2 text-center">Cover Depan / Kanan</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        let fabricCanvas = null;

        document.addEventListener('alpine:init', () => {
            Alpine.data('adminEditorApp', () => ({
                templateId: {{ $template->id }},
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                saveStatus: '',
                isObjectSelected: false,
                isTextSelected: false,
                selectedColor: '#000000',
                selectedFont: 'Plus Jakarta Sans',
                bgColor: '#ffffff',
                thumbnailUrl: '{{ $template->thumbnail }}',
                savedJson: {!! $template->layout_structure ? json_encode($template->layout_structure) : 'null' !!},
                bookSize: '{{ $template->book_size ?? "A5" }}',
                canvasWidth: 500,
                canvasHeight: 709,
                canvasScale: 1,

                initCanvasSize() {
                    let w = 500; let h = 709; // Default A5/A4/B5 ratio
                    if (this.bookSize === 'Persegi') { w = 600; h = 600; }
                    else if (this.bookSize === 'Mini') { w = 500; h = 750; }
                    this.singlePageWidth = w;
                    this.canvasWidth = w * 2; // SPREAD WIDTH
                    this.canvasHeight = h;
                },

                resizeCanvas() {
                    const wrapper = document.getElementById('canvas-wrapper');
                    if(!wrapper) return;
                    
                    const availableWidth = wrapper.clientWidth - 64; 
                    const availableHeight = wrapper.clientHeight - 64;
                    
                    const originalWidth = this.canvasWidth;
                    const originalHeight = this.canvasHeight;
                    
                    const scaleX = availableWidth / originalWidth;
                    const scaleY = availableHeight / originalHeight;
                    
                    let scale = Math.min(scaleX, scaleY);
                    if (scale > 1) scale = 1;
                    
                    this.canvasScale = scale;
                    
                    setTimeout(() => {
                        if(fabricCanvas) {
                            fabricCanvas.calcOffset();
                            fabricCanvas.renderAll();
                        }
                    }, 50);
                },

                init() {
                    this.initCanvasSize();
                    
                    setTimeout(() => {
                        fabricCanvas = new fabric.Canvas('template-canvas', {
                            width: this.canvasWidth,
                            height: this.canvasHeight,
                            backgroundColor: this.bgColor,
                            preserveObjectStacking: true
                        });
                        
                        this.resizeCanvas();
                        window.addEventListener('resize', () => this.resizeCanvas());

                        fabric.Textbox.prototype.fontFamily = "'Plus Jakarta Sans', sans-serif";

                        // Selection events
                        fabricCanvas.on('selection:created', (e) => this.handleSelection(e));
                        fabricCanvas.on('selection:updated', (e) => this.handleSelection(e));
                        fabricCanvas.on('selection:cleared', () => { 
                            this.isObjectSelected = false; 
                            this.isTextSelected = false;
                        });

                        // Delete / Backspace Keyboard Shortcut
                        window.addEventListener('keydown', (e) => {
                            // Don't delete if user is typing in an input field
                            if (e.target.tagName.toLowerCase() === 'input' || e.target.tagName.toLowerCase() === 'textarea') return;
                            
                            if (e.key === 'Delete' || e.key === 'Backspace') {
                                const activeObj = fabricCanvas.getActiveObject();
                                // Don't delete if editing text inside canvas
                                if (activeObj && activeObj.isEditing) return;
                                
                                this.deleteSelected();
                            }
                        });

                        const applyStaticCovers = () => {
                            if (this.thumbnailUrl) {
                                fabric.Image.fromURL(this.thumbnailUrl, (img) => {
                                    if (!img) return;
                                    const isPortrait = img.width <= img.height;
                                    const targetWidth = isPortrait ? fabricCanvas.width / 2 : fabricCanvas.width;
                                    const leftPos = isPortrait ? fabricCanvas.width / 2 : 0;
                                    const scale = Math.max(targetWidth / img.width, fabricCanvas.height / img.height);
                                    
                                    if (isPortrait) {
                                        img.set({
                                            scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: leftPos,
                                            selectable: false, evented: false, excludeFromExport: true
                                        });
                                        fabricCanvas.add(img);
                                        fabricCanvas.sendToBack(img);
                                        
                                        const coverBackUrl = this.savedJson && this.savedJson.cover_back_url ? this.savedJson.cover_back_url : null;
                                        if (coverBackUrl && coverBackUrl !== this.thumbnailUrl) {
                                            fabric.Image.fromURL(coverBackUrl, (backImg) => {
                                                if (!backImg) return;
                                                const backScale = Math.max(targetWidth / backImg.width, fabricCanvas.height / backImg.height);
                                                backImg.set({
                                                    scaleX: backScale, scaleY: backScale, originX: 'left', originY: 'top', left: 0,
                                                    selectable: false, evented: false, excludeFromExport: true
                                                });
                                                fabricCanvas.add(backImg);
                                                fabricCanvas.sendToBack(backImg);
                                                fabricCanvas.renderAll();
                                            }, { crossOrigin: 'anonymous' });
                                        } else {
                                            fabricCanvas.renderAll();
                                        }
                                    } else {
                                        fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas), {
                                            scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: leftPos
                                        });
                                    }
                                }, { crossOrigin: 'anonymous' });
                            }
                        };

                        // Load saved data or initialize with background
                        if (this.savedJson && this.savedJson.objects) {
                            fabricCanvas.loadFromJSON(this.savedJson, () => {
                                if (fabricCanvas.backgroundColor) {
                                    this.bgColor = fabricCanvas.backgroundColor;
                                }
                                applyStaticCovers();
                            });
                        } else {
                            applyStaticCovers();
                        }
                    }, 100);
                },

                handleSelection(e) {
                    if(e.selected && e.selected.length > 0) {
                        this.isObjectSelected = true;
                        const obj = e.selected[0];
                        
                        if (obj.fill && typeof obj.fill === 'string') {
                            this.selectedColor = obj.fill;
                        }

                        if (obj.type === 'text' || obj.type === 'textbox') {
                            this.isTextSelected = true;
                            if (obj.fontFamily) {
                                // Remove quotes if present
                                this.selectedFont = obj.fontFamily.replace(/['"]/g, '');
                            }
                        } else {
                            this.isTextSelected = false;
                        }
                    }
                },

                changeColor() {
                    const activeObj = fabricCanvas.getActiveObject();
                    if (activeObj) {
                        activeObj.set('fill', this.selectedColor);
                        fabricCanvas.renderAll();
                    }
                },

                changeFont() {
                    const activeObj = fabricCanvas.getActiveObject();
                    if (activeObj && (activeObj.type === 'text' || activeObj.type === 'textbox')) {
                        activeObj.set('fontFamily', this.selectedFont);
                        if (this.selectedFont === "'Londrina Solid'") {
                            activeObj.set('fontWeight', 900);
                        }
                        fabricCanvas.renderAll();
                    }
                },

                changeBgColor() {
                    fabricCanvas.backgroundColor = this.bgColor;
                    fabricCanvas.renderAll();
                },

                addText(textStr, size, weight) {
                    const text = new fabric.Textbox(textStr, {
                        left: 100,
                        top: 200,
                        width: 300,
                        fontSize: size,
                        fontWeight: weight,
                        fill: '#1e293b',
                        textAlign: 'center',
                    });
                    fabricCanvas.add(text);
                    fabricCanvas.setActiveObject(text);
                },

                addEmoji(emoji) {
                    const text = new fabric.Text(emoji, {
                        left: 200, top: 300, fontSize: 64,
                    });
                    fabricCanvas.add(text);
                    fabricCanvas.setActiveObject(text);
                },

                uploadImage(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (f) => {
                        const data = f.target.result;
                        fabric.Image.fromURL(data, (img) => {
                            // Scale down if too large
                            if (img.width > 400) {
                                img.scaleToWidth(400);
                            }
                            fabricCanvas.add(img);
                            fabricCanvas.centerObject(img);
                            fabricCanvas.setActiveObject(img);
                            fabricCanvas.renderAll();
                        });
                    };
                    reader.readAsDataURL(file);
                    event.target.value = '';
                },

                deleteSelected() {
                    const activeObjects = fabricCanvas.getActiveObjects();
                    if (activeObjects.length) {
                        fabricCanvas.discardActiveObject();
                        activeObjects.forEach(function(object) {
                            fabricCanvas.remove(object);
                        });
                        this.isObjectSelected = false;
                    }
                },

                async saveTemplate() {
                    this.saveStatus = 'Menyimpan...';
                    
                    // Deselect object so controls aren't in thumbnail
                    fabricCanvas.discardActiveObject();
                    fabricCanvas.renderAll();
                    
                    const json = JSON.stringify(fabricCanvas.toJSON());
                    // Export only the right half (front cover) for the thumbnail
                    const thumbnail_data_url = fabricCanvas.toDataURL({ 
                        format: 'jpeg', 
                        quality: 0.8,
                        left: this.singlePageWidth,
                        top: 0,
                        width: this.singlePageWidth,
                        height: this.canvasHeight
                    });

                    try {
                        const response = await fetch(`/admin/template/${this.templateId}/save`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify({ 
                                json: json,
                                thumbnail_data_url: thumbnail_data_url 
                            })
                        });
                        
                        if (response.ok) {
                            this.saveStatus = 'Tersimpan ✓';
                            setTimeout(() => {
                                this.saveStatus = '';
                                window.location.href = '/admin/template';
                            }, 1500);
                        } else {
                            this.saveStatus = 'Gagal menyimpan!';
                        }
                    } catch (e) {
                        this.saveStatus = 'Error server.';
                    }
                }
            }));
        });
    </script>
</body>
</html>
