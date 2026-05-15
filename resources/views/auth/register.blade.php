<!DOCTYPE html>
<html>
<head>
<title>Daftar Akun - E-Absensi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 20px 0;
}
.card-register {
    width: 100%;
    max-width: 500px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    transform: translateY(0);
    transition: transform 0.3s ease;
}
.card-register:hover {
    transform: translateY(-5px);
}
.header {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    color: white;
    padding: 40px 20px;
    text-align: center;
}
.header h4 {
    margin: 0;
    font-weight: 700;
    font-size: 28px;
}
.header small {
    opacity: 0.9;
    display: block;
    margin-top: 8px;
}
.form-section {
    padding: 40px;
}
.form-link {
    text-align: center;
    margin-top: 20px;
}
.form-link a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}
.form-link a:hover {
    text-decoration: underline;
    color: #1e40af;
}
.form-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
}
.form-control {
    border-radius: 10px;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}
.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}
.form-control.is-invalid {
    border-color: #ef4444;
}
.invalid-feedback {
    display: block;
    color: #ef4444;
    margin-top: 5px;
    font-size: 14px;
}
.btn-register {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    border: none;
    color: white;
    font-weight: 600;
    padding: 12px;
    border-radius: 10px;
    transition: all 0.3s ease;
}
.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
    color: white;
}
.password-hint {
    font-size: 13px;
    color: #64748b;
    margin-top: 5px;
}
.alert {
    border-radius: 10px;
    border: none;
}
.alert-success {
    background-color: #dcfce7;
    color: #15803d;
}
.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
}
</style>
</head>

<body>

<div class="card-register bg-white">
    <div class="header">
        <i class="fas fa-user-plus" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
        <h4>Daftar Akun</h4>
        <small>Buat akun baru untuk mengakses sistem E-Absensi</small>
    </div>

    <div class="form-section">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <strong>Terjadi Kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user"></i> Nama Lengkap</label>
                <input autocomplete="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input autocomplete="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email Gmail atau Yahoo" value="{{ old('email') }}" required>
                <div class="form-text text-muted">Gunakan alamat Gmail atau Yahoo untuk daftar.</div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                <div class="input-group">
                    <input autocomplete="new-password" id="registerPassword" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password (minimal 8 karakter)" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleRegisterPassword" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px; width: 46px; display: flex; align-items: center; justify-content: center;" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-hint">
                    <i class="fas fa-info-circle"></i> Password harus minimal 8 karakter
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock"></i> Konfirmasi Password</label>
                <div class="input-group">
                    <input autocomplete="new-password" id="registerConfirmPassword" type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Ketik ulang password Anda" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleRegisterConfirmPassword" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px; width: 46px; display: flex; align-items: center; justify-content: center;" aria-label="Toggle password confirmation visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="agree" required>
                <label class="form-check-label" for="agree">
                    Saya setuju dengan <a href="#" style="color: #2563eb;">Syarat & Ketentuan</a>
                </label>
            </div>

            <button class="btn btn-register w-100 mb-3"><i class="fas fa-arrow-right"></i> Daftar Sekarang</button>

        </form>

        <div class="form-link">
            Sudah punya akun? 
            <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login di sini</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleVisibility(buttonId, inputId) {
        const button = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        const icon = button?.querySelector('i');
        if (!button || !input || !icon) return;

        button.addEventListener('click', function () {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }

    toggleVisibility('toggleRegisterPassword', 'registerPassword');
    toggleVisibility('toggleRegisterConfirmPassword', 'registerConfirmPassword');
</script>
</body>
</html>
