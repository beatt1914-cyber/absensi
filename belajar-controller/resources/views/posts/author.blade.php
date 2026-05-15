@extends('layouts.master')

@section('title', 'Posts oleh ' . $user->name)

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex align-items-center flex-column flex-md-row gap-4">
                    <div class="flex-shrink-0">
                        <div class="bg-secondary text-white rounded-circle p-4">
                            <i class="fas fa-user fa-4x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h1 class="display-4 mb-2">{{ $user->name }}</h1>
                        <p class="lead mb-2">Member sejak {{ $user->created_at->format('d F Y') }}</p>

                        @php
                            $stats = [
                                'posts' => $user->publishedPosts()->count(),
                                'views' => $user->posts()->sum('views'),
                                'comments' => $user->posts()->withCount('comments')->get()->sum('comments_count'),
                            ];
                        @endphp

                        <div class="row text-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>{{ $stats['posts'] }}</h3>
                                    <small>Posts</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>{{ number_format($stats['views']) }}</h3>
                                    <small>Total Views</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h3>{{ $stats['comments'] }}</h3>
                                    <small>Comments</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-md-4 mb-4">
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
                User ini belum memiliki posts.
            </div>
        @endif
    </div>
</div>
@endsection