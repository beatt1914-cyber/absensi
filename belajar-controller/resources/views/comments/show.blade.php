@extends('layouts.master')

@section('title', 'Detail Komentar')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-comment me-2"></i>Detail Komentar
                </h1>
                <div>
                    <a href="{{ route('comments.edit', $comment) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('comments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-user me-2"></i>{{ $comment->author_name }}
                            </h5>
                            <small class="text-muted">{{ $comment->author_email }}</small>
                        </div>
                        <div class="text-end">
                            @if($comment->status == 'approved')
                                <span class="badge bg-success fs-6">Approved</span>
                            @elseif($comment->status == 'pending')
                                <span class="badge bg-warning fs-6">Pending</span>
                            @else
                                <span class="badge bg-danger fs-6">Rejected</span>
                            @endif
                            <br>
                            <small class="text-muted">{{ $comment->created_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Komentar:</h6>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($comment->content)) !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Post Terkait:</h6>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('posts.show', $comment->post) }}" class="text-decoration-none">
                                        {{ $comment->post->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit($comment->post->content, 100) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $comment->post->created_at->format('d M Y') }}
                                    </small>
                                    <a href="{{ route('posts.show', $comment->post) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Lihat Post
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($comment->updated_at != $comment->created_at)
                        <div class="text-muted">
                            <small>Terakhir diperbarui: {{ $comment->updated_at->format('d M Y H:i') }}</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection