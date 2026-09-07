<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editor Memory Book - CeritaKu</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Fabric.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .canvas-container { margin: 0 auto; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Slot styling in canvas */
        .slot-hover { cursor: pointer; }
    </style>
</head>
<body class="bg-gray-100 overflow-hidden h-screen flex flex-col" x-data="editorApp()">

    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-3 md:px-6 flex-shrink-0 z-30 relative">
        <div class="flex items-center gap-2 md:gap-4">
            <!-- Mobile Tools Toggle -->
            <button @click="mobileToolsOpen = !mobileToolsOpen; mobilePagesOpen = false" class="md:hidden p-1.5 text-gray-500 hover:text-blue-600 hover:bg-gray-100 rounded-md">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
            </button>
            <a href="/" class="text-lg md:text-xl font-bold text-gray-900 flex items-center gap-2">
                <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-600 rounded text-white flex items-center justify-center text-xs md:text-base">C</div>
                <span class="hidden md:inline">Cerita<span class="text-blue-600">Ku</span></span>
            </a>
            <div class="hidden md:block h-6 w-px bg-gray-300 mx-2"></div>
            <input type="text" x-model="projectName" class="hidden md:block text-lg font-medium text-gray-700 bg-transparent border-none focus:outline-none focus:ring-0 w-32 lg:w-56" placeholder="Nama Proyek...">
            <div class="hidden lg:block h-6 w-px bg-gray-300 mx-2"></div>
            <div class="hidden md:flex items-center bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-100">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Ukuran: <span x-text="bookSize" class="ml-1 uppercase"></span>
            </div>
        </div>
        <div class="flex items-center gap-2 md:gap-3">
            <span class="hidden md:inline text-xs text-gray-500 italic mr-2" x-text="saveStatus"></span>
            
            <!-- Undo/Redo -->
            <div class="hidden md:flex items-center bg-gray-100 rounded-md overflow-hidden mr-1">
                <button @click="undo()" :disabled="!canUndo()" class="p-1.5 text-gray-600 hover:bg-gray-200 disabled:opacity-30 transition" title="Undo (Ctrl+Z)">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                </button>
                <div class="w-px h-4 bg-gray-300"></div>
                <button @click="redo()" :disabled="!canRedo()" class="p-1.5 text-gray-600 hover:bg-gray-200 disabled:opacity-30 transition" title="Redo (Ctrl+Y)">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6" /></svg>
                </button>
            </div>

            <button @click="saveProject(true)" class="px-3 md:px-4 py-1.5 md:py-2 text-xs md:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                Simpan
            </button>
            <a :href="'/preview?design_code=' + designCode" target="_blank" class="hidden md:inline-flex px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                Preview
            </a>
            <button @click="openOrderModal = true" class="px-3 md:px-5 py-1.5 md:py-2 text-xs md:text-sm font-bold text-white bg-blue-600 rounded-md hover:bg-blue-700 transition shadow-sm">
                Pesan
            </button>
            <!-- Mobile Pages Toggle -->
            <button @click="mobilePagesOpen = !mobilePagesOpen; mobileToolsOpen = false" class="md:hidden p-1.5 text-gray-500 hover:text-blue-600 hover:bg-gray-100 rounded-md">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex flex-1 overflow-hidden">
        
        <!-- Left Sidebar (Tools) -->
        <div :class="mobileToolsOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="absolute md:relative w-80 h-[calc(100vh-64px)] md:h-auto bg-white border-r border-gray-200 flex flex-shrink-0 z-20 transition-transform duration-300 ease-in-out shadow-2xl md:shadow-none">
            <!-- Tool Tabs -->
            <div class="w-20 bg-gray-50 border-r border-gray-200 flex flex-col py-4 gap-2 items-center">
                <template x-for="tab in tabs" :key="tab.id">
                    <button @click="activeTab = tab.id" 
                            :class="activeTab === tab.id ? 'bg-blue-100 text-blue-600' : 'text-gray-500 hover:bg-gray-200'"
                            class="w-14 h-14 rounded-xl flex flex-col items-center justify-center gap-1 transition-colors">
                        <span x-html="tab.icon" class="w-6 h-6"></span>
                        <span class="text-[10px] font-semibold" x-text="tab.name"></span>
                    </button>
                </template>
            </div>
            
            <!-- Tool Panels -->
            <div class="flex-1 flex flex-col bg-white">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800" x-text="tabs.find(t => t.id === activeTab).name"></h3>
                </div>
                <div class="p-4 overflow-y-auto flex-1">
                    
                    <!-- Panel Foto -->
                    <div x-show="activeTab === 'foto'" class="space-y-4">
                        
                        <!-- Magic Auto-Fill Button -->
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-4 text-white shadow-lg relative overflow-hidden group cursor-pointer" @click="$refs.autoFillInput.click()">
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <svg class="w-8 h-8 mb-2 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                                <span class="font-bold text-base">✨ Magic Auto-Fill</span>
                                <span class="text-xs text-purple-100 mt-1 opacity-90">Pilih banyak foto, sistem isi otomatis!</span>
                            </div>
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            <input type="file" x-ref="autoFillInput" class="hidden" accept="image/*" multiple @change="handleAutoFill($event)">
                        </div>

                        <!-- Auto-Fill Progress -->
                        <div x-show="isAutoFilling" class="bg-indigo-50 border border-indigo-100 rounded-lg p-3">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-bold text-indigo-700" x-text="autoFillText"></span>
                                <span class="text-xs font-bold text-indigo-700" x-text="Math.round(autoFillProgress) + '%'"></span>
                            </div>
                            <div class="w-full bg-indigo-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" :style="`width: ${autoFillProgress}%`"></div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <div class="flex-1 border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition cursor-pointer flex flex-col items-center justify-center" @click="$refs.fileInput.click()">
                                <svg class="h-6 w-6 text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                <span class="text-[10px] text-gray-600 font-bold uppercase tracking-wider">Upload Satuan</span>
                                <input type="file" x-ref="fileInput" class="hidden" accept="image/*" @change="uploadPhoto($event)">
                            </div>
                        </div>
                        
                        <!-- Hidden input for slot photo upload -->
                        <input type="file" id="slot-file-input" class="hidden" accept="image/*" @change="fillSlotPhoto($event)">

                        <!-- Gallery Grid -->
                        <div class="mt-6 border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-bold text-gray-700 mb-3">Galeri Foto Saya</h4>
                            <div class="grid grid-cols-2 gap-2" x-show="gallery.length > 0">
                                <template x-for="(imgUrl, index) in gallery" :key="index">
                                    <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:border-blue-500 cursor-grab"
                                         draggable="true" @dragstart="dragStart($event, imgUrl)">
                                        <img :src="imgUrl" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center">
                                            <button @click="addToCanvas(imgUrl)" class="bg-white text-gray-800 text-xs font-bold py-1 px-2 rounded opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition shadow">Pakai</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div x-show="gallery.length === 0" class="text-center p-4 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                <p class="text-xs text-gray-500">Belum ada foto yang diupload.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Teks -->
                    <div x-show="activeTab === 'teks'" class="space-y-4">
                        <button @click="addText('Judul Besar', 36, 'bold')" class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-lg text-left hover:bg-blue-50 transition flex items-center justify-between group shadow-sm">
                            <span class="font-bold text-2xl text-gray-800">Tambahkan Judul</span>
                            <span class="text-blue-600 opacity-0 group-hover:opacity-100">+</span>
                        </button>
                        <button @click="addText('Subjudul Menarik', 24, 'normal')" class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-lg text-left hover:bg-blue-50 transition flex items-center justify-between group shadow-sm">
                            <span class="font-semibold text-lg text-gray-800">Tambahkan Subjudul</span>
                            <span class="text-blue-600 opacity-0 group-hover:opacity-100">+</span>
                        </button>
                        <button @click="addText('Tulis paragraf isi cerita kamu di sini...', 16, 'normal')" class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-lg text-left hover:bg-blue-50 transition flex items-center justify-between group shadow-sm">
                            <span class="text-sm text-gray-600">Tambahkan Teks Isi</span>
                            <span class="text-blue-600 opacity-0 group-hover:opacity-100">+</span>
                        </button>

                        <!-- Text Formatting Tools -->
                        <div x-show="selectedTextObject" x-transition class="mt-6 p-4 border border-blue-100 bg-blue-50/50 rounded-xl space-y-3">
                            <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider">Format Teks Terpilih</h4>
                            
                            <div class="mb-2">
                                <label class="block text-xs text-gray-500 mb-1">Font Teks</label>
                                <select x-model="currentTextFont" @change="updateTextFont($event.target.value)" class="w-full text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 px-2 py-1.5">
                                    <option value="Plus Jakarta Sans">Plus Jakarta Sans</option>
                                    <option value="'Londrina Solid'">Londrina Solid Black</option>
                                    <option value="Arial">Arial</option>
                                    <option value="'Times New Roman'">Times New Roman</option>
                                    <option value="'Courier New'">Courier New</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Verdana">Verdana</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Warna Teks</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" x-model="currentTextColor" @input="updateTextColor($event.target.value)" class="w-8 h-8 rounded cursor-pointer border border-gray-200 p-0 overflow-hidden">
                                    <input type="text" x-model="currentTextColor" @input="updateTextColor($event.target.value)" class="flex-1 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 px-2 py-1 uppercase">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-6 gap-1 pt-1">
                                <template x-for="color in ['#1e293b', '#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#a855f7', '#ec4899', '#ffffff']">
                                    <button @click="updateTextColor(color)" :style="`background-color: ${color}`" class="w-full aspect-square rounded border border-gray-300 hover:scale-110 transition-transform shadow-sm"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Latar -->
                    <div x-show="activeTab === 'latar'" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Warna Latar Kustom</label>
                            <div class="flex items-center gap-3 mb-4">
                                <input type="color" x-model="canvasBgColor" @input="setBackgroundColor($event.target.value)" class="w-10 h-10 rounded cursor-pointer border border-gray-200 p-0 overflow-hidden">
                                <input type="text" x-model="canvasBgColor" @input="setBackgroundColor($event.target.value)" class="flex-1 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 px-3 py-2 uppercase">
                            </div>
                        </div>
                        
                        <div class="h-px bg-gray-200 w-full my-4"></div>

                        <p class="text-sm font-medium text-gray-700 mb-2">Pilihan Warna Favorit</p>
                        <div class="grid grid-cols-5 gap-2">
                            <template x-for="color in bgColors">
                                <button @click="setBackgroundColor(color)" :style="`background-color: ${color}`" class="w-full aspect-square rounded-md border border-gray-200 hover:scale-110 transition-transform shadow-sm"></button>
                            </template>
                        </div>
                    </div>

                    <!-- Panel Stiker -->
                    <div x-show="activeTab === 'stiker'" class="space-y-4">
                        <p class="text-sm text-gray-500 mb-2">Bentuk Dasar</p>
                        <div class="grid grid-cols-3 gap-3">
                            <button @click="addShape('rect')" class="aspect-square bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center hover:bg-blue-50 transition">
                                <div class="w-8 h-8 bg-gray-400"></div>
                            </button>
                            <button @click="addShape('circle')" class="aspect-square bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center hover:bg-blue-50 transition">
                                <div class="w-8 h-8 bg-gray-400 rounded-full"></div>
                            </button>
                            <button @click="addShape('triangle')" class="aspect-square bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center hover:bg-blue-50 transition">
                                <div class="w-0 h-0 border-l-[16px] border-r-[16px] border-b-[28px] border-l-transparent border-r-transparent border-b-gray-400"></div>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mb-2 mt-4">Emoji Stiker</p>
                        <div class="grid grid-cols-4 gap-2 text-2xl text-center">
                            <button @click="addEmoji('❤️')" class="p-2 hover:bg-gray-100 rounded">❤️</button>
                            <button @click="addEmoji('✨')" class="p-2 hover:bg-gray-100 rounded">✨</button>
                            <button @click="addEmoji('🌟')" class="p-2 hover:bg-gray-100 rounded">🌟</button>
                            <button @click="addEmoji('✈️')" class="p-2 hover:bg-gray-100 rounded">✈️</button>
                            <button @click="addEmoji('🎓')" class="p-2 hover:bg-gray-100 rounded">🎓</button>
                            <button @click="addEmoji('🎉')" class="p-2 hover:bg-gray-100 rounded">🎉</button>
                            <button @click="addEmoji('🎂')" class="p-2 hover:bg-gray-100 rounded">🎂</button>
                            <button @click="addEmoji('💍')" class="p-2 hover:bg-gray-100 rounded">💍</button>
                        </div>
                    </div>

                    <!-- Panel Layout -->
                    <div x-show="activeTab === 'layout'" class="space-y-4">
                        <div class="mb-4 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg p-4 text-white text-center shadow-md">
                            <h4 class="font-bold mb-1 flex items-center justify-center gap-2">🪄 Auto-Fill Foto</h4>
                            <p class="text-xs text-purple-100 mb-3">Pilih banyak foto, sistem akan otomatis menyusunnya ke seluruh halaman!</p>
                            <button @click="document.getElementById('autofill-upload').click()" class="bg-white text-indigo-600 font-bold py-2 px-4 rounded-md shadow hover:bg-gray-50 transition w-full">Mulai Auto-Fill</button>
                            <input type="file" id="autofill-upload" class="hidden" multiple accept="image/*" @change="handleAutoFill($event)">
                        </div>

                        <p class="text-sm text-gray-500 mb-2">Ganti Layout Halaman Ini</p>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="applyLayout('1_foto')" class="bg-gray-50 border border-gray-200 rounded-lg p-2 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center">
                                <div class="w-12 h-16 bg-gray-300 rounded-sm mb-2"></div>
                                <span class="text-xs font-semibold text-gray-600">1 Foto Full</span>
                            </button>
                            <button @click="applyLayout('2_foto')" class="bg-gray-50 border border-gray-200 rounded-lg p-2 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center">
                                <div class="w-12 h-16 flex flex-col gap-1 mb-2">
                                    <div class="w-full h-1/2 bg-gray-300 rounded-sm"></div>
                                    <div class="w-full h-1/2 bg-gray-300 rounded-sm"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-600">2 Foto</span>
                            </button>
                            <button @click="applyLayout('4_foto')" class="bg-gray-50 border border-gray-200 rounded-lg p-2 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center">
                                <div class="w-12 h-16 grid grid-cols-2 gap-1 mb-2">
                                    <div class="w-full h-full bg-gray-300 rounded-sm"></div>
                                    <div class="w-full h-full bg-gray-300 rounded-sm"></div>
                                    <div class="w-full h-full bg-gray-300 rounded-sm"></div>
                                    <div class="w-full h-full bg-gray-300 rounded-sm"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-600">4 Foto</span>
                            </button>
                            <button @click="applyLayout('foto_teks')" class="bg-gray-50 border border-gray-200 rounded-lg p-2 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center">
                                <div class="w-12 h-16 flex flex-col gap-1 mb-2">
                                    <div class="w-full h-2/3 bg-gray-300 rounded-sm"></div>
                                    <div class="w-full h-1/3 flex flex-col gap-1 justify-center px-1">
                                        <div class="w-full h-1 bg-gray-400"></div>
                                        <div class="w-2/3 h-1 bg-gray-400"></div>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-gray-600">Foto + Teks</span>
                            </button>
                            <button @click="applyLayout('panorama')" class="bg-gray-50 border border-gray-200 rounded-lg p-2 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center col-span-2">
                                <div class="w-16 h-12 flex items-center justify-center mb-2">
                                    <div class="w-full h-2/3 bg-gray-300 rounded-sm"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-600">Panorama</span>
                            </button>
                        </div>
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs rounded-lg">
                            <span class="font-bold">Perhatian:</span> Mengganti layout akan menghapus susunan foto dan teks saat ini di halaman.
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Center Canvas -->
        <div class="flex-1 bg-[#e5e7eb] overflow-hidden relative" id="canvas-wrapper"
             @dragover.prevent
             @drop="handleDrop($event)"
             @touchstart="touchStartX = $event.touches[0].clientX"
             @touchend="handleSwipe($event.changedTouches[0].clientX)">
             
             <!-- Mobile Overlay -->
             <div x-show="mobileToolsOpen || mobilePagesOpen" @click="mobileToolsOpen = false; mobilePagesOpen = false" class="fixed inset-0 bg-black/20 z-10 md:hidden" style="display: none;"></div>

            <div class="min-w-max min-h-full p-4 md:p-8 flex items-center justify-center">
                <!-- Delete Object Button (appears when object selected) -->
                <button x-show="showDeleteBtn" @click="deleteSelectedObject()" class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1.5 rounded-md shadow text-sm font-medium hover:bg-red-600 transition flex items-center gap-1 z-20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    <span class="hidden md:inline">Hapus Objek</span>
                </button>
                
                <!-- Canvas Container Wrapper -->
                <div class="relative" :style="`width: ${canvasWidth * canvasScale}px; height: ${canvasHeight * canvasScale}px;`">
                    <div class="bg-white shadow-xl origin-top-left z-0 absolute top-0 left-0" :style="`width: ${canvasWidth}px; height: ${canvasHeight}px; transform: scale(${canvasScale}); transition: transform 0.2s ease-out;`">
                        <canvas id="main-canvas"></canvas>
                        
                        <!-- Center Fold Line Overlay -->
                        <div class="absolute inset-y-0 w-px bg-black/20" style="left: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.3); pointer-events: none; z-index: 50;"></div>
                        
                        <!-- Watermark Overlay (Anti-Maling) -->
                        <div class="absolute inset-0 z-[60] pointer-events-none overflow-hidden flex flex-wrap content-start justify-center opacity-[0.03]" style="transform: rotate(-15deg) scale(1.5);">
                            <template x-for="i in 100">
                                <span class="text-3xl md:text-5xl font-black text-gray-900 m-4 md:m-8 whitespace-nowrap uppercase">CeritaKu</span>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Bottom Navigation Bar (Mobile & Desktop) -->
                    <div class="absolute -bottom-14 left-0 right-0 flex justify-between items-center text-sm w-full">
                        <button @click="goToPrevPage()" :disabled="!hasPrevPage()" class="text-blue-600 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center gap-1 font-bold bg-white/50 backdrop-blur-sm px-3 py-1.5 rounded-full hover:bg-white transition shadow-sm">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            <span class="text-xs md:text-sm">Sebelumnya</span>
                        </button>
                        
                        <div class="text-gray-700 bg-white px-4 py-1.5 rounded-full shadow-md border border-gray-200 text-xs md:text-sm font-bold flex items-center gap-2 cursor-pointer hover:bg-gray-50 transition" @click="mobilePagesOpen = true">
                            <span x-text="currentPageId === 0 ? 'Cover' : 'Hal ' + ((currentPageId * 2) - 1) + '-' + (currentPageId * 2)"></span>
                            <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>

                        <button @click="goToNextPage()" :disabled="!hasNextPage()" class="text-blue-600 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center gap-1 font-bold bg-white/50 backdrop-blur-sm px-3 py-1.5 rounded-full hover:bg-white transition shadow-sm">
                            <span class="text-xs md:text-sm">Selanjutnya</span>
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar (Pages) -->
        <div :class="mobilePagesOpen ? 'translate-x-0' : 'translate-x-full md:translate-x-0'" class="absolute right-0 md:relative w-64 h-[calc(100vh-64px)] md:h-auto bg-white border-l border-gray-200 flex flex-col flex-shrink-0 z-20 transition-transform duration-300 ease-in-out shadow-2xl md:shadow-none">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Halaman Buku</h3>
            </div>
            
            <!-- Pages List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                
                <!-- Pages loop handles Cover (id=0) and regular pages -->
                <template x-for="(page, index) in pages" :key="page.id">
                    <div class="border-2 rounded-lg p-2 transition cursor-pointer relative group"
                         :class="currentPageId === page.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'"
                         @click="switchPage(page.id)">
                        
                        <div class="flex justify-between items-center mb-2 px-1">
                            <span class="text-sm font-bold" :class="page.id === 0 ? 'text-blue-600 uppercase tracking-wider text-xs' : 'text-gray-700'" x-text="page.id === 0 ? 'Cover Buku' : 'Hal ' + ((page.id * 2) - 1) + '-' + (page.id * 2)"></span>
                            <!-- Delete Page -->
                            <button x-show="page.id !== 0 && pages.length > 2" @click.stop="deletePage(page.id)" class="text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        
                        <!-- Thumbnail Placeholder -->
                        <div class="w-full aspect-[3/4] bg-white border border-gray-200 rounded flex items-center justify-center overflow-hidden">
                            <template x-if="page.id === 0">
                                <img :src="coverUrl" class="w-full h-full object-cover object-right opacity-50" alt="Cover">
                            </template>
                            <template x-if="page.id !== 0">
                                <span class="text-xs text-gray-400 font-medium text-center px-2" x-text="'Halaman ' + ((page.id * 2) - 1) + ' & ' + (page.id * 2)"></span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <button @click="openLayoutModal = true" class="w-full py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-100 transition flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Tambah Halaman
                </button>
            </div>
        </div>
    </div>

    <!-- Layout Selection Modal -->
    <div x-show="openLayoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-cloak>
        <div class="bg-white rounded-2xl p-6 w-[500px] max-w-full shadow-2xl" @click.away="openLayoutModal = false">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Pilih Layout Halaman</h2>
            <p class="text-gray-500 mb-6">Pilih susunan grid untuk halaman baru Anda.</p>
            
            <div class="grid grid-cols-3 gap-4 mb-6">
                <!-- Layout 1 -->
                <button @click="addPage('1_foto')" class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center">
                    <div class="w-16 h-20 bg-gray-300 rounded-sm mb-3"></div>
                    <span class="text-sm font-semibold text-gray-700">1 Foto Full</span>
                </button>
                <!-- Layout 2 -->
                <button @click="addPage('2_foto')" class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center">
                    <div class="w-16 h-20 flex flex-col gap-1 mb-3">
                        <div class="w-full h-1/2 bg-gray-300 rounded-sm"></div>
                        <div class="w-full h-1/2 bg-gray-300 rounded-sm"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">2 Foto</span>
                </button>
                <!-- Layout 4 -->
                <button @click="addPage('4_foto')" class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center">
                    <div class="w-16 h-20 grid grid-cols-2 gap-1 mb-3">
                        <div class="w-full h-full bg-gray-300 rounded-sm"></div>
                        <div class="w-full h-full bg-gray-300 rounded-sm"></div>
                        <div class="w-full h-full bg-gray-300 rounded-sm"></div>
                        <div class="w-full h-full bg-gray-300 rounded-sm"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">4 Foto</span>
                </button>
                <!-- Layout Foto + Teks -->
                <button @click="addPage('foto_teks')" class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center">
                    <div class="w-16 h-20 flex flex-col gap-1 mb-3">
                        <div class="w-full h-2/3 bg-gray-300 rounded-sm"></div>
                        <div class="w-full h-1/3 flex flex-col gap-1.5 justify-center px-1">
                            <div class="w-full h-1.5 bg-gray-400"></div>
                            <div class="w-2/3 h-1.5 bg-gray-400"></div>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Foto + Teks</span>
                </button>
                <!-- Layout Panorama -->
                <button @click="addPage('panorama')" class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center">
                    <div class="w-20 h-16 flex items-center justify-center mb-3">
                        <div class="w-full h-2/3 bg-gray-300 rounded-sm"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Panorama</span>
                </button>
                <!-- Layout Blank -->
                <button @click="addPage('blank')" class="bg-white border border-dashed border-gray-300 rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 transition flex flex-col items-center justify-center">
                    <div class="w-16 h-20 border border-dashed border-gray-300 rounded-sm mb-3"></div>
                    <span class="text-sm font-semibold text-gray-700">Halaman Kosong</span>
                </button>
            </div>
            
            <div class="flex justify-end">
                <button @click="openLayoutModal = false" class="px-4 py-2 text-gray-600 font-medium hover:bg-gray-100 rounded-lg">Batal</button>
            </div>
        </div>
    </div>

    <!-- Order Modal -->
    <div x-show="openOrderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-cloak>
        <div class="bg-white rounded-2xl p-6 w-[600px] max-w-full shadow-2xl max-h-[90vh] overflow-y-auto" @click.away="openOrderModal = false">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Pesan Memory Book</h2>
            <p class="text-gray-500 mb-6 text-sm">Lengkapi data untuk memesan buku kenangan Anda.</p>
            
            <form @submit.prevent="submitOrderForm" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" x-model="orderForm.customer_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp *</label>
                        <input type="text" x-model="orderForm.whatsapp" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="081234567890">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                    <input type="email" x-model="orderForm.email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                    <textarea x-model="orderForm.address" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Buku *</label>
                        <select x-model="orderForm.book_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="Hardcover">Hardcover Premium</option>
                            <option value="Softcover">Softcover Classic</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Buku *</label>
                        <input type="number" x-model="orderForm.quantity" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea x-model="orderForm.note" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
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

    <!-- Auto-Fill Loading Overlay -->
    <div x-show="isAutoFilling" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black bg-opacity-80 backdrop-blur-sm" style="display: none;" x-cloak>
        <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full text-center">
            <div class="mb-6 flex justify-center">
                <div class="relative w-20 h-20">
                    <svg class="animate-spin w-full h-full text-blue-100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8"></circle>
                    </svg>
                    <svg class="absolute top-0 left-0 w-full h-full text-blue-600" viewBox="0 0 100 100" style="transform: rotate(-90deg);">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" :stroke-dasharray="283" :stroke-dashoffset="283 - (283 * autoFillProgress / 100)" class="transition-all duration-300 ease-out"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center font-bold text-xl text-blue-600" x-text="Math.round(autoFillProgress) + '%'"></div>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Menyusun Buku Kenangan...</h3>
            <p class="text-gray-500 font-medium" x-text="autoFillText">Memproses foto-foto Anda</p>
            
            <div class="mt-8 w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-300 ease-out" :style="`width: ${autoFillProgress}%`"></div>
            </div>
        </div>
    </div>

    <!-- Application Script -->
    <script>
        let fabricCanvas = null;

        document.addEventListener('alpine:init', () => {
            Alpine.data('editorApp', () => ({
                designCode: '{{ isset($design) ? $design->design_code : "" }}',
                projectName: '{{ isset($design) ? $design->title : "Buku Kenanganku" }}',
                activeTab: 'foto',
                showDeleteBtn: false,
                saveStatus: 'Tersimpan otomatis.',
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                
                tabs: [
                    { id: 'foto', name: 'Foto', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' },
                    { id: 'teks', name: 'Teks', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 00-1-1H4a1 1 0 01-1-1V4a1 1 0 011-1h3a1 1 0 001-1v-1z"/><text x="6" y="16" font-family="sans-serif" font-size="12" fill="currentColor" stroke="none">T</text></svg>' },
                    { id: 'latar', name: 'Latar', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>' },
                    { id: 'stiker', name: 'Stiker', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' },
                    { id: 'layout', name: 'Layout', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>' },
                ],
                bgColors: ['#ffffff', '#f8fafc', '#f1f5f9', '#fee2e2', '#ffedd5', '#fef3c7', '#dcfce7', '#e0f2fe', '#dbeafe', '#f3e8ff', '#fce7f3', '#1e293b'],
                
                pages: {!! json_encode(isset($design) && $design->pages->count() > 0 ? $design->pages->map(function($p) {
                    return ['id' => $p->page_number, 'json' => $p->photos, 'layout' => $p->layout_type];
                })->values()->toArray() : [['id' => 0, 'json' => null, 'layout' => 'cover'], ['id' => 1, 'json' => null, 'layout' => '1_foto']]) !!},
                gallery: {!! json_encode($gallery ?? []) !!},
                draggedImageUrl: null,
                currentPageId: 0,
                coverUrl: '{{ isset($design) && $design->template ? $design->template->thumbnail : "" }}',
                coverBackUrl: '{!! isset($design) && $design->template ? (is_array($design->template->layout_structure) && isset($design->template->layout_structure["cover_back_url"]) ? $design->template->layout_structure["cover_back_url"] : $design->template->thumbnail) : "" !!}',
                nextPageId: {{ isset($design) ? ($design->pages->max('page_number') ?? 0) + 1 : 2 }},
                isStaticTemplate: {{ (isset($design) && $design->template && is_array($design->template->layout_structure) && isset($design->template->layout_structure['is_static'])) ? 'true' : 'false' }},
                openLayoutModal: false,
                openOrderModal: false,
                isSubmittingOrder: false,
                
                isAutoFilling: false,
                autoFillProgress: 0,
                autoFillText: '',
                
                currentSlot: null,
                currentTextFont: 'Plus Jakarta Sans',
                
                // History States
                pageHistory: {},
                isHistoryModifying: false,
                historyTimeout: null,
                
                orderForm: {
                    customer_name: '',
                    whatsapp: '',
                    email: '',
                    address: '',
                    book_type: 'Hardcover',
                    quantity: 1,
                    note: ''
                },

                // Responsiveness
                mobileToolsOpen: false,
                mobilePagesOpen: false,
                canvasScale: 1,
                bookSize: '{{ isset($design) && in_array($design->book_size, ["A5","A4","B5","Persegi","Mini"]) ? $design->book_size : "A5" }}',
                canvasWidth: 500,
                canvasHeight: 709,
                
                selectedTextObject: false,
                selectedImageObject: false,
                currentTextColor: '#1e293b',
                canvasBgColor: '#ffffff',
                
                touchStartX: 0,
                
                handleSwipe(endX) {
                    const threshold = 50;
                    if (this.touchStartX - endX > threshold) {
                        this.goToNextPage();
                    } else if (endX - this.touchStartX > threshold) {
                        this.goToPrevPage();
                    }
                },
                
                hasPrevPage() {
                    const currentIdx = this.pages.findIndex(p => p.id === this.currentPageId);
                    return currentIdx > 0;
                },
                
                hasNextPage() {
                    const currentIdx = this.pages.findIndex(p => p.id === this.currentPageId);
                    return currentIdx >= 0 && currentIdx < this.pages.length - 1;
                },
                
                goToPrevPage() {
                    if (!this.hasPrevPage()) return;
                    let currentIdx = this.pages.findIndex(p => p.id === this.currentPageId);
                    if(currentIdx > 0) this.switchPage(this.pages[currentIdx - 1].id);
                },
                
                goToNextPage() {
                    if (!this.hasNextPage()) return;
                    let currentIdx = this.pages.findIndex(p => p.id === this.currentPageId);
                    if(currentIdx < this.pages.length - 1) this.switchPage(this.pages[currentIdx + 1].id);
                },

                initCanvasSize() {
                    let w = 500; let h = 709; // Default A5/A4/B5 ratio
                    if (this.bookSize === 'Persegi') { w = 600; h = 600; }
                    else if (this.bookSize === 'Mini') { w = 500; h = 750; }
                    this.singlePageWidth = w;
                    this.canvasWidth = w * 2; // SPREAD WIDTH
                    this.canvasHeight = h;
                },

                init() {
                    this.initCanvasSize();
                    
                    // Tunggu DOM selesai render
                    setTimeout(() => {
                        // Kunci aspek rasio untuk semua gambar agar corner handle scale seragam
                        fabric.Image.prototype.lockUniScaling = true;

                        // FITUR CROP OTOMATIS: Tarik ujung tengah gambar akan memotong (crop) bukan melar (stretch)
                        const cropRight = function(eventData, transform, x, y) {
                            const target = transform.target;
                            const localPoint = fabric.controlsUtils.getLocalPoint(transform, transform.originX, transform.originY, x, y);
                            const newWidth = localPoint.x / target.scaleX;
                            const maxW = target._element.width - target.cropX;
                            target.set('width', Math.min(Math.max(newWidth, 10), maxW));
                            return true;
                        };
                        const cropBottom = function(eventData, transform, x, y) {
                            const target = transform.target;
                            const localPoint = fabric.controlsUtils.getLocalPoint(transform, transform.originX, transform.originY, x, y);
                            const newHeight = localPoint.y / target.scaleY;
                            const maxH = target._element.height - target.cropY;
                            target.set('height', Math.min(Math.max(newHeight, 10), maxH));
                            return true;
                        };
                        const cropLeft = function(eventData, transform, x, y) {
                            const target = transform.target;
                            const localPoint = fabric.controlsUtils.getLocalPoint(transform, transform.originX, transform.originY, x, y);
                            
                            let newWidth = -localPoint.x / target.scaleX;
                            let deltaW = newWidth - target.width;
                            
                            if (target.cropX - deltaW < 0) {
                                deltaW = target.cropX;
                                newWidth = target.width + deltaW;
                            }
                            
                            if (newWidth < 10) {
                                newWidth = 10;
                                deltaW = newWidth - target.width;
                            }
                            
                            target.set({
                                width: newWidth,
                                cropX: target.cropX - deltaW,
                                left: target.left - (deltaW * target.scaleX)
                            });
                            return true;
                        };
                        const cropTop = function(eventData, transform, x, y) {
                            const target = transform.target;
                            const localPoint = fabric.controlsUtils.getLocalPoint(transform, transform.originX, transform.originY, x, y);
                            
                            let newHeight = -localPoint.y / target.scaleY;
                            let deltaH = newHeight - target.height;
                            
                            if (target.cropY - deltaH < 0) {
                                deltaH = target.cropY;
                                newHeight = target.height + deltaH;
                            }
                            
                            if (newHeight < 10) {
                                newHeight = 10;
                                deltaH = newHeight - target.height;
                            }
                            
                            target.set({
                                height: newHeight,
                                cropY: target.cropY - deltaH,
                                top: target.top - (deltaH * target.scaleY)
                            });
                            return true;
                        };

                        fabric.Image.prototype.controls.mr.actionHandler = cropRight;
                        fabric.Image.prototype.controls.mb.actionHandler = cropBottom;
                        fabric.Image.prototype.controls.ml.actionHandler = cropLeft;
                        fabric.Image.prototype.controls.mt.actionHandler = cropTop;

                        fabricCanvas = new fabric.Canvas('main-canvas', {
                            width: this.canvasWidth,
                            height: this.canvasHeight,
                            backgroundColor: '#ffffff',
                            preserveObjectStacking: true // Stabilitas object z-index
                        });

                        this.resizeCanvas();
                        window.addEventListener('resize', () => {
                            this.resizeCanvas();
                        });

                        // Load saved page or default layout
                        if (this.pages.length > 0 && this.pages[0].json) {
                            this.loadPage(this.pages[0].id);
                        } else if (this.pages.length > 0) {
                            this.loadPage(this.pages[0].id);
                        }

                        // Event listener untuk memunculkan tombol hapus saat objek diklik
                        fabricCanvas.on('selection:created', (e) => { this.handleSelection(e); });
                        fabricCanvas.on('selection:updated', (e) => { this.handleSelection(e); });
                        fabricCanvas.on('selection:cleared', () => { 
                            this.showDeleteBtn = false; 
                            this.selectedTextObject = false;
                            this.selectedImageObject = false;
                        });
                        
                        let activePanObject = null;
                        let isDraggingPan = false;
                        let lastPanX = 0;
                        let lastPanY = 0;
                        let hasSeenPanToast = false;

                        fabricCanvas.on('mouse:dblclick', (options) => {
                            if (options.target && options.target.isFilledSlot && !options.target.isRawImage) {
                                activePanObject = options.target;
                                activePanObject.lockMovementX = true;
                                activePanObject.lockMovementY = true;
                                activePanObject.setControlsVisibility({ mt: false, mb: false, ml: false, mr: false, mtr: false });
                                fabricCanvas.defaultCursor = 'grab';
                                
                                // Tampilkan notifikasi hanya sekali
                                if (!hasSeenPanToast) {
                                    hasSeenPanToast = true;
                                    if (document.getElementById('pan-toast')) document.getElementById('pan-toast').remove();
                                    const toast = document.createElement('div');
                                    toast.id = 'pan-toast';
                                    toast.className = 'fixed bottom-24 left-4 right-4 md:top-4 md:bottom-auto md:left-1/2 md:-translate-x-1/2 md:w-auto bg-blue-600 text-white p-3 md:px-6 md:py-3 rounded-xl md:rounded-full shadow-2xl z-[100] text-xs md:text-sm font-bold flex items-center justify-center gap-2 animate-bounce transition-opacity duration-500';
                                    toast.innerHTML = '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg> <span>Mode Geser & Zoom Aktif! Scroll & tarik foto.</span>';
                                    document.body.appendChild(toast);
                                    
                                    // Hilangkan otomatis setelah 3.5 detik
                                    setTimeout(() => {
                                        const t = document.getElementById('pan-toast');
                                        if (t) {
                                            t.style.opacity = '0';
                                            setTimeout(() => t.remove(), 500);
                                        }
                                    }, 3500);
                                }
                            }
                        });

                        // Handle click on slot or placeholder text
                        fabricCanvas.on('mouse:down', (options) => {
                            if (activePanObject && options.target === activePanObject) {
                                isDraggingPan = true;
                                const pointer = fabricCanvas.getPointer(options.e);
                                lastPanX = pointer.x;
                                lastPanY = pointer.y;
                                fabricCanvas.defaultCursor = 'grabbing';
                            } else if (activePanObject && options.target !== activePanObject) {
                                activePanObject.lockMovementX = false;
                                activePanObject.lockMovementY = false;
                                activePanObject.setControlsVisibility({ mt: true, mb: true, ml: true, mr: true, mtr: false });
                                activePanObject = null;
                                fabricCanvas.defaultCursor = 'default';
                                const toast = document.getElementById('pan-toast');
                                if (toast) toast.remove();
                                this.saveHistoryState();
                            }

                            if (options.target && !activePanObject) {
                                if (options.target.isSlot) {
                                    this.currentSlot = options.target;
                                    document.getElementById('slot-file-input').click();
                                } else if (options.target.isPlaceholderText) {
                                    const slotId = options.target.belongsToId;
                                    const slot = fabricCanvas.getObjects().find(o => o.id === slotId && o.isSlot);
                                    if (slot) {
                                        this.currentSlot = slot;
                                        document.getElementById('slot-file-input').click();
                                    }
                                }
                            }
                        });

                        // Cursor change for slots
                        fabricCanvas.on('mouse:move', (options) => {
                            if (isDraggingPan && activePanObject) {
                                const pointer = fabricCanvas.getPointer(options.e);
                                let dx = pointer.x - lastPanX;
                                let dy = pointer.y - lastPanY;
                                
                                let obj = activePanObject;
                                let newCropX = obj.cropX - (dx / obj.scaleX);
                                let newCropY = obj.cropY - (dy / obj.scaleY);
                                
                                let maxCropX = obj._element.width - obj.width;
                                let maxCropY = obj._element.height - obj.height;
                                
                                if (newCropX < 0) newCropX = 0;
                                if (newCropX > maxCropX) newCropX = maxCropX;
                                if (newCropY < 0) newCropY = 0;
                                if (newCropY > maxCropY) newCropY = maxCropY;
                                
                                obj.set({ cropX: newCropX, cropY: newCropY });
                                fabricCanvas.renderAll();
                                
                                lastPanX = pointer.x;
                                lastPanY = pointer.y;
                            } else if (options.target && (options.target.isSlot || options.target.isPlaceholderText)) {
                                fabricCanvas.defaultCursor = 'pointer';
                            } else if (!activePanObject) {
                                fabricCanvas.defaultCursor = 'default';
                            }
                        });

                        fabricCanvas.on('mouse:up', () => {
                            if (isDraggingPan) {
                                isDraggingPan = false;
                                fabricCanvas.defaultCursor = 'grab';
                            }
                        });

                        fabricCanvas.on('mouse:wheel', (opt) => {
                            if (activePanObject && activePanObject === fabricCanvas.getActiveObject()) {
                                let delta = opt.e.deltaY;
                                let obj = activePanObject;
                                
                                let zoomStep = 0.05 * obj.scaleX;
                                let newScaleX = obj.scaleX;
                                
                                if (delta < 0) newScaleX += zoomStep; // zoom in
                                else newScaleX -= zoomStep; // zoom out
                                
                                let frameW = obj.width * obj.scaleX;
                                let frameH = obj.height * obj.scaleY;
                                
                                let minScaleX = frameW / obj._element.width;
                                let minScaleY = frameH / obj._element.height;
                                let minScale = Math.max(minScaleX, minScaleY);
                                
                                if (newScaleX < minScale) newScaleX = minScale;
                                
                                let newWidth = frameW / newScaleX;
                                let newHeight = frameH / newScaleX;
                                
                                let newCropX = obj.cropX + (obj.width - newWidth) / 2;
                                let newCropY = obj.cropY + (obj.height - newHeight) / 2;
                                
                                let maxCropX = obj._element.width - newWidth;
                                let maxCropY = obj._element.height - newHeight;
                                
                                if (newCropX < 0) newCropX = 0;
                                if (newCropX > maxCropX) newCropX = maxCropX;
                                if (newCropY < 0) newCropY = 0;
                                if (newCropY > maxCropY) newCropY = maxCropY;
                                
                                obj.set({
                                    scaleX: newScaleX, scaleY: newScaleX,
                                    width: newWidth, height: newHeight,
                                    cropX: newCropX, cropY: newCropY
                                });
                                
                                fabricCanvas.renderAll();
                                opt.e.preventDefault();
                                opt.e.stopPropagation();
                            }
                        });
                        
                        // Default Font untuk textbox
                        fabric.Textbox.prototype.fontFamily = "'Plus Jakarta Sans', sans-serif";
                        
                        // History tracking events
                        fabricCanvas.on('object:modified', () => { this.saveHistoryState(); });
                        fabricCanvas.on('object:added', () => { this.saveHistoryState(); });
                        fabricCanvas.on('object:removed', () => { this.saveHistoryState(); });

                        // Keyboard shortcuts for Undo/Redo
                        window.addEventListener('keydown', (e) => {
                            if (e.ctrlKey && e.key === 'z') {
                                e.preventDefault();
                                this.undo();
                            } else if (e.ctrlKey && e.key === 'y') {
                                e.preventDefault();
                                this.redo();
                            }
                        });

                        // Set Autosave interval 30 seconds
                        setInterval(() => {
                            this.saveProject(false);
                        }, 30000);
                        
                    }, 100);
                },

                resizeCanvas() {
                    const wrapper = document.getElementById('canvas-wrapper');
                    if(!wrapper) return;
                    
                    const availableWidth = wrapper.clientWidth - 32; // 16px padding on each side
                    const availableHeight = wrapper.clientHeight - 32;
                    
                    const originalWidth = this.canvasWidth;
                    const originalHeight = this.canvasHeight;
                    
                    const scaleX = availableWidth / originalWidth;
                    const scaleY = availableHeight / originalHeight;
                    
                    let scale = Math.min(scaleX, scaleY);
                    if (scale > 1) scale = 1; // Limit max scale to 100%
                    
                    this.canvasScale = scale;
                    
                    setTimeout(() => {
                        if(fabricCanvas) {
                            fabricCanvas.calcOffset();
                            fabricCanvas.renderAll();
                        }
                    }, 50);
                },

                handleSelection(e) {
                    if(e.selected && e.selected.length > 0) {
                        const obj = e.selected[0];
                        // Jangan tampilkan hapus untuk slot kosong
                        if(obj.isSlot) {
                            this.showDeleteBtn = false;
                        } else {
                            this.showDeleteBtn = true;
                        }
                        
                        if (obj.type === 'textbox' || obj.type === 'i-text' || obj.type === 'text') {
                            if (!obj.isPlaceholderText) {
                                this.selectedTextObject = true;
                                this.selectedImageObject = false;
                                if (obj.fontFamily) {
                                    this.currentTextFont = obj.fontFamily.replace(/['"]/g, '') === 'Londrina Solid' ? "'Londrina Solid'" : obj.fontFamily.replace(/['"]/g, '');
                                }
                            }
                        } else if (obj.type === 'image') {
                            this.selectedImageObject = true;
                            this.selectedTextObject = false;
                        } else {
                            this.selectedImageObject = false;
                            this.selectedTextObject = false;
                        }
                    } else {
                        this.selectedTextObject = false;
                        this.selectedImageObject = false;
                        this.showDeleteBtn = false;
                    }
                },

                // FITUR UNDO / REDO
                saveHistoryState() {
                    if (this.isHistoryModifying || !fabricCanvas) return;
                    clearTimeout(this.historyTimeout);
                    this.historyTimeout = setTimeout(() => {
                        if (!this.pageHistory[this.currentPageId]) {
                            this.pageHistory[this.currentPageId] = { undo: [], redo: [] };
                        }
                        const json = fabricCanvas.toJSON(['isSlot', 'isFilledSlot', 'isRawImage', 'originalSlotProps', 'id', 'isPlaceholderText', 'belongsToId']);
                        const stateStr = JSON.stringify(json);
                        
                        const hist = this.pageHistory[this.currentPageId];
                        if (hist.undo.length === 0 || hist.undo[hist.undo.length - 1] !== stateStr) {
                            hist.undo.push(stateStr);
                            hist.redo = []; 
                        }
                    }, 300);
                },
                
                canUndo() {
                    return this.pageHistory[this.currentPageId] && this.pageHistory[this.currentPageId].undo.length > 1;
                },
                
                canRedo() {
                    return this.pageHistory[this.currentPageId] && this.pageHistory[this.currentPageId].redo.length > 0;
                },
                
                undo() {
                    const hist = this.pageHistory[this.currentPageId];
                    if (!hist || hist.undo.length <= 1) return;
                    this.isHistoryModifying = true;
                    
                    hist.redo.push(hist.undo.pop());
                    const stateToLoad = hist.undo[hist.undo.length - 1];
                    
                    fabricCanvas.loadFromJSON(stateToLoad, () => {
                        // Re-bind controls
                        fabricCanvas.getObjects().forEach(o => {
                            if(o.isSlot) {
                                o.setControlsVisibility({ mt: false, mb: false, ml: false, mr: false, bl: false, br: false, tl: false, tr: false, mtr: false });
                            } else if (o.isFilledSlot) {
                                o.setControlsVisibility({ mt: true, mb: true, ml: true, mr: true, mtr: false });
                                o.hasControls = true; o.lockMovementX = false; o.lockMovementY = false;
                                if (o.clipPath) o.clipPath = null;
                            } else if (o.type === 'image') {
                                o.setControlsVisibility({ mt: false, mb: false, ml: false, mr: false });
                            }
                        });
                        fabricCanvas.renderAll();
                        this.isHistoryModifying = false;
                        this.saveCurrentPage();
                    });
                },
                
                redo() {
                    const hist = this.pageHistory[this.currentPageId];
                    if (!hist || hist.redo.length === 0) return;
                    this.isHistoryModifying = true;
                    
                    const stateToLoad = hist.redo.pop();
                    hist.undo.push(stateToLoad);
                    
                    fabricCanvas.loadFromJSON(stateToLoad, () => {
                        // Re-bind controls
                        fabricCanvas.getObjects().forEach(o => {
                            if(o.isSlot) {
                                o.setControlsVisibility({ mt: false, mb: false, ml: false, mr: false, bl: false, br: false, tl: false, tr: false, mtr: false });
                            } else if (o.isFilledSlot) {
                                o.setControlsVisibility({ mt: true, mb: true, ml: true, mr: true, mtr: false });
                                o.hasControls = true; o.lockMovementX = false; o.lockMovementY = false;
                                if (o.clipPath) o.clipPath = null;
                            } else if (o.type === 'image') {
                                o.setControlsVisibility({ mt: false, mb: false, ml: false, mr: false });
                            }
                        });
                        fabricCanvas.renderAll();
                        this.isHistoryModifying = false;
                        this.saveCurrentPage();
                    });
                },

                // FITUR FOTO & UPLOAD
                async uploadPhotoToServer(file) {
                    this.saveStatus = 'Mengunggah foto...';
                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('design_code', this.designCode);

                    try {
                        const response = await fetch('/editor/upload-asset', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': this.csrfToken },
                            body: formData
                        });
                        const data = await response.json();
                        this.saveStatus = 'Tersimpan otomatis.';
                        return data.url;
                    } catch (error) {
                        this.saveStatus = 'Gagal mengunggah.';
                        alert('Gagal mengunggah foto');
                        return null;
                    }
                },

                async uploadPhoto(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const url = await this.uploadPhotoToServer(file);
                    if (url) {
                        if (!this.gallery.includes(url)) {
                            this.gallery.push(url);
                        }
                    }
                    event.target.value = '';
                },

                // AUTO-FILL
                fillSlotWithUrlAsync(slotObj, url) {
                    return new Promise((resolve) => {
                        const id = slotObj.id;
                        fabric.Image.fromURL(url, (img) => {
                            if (!img || !img.width || !img.height) {
                                resolve(false);
                                return;
                            }
                            
                            this.isHistoryModifying = true; 
                            fabricCanvas.discardActiveObject(); 
                            
                            const slotW = slotObj.getScaledWidth();
                            const slotH = slotObj.getScaledHeight();
                            
                            const scale = Math.max(slotW / img.width, slotH / img.height);
                            
                            img.set({
                                left: slotObj.left,
                                top: slotObj.top,
                                originX: slotObj.originX,
                                originY: slotObj.originY,
                                scaleX: scale,
                                scaleY: scale,
                                cropX: (img.width - slotW / scale) / 2,
                                cropY: (img.height - slotH / scale) / 2,
                                width: slotW / scale,
                                height: slotH / scale,
                                isFilledSlot: true,
                                originalSlotProps: { 
                                    left: slotObj.left, top: slotObj.top, width: slotW, height: slotH, 
                                    id: id, originX: slotObj.originX, originY: slotObj.originY
                                },
                                selectable: true, hasControls: true, lockMovementX: false, lockMovementY: false
                            });
                            
                            img.setControlsVisibility({ mt: true, mb: true, ml: true, mr: true, mtr: false });
                            fabricCanvas.add(img);
                            
                            const objects = fabricCanvas.getObjects();
                            objects.forEach(o => {
                                if(o.isPlaceholderText && o.belongsToId === id) {
                                    fabricCanvas.remove(o);
                                }
                            });
                            
                            const slotIndex = fabricCanvas.getObjects().indexOf(slotObj);
                            fabricCanvas.remove(slotObj);
                            
                            if (slotIndex !== -1) {
                                img.moveTo(slotIndex);
                            }
                            
                            fabricCanvas.setActiveObject(img);
                            fabricCanvas.renderAll();
                            
                            this.isHistoryModifying = false;
                            this.saveCurrentPage();
                            resolve(true);
                        }, { crossOrigin: 'anonymous' });
                    });
                },

                async handleAutoFill(event) {
                    const files = Array.from(event.target.files);
                    if (files.length === 0) return;
                    
                    this.isAutoFilling = true;
                    this.autoFillProgress = 0;
                    this.autoFillText = `Mengunggah foto 1 dari ${files.length}...`;
                    this.saveStatus = `Mengunggah ${files.length} foto...`;
                    
                    const urls = [];
                    for (let i = 0; i < files.length; i++) {
                        this.autoFillText = `Mengunggah foto ${i+1} dari ${files.length}...`;
                        const url = await this.uploadPhotoToServer(files[i]);
                        if (url) {
                            urls.push(url);
                            if (!this.gallery.includes(url)) this.gallery.push(url);
                        }
                        // 50% dari progress bar adalah untuk upload
                        this.autoFillProgress = ((i + 1) / files.length) * 50;
                    }
                    
                    this.saveStatus = 'Menyusun layout otomatis...';
                    this.autoFillText = 'Menyusun halaman buku...';
                    
                    let photoIndex = 0;
                    let pageIndex = this.pages.findIndex(p => p.id === this.currentPageId);
                    const totalPhotos = urls.length;
                    
                    while(photoIndex < urls.length) {
                        if (pageIndex >= this.pages.length) {
                            this.addPage('blank');
                        }
                        
                        let page = this.pages[pageIndex];
                        this.switchPage(page.id);
                        await new Promise(r => setTimeout(r, 200)); 
                        
                        if (!page.layout || page.layout === 'blank') {
                            const remaining = urls.length - photoIndex;
                            let chosenLayout = '1_foto';
                            if (remaining >= 4) chosenLayout = '4_foto';
                            else if (remaining >= 2) chosenLayout = '2_foto';
                            else chosenLayout = '1_foto';
                            
                            this.applyLayout(chosenLayout);
                            await new Promise(r => setTimeout(r, 100)); 
                        }
                        
                        const objects = fabricCanvas.getObjects();
                        const emptySlots = objects.filter(o => o.isSlot);
                        
                        for (let slot of emptySlots) {
                            if (photoIndex < urls.length) {
                                const url = urls[photoIndex++];
                                await this.fillSlotWithUrlAsync(slot, url);
                                
                                // sisa 50% progress bar untuk menyusun foto
                                this.autoFillProgress = 50 + ((photoIndex / totalPhotos) * 50);
                            }
                        }
                        
                        pageIndex++;
                    }
                    
                    this.autoFillProgress = 100;
                    this.autoFillText = 'Selesai!';
                    this.saveCurrentPage();
                    this.saveStatus = 'Auto-Fill Selesai!';
                    event.target.value = '';
                    
                    setTimeout(() => {
                        this.isAutoFilling = false;
                    }, 1000);
                },

                // DRAG AND DROP & GALLERY ACTIONS
                dragStart(e, url) {
                    this.draggedImageUrl = url;
                    e.dataTransfer.setData('text/plain', url);
                    e.dataTransfer.effectAllowed = 'copy';
                },
                
                handleDrop(e) {
                    const url = e.dataTransfer.getData('text/plain');
                    if (!url) return;
                    
                    const pointer = fabricCanvas.getPointer(e);
                    
                    let targetSlot = null;
                    const objects = fabricCanvas.getObjects();
                    for(let i=objects.length-1; i>=0; i--) {
                        if (objects[i].isSlot && objects[i].containsPoint(new fabric.Point(pointer.x, pointer.y))) {
                            targetSlot = objects[i];
                            break;
                        }
                    }
                    
                    if (targetSlot) {
                        this.fillSlotWithUrl(targetSlot, url);
                    } else {
                        this.addToCanvasAt(url, pointer.x, pointer.y);
                    }
                },
                
                addToCanvas(url) {
                    this.addToCanvasAt(url, fabricCanvas.width/2, fabricCanvas.height/2);
                },
                
                addToCanvasAt(url, x, y) {
                    fabric.Image.fromURL(url, (img) => {
                        let scale = 1;
                        if(img.width > 400) scale = 400 / img.width;
                        
                        img.set({ 
                            left: x, 
                            top: y, 
                            originX: 'center', 
                            originY: 'center',
                            scaleX: scale,
                            scaleY: scale,
                            cropX: 0,
                            cropY: 0,
                            width: img.width,
                            height: img.height,
                            isFilledSlot: true, // Make it act like a croppable frame
                            isRawImage: true,
                            originalSlotProps: { width: img.width, height: img.height, left: x, top: y, id: Date.now() }
                        });
                        img.setControlsVisibility({ mt: true, mb: true, ml: true, mr: true, mtr: true });
                        fabricCanvas.add(img);
                        fabricCanvas.setActiveObject(img);
                        fabricCanvas.renderAll();
                    });
                },
                
                fillSlotWithUrl(slotObj, url) {
                    const id = slotObj.id || Date.now();
                    fabric.Image.fromURL(url, (img) => {
                        if (!img || !img.width || !img.height) return;

                        const slotW = slotObj.getScaledWidth();
                        const slotH = slotObj.getScaledHeight();
                        const scale = Math.max(slotW / img.width, slotH / img.height);
                        
                        img.set({
                            left: slotObj.left,
                            top: slotObj.top,
                            originX: slotObj.originX || 'left',
                            originY: slotObj.originY || 'top',
                            scaleX: scale,
                            scaleY: scale,
                            cropX: (img.width - slotW / scale) / 2,
                            cropY: (img.height - slotH / scale) / 2,
                            width: slotW / scale,
                            height: slotH / scale,
                            isFilledSlot: true,
                            originalSlotProps: { left: slotObj.left, top: slotObj.top, width: slotW, height: slotH, id: id },
                            selectable: true,
                            hasControls: true,
                            lockMovementX: false,
                            lockMovementY: false
                        });
                        
                        img.setControlsVisibility({ mt: true, mb: true, ml: true, mr: true, mtr: false });
                        
                        fabricCanvas.add(img);
                        
                        const objects = fabricCanvas.getObjects();
                        objects.forEach(o => {
                            if(o.isPlaceholderText && o.belongsToId === id) {
                                fabricCanvas.remove(o);
                            }
                        });
                        
                        // Preserve slot index
                        const slotIndex = fabricCanvas.getObjects().indexOf(slotObj);
                        fabricCanvas.remove(slotObj);
                        if (slotIndex !== -1) {
                            img.moveTo(slotIndex);
                        }
                        
                        fabricCanvas.setActiveObject(img);
                        fabricCanvas.renderAll();
                        setTimeout(() => this.saveHistoryState(), 500);
                    }, { crossOrigin: 'anonymous' });
                },
                
                async fillSlotPhoto(event) {
                    const file = event.target.files[0];
                    if (!file || !this.currentSlot) return;

                    const slot = this.currentSlot;
                    
                    const url = await this.uploadPhotoToServer(file);
                    if (url) {
                        if (!this.gallery.includes(url)) {
                            this.gallery.push(url);
                        }
                        this.fillSlotWithUrl(slot, url);
                    }
                    event.target.value = '';
                    this.currentSlot = null;
                },

                // FITUR TEKS
                addText(textStr, size, weight, yOffset = 50) {
                    const text = new fabric.Textbox(textStr, {
                        left: this.canvasWidth/2,
                        top: yOffset,
                        width: 300,
                        fontSize: size,
                        fontWeight: weight,
                        fill: '#1e293b',
                        textAlign: 'center',
                        originX: 'center'
                    });
                    fabricCanvas.add(text);
                    fabricCanvas.setActiveObject(text);
                    fabricCanvas.renderAll();
                },

                // FITUR LATAR
                setBackgroundColor(color) {
                    fabricCanvas.backgroundColor = color;
                    fabricCanvas.renderAll();
                    this.saveHistoryState();
                },

                // FITUR STIKER / SHAPES
                addShape(type) {
                    let shape;
                    if (type === 'rect') {
                        shape = new fabric.Rect({ left: 100, top: 100, fill: '#94a3b8', width: 100, height: 100 });
                    } else if (type === 'circle') {
                        shape = new fabric.Circle({ left: 100, top: 100, fill: '#94a3b8', radius: 50 });
                    } else if (type === 'triangle') {
                        shape = new fabric.Triangle({ left: 100, top: 100, fill: '#94a3b8', width: 100, height: 100 });
                    }
                    if (shape) {
                        fabricCanvas.add(shape);
                        fabricCanvas.centerObject(shape);
                        fabricCanvas.setActiveObject(shape);
                        fabricCanvas.renderAll();
                    }
                },
                
                addEmoji(emoji) {
                    const text = new fabric.Text(emoji, {
                        left: 100,
                        top: 100,
                        fontSize: 64,
                    });
                    fabricCanvas.add(text);
                    fabricCanvas.centerObject(text);
                    fabricCanvas.setActiveObject(text);
                    fabricCanvas.renderAll();
                },

                // FITUR MANAJEMEN HALAMAN & LAYOUT
                applyLayout(type) {
                    fabricCanvas.clear();
                    fabricCanvas.backgroundColor = '#ffffff';
                    
                    const slotColor = '#e2e8f0';
                    const slotStroke = '#94a3b8';
                    
                    const createSlot = (l, t, w, h) => {
                        return new fabric.Rect({
                            left: l, top: t, width: w, height: h,
                            fill: slotColor, stroke: slotStroke, strokeWidth: 2, strokeDashArray: [5, 5],
                            isSlot: true, selectable: true, hasControls: false, lockMovementX: true, lockMovementY: true,
                            hoverCursor: 'pointer'
                        });
                    };

                    const createPlaceholderText = (l, t, text, slotObj) => {
                        const tObj = new fabric.Text(text, {
                            left: l + slotObj.width/2, top: t + slotObj.height/2,
                            fontSize: 16, fill: '#64748b', originX: 'center', originY: 'center',
                            selectable: false, evented: false, isPlaceholderText: true, belongsToId: slotObj.id
                        });
                        return tObj;
                    };

                    let slots = [];
                    const w = this.singlePageWidth;
                    const h = this.canvasHeight;
                    const margin = 20;
                    const innerW = w - (margin*2);
                    const innerH = h - (margin*2);
                    
                    if (type === '1_foto') {
                        slots.push(createSlot(margin, margin, innerW, innerH));
                        slots.push(createSlot(w + margin, margin, innerW, innerH));
                    } else if (type === '2_foto') {
                        slots.push(createSlot(margin, margin, innerW, (innerH/2)-10));
                        slots.push(createSlot(margin, margin + (innerH/2) + 10, innerW, (innerH/2)-10));
                        slots.push(createSlot(w + margin, margin, innerW, (innerH/2)-10));
                        slots.push(createSlot(w + margin, margin + (innerH/2) + 10, innerW, (innerH/2)-10));
                    } else if (type === '4_foto') {
                        slots.push(createSlot(margin, margin, (innerW/2)-10, (innerH/2)-10));
                        slots.push(createSlot(margin + (innerW/2) + 10, margin, (innerW/2)-10, (innerH/2)-10));
                        slots.push(createSlot(margin, margin + (innerH/2) + 10, (innerW/2)-10, (innerH/2)-10));
                        slots.push(createSlot(margin + (innerW/2) + 10, margin + (innerH/2) + 10, (innerW/2)-10, (innerH/2)-10));
                        slots.push(createSlot(w + margin, margin, (innerW/2)-10, (innerH/2)-10));
                        slots.push(createSlot(w + margin + (innerW/2) + 10, margin, (innerW/2)-10, (innerH/2)-10));
                        slots.push(createSlot(w + margin, margin + (innerH/2) + 10, (innerW/2)-10, (innerH/2)-10));
                        slots.push(createSlot(w + margin + (innerW/2) + 10, margin + (innerH/2) + 10, (innerW/2)-10, (innerH/2)-10));
                    } else if (type === 'foto_teks') {
                        slots.push(createSlot(margin, margin, innerW, innerH));
                        this.addText('Tulis Ceritamu Disini...', 24, 'bold', 50);
                    } else if (type === 'panorama') {
                        slots.push(createSlot(margin, margin, (w * 2) - (margin*2), innerH));
                    } else if (type === 'cover') {
                        slots.push(createSlot(w + margin, margin, innerW, innerH));
                    }

                    slots.forEach((s, idx) => {
                        s.id = 'slot_' + Date.now() + '_' + idx;
                        fabricCanvas.add(s);
                        fabricCanvas.add(createPlaceholderText(s.left, s.top, '+ Klik Tambah Foto', s));
                    });

                    const pageIndex = this.pages.findIndex(p => p.id === this.currentPageId);
                    if (pageIndex !== -1) {
                        this.pages[pageIndex].layout = type;
                    }

                    fabricCanvas.renderAll();
                    this.saveHistoryState();
                },
                
                setZoomPercent(val) {
                    const obj = fabricCanvas.getActiveObject();
                    if (!obj || !obj.isFilledSlot) return;
                    
                    const minScale = Math.max(obj.originalSlotProps.width / obj._element.width, obj.originalSlotProps.height / obj._element.height);
                    let newScale = (val / 100) * minScale;
                    if (newScale < minScale) newScale = minScale;
                    
                    const currentRenderedWidth = obj.width * obj.scaleX;
                    const currentRenderedHeight = obj.height * obj.scaleY;
                    
                    obj.set({
                        scaleX: newScale,
                        scaleY: newScale,
                        width: currentRenderedWidth / newScale,
                        height: currentRenderedHeight / newScale
                    });
                    
                    this.constrainImagePan(obj);
                    fabricCanvas.renderAll();
                    this.updateZoomPercent(obj);
                },

                // FITUR DELETE OBJEK (Disesuaikan untuk memulihkan slot)
                deleteSelectedObject() {
                    const activeObjects = fabricCanvas.getActiveObjects();
                    if (activeObjects.length) {
                        fabricCanvas.discardActiveObject();
                        activeObjects.forEach(function(object) {
                            if(object.isFilledSlot && !object.isRawImage) {
                                // Restore empty slot
                                const p = object.originalSlotProps;
                                if (p && p.width !== undefined && p.height !== undefined && p.left !== undefined && p.top !== undefined) {
                                    const newSlot = new fabric.Rect({
                                        left: p.left, top: p.top, width: p.width, height: p.height,
                                        fill: '#e2e8f0', stroke: '#94a3b8', strokeWidth: 2, strokeDashArray: [5, 5],
                                        isSlot: true, selectable: true, hasControls: false, lockMovementX: true, lockMovementY: true,
                                        hoverCursor: 'pointer', id: p.id || Date.now()
                                    });
                                    fabricCanvas.add(newSlot);
                                    // add text
                                    const tObj = new fabric.Text('+ Klik Tambah Foto', {
                                        left: p.left + p.width/2, top: p.top + p.height/2,
                                        fontSize: 16, fill: '#64748b', originX: 'center', originY: 'center',
                                        selectable: false, evented: false, isPlaceholderText: true, belongsToId: p.id || newSlot.id
                                    });
                                    fabricCanvas.add(tObj);
                                    // move to bottom so text is above it
                                    newSlot.sendToBack();
                                }
                            }
                            fabricCanvas.remove(object);
                        });
                        this.showDeleteBtn = false;
                        fabricCanvas.renderAll();
                        this.saveCurrentPage();
                    }
                },

                // FITUR TEKS
                updateTextColor(color) {
                    this.currentTextColor = color;
                    const activeObj = fabricCanvas.getActiveObject();
                    if (activeObj && (activeObj.type === 'textbox' || activeObj.type === 'i-text' || activeObj.type === 'text')) {
                        activeObj.set('fill', color);
                        fabricCanvas.renderAll();
                        this.saveProject(false);
                    }
                },
                
                updateTextFont(font) {
                    this.currentTextFont = font;
                    const activeObj = fabricCanvas.getActiveObject();
                    if (activeObj && (activeObj.type === 'textbox' || activeObj.type === 'i-text' || activeObj.type === 'text')) {
                        activeObj.set('fontFamily', font);
                        if (font === "'Londrina Solid'") {
                            activeObj.set('fontWeight', 900);
                        }
                        fabricCanvas.renderAll();
                        this.saveProject(false);
                    }
                },

                addText(textStr, size, weight, defaultTop = 50) {
                    const text = new fabric.Textbox(textStr, {
                        left: 20,
                        top: defaultTop,
                        width: 460,
                        fontSize: size,
                        fontWeight: weight,
                        fill: '#1e293b',
                        textAlign: 'center',
                    });
                    fabricCanvas.add(text);
                    fabricCanvas.centerObject(text);
                    fabricCanvas.setActiveObject(text);
                    fabricCanvas.renderAll();
                },

                // FITUR LATAR
                setBackgroundColor(color) {
                    this.canvasBgColor = color;
                    fabricCanvas.backgroundColor = color;
                    fabricCanvas.renderAll();
                    this.saveProject(false);
                },

                // FITUR STIKER / SHAPES
                addShape(type) {
                    let shape;
                    if (type === 'rect') {
                        shape = new fabric.Rect({ left: 100, top: 100, fill: '#94a3b8', width: 100, height: 100 });
                    } else if (type === 'circle') {
                        shape = new fabric.Circle({ left: 100, top: 100, fill: '#94a3b8', radius: 50 });
                    } else if (type === 'triangle') {
                        shape = new fabric.Triangle({ left: 100, top: 100, fill: '#94a3b8', width: 100, height: 100 });
                    }
                    if (shape) {
                        fabricCanvas.add(shape);
                        fabricCanvas.centerObject(shape);
                        fabricCanvas.setActiveObject(shape);
                        fabricCanvas.renderAll();
                    }
                },
                addEmoji(emoji) {
                    const text = new fabric.Text(emoji, {
                        left: 100,
                        top: 100,
                        fontSize: 64,
                    });
                    fabricCanvas.add(text);
                    fabricCanvas.centerObject(text);
                    fabricCanvas.setActiveObject(text);
                    fabricCanvas.renderAll();
                },

                // FITUR MANAJEMEN HALAMAN
                saveCurrentPage() {
                    if(!fabricCanvas) return;
                    const pageIndex = this.pages.findIndex(p => p.id === this.currentPageId);
                    if (pageIndex !== -1) {
                        // Serialize canvas state, including our custom properties
                        const json = fabricCanvas.toJSON(['isSlot', 'isFilledSlot', 'isRawImage', 'originalSlotProps', 'id', 'isPlaceholderText', 'belongsToId']);
                        this.pages[pageIndex].json = JSON.stringify(json);
                    }
                },
                
                loadPage(id) {
                    const page = this.pages.find(p => p.id === id);
                    if (page) {
                        this.currentPageId = id;
                        fabricCanvas.clear();
                        
                        const applyStaticCovers = () => {
                            if (id === 0) {
                                fabricCanvas.backgroundColor = '#ffffff';
                                if (this.coverUrl) {
                                    fabric.Image.fromURL(this.coverUrl, (img) => {
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
                                            
                                            if (this.coverBackUrl && this.coverBackUrl !== this.coverUrl) {
                                                fabric.Image.fromURL(this.coverBackUrl, (backImg) => {
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
                                                scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: 0
                                            });
                                        }
                                    }, { crossOrigin: 'anonymous' });
                                }
                            } else if (id === 9) {
                                fabricCanvas.backgroundColor = '#ffffff';
                                if (this.coverBackUrl) {
                                    fabric.Image.fromURL(this.coverBackUrl, (img) => {
                                        if (!img) return;
                                        const isPortrait = img.width <= img.height;
                                        const targetWidth = isPortrait ? fabricCanvas.width / 2 : fabricCanvas.width;
                                        const scale = Math.max(targetWidth / img.width, fabricCanvas.height / img.height);
                                        fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas), {
                                            scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: 0
                                        });
                                    }, { crossOrigin: 'anonymous' });
                                }
                            }
                        };

                        if (page.json) {
                            fabricCanvas.loadFromJSON(page.json, () => {
                                // Re-bind event locking for slots
                                fabricCanvas.getObjects().forEach(o => {
                                    if(o.isSlot) {
                                        o.setControlsVisibility({ mt: false, mb: false, ml: false, mr: false, bl: false, br: false, tl: false, tr: false, mtr: false });
                                    } else if (o.isFilledSlot) {
                                        o.setControlsVisibility({ mt: true, mb: true, ml: true, mr: true, mtr: false });
                                        o.hasControls = true;
                                        o.lockMovementX = false;
                                        o.lockMovementY = false;
                                        if (o.clipPath) o.clipPath = null;
                                    } else if (o.type === 'image') {
                                        o.setControlsVisibility({ mt: false, mb: false, ml: false, mr: false });
                                    }
                                });
                                
                                if (id === 0 || id === 9) {
                                    if (this.isStaticTemplate) {
                                        applyStaticCovers();
                                    } else {
                                        fabricCanvas.renderAll();
                                    }
                                } else {
                                    fabricCanvas.renderAll();
                                }
                            });
                        } else {
                            if (id === 0 || id === 9) {
                                if (this.isStaticTemplate) {
                                    applyStaticCovers();
                                }
                            } else if (page.layout) {
                                this.applyLayout(page.layout);
                            } else {
                                fabricCanvas.backgroundColor = '#ffffff';
                                fabricCanvas.backgroundImage = null;
                                fabricCanvas.renderAll();
                            }
                        }
                    }
                },

                // --- HISTORY SYSTEM (UNDO/REDO) ---
                saveHistoryState() {
                    if (this.isHistoryModifying) return;
                    
                    clearTimeout(this.historyTimeout);
                    this.historyTimeout = setTimeout(() => {
                        const json = fabricCanvas.toJSON(['isSlot', 'isFilledSlot', 'isRawImage', 'originalSlotProps', 'id', 'isPlaceholderText', 'belongsToId']);
                        const stateStr = JSON.stringify(json);
                        
                        if (!this.pageHistory[this.currentPageId]) {
                            this.pageHistory[this.currentPageId] = { stack: [], currentIndex: -1 };
                        }
                        
                        const history = this.pageHistory[this.currentPageId];
                        
                        // Prevent saving identical consecutive states
                        if (history.currentIndex >= 0 && history.stack[history.currentIndex] === stateStr) {
                            return;
                        }
                        
                        // Drop future states if we branched off from a previous undo
                        history.stack = history.stack.slice(0, history.currentIndex + 1);
                        history.stack.push(stateStr);
                        history.currentIndex++;
                        
                        // Limit stack size to 50
                        if (history.stack.length > 50) {
                            history.stack.shift();
                            history.currentIndex--;
                        }
                    }, 300);
                },

                canUndo() {
                    const history = this.pageHistory[this.currentPageId];
                    return history && history.currentIndex > 0;
                },

                canRedo() {
                    const history = this.pageHistory[this.currentPageId];
                    return history && history.currentIndex < history.stack.length - 1;
                },

                undo() {
                    if (!this.canUndo()) return;
                    this.isHistoryModifying = true;
                    
                    const history = this.pageHistory[this.currentPageId];
                    history.currentIndex--;
                    
                    const stateStr = history.stack[history.currentIndex];
                    fabricCanvas.loadFromJSON(JSON.parse(stateStr), () => {
                        fabricCanvas.renderAll();
                        this.isHistoryModifying = false;
                    });
                },

                redo() {
                    if (!this.canRedo()) return;
                    this.isHistoryModifying = true;
                    
                    const history = this.pageHistory[this.currentPageId];
                    history.currentIndex++;
                    
                    const stateStr = history.stack[history.currentIndex];
                    fabricCanvas.loadFromJSON(JSON.parse(stateStr), () => {
                        fabricCanvas.renderAll();
                        this.isHistoryModifying = false;
                    });
                },



                switchPage(id) {
                    if(id === this.currentPageId) return;
                    this.saveCurrentPage();
                    this.loadPage(id);
                },

                addPage(layoutType) {
                    this.openLayoutModal = false;
                    this.saveCurrentPage();
                    const newId = this.nextPageId++;
                    this.pages.push({ id: newId, json: null, layout: layoutType });
                    this.loadPage(newId);
                    
                    if (layoutType !== 'blank') {
                        this.applyLayout(layoutType);
                    }
                    
                    // Scroll to bottom of pages list
                    setTimeout(() => {
                        const rightSidebar = document.querySelector('.overflow-y-auto');
                        if(rightSidebar) rightSidebar.scrollTop = rightSidebar.scrollHeight;
                    }, 100);
                },

                deletePage(id) {
                    if(this.pages.length <= 1) return; // minimal 1 halaman
                    
                    const index = this.pages.findIndex(p => p.id === id);
                    if(index > -1) {
                        this.pages.splice(index, 1);
                        if(this.currentPageId === id) {
                            const newActive = this.pages[this.pages.length - 1];
                            this.loadPage(newActive.id);
                        }
                    }
                },

                async saveProject(showNotification = false) {
                    this.saveCurrentPage();
                    this.saveStatus = 'Menyimpan...';

                    try {
                        const response = await fetch('/editor/save', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify({
                                design_code: this.designCode,
                                title: this.projectName,
                                book_size: this.bookSize,
                                pages: this.pages
                            })
                        });
                        
                        if (response.ok) {
                            this.saveStatus = 'Tersimpan otomatis.';
                            if (showNotification) {
                                alert('Desain berhasil disimpan!');
                            }
                        } else {
                            this.saveStatus = 'Gagal menyimpan.';
                        }
                    } catch (error) {
                        this.saveStatus = 'Koneksi error.';
                    }
                },

                async submitOrderForm() {
                    this.isSubmittingOrder = true;
                    // Auto save before order
                    await this.saveProject(false);
                    
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
