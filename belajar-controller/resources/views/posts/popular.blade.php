@extends('layouts.master')

@section('title', 'Posts Populer')

@section('content')
<div class="row">
    {{-- Posts List --}}
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-fire text-danger me-2"></i>Posts Populer</h1>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Posts
            </a>
        </div>

        @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}"
                                     alt="{{ $post->title }}"
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover;">
                            @endif

                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $post->title }}</h5>
                                    {!! $post->status_badge !!}
                                </div>

                                <p class="text-muted small mb-2">
                                    <i class="fas fa-folder me-1"></i>{{ $post->category->name }} |
                                    <i class="fas fa-tags me-1"></i>
                                    @foreach($post->tags as $tag)
                                        <span class="badge bg-secondary">{{ $tag->name }}</span>
                                    @endforeach
                                </p>

                                <p class="card-text">{{ $post->excerpt }}</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="fas fa-eye me-1"></i>{{ number_format($post->views) }} views |
                                        <i class="fas fa-clock me-1"></i>{{ $post->reading_time }}
                                    </div>
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary btn-sm">
                                        Baca <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $post->published_at ? $post->published_at->format('d M Y') : 'Belum dipublikasi' }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $posts->withQueryString()->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada posts populer yang tersedia.
            </div>
        @endif
    </div>
</div>
@endsection