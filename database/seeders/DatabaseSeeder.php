<?php

namespace Database\Seeders;

use App\Models\PortfolioPhoto;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => 'beniken',
        ], [
            'name' => 'Admin R&D Photography',
            'password' => 'rdpotrait272822',
        ]);

        User::query()->updateOrCreate([
            'email' => 'rakun',
        ], [
            'name' => 'Admin R&D Photography',
            'password' => 'rakan1982',
        ]);

        $photos = [
            ['Wedding Story', 'Wedding', 'https://images.unsplash.com/photo-1523438885200-e635ba2c371e?auto=format&fit=crop&w=1400&q=85', 1],
            ['Pre-Wedding', 'Pre-Wedding', 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=900&q=85', 2],
            ['Details', 'Details', 'https://images.unsplash.com/photo-1606800052052-a08af7148866?auto=format&fit=crop&w=900&q=85', 3],
            ['Event Coverage', 'Event', 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1200&q=85', 4],
        ];

        foreach ($photos as [$title, $category, $imagePath, $sortOrder]) {
            PortfolioPhoto::query()->updateOrCreate([
                'title' => $title,
            ], [
                'category' => $category,
                'placement' => 'portfolio',
                'media_type' => 'image',
                'image_path' => $imagePath,
                'sort_order' => $sortOrder,
                'is_visible' => true,
            ]);
        }

        PortfolioPhoto::query()->updateOrCreate([
            'title' => 'Video Highlight',
            'placement' => 'portfolio',
        ], [
            'category' => 'Video',
            'media_type' => 'video',
            'image_path' => 'portfolio/portrait-background-reference.mp4',
            'sort_order' => 0,
            'is_visible' => true,
        ]);

        foreach (range(1, 6) as $index) {
            PortfolioPhoto::query()->updateOrCreate([
                'title' => "R&D Portrait {$index}",
                'placement' => 'hero',
            ], [
                'category' => 'Portrait',
                'media_type' => 'image',
                'image_path' => "hero/model-0{$index}.jpeg",
                'sort_order' => $index,
                'is_visible' => true,
            ]);
        }
    }
}
