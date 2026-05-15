@extends('layouts.master')

@section('title', 'Kategori: ' . $category->name)

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-4 mb-2">
                            <i class="fas fa-folder me-2"></i>{{ $category->name }}
                        </h1>
                        <p class="lead mb-0">{{ $category->description }}</p>
                        <small>{{ $posts->total() }} posts dalam kategori ini</small>
                    </div>
                    <div style="font-size: 5rem;">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-md-6 mb-4">
                        @include('partials.post-card', ['post' => $post])
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada posts dalam kategori ini.
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Kategori Lain</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach(App\Models\Category::where('id', '!=', $category->id)->limit(10)->get() as $cat)
                        <a href="{{ route('posts.category', $cat->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            {{ $cat->name }}
                            <span class="badge bg-primary rounded-pill">{{ $cat->posts_count ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection