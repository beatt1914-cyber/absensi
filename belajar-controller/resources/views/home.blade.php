@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </h1>
                </div>
                <div class="card-body">
                    <p class="lead">Welcome to your dashboard!</p>
                    <p>This is the home page for authenticated users.</p>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-newspaper fa-2x mb-2"></i>
                                    <h5>Posts</h5>
                                    <p>Manage your blog posts</p>
                                    <a href="{{ route('posts.index') }}" class="btn btn-light btn-sm">View Posts</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-tags fa-2x mb-2"></i>
                                    <h5>Categories</h5>
                                    <p>Organize your content</p>
                                    <a href="{{ route('categories.index') }}" class="btn btn-light btn-sm">View Categories</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-comments fa-2x mb-2"></i>
                                    <h5>Comments</h5>
                                    <p>Manage user comments</p>
                                    <a href="{{ route('comments.index') }}" class="btn btn-light btn-sm">View Comments</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection