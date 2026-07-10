<?php

namespace Database\Seeders;

use App\Models\HeroSetting;
use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSettingSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['page' => 'home', 'image' => null],
            ['page' => 'about', 'image' => 'frontend/assets/img/about-top.jpg'],
            ['page' => 'contact', 'image' => 'frontend/assets/img/contact-top.jpg'],
            ['page' => 'gallery', 'image' => 'frontend/assets/img/gallery-top.jpg'],
            ['page' => 'homestay', 'image' => 'frontend/assets/img/homestay-top.jpg'],
            ['page' => 'impact', 'image' => 'frontend/assets/img/community-top.jpg'],
            ['page' => 'blog', 'image' => 'frontend/assets/img/top-blog.jpg'],
            ['page' => 'packages', 'image' => 'frontend/assets/img/package-top.jpg'],
            ['page' => 'activities', 'image' => 'frontend/assets/img/hero2.jpg'],
        ];

        foreach ($pages as $pageData) {
            HeroSetting::create($pageData);
        }

        // Add default home slides
        $homeHero = HeroSetting::where('page', 'home')->first();
        if ($homeHero) {
            $slides = [
                ['image' => 'frontend/assets/img/hero1.jpg', 'alt_text' => 'Desa Wisata Gabugan', 'order' => 1],
                ['image' => 'frontend/assets/img/hero2.jpg', 'alt_text' => 'Gabugan Village', 'order' => 2],
                ['image' => 'frontend/assets/img/hero3.jpg', 'alt_text' => 'Gabugan Activities', 'order' => 3],
                ['image' => 'frontend/assets/img/hero4.jpg', 'alt_text' => 'Gabugan Landscape', 'order' => 4],
                ['image' => 'frontend/assets/img/hero5.jpg', 'alt_text' => 'Gabugan Culture', 'order' => 5],
            ];

            foreach ($slides as $slide) {
                $homeHero->slides()->create($slide);
            }
        }
    }
}
