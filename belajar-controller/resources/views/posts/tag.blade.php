@extends('layouts.master')

@section('title', 'Tag: ' . $tag->name)

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-4 mb-2">
                            <i class="fas fa-tag me-2"></i>{{ $tag->name }}
                        </h1>
                        <p class="lead mb-0">{{ $posts->total() }} posts dengan tag ini</p>
                    </div>
                    <div style="font-size: 5rem;">
                        <i class="fas fa-tags"></i>
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
                Belum ada posts dengan tag ini.
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Tags Populer</h5>
            </div>
            <div class="card-body">
                @foreach(App\Models\Tag::withCount('posts')->orderBy('posts_count', 'desc')->limit(20)->get() as $t)
                    <a href="{{ route('posts.tag', $t->slug) }}" class="badge bg-secondary text-decoration-none me-1 mb-1 p-2" style="font-size: {{ 0.8 + ($t->posts_count / 30) }}rem;">
                        {{ $t->name }} ({{ $t->posts_count }})
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection