@extends('layouts.master')

@section('title', 'Kategori: ' . $category->name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-tag me-2"></i>{{ $category->name }}
                    </h1>
                    <p class="text-muted mb-0">{{ $category->description }}</p>
                </div>
                <div>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-newspaper me-2"></i>Posts dalam Kategori Ini ({{ $posts->total() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($posts->count() > 0)
                                <div class="row">
                                    @foreach($posts as $post)
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                                            {{ $post->title }}
                                                        </a>
                                                    </h5>
                                                    <p class="card-text text-muted">
                                                        {{ Str::limit($post->content, 100) }}
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>{{ $post->created_at->format('d M Y') }}
                                                        </small>
                                                        <div>
                                                            @if($post->tags->count() > 0)
                                                                @foreach($post->tags as $tag)
                                                                    <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i>Baca Selengkapnya
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Pagination --}}
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $posts->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada post dalam kategori ini</h5>
                                    <p class="text-muted">Kategori ini belum memiliki post apapun.</p>
                                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>Buat Post Baru
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection