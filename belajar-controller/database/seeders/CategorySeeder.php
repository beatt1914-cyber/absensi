<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Teknologi',
                'description' => 'Artikel tentang teknologi terbaru,
gadget, dan inovasi digital.',
                'color' => '#FF5733',
            ],
            [
                'name' => 'Pemrograman',
                'description' => 'Tutorial, tips, dan trik seputar dunia
pemrograman.',
                'color' => '#33FF57',
            ],
            [
                'name' => 'Database',
                'description' => 'Pembahasan tentang database, SQL, NoSQL,
dan optimasi query.',
                'color' => '#3357FF',
            ],
            [
                'name' => 'Web Development',
                'description' => 'Pengembangan web frontend dan backend,
framework, dan tools.',
                'color' => '#F3FF33',
            ],
            [
                'name' => 'Artificial Intelligence',
                'description' => 'Kecerdasan buatan, machine learning, dan
deep learning.',
                'color' => '#FF33F3',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 