<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Comment::with(['post']);

        // Search by author name or content
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('author_name', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $comments = $query->paginate(15)->withQueryString();

        return view('comments.index', compact('comments'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $comment->load(['post']);
        return view('comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        $comment->load(['post']);
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'author_name' => 'required|min:3|max:100',
            'author_email' => 'required|email|max:255',
            'content' => 'required|min:5|max:1000',
            'status' => 'required|in:pending,approved',
        ]);

        $comment->update([
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'content' => $validated['content'],
            'is_approved' => $validated['status'] === 'approved',
        ]);

        return redirect()->route('comments.index')->with('success', 'Komentar berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('comments.index')->with('success', 'Komentar berhasil dihapus!');
    }

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'author_name' => 'required|min:3|max:100',
            'author_email' => 'required|email|max:255',
            'content' => 'required|min:5|max:1000',
        ], [
            'author_name.required' => 'Nama wajib diisi!',
            'author_name.min' => 'Nama minimal 3 karakter!',
            'author_email.required' => 'Email wajib diisi!',
            'author_email.email' => 'Format email tidak valid!',
            'content.required' => 'Komentar wajib diisi!',
            'content.min' => 'Komentar minimal 5 karakter!',
        ]);

        $comment = $post->comments()->create(array_merge($validated, ['is_approved' => false]));

        return redirect()->route('posts.show', $post)
                         ->with('success', 'Komentar berhasil ditambahkan dan menunggu moderasi!');
    }
}
