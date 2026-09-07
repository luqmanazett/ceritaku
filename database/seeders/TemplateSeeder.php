<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\TemplateCategory;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Travel',
            'Wisuda',
            'Family',
            'Couple',
            'Football Memories',
            'Wedding',
        ];

        $images = [
            'Travel' => 'https://images.unsplash.com/photo-1528543606781-2f6e6857f318?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            'Wisuda' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            'Family' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            'Couple' => 'https://images.unsplash.com/photo-1516589178581-6cd7833ae3b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            'Football Memories' => 'https://images.unsplash.com/photo-1518605368461-1ee7c664deaf?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            'Wedding' => 'https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
        ];

        foreach ($categories as $categoryName) {
            $category = TemplateCategory::firstOrCreate([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            // Create 2 dummy templates for each category
            for ($i = 1; $i <= 2; $i++) {
                Template::create([
                    'category_id' => $category->id,
                    'name' => $categoryName . ' Template ' . $i,
                    'description' => 'Desain premium untuk momen ' . strtolower($categoryName) . ' Anda.',
                    'thumbnail' => $images[$categoryName],
                    'cover_front' => 'front_' . strtolower(str_replace(' ', '_', $categoryName)) . '_' . $i . '.jpg',
                    'cover_back' => 'back_' . strtolower(str_replace(' ', '_', $categoryName)) . '_' . $i . '.jpg',
                    'spine' => 'spine_' . strtolower(str_replace(' ', '_', $categoryName)) . '_' . $i . '.jpg',
                    'status' => 'active',
                ]);
            }
        }
    }
}
