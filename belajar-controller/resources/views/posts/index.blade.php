@extends('layouts.master')

@section('title', 'Daftar Posts')

@push('styles')
<style>
    .pagination {
        font-size: 0.9rem;
    }
    .pagination .page-link {
        padding: .35rem .65rem;
    }
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        border-radius: .25rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('posts.index') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari judul atau konten..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->posts_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="author" class="form-select">
                            <option value="">Semua Penulis</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }} ({{ $author->posts_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-md-6 mb-4">
                        <div class="card post-card h-100">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-4x"></i>
                                </div>
                            @endif

                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <a href="{{ route('posts.category', $post->category->slug) }}" class="category-badge">
                                        <i class="fas fa-folder me-1"></i>{{ $post->category->name }}
                                    </a>
                                    @if($post->is_featured)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-star me-1"></i>Featured
                                        </span>
                                    @endif
                                </div>

                                <h5 class="card-title">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none text-dark">
                                        {{ $post->title }}
                                    </a>
                                </h5>

                                <div class="post-meta mb-2">
                                    <a href="{{ route('posts.author', $post->user->id) }}" class="text-decoration-none text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $post->user->name }}
                                    </a> |
                                    <i class="fas fa-calendar me-1"></i>{{ $post->published_at? $post->published_at->format('d M Y') : 'Belum dipublikasi' }} |
                                    <i class="fas fa-clock me-1"></i>{{ $post->reading_time }} |
                                    <i class="fas fa-eye me-1"></i>{{ number_format($post->views) }}
                                </div>

                                <p class="post-excerpt">{{ $post->excerpt }}</p>

                                <div class="mb-3">
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('posts.tag', $tag->slug) }}" class="badge bg-secondary text-decoration-none me-1">
                                            <i class="fas fa-tag me-1"></i>{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                                        Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                                    </a>

                                    <small class="text-muted">
                                        <i class="fas fa-comment me-1"></i>
                                        {{ $post->comments()->approved()->count() }} komentar
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $posts->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada posts yang tersedia.
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Top Penulis</h5>
            </div>
            <div class="card-body">
                @foreach($authors->take(5) as $author)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <a href="{{ route('posts.author', $author->id) }}" class="text-decoration-none">
                            {{ $author->name }}
                        </a>
                        <span class="badge bg-primary rounded-pill">
                            {{ $author->posts_count }} posts
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Kategori</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($categories as $category)
                        <a href="{{ route('posts.category', $category->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-folder me-2" style="color: {{ $category->color }}"></i>
                                {{ $category->name }}
                            </span>
                            <span class="badge bg-primary rounded-pill">{{ $category->posts_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Tags Populer</h5>
            </div>
            <div class="card-body">
                @foreach($popularTags as $tag)
                    <a href="{{ route('posts.tag', $tag->slug) }}" class="badge bg-secondary text-decoration-none me-1 mb-1 p-2" style="font-size: {{ 0.8 + ($tag->posts_count / 20) }}rem;">
                        {{ $tag->name }} ({{ $tag->posts_count }})
                    </a>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Popular Posts</h5>
            </div>
            <div class="card-body">
                @foreach($popularPosts as $popular)
                    <div class="mb-3 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <a href="{{ route('posts.show', $popular->slug) }}" class="d-block text-decoration-none text-dark">
                            <h6 class="mb-1">{{ $popular->title }}</h6>
                            <small class="text-muted d-block">
                                <i class="fas fa-user me-1"></i>{{ $popular->user->name }} |
                                <i class="fas fa-eye me-1"></i>{{ number_format($popular->views) }} views
                            </small>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 