<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan - CeritaKu</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: { brand: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 900: '#1e3a8a' } }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 selection:bg-brand-100 selection:text-brand-900">

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-brand-600 flex items-center justify-center text-white font-bold">C</div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">Cerita<span class="text-brand-600">Ku</span></span>
                </a>
                <a href="/" class="text-sm font-medium text-slate-500 hover:text-brand-600 transition-colors">Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="bg-brand-50 border-b border-brand-100 py-16">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-extrabold text-slate-900 mb-4">Pusat Bantuan</h1>
            <p class="text-lg text-slate-600">Temukan jawaban untuk semua pertanyaan Anda seputar CeritaKu.</p>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-3xl mx-auto px-4 py-16 space-y-16">
        
        <!-- Cara Pemesanan -->
        <section id="pemesanan" class="scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-sm">1</span>
                Cara Pemesanan
            </h2>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6 text-slate-600">
                <ol class="list-decimal list-inside space-y-4">
                    <li>Kunjungi halaman <a href="/template-cover" class="text-brand-600 font-semibold hover:underline">Template</a> dan pilih desain dasar buku Anda.</li>
                    <li>Setelah masuk ke halaman Editor, Anda bisa mulai mengunggah foto-foto Anda di panel sebelah kiri.</li>
                    <li>Geser (*drag*) foto ke dalam kanvas dan letakkan pada bingkai yang tersedia.</li>
                    <li>Tambahkan teks, stiker, atau ganti warna latar belakang sesuai selera.</li>
                    <li>Klik tombol <strong>"Lihat Preview 3D"</strong> di pojok kanan atas untuk melihat hasil desain dalam bentuk buku nyata.</li>
                    <li>Jika sudah puas, klik <strong>"Pesan Buku Sekarang"</strong>. Isi formulir pengiriman, dan Anda akan diarahkan ke WhatsApp Admin untuk konfirmasi pembayaran.</li>
                </ol>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-sm">?</span>
                FAQ (Pertanyaan Umum)
            </h2>
            <div class="space-y-4">
                <details class="group bg-white rounded-xl shadow-sm border border-slate-100 open:ring-1 open:ring-brand-200">
                    <summary class="flex items-center justify-between font-bold cursor-pointer p-5 text-slate-800">
                        Apakah saya bisa memesan buku dengan jumlah halaman custom?
                        <span class="transition group-open:rotate-180">
                            <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </summary>
                    <div class="px-5 pb-5 text-slate-600">
                        Ya! Anda bisa terus menambahkan halaman di dalam Editor. Harga akan disesuaikan secara otomatis berdasarkan jumlah total halaman sebelum Anda <i>checkout</i>.
                    </div>
                </details>
                <details class="group bg-white rounded-xl shadow-sm border border-slate-100 open:ring-1 open:ring-brand-200">
                    <summary class="flex items-center justify-between font-bold cursor-pointer p-5 text-slate-800">
                        Berapa lama proses pencetakan buku?
                        <span class="transition group-open:rotate-180">
                            <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </summary>
                    <div class="px-5 pb-5 text-slate-600">
                        Normalnya proses cetak dan <i>binding</i> (penjilidan) memakan waktu 3-5 hari kerja setelah desain Anda divalidasi dan pembayaran telah dikonfirmasi.
                    </div>
                </details>
                <details class="group bg-white rounded-xl shadow-sm border border-slate-100 open:ring-1 open:ring-brand-200">
                    <summary class="flex items-center justify-between font-bold cursor-pointer p-5 text-slate-800">
                        Apakah kualitas cetaknya bagus?
                        <span class="transition group-open:rotate-180">
                            <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </summary>
                    <div class="px-5 pb-5 text-slate-600">
                        Tentu! Kami menggunakan mesin cetak resolusi tinggi (High-Res 300dpi) dan kertas premium (Art Paper/Ivory) yang dilapisi <i>laminasi</i> sehingga warna tidak pudar dan tahan cipratan air.
                    </div>
                </details>
            </div>
        </section>

        <!-- Pengiriman -->
        <section id="pengiriman" class="scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                </span>
                Pengiriman
            </h2>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-4 text-slate-600">
                <p>Kami melayani pengiriman ke seluruh Indonesia menggunakan jasa kurir terpercaya (JNE, J&T, SiCepat, AnterAja, dll).</p>
                <p>Setelah pesanan Anda masuk tahap <strong>"Dikirim"</strong>, Anda dapat melacak posisi paket secara langsung melalui halaman <a href="/lacak" class="text-brand-600 font-semibold hover:underline">Lacak Pesanan</a>.</p>
                <p>Ongkos kirim akan dihitung secara manual oleh Admin kami saat konfirmasi via WhatsApp, karena berat paket berbeda-beda tergantung jenis dan jumlah buku yang dipesan.</p>
            </div>
        </section>

        <!-- Kontak -->
        <section id="kontak" class="scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                </span>
                Hubungi Kami
            </h2>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 text-slate-600 flex flex-col md:flex-row gap-6 items-center">
                <div class="flex-1">
                    <p class="mb-4">Mengalami kendala saat mendesain atau ada pertanyaan spesifik? Jangan ragu untuk menghubungi Customer Service kami. Kami siap melayani Anda di jam kerja (Senin - Sabtu, 09.00 - 17.00 WIB).</p>
                    <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-[#25D366] text-white font-bold rounded-xl hover:bg-[#128C7E] transition shadow-lg shadow-[#25D366]/30">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Chat WhatsApp
                    </a>
                </div>
            </div>
        </section>

        <!-- Terms and Privacy -->
        <section id="syarat" class="pt-8 scroll-mt-24">
            <h3 class="font-bold text-slate-800">Syarat & Ketentuan</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">Dengan menggunakan layanan CeritaKu, Anda menyetujui seluruh aturan dan ketentuan mengenai hak cipta gambar dan transaksi pemesanan yang berlaku. Gambar yang diunggah sepenuhnya menjadi tanggung jawab pelanggan, dan CeritaKu tidak akan menggunakan foto pelanggan untuk keperluan apa pun selain untuk proses pencetakan Memory Book.</p>
        </section>
        
        <section id="privasi" class="scroll-mt-24">
            <h3 class="font-bold text-slate-800">Kebijakan Privasi</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">Seluruh foto dan informasi pribadi yang diunggah ke server kami dienkripsi dan dijamin kerahasiaannya. Data desain akan dihapus secara otomatis selambat-lambatnya 30 hari setelah pesanan selesai dan dikirim ke alamat Anda.</p>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-8">
        <div class="max-w-4xl mx-auto px-4 text-center text-sm text-slate-500">
            &copy; 2026 CeritaKu. Hak Cipta Dilindungi Undang-Undang.
        </div>
    </footer>
</body>
</html>
