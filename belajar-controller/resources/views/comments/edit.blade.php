@extends('layouts.master')

@section('title', 'Edit Komentar')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Komentar
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('comments.update', $comment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="author_name" class="form-label">Nama Penulis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('author_name') is-invalid @enderror"
                                   id="author_name" name="author_name" value="{{ old('author_name', $comment->author_name) }}" required>
                            @error('author_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author_email" class="form-label">Email Penulis <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('author_email') is-invalid @enderror"
                                   id="author_email" name="author_email" value="{{ old('author_email', $comment->author_email) }}" required>
                            @error('author_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Isi Komentar <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content" name="content" rows="5" required>{{ old('content', $comment->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending" {{ old('status', $comment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $comment->status) == 'approved' ? 'selected' : '' }}>Approved</option>

                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update
                            </button>
                            <a href="{{ route('comments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Post Information --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Post Terkait</h6>
                </div>
                <div class="card-body">
                    <h5>
                        <a href="{{ route('posts.show', $comment->post) }}" class="text-decoration-none">
                            {{ $comment->post->title }}
                        </a>
                    </h5>
                    <p class="text-muted mb-2">{{ Str::limit($comment->post->content, 100) }}</p>
                    <a href="{{ route('posts.show', $comment->post) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>Lihat Post Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection