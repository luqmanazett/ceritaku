<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cleanup:drafts', function () {
    $this->info('Memulai pembersihan draft desain yang sudah kedaluwarsa (lebih dari 3 hari)...');
    
    // Cari desain yang usianya lebih dari 3 hari dan TIDAK punya pesanan
    $drafts = \App\Models\Design::whereDoesntHave('order')
        ->where('created_at', '<', \Carbon\Carbon::now()->subDays(3))
        ->get();
        
    $deletedCount = 0;
    
    foreach ($drafts as $draft) {
        // Hapus foto fisik dari storage
        $photos = $draft->photos;
        if ($photos) {
            foreach ($photos as $photo) {
                $filePath = 'public/' . $photo->stored_path;
                if (\Illuminate\Support\Facades\Storage::exists($filePath)) {
                    \Illuminate\Support\Facades\Storage::delete($filePath);
                }
                $photo->delete();
            }
        }
        
        // Hapus halaman desain
        $draft->pages()->delete();
        
        // Hapus desain
        $draft->delete();
        $deletedCount++;
    }
    
    $this->info("Pembersihan selesai! Total $deletedCount draft desain telah dihapus.");
})->purpose('Membersihkan desain draft yang sudah lama (3 hari) dan tidak dilanjutkan ke pembayaran')->daily();
