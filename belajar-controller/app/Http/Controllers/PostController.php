<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'user', 'tags'])
                     ->published()
                     ->latest('published_at');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->ofCategory($request->category);
        }

        if ($request->filled('tag')) {
            $query->withTag($request->tag);
        }

        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        $posts = $query->paginate(9)->withQueryString();

        $categories = Category::withCount('posts')
                              ->whereHas('posts')
                              ->orderBy('posts_count', 'desc')
                              ->get();

        $popularTags = Tag::withCount('posts')
                          ->whereHas('posts')
                          ->orderBy('posts_count', 'desc')
                          ->limit(15)
                          ->get();

        $popularPosts = Post::published()
                            ->popular()
                            ->with('user')
                            ->limit(5)
                            ->get();

        $authors = User::withCount('posts')
                       ->whereHas('posts')
                       ->orderBy('posts_count', 'desc')
                       ->get();

        return view('posts.index', compact(
            'posts',
            'categories',
            'popularTags',
            'popularPosts',
            'authors'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();
        
        return view('posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi akan ditambahkan nanti
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'slug' => 'nullable|unique:posts,slug',
            'body' => 'required|min:10',
            'excerpt' => 'nullable|max:500',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|url',
            'status' => 'required|in:draft,published,archived',
        ]);
         
        // Buat slug jika tidak diisi
        if (empty($validated['slug'])) {
            $validated['slug'] = \Str::slug($validated['title']);
        }
         
        // Buat excerpt jika tidak diisi
        if (empty($validated['excerpt'])) {
            $validated['excerpt'] = \Str::limit(strip_tags($validated['body']), 150);
        }

        // Set published_at otomatis saat status published
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }
         
        // Simpan post melalui relasi user
        $post = Auth::user()->posts()->create($validated);
         
        // Attach tags
        if ($request->has('tags')) {
            $post->tags()->attach($validated['tags']);
        }
         
        // Redirect dengan flash message
        return redirect()->route('posts.index')
                         ->with('success', 'Post berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->incrementViews();

        $post->load([
            'category',
            'user',
            'tags',
            'comments' => function ($query) {
                $query->approved()->latest();
            },
            'comments.user'
        ]);

        $relatedPosts = Post::published()
                            ->where('category_id', $post->category_id)
                            ->where('id', '!=', $post->id)
                            ->with(['user', 'category'])
                            ->limit(3)
                            ->get();

        $previousPost = Post::published()
                            ->where('published_at', '<', $post->published_at)
                            ->orderBy('published_at', 'desc')
                            ->first();

        $nextPost = Post::published()
                        ->where('published_at', '>', $post->published_at)
                        ->orderBy('published_at', 'asc')
                        ->first();

        return view('posts.show', compact(
            'post',
            'relatedPosts',
            'previousPost',
            'nextPost'
        ));
    }

    /**
     * Show posts by category.
     */
    public function byCategory(Category $category)
    {
        $posts = $category->posts()
                          ->published()
                          ->with(['user', 'tags'])
                          ->latest('published_at')
                          ->paginate(9);

        return view('posts.category', compact('category', 'posts'));
    }

    /**
     * Show posts by tag.
     */
    public function byTag(Tag $tag)
    {
        $posts = $tag->posts()
                     ->published()
                     ->with(['user', 'category'])
                     ->latest('published_at')
                     ->paginate(9);

        return view('posts.tag', compact('tag', 'posts'));
    }

    /**
     * Show posts by author.
     */
    public function byAuthor(User $user)
    {
        $posts = $user->posts()
                      ->published()
                      ->with(['category', 'tags'])
                      ->latest('published_at')
                      ->paginate(9);

        return view('posts.author', compact('user', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();
        $postTags = $post->tags->pluck('id')->toArray();
        
        return view('posts.edit', compact('post', 'categories', 'tags', 'postTags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validasi data
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'slug' => 'nullable|unique:posts,slug,' . $post->id,
            'body' => 'required|min:10',
            'excerpt' => 'nullable|max:500',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|url',
            'status' => 'required|in:draft,published,archived',
        ], [
            'title.required' => 'Judul post wajib diisi!',
            'title.min' => 'Judul minimal :min karakter!',
            'body.required' => 'Konten post wajib diisi!',
            'body.min' => 'Konten minimal :min karakter!',
            'category_id.required' => 'Kategori wajib dipilih!',
            'category_id.exists' => 'Kategori tidak valid!',
        ]);
         
        // Buat slug jika tidak diisi
        if (empty($validated['slug'])) {
            $validated['slug'] = \Str::slug($validated['title']);
        }
         
        // Buat excerpt jika tidak diisi
        if (empty($validated['excerpt'])) {
            $validated['excerpt'] = \Str::limit(strip_tags($validated['body']), 150);
        }
         
        // Update post
        $post->update($validated);
         
        // Sync tags
        if ($request->has('tags')) {
            $post->tags()->sync($validated['tags']);
        } else {
            $post->tags()->detach();
        }
         
        // Jika status berubah menjadi published dan belum ada published_at
        if ($post->status == 'published' && !$post->published_at) {
            $post->publish();
        }
         
        // Redirect dengan flash message
        return redirect()->route('posts.show', $post)
                         ->with('success', 'Post berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Soft delete
        $post->delete();

        return redirect()->route('posts.index')
                         ->with('success', 'Post berhasil dipindahkan ke
trash!');
    }

    /**
     * Method tambahan
     */
    public function featured()
    {
        $posts = Post::featured()
                     ->published()
                     ->with(['category', 'tags'])
                     ->latest()
                     ->paginate(12);

        return view('posts.featured', compact('posts'));
    }

    public function popular()
    {
$posts = Post::published() ->popular() ->with(['category', 'tags']) ->paginate(12);
return view('posts.popular', compact('posts'));
}
public function trash()
{
$posts = Post::onlyTrashed() ->with(['category']) ->latest() ->paginate(10);
return view('posts.trash', compact('posts'));
}
public function restore($id)
{
$post = Post::onlyTrashed()->findOrFail($id);
$post->restore();
return redirect()->route('posts.trash') ->with('success', 'Post berhasil direstorasi!');
}
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->forceDelete();
        return redirect()->route('posts.trash')->with('success', 'Post berhasil dihapus permanen!');
    }

    // Multi-step post creation
    public function createStep1()
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();
        return view('posts.create-step1', compact('categories', 'tags'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        session(['post_data' => $validated]);
        return redirect()->route('posts.create.step2');
    }

    public function createStep2()
    {
        if (!session('post_data')) {
            return redirect()->route('posts.create.step1');
        }
        return view('posts.create-step2');
    }

    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|min:10',
            'excerpt' => 'nullable|max:500',
            'featured_image' => 'nullable|url',
        ]);

        $postData = session('post_data', []);
        $postData = array_merge($postData, $validated);
        session(['post_data' => $postData]);

        return redirect()->route('posts.create.step3');
    }

    public function createStep3()
    {
        if (!session('post_data')) {
            return redirect()->route('posts.create.step1');
        }
        return view('posts.create-step3');
    }

    public function storeMultiStep(Request $request)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'focus_keyword' => 'nullable|max:100',
        ]);

        $postData = session('post_data', []);
        $postData = array_merge($postData, $validated);

        if (empty($postData['slug'])) {
            $postData['slug'] = \Str::slug($postData['title']);
        }
        if (empty($postData['excerpt'])) {
            $postData['excerpt'] = \Str::limit(strip_tags($postData['body']), 150);
        }

        $post = Post::create($postData);

        if (isset($postData['tags'])) {
            $post->tags()->attach($postData['tags']);
        }

        session()->forget('post_data');

        return redirect()->route('posts.show', $post)->with('success', 'Post berhasil dibuat!');
    }
}