<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Laravel', 'PHP', 'JavaScript', 'Vue.js', 'React',
            'MySQL', 'PostgreSQL', 'MongoDB', 'Python', 'Java',
            'Kotlin', 'Swift', 'Flutter', 'Docker', 'Kubernetes',
            'AWS', 'Azure', 'DevOps', 'UI/UX', 'SEO'
        ];

        foreach ($tags as $tagName) {
            Tag::create(['name' => $tagName]);
        }
    }
}