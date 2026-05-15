@extends('layouts.master')

@section('title', $post->title)

@push('styles')
<style>
    .post-body {
        font-size: 1.05rem;
        line-height: 1.75;
    }

    .post-body h2,
    .post-body h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .post-body blockquote {
        border-left: 4px solid #0d6efd;
        padding: 0.75rem 1rem;
        background-color: #f8f9fa;
        margin: 1.5rem 0;
    }

    .post-navigation {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-3">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-folder me-1"></i>
                            <a href="{{ route('posts.category', $post->category->slug) }}" class="text-decoration-none">{{ $post->category->name }}</a>
                            •
                            <i class="fas fa-user me-1"></i>
                            <a href="{{ route('posts.author', $post->user->id) }}" class="text-decoration-none">{{ $post->user->name }}</a>
                        </small>
                        <h1 class="mt-2 mb-3">{{ $post->title }}</h1>
                    </div>

                    <div class="text-md-end">
                        {!! $post->status_badge !!}
                        <div class="text-muted small mt-2">
                            <i class="fas fa-calendar me-1"></i>{{ $post->published_at ? $post->published_at->format('d M Y') : 'Belum dipublikasi' }}
                            <span class="mx-1">•</span>
                            <i class="fas fa-clock me-1"></i>{{ $post->reading_time }}
                        </div>
                    </div>
                </div>

                @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" class="img-fluid rounded mb-4" alt="{{ $post->title }}">
                @endif

                <div class="mb-4">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('posts.tag', $tag->slug) }}" class="badge bg-secondary text-decoration-none me-1 mb-1">
                            <i class="fas fa-tag me-1"></i>{{ $tag->name }}
                        </a>
                    @endforeach
                </div>

                <div class="post-body">{!! $post->body !!}</div>

                <div class="mt-5 d-flex gap-2 flex-wrap">
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i>Hapus
                    </button>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary ms-auto">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="post-navigation mb-4">
            <div class="row">
                <div class="col-6">
                    @if($previousPost)
                        <a href="{{ route('posts.show', $previousPost->slug) }}" class="text-decoration-none">
                            <small><i class="fas fa-arrow-left me-1"></i>Previous Post</small>
                            <h6 class="mb-0">{{ $previousPost->title }}</h6>
                        </a>
                    @endif
                </div>
                <div class="col-6 text-end">
                    @if($nextPost)
                        <a href="{{ route('posts.show', $nextPost->slug) }}" class="text-decoration-none">
                            <small>Next Post <i class="fas fa-arrow-right ms-1"></i></small>
                            <h6 class="mb-0">{{ $nextPost->title }}</h6>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Komentar ({{ $post->approvedComments->count() }})</h5>
            </div>
            <div class="card-body">
                @forelse($post->approvedComments as $comment)
                    <div class="d-flex mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <div class="flex-shrink-0">
                            <div class="bg-secondary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">{{ $comment->author_name }}</h6>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">{{ $comment->content }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada komentar.</p>
                @endforelse
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-pencil-alt me-2"></i>Tinggalkan Komentar</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="author_name" class="form-label">Nama</label>
                        <input type="text" name="author_name" id="author_name" value="{{ old('author_name') }}" class="form-control @error('author_name') is-invalid @enderror" required>
                        @error('author_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author_email" class="form-label">Email</label>
                        <input type="email" name="author_email" id="author_email" value="{{ old('author_email') }}" class="form-control @error('author_email') is-invalid @enderror" required>
                        @error('author_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Komentar</label>
                        <textarea name="content" id="content" rows="5" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Tentang Penulis</h5>
            </div>
            <div class="card-body text-center">
                <div class="bg-secondary text-white rounded-circle d-inline-flex p-4 mb-3">
                    <i class="fas fa-user fa-4x"></i>
                </div>
                <h5>{{ $post->user->name }}</h5>
                <p class="text-muted small">Member sejak {{ $post->user->created_at->format('M Y') }}</p>

                @php
                    $userStats = [
                        'total_posts' => $post->user->publishedPosts()->count(),
                        'total_views' => $post->user->posts()->sum('views'),
                        'total_comments' => $post->user->posts()->withCount('comments')->get()->sum('comments_count'),
                    ];
                @endphp

                <div class="row text-center">
                    <div class="col-4">
                        <h6>{{ $userStats['total_posts'] }}</h6>
                        <small>Posts</small>
                    </div>
                    <div class="col-4">
                        <h6>{{ number_format($userStats['total_views']) }}</h6>
                        <small>Views</small>
                    </div>
                    <div class="col-4">
                        <h6>{{ $userStats['total_comments'] }}</h6>
                        <small>Comments</small>
                    </div>
                </div>

                <a href="{{ route('posts.author', $post->user->id) }}" class="btn btn-outline-primary btn-sm mt-3 w-100">
                    <i class="fas fa-newspaper me-2"></i>Lihat Semua Posts
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-link me-2"></i>Related Posts</h5>
            </div>
            <div class="card-body">
                @forelse($relatedPosts as $related)
                    <div class="mb-3 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <a href="{{ route('posts.show', $related->slug) }}" class="text-decoration-none">
                            <h6 class="mb-1">{{ $related->title }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>{{ $related->user->name }} |
                                <i class="fas fa-eye me-1"></i>{{ number_format($related->views) }} views
                            </small>
                        </a>
                    </div>
                @empty
                    <p class="text-muted mb-0">Tidak ada related posts.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus post <strong>"{{ $post->title }}"</strong>?</p>
                <p class="text-danger small"><i class="fas fa-info-circle me-1"></i>Data akan dipindahkan ke trash dan dapat direstorasi.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Batal</button>
                <form action="{{ route('posts.destroy', $post) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-2"></i>Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const contentTextarea = document.getElementById('content');
if (contentTextarea) {
    contentTextarea.addEventListener('input', function (e) {
        document.getElementById('comment-counter').textContent = e.target.value.length;
    });
}
</script>
@endpush
