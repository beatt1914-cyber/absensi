<?php 
 
namespace Database\Seeders; 
 
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB; 
 
class PostTagSeeder extends Seeder 
{ 
    public function run(): void 
    { 
        $postIds = DB::table('posts')->pluck('id')->toArray(); 
        $tagIds = DB::table('tags')->pluck('id')->toArray(); 
         
        $postTags = []; 
         
        // Setiap post mendapat 2-5 tag random 
        foreach ($postIds as $postId) { 
            $selectedTags = fake()->randomElements($tagIds, fake() ->numberBetween(2, 5)); 
             
            foreach ($selectedTags as $tagId) { 
                $postTags[] = [ 
                    'post_id' => $postId, 
'tag_id' => $tagId, 
'created_at' => now(), 
'updated_at' => now(), 
]; 
} 
} 
// Batch insert untuk efisiensi 
DB::table('post_tag')->insert($postTags); 
} 
} 