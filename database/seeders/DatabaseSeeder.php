<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\AdminUser;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            'Travel',
            'Wisuda',
            'Family',
            'Couple',
            'Football Memories',
            'Wedding',
        ];
        foreach ($categories as $cat) {
            TemplateCategory::firstOrCreate(['name' => $cat, 'slug' => Str::slug($cat)]);
        }

        // Admin
        AdminUser::firstOrCreate(
            ['email' => 'admin@ceritaku.id'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        // Settings
        $harga = [
            '8x8' => ['base_price' => 150000, 'extra_page_price' => 5000],
            '10x10' => ['base_price' => 200000, 'extra_page_price' => 7000]
        ];
        Setting::firstOrCreate(
            ['key' => 'harga_per_ukuran'],
            ['value' => json_encode($harga)]
        );
    }
}
