<!DOCTYPE html>
<html>
<head>
<title>Login - E-Absensi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.card-login {
    width: 100%;
    max-width: 450px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    transform: translateY(0);
    transition: transform 0.3s ease;
}
.card-login:hover {
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
.btn-login {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    border: none;
    color: white;
    font-weight: 600;
    padding: 12px;
    border-radius: 10px;
    transition: all 0.3s ease;
}
.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
    color: white;
}
.form-check-input {
    border-radius: 5px;
}
.form-check-input:checked {
    background-color: #2563eb;
    border-color: #2563eb;
}
.alert {
    border-radius: 10px;
    border: none;
}
.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
}
</style>
</head>

<body>

<div class="card-login bg-white">
    <div class="header">
        <i class="fas fa-sign-in-alt" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
        <h4>Selamat Datang</h4>
        <small>Masuk untuk mengakses sistem E-Absensi</small>
    </div>

    <div class="form-section">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <strong>Login Gagal!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email Anda" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                <div class="input-group">
                    <input autocomplete="current-password" id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px; width: 46px; display: flex; align-items: center; justify-content: center;" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <button class="btn btn-login w-100 mb-3"><i class="fas fa-arrow-right"></i> Masuk</button>

        </form>

        <div class="form-link">
            Belum punya akun? 
            <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Daftar di sini</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePassword')?.addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const icon = this.querySelector('i');
        if (!passwordField || !icon) return;

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
</body>
</html>