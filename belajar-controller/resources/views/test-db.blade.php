<!DOCTYPE html>
<html>

<head>
    <title>Test Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Data Posts dari Database</h1>

        <div class="row">
            @foreach($posts as $post)
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $post->title }}</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            Kategori: {{ $post->category_name }} |
                                            Penulis: {{ $post->author }}
                                        </h6>
                                        <p class="card-text">{{ $post->excerpt }}</p>

                                        <div class="d-flex justify-content-between">
                                            <span class="badge bg-primary">Views: {{ 
                $post->views }}</span>
                                            <span class="badge bg-{{ $post->status ==
                        'published' ? 'success' : 'warning' }}">
                                                {{ $post->status }}
                                            </span>
                                        </div>
                                        @if($post->featured_image)
                                            <img src="{{ $post->featured_image }}" class="img-fluid mt-2">
                                        @endif
                                    </div>
                                </div>
                            </div>
            @endforeach
        </div>
    </div>
</body>

</html>