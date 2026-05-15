@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- PROFILE HEADER WITH PHOTO -->
            <div class="card-custom p-5 mb-4">
                <div class="text-center mb-4">
                    <div style="position: relative; width: 120px; height: 120px; margin: 0 auto 20px;">
                        @if ($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" 
                                style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 5px solid #3b82f6; box-shadow: 0 5px 15px rgba(0,0,0,0.15);">
                        @else
                            <div style="width: 100%; height: 100%; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 48px; border: 5px solid #3b82f6; box-shadow: 0 5px 15px rgba(0,0,0,0.15);">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h2>{{ $user->name }}</h2>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
            </div>

            <!-- PROFILE INFO -->
            <div class="card-custom p-4 mb-4">
                <h5 class="mb-4"><i class="fas fa-user-circle"></i> Informasi Profil</h5>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ url('/profile/update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- FOTO PROFIL -->
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-camera"></i> Foto Profil</label>
                        <div class="input-group">
                            <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" id="profilePhotoInput" accept="image/*">
                            @if ($user->profile_photo)
                                <button class="btn btn-outline-danger" type="button" onclick="removePhoto()"><i class="fas fa-trash"></i> Hapus</button>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> Format: JPG, PNG, GIF (Max 2MB)
                        </small>
                        @error('profile_photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <!-- PREVIEW FOTO -->
                        <div id="photoPreview" class="mt-3">
                            @if ($user->profile_photo)
                                <img id="previewImg" src="{{ asset('storage/' . $user->profile_photo) }}" 
                                    style="max-width: 150px; height: 150px; border-radius: 10px; object-fit: cover; border: 2px solid #3b82f6;">
                            @else
                                <img id="previewImg" src="" style="display: none; max-width: 150px; height: 150px; border-radius: 10px; object-fit: cover; border: 2px solid #3b82f6;">
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- NAMA -->
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user"></i> Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- BERGABUNG SEJAK -->
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-calendar"></i> Bergabung Sejak</label>
                        <input type="text" class="form-control" value="{{ $user->created_at->format('d M Y') }}" disabled>
                    </div>

                    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="/" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>
            </div>

            <!-- SECURITY SECTION -->
            <div class="card-custom p-4">
                <h5 class="mb-4"><i class="fas fa-lock"></i> Keamanan</h5>

                <form action="{{ url('/profile/change-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-key"></i> Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-lock"></i> Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-lock"></i> Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-warning"><i class="fas fa-refresh"></i> Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Preview foto sebelum upload
document.getElementById('profilePhotoInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('previewImg');
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// Hapus foto profil
function removePhoto() {
    if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
        // Create a hidden form to delete photo
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("/profile/delete-photo") }}';
        form.innerHTML = '@csrf<input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

@endsection
