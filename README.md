# CeritaKu

Aplikasi web untuk membuat dan memesan Memory Book custom secara online.

## Tentang Project

CeritaKu adalah platform yang membantu pengguna membuat buku kenangan digital menjadi produk fisik. Pengguna dapat memilih template, mengunggah foto, mengatur layout halaman, melihat preview buku, dan langsung memesan melalui WhatsApp.

Project ini dibuat untuk menunjukkan kemampuan saya dalam membangun aplikasi web full-stack dengan Laravel, serta menghadirkan pengalaman pengguna yang intuitif dan proses bisnis yang terstruktur.

## Fitur Utama

- Landing page modern dan responsif
- Editor custom untuk mengatur halaman buku
- Upload foto dan pengisian layout otomatis
- Preview buku dengan tampilan flipbook 3D
- Form pemesanan customer
- Tracking status pesanan
- Dashboard admin untuk mengelola pesanan, template, dan kategori

## Teknologi

- Laravel 12
- PHP 8.2+
- Blade
- Tailwind CSS
- Alpine.js
- Vite
- SQLite/MySQL

## Link

- GitHub: https://github.com/luqmanazett/ceritaku
- 

## Cara Menjalankan

```bash
git clone https://github.com/luqmanazett/ceritaku.git
cd ceritaku
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

## Status Project

Project ini siap dipakai sebagai portfolio dan dapat dikembangkan lebih lanjut untuk kebutuhan produksi nyata.

## Lisensi

Project ini menggunakan lisensi MIT.
