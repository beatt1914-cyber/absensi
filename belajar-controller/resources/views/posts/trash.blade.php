@extends('layouts.master')

@section('title', 'Kelola Sampah Posts')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-trash me-2"></i>Kelola Sampah Posts</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="mb-0">Posts yang telah dihapus (soft delete) dapat dipulihkan atau dihapus permanen.</p>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Posts
                    </a>
                </div>

                @if($posts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Dihapus Pada</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->category->name ?? 'N/A' }}</td>
                                        <td>{{ $post->deleted_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('posts.restore', $post->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin memulihkan post ini?')">
                                                    <i class="fas fa-undo me-1"></i>Pulihkan
                                                </button>
                                            </form>
                                            <form action="{{ route('posts.force-delete', $post->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus permanen post ini?')">
                                                    <i class="fas fa-times me-1"></i>Hapus Permanen
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Tidak ada posts di sampah.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection