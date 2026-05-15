@extends('layouts.master')

@section('title', 'Buat Post - Langkah 2')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Buat Post Baru - Langkah 2: Konten</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('posts.store.step2') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="body" class="form-label">Konten <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('body') is-invalid @enderror"
                                  id="body" name="body" rows="15" required>{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Excerpt (Ringkasan)</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                  id="excerpt" name="excerpt" rows="3">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="featured_image" class="form-label">URL Gambar Utama</label>
                        <input type="url" class="form-control @error('featured_image') is-invalid @enderror"
                               id="featured_image" name="featured_image" value="{{ old('featured_image') }}">
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('posts.create.step1') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Lanjut ke Langkah 3</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection