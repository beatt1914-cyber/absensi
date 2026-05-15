<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $categories = Category::all();
        $tags = Tag::all();
        $users = User::all();

        if ($users->isEmpty()) {
            $users = User::factory()->count(5)->create();
        }

        for ($i = 1; $i <= 30; $i++) {
            $user = $users->random();

            $post = $user->posts()->create([
                'category_id' => $categories->random()->id,
                'title' => $faker->sentence(6),
                'body' => $this->generateContent($faker),
                'excerpt' => $faker->paragraph(3),
                'featured_image' => $faker->imageUrl(800, 400, 'technology', true),
                'views' => $faker->numberBetween(0, 5000),
                'is_featured' => $faker->boolean(20),
                'status' => $faker->randomElement(['draft', 'published', 'archived']),
            ]);

            if ($post->status == 'published') {
                $post->published_at = $faker->dateTimeBetween('-3 months', 'now');
                $post->save();
            }

            $post->tags()->attach(
                $tags->random($faker->numberBetween(2, 5))->pluck('id')->toArray()
            );

            $this->createComments($post, $faker);
        }
    }

    private function generateContent($faker)
    {
        $content = "<h2>" . $faker->sentence(4) . "</h2>\n\n";
        $content .= "<p>" . $faker->paragraph(5) . "</p>\n\n";
        $content .= "<h3>" . $faker->sentence(3) . "</h3>\n\n";
        $content .= "<p>" . $faker->paragraph(4) . "</p>\n\n";
        $content .= "<blockquote>" . $faker->paragraph(2) .
"</blockquote>\n\n";
        $content .= "<ul>\n";
        for ($j = 1; $j <= 4; $j++) {
            $content .= "<li>" . $faker->sentence(3) . "</li>\n";
        }
        $content .= "</ul>\n\n";
        $content .= "<p>" . $faker->paragraph(3) . "</p>";
        
        return $content;
    }

    private function createComments($post, $faker)
    {
        $commentCount = $faker->numberBetween(0, 15);
        
        for ($j = 1; $j <= $commentCount; $j++) {
            $post->comments()->create([
                'author_name' => $faker->name,
                'author_email' => $faker->email,
                'content' => $faker->paragraph(2),
                'is_approved' => $faker->boolean(70),
                'created_at' => $faker->dateTimeBetween($post->created_at,
'now'),
            ]);
        }
    }
}