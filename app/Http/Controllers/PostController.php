<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'comments.user', 'likes')->latest()->get();
        return view('post.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $file = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image')->store('post', 'public');
        }

        Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'image' => $file
        ]);

        return back();
    }

    public function like($id)
    {
        Like::firstOrCreate([
            'user_id' => auth()->id(),
            'post_id' => $id
        ]);
        return response()->json(['status' => 'ok']);
    }

    public function comment(Request $request)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'comment' => $request->comment
        ]);
        return response()->json(['status' => 'ok']);
    }
}
