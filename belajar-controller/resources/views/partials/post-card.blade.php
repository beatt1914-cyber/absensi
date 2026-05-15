<div class="card post-card h-100 shadow-sm">
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
                <span class="badge bg-warning"><i class="fas fa-star me-1"></i>Featured</span>
            @endif
        </div>

        <h5 class="card-title">
            <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none text-dark">
                {{ $post->title }}
            </a>
        </h5>

        <p class="text-muted small mb-2">
            <i class="fas fa-user me-1"></i>{{ $post->user->name }} |
            <i class="fas fa-calendar me-1"></i>{{ $post->published_at ? $post->published_at->format('d M Y') : 'Belum dipublikasi' }} |
            <i class="fas fa-eye me-1"></i>{{ number_format($post->views) }}
        </p>

        <p class="card-text">{{ $post->excerpt }}</p>

        <div class="mb-3">
            @foreach($post->tags as $tag)
                <a href="{{ route('posts.tag', $tag->slug) }}" class="badge bg-secondary text-decoration-none me-1">{{ $tag->name }}</a>
            @endforeach
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                Baca <i class="fas fa-arrow-right ms-1"></i>
            </a>
            <small class="text-muted">
                <i class="fas fa-comment me-1"></i>{{ $post->comments()->approved()->count() }} komentar
            </small>
        </div>
    </div>
</div>
