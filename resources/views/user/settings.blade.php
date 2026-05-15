@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- SETTINGS HEADER -->
            <div class="card-custom p-4 mb-4">
                <h3><i class="fas fa-cog"></i> Pengaturan Akun</h3>
                <p class="text-muted">Kelola preferensi dan pengaturan akun Anda</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- NOTIFICATION SETTINGS -->
            <div class="card-custom p-4 mb-4">
                <h5 class="mb-4"><i class="fas fa-bell"></i> Notifikasi</h5>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                    <label class="form-check-label" for="emailNotif">
                        <strong>Notifikasi Email</strong>
                        <br>
                        <small class="text-muted">Terima notifikasi absensi dan update penting</small>
                    </label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="forumNotif" checked>
                    <label class="form-check-label" for="forumNotif">
                        <strong>Notifikasi Forum</strong>
                        <br>
                        <small class="text-muted">Notifikasi saat ada komentar di postingan Anda</small>
                    </label>
                </div>

                <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan Notifikasi</button>
            </div>

            <!-- DISPLAY SETTINGS -->
            <div class="card-custom p-4 mb-4">
                <h5 class="mb-4"><i class="fas fa-paint-brush"></i> Tampilan</h5>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-moon"></i> Mode Gelap</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="darkMode">
                        <label class="form-check-label" for="darkMode">
                            Aktifkan mode gelap
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-language"></i> Bahasa</label>
                    <select class="form-select">
                        <option selected>Bahasa Indonesia</option>
                        <option>English</option>
                        <option>中文</option>
                    </select>
                </div>
            </div>

            <!-- PRIVACY SETTINGS -->
            <div class="card-custom p-4 mb-4">
                <h5 class="mb-4"><i class="fas fa-shield-alt"></i> Privasi & Keamanan</h5>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-eye-slash"></i> Visibilitas Profil</label>
                    <select class="form-select">
                        <option selected>Public - Semua orang bisa melihat</option>
                        <option>Private - Hanya admin</option>
                        <option>Custom - Kelompok tertentu</option>
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="twoFactor">
                    <label class="form-check-label" for="twoFactor">
                        <strong>Autentikasi Dua Faktor (2FA)</strong>
                        <br>
                        <small class="text-muted">Tambahkan lapisan keamanan ekstra ke akun Anda</small>
                    </label>
                </div>

                <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan Privasi</button>
            </div>

            <!-- ACCOUNT SETTINGS -->
            <div class="card-custom p-4 mb-4">
                <h5 class="mb-4"><i class="fas fa-cog"></i> Akun</h5>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Informasi Akun:</strong>
                    <ul class="mb-0 mt-2">
                        <li>ID: {{ $user->id }}</li>
                        <li>Email: {{ $user->email }}</li>
                        <li>Terdaftar: {{ $user->created_at->format('d M Y H:i') }}</li>
                        <li>Login Terakhir: {{ $user->last_login_at ?? 'Belum login' }}</li>
                    </ul>
                </div>
            </div>

            <!-- DANGER ZONE -->
            <div class="card-custom p-4 border-danger mb-4">
                <h5 class="mb-4 text-danger"><i class="fas fa-exclamation-triangle"></i> Zona Berbahaya</h5>

                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="fas fa-trash"></i> Hapus Akun
                </button>
            </div>

            <a href="/" class="btn btn-secondary w-100"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        </div>
    </div>
</div>

<!-- DELETE ACCOUNT MODAL -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-circle"></i> Konfirmasi Penghapusan Akun</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                <p>Semua data Anda akan dihapus secara permanen termasuk:</p>
                <ul>
                    <li>Profil dan informasi akun</li>
                    <li>Riwayat absensi</li>
                    <li>Postingan dan komentar</li>
                </ul>
                <p>Ketik nama Anda untuk konfirmasi:</p>
                <input type="text" class="form-control" id="confirmName" placeholder="{{ $user->name }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="deleteAccount()" id="deleteBtn" disabled>Hapus Akun Saya</button>
            </div>
        </div>
    </div>
</div>

<script>
// Enable delete button only when name matches
document.getElementById('confirmName').addEventListener('input', function() {
    const btn = document.getElementById('deleteBtn');
    const userName = '{{ $user->name }}';
    btn.disabled = this.value !== userName;
});

function deleteAccount() {
    // This would be implemented with proper backend confirmation
    alert('Fitur penghapusan akun akan segera diimplementasikan');
}
</script>

@endsection
