# CeritaKu

CeritaKu adalah platform web untuk membuat dan memesan **Memory Book** secara online. Pengguna dapat memilih template, mengunggah foto, menyusun halaman melalui editor visual, melihat preview buku, lalu mengirim detail pesanan melalui WhatsApp.

Project ini dibuat sebagai portfolio aplikasi e-commerce berbasis Laravel dengan alur pengguna dan operasional admin yang lengkap.

## Fitur Utama

### Sisi pelanggan

- Landing page dengan kategori dan template cover aktif.
- Editor Memory Book berbasis browser.
- Upload banyak foto dan fitur Magic Auto-Fill.
- Penyusunan halaman, layout, teks, stiker, dan warna.
- Preview buku dengan animasi flipbook 3D.
- Form pemesanan dengan pilihan ukuran, jenis buku, jumlah, dan alamat.
- Pengiriman detail order melalui WhatsApp.
- Pelacakan status pesanan menggunakan kode order dan nomor WhatsApp.
- Halaman bantuan dan FAQ.

### Sisi admin

- Dashboard ringkasan template dan pesanan.
- Pencarian dan pagination daftar pesanan.
- Perubahan status pesanan dari baru hingga selesai.
- Pengelolaan kategori dan template.
- Editor layout template dinamis.
- Tampilan produksi untuk melihat desain pelanggan.
- Download foto pesanan dalam bentuk ZIP.
- Export layout desain dalam bentuk JSON.
- Pengaturan harga dan nomor WhatsApp.

## Teknologi

- PHP 8.2+
- Laravel 12
- SQLite atau MySQL
- Laravel Eloquent dan Blade
- Alpine.js
- Tailwind CSS
- Vite
- Fabric.js untuk canvas editor
- Page-flip untuk preview buku
- Dompdf dan Intervention Image

## Menjalankan Project Secara Lokal

### Prasyarat

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- SQLite atau database lain yang didukung Laravel

### Instalasi

```bash
git clone https://github.com/luqmanazett/ceritaku.git
cd ceritaku
composer run setup
```

Perintah setup akan memasang dependency, membuat file `.env`, membuat application key, menjalankan migration, memasang dependency frontend, dan melakukan build asset.

Jalankan server development dengan:

```bash
composer run dev
```

Aplikasi dapat dibuka di `http://localhost:8000`.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
