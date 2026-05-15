@extends('layouts.master')

@section('title', 'Buat Post Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Buat Post Baru</h5>
                <a href="{{ route('posts.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
             
            <div class="card-body">
                {{-- Tampilkan error jika ada --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Terdapat kesalahan:</h6>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Title Field --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">
                            Judul Post <span class="text-danger">*</span>
                        </label>
                        <input type="text"  
                               class="form-control @error('title') is-invalid @enderror"  
                               id="title"  
                               name="title"  
                               value="{{ old('title') }}"  
                               placeholder="Masukkan judul post" 
                               autofocus>
                         
                        @error('title')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                         
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Minimal 5 karakter, maksimal 255 karakter.
                        </small>
                    </div>

                    {{-- Slug Field --}}
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (URL)</label>
                        <input type="text"  
                               class="form-control @error('slug') is-invalid @enderror"  
                               id="slug"  
                               name="slug"  
                               value="{{ old('slug') }}"  
                               placeholder="contoh-judul-post">
                         
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                         
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Kosongkan untuk generate otomatis dari judul.
                        </small>
                    </div>

                    {{-- Category and Status Row --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('category_id') is-invalid @enderror"  
                                    id="category_id"  
                                    name="category_id">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"  
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror"  
                                    id="status"  
                                    name="status">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Tags Field --}}
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-select @error('tags') is-invalid @enderror"  
                                id="tags"  
                                name="tags[]"  
                                multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}"  
                                    {{ (collect(old('tags'))->contains($tag->id)) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Tekan Ctrl/Cmd untuk memilih lebih dari satu.
                        </small>
                    </div>

                    {{-- Featured Image --}}
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">URL Gambar Utama</label>
                        <input type="url"  
                               class="form-control @error('featured_image') is-invalid @enderror"  
                               id="featured_image"  
                               name="featured_image"  
                               value="{{ old('featured_image') }}"  
                               placeholder="https://example.com/image.jpg">
                         
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                         
                        <div class="mt-2" id="image-preview" style="display: none;">
                            <img src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    </div>

                    {{-- Excerpt --}}
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Excerpt (Ringkasan)</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror"  
                                  id="excerpt"  
                                  name="excerpt"  
                                  rows="3"  
                                  placeholder="Ringkasan post (opsional)">{{ old('excerpt') }}</textarea>
                         
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                         
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Kosongkan untuk generate otomatis dari konten. Maksimal 500 karakter.
                        </small>
                        <div class="text-end">
                            <span id="excerpt-counter">0</span>/500 karakter
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="mb-3">
                        <label for="body" class="form-label">
                            Konten <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('body') is-invalid @enderror"  
                                  id="body"  
                                  name="body"  
                                  rows="15"  
                                  placeholder="Tulis konten post di sini...">{{ old('body') }}</textarea>
                         
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                         
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Minimal 10 karakter.
                        </small>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                         
                        <div>
                            <button type="submit" class="btn btn-primary" name="action" value="save">
                                <i class="fas fa-save me-2"></i>Simpan Post
                            </button>
                             
                            <button type="submit" class="btn btn-success" name="action" value="save_and_publish">
                                <i class="fas fa-check-circle me-2"></i>Simpan & Publikasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview image dari URL
    document.getElementById('featured_image').addEventListener('input', function(e) {
        const url = e.target.value;
        const preview = document.getElementById('image-preview');
        const img = preview.querySelector('img');
         
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            img.src = url;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });

    // Hitung karakter excerpt
    document.getElementById('excerpt').addEventListener('input', function(e) {
        const count = e.target.value.length;
        document.getElementById('excerpt-counter').textContent = count;
         
        if (count > 500) {
            e.target.classList.add('is-invalid');
        } else {
            e.target.classList.remove('is-invalid');
        }
    });

    // Generate slug dari title
    document.getElementById('title').addEventListener('input', function(e) {
        const title = e.target.value;
        const slugField = document.getElementById('slug');
         
        // Hanya generate jika slug masih kosong
        if (slugField.value === '') {
            const slug = title.toLowerCase()
                .replace(/[^\w\s-]/g, '') // Hapus karakter spesial
                .replace(/\s+/g, '-') // Ganti spasi dengan -
                .replace(/--+/g, '-') // Hapus multiple -
                .trim('-'); // Hapus - di awal dan akhir
             
            slugField.value = slug;
        }
    });

    // Validasi sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const body = document.getElementById('body').value;
        if (body.length < 10) {
            e.preventDefault();
            alert('Konten minimal 10 karakter!');
        }
    });
</script>
@endpush 