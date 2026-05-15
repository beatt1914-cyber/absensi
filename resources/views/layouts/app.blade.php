<!DOCTYPE html>
<html>
<head>
<title>E-Absensi</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #f1f5f9;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body.dark {
    background: #0f172a;
    color: #e2e8f0;
}

body.dark .sidebar {
    background: linear-gradient(180deg, #111827 0%, #0f172a 100%);
}

body.dark .sidebar a {
    color: #cbd5e1;
}

body.dark .sidebar a:hover {
    background: #2563eb;
    color: white;
}

body.dark .topbar {
    background: #111827;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.5);
}

body.dark .topbar-search input,
body.dark .content,
body.dark .card-custom,
body.dark .user-dropdown {
    background: #1f2937;
    color: #e2e8f0;
}

body.dark .content {
    border-color: #374151;
}

body.dark .card-custom {
    box-shadow: 0 5px 20px rgba(15, 23, 42, 0.6);
}

body.dark .user-dropdown a,
body.dark .user-dropdown button {
    color: #e2e8f0;
}

body.dark .user-dropdown a:hover,
body.dark .user-dropdown button:hover {
    background: #334155;
}

body.dark input,
body.dark select,
body.dark textarea {
    background: #111827;
    color: #e2e8f0;
    border-color: #334155;
}

/* SIDEBAR */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    background: linear-gradient(180deg, #1e3a8a 0%, #1e293b 100%);
    color: white;
    overflow-y: auto;
    z-index: 1000;
}

.sidebar h4 {
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.sidebar a {
    display: block;
    padding: 12px 15px;
    color: #cbd5e1;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: all 0.3s ease;
    font-size: 14px;
}

.sidebar a:hover {
    background: #3b82f6;
    color: white;
    padding-left: 25px;
}

/* TOPBAR */
.topbar {
    position: fixed;
    top: 0;
    left: 250px;
    right: 0;
    height: 70px;
    background: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    z-index: 999;
}

.topbar-search {
    max-width: 400px;
}

.topbar-user {
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
}

.topbar-user > div:first-child {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 15px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.topbar-user > div:first-child:hover {
    background: #f1f5f9;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    cursor: pointer;
}

.user-dropdown {
    position: absolute;
    top: 70px;
    right: 0;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    min-width: 200px;
    display: none;
    z-index: 1001;
    overflow: hidden;
}

.user-dropdown.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.user-dropdown a,
.user-dropdown button {
    display: block;
    width: 100%;
    padding: 12px 20px;
    border: none;
    background: none;
    color: #1e293b;
    text-align: left;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.user-dropdown a:hover,
.user-dropdown button:hover {
    background: #f1f5f9;
    padding-left: 25px;
}

.user-dropdown a i,
.user-dropdown button i {
    margin-right: 10px;
    width: 16px;
}

.user-dropdown hr {
    margin: 0;
    border: none;
    border-top: 1px solid #e2e8f0;
}

.topbar-user img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

/* CONTENT */
.content {
    margin-left: 250px;
    margin-top: 70px;
    padding: 30px;
    min-height: calc(100vh - 70px);
}

/* CARD CUSTOM */
.card-custom {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.card-custom:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

/* STAT COLORS */
.bg-blue {
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    color: white;
}

.bg-green {
    background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
    color: white;
}

.bg-yellow {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.bg-red {
    background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
    color: white;
}

/* LOADING */
#loading {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    gap: 20px;
}

#loading.show {
    display: flex;
}

#loading h3 {
    color: #1e293b;
    font-weight: 600;
}

/* LOGOUT BUTTON */
/* Removed - logout now in topbar dropdown */

/* RESPONSIVE */
@media (max-width: 768px) {
    .sidebar {
        width: 60px;
        padding: 10px !important;
    }

    .sidebar h4 {
        display: none;
    }

    .sidebar a {
        padding: 15px;
        text-align: center;
        font-size: 0;
    }

    .sidebar a:hover {
        padding-left: 15px;
    }

    .topbar {
        left: 60px;
    }

    .topbar-search {
        display: none;
    }

    .content {
        margin-left: 60px;
        padding: 15px;
    }

    .card-custom {
        border-radius: 10px;
    }
}

</style>

</head>

<body>

<!-- LOADING ANIMATION -->
<div id="loading">
    <div class="spinner-border text-primary" role="status" style="width: 50px; height: 50px; margin-right: 20px;"></div>
    <h3>Loading...</h3>
</div>

<!-- SIDEBAR -->
<div class="sidebar p-3">
    <h4><i class="fas fa-book"></i> E-Absensi</h4>

    <a href="/" title="Dashboard"><i class="fas fa-home"></i> <span class="d-none d-md-inline ms-2">Dashboard</span></a>
    <a href="/kelas" title="Daftar Kelas"><i class="fas fa-chalkboard-user"></i> <span class="d-none d-md-inline ms-2">Daftar Kelas</span></a>
    <a href="/mahasiswa" title="Daftar Mahasiswa"><i class="fas fa-users"></i> <span class="d-none d-md-inline ms-2">Daftar Mahasiswa</span></a>
    <a href="/dosen" title="Daftar Dosen"><i class="fas fa-user-tie"></i> <span class="d-none d-md-inline ms-2">Daftar Dosen</span></a>
    <a href="/matakuliah" title="Daftar Mata Kuliah"><i class="fas fa-book"></i> <span class="d-none d-md-inline ms-2">Daftar Mata Kuliah</span></a>
    <a href="/absen" title="Input Absen"><i class="fas fa-clipboard-check"></i> <span class="d-none d-md-inline ms-2">Input Absen</span></a>
    <a href="/rekap" title="Rekap"><i class="fas fa-file-excel"></i> <span class="d-none d-md-inline ms-2">Rekap</span></a>
    <a href="/jadwal" title="Jadwal Kuliah"><i class="fas fa-calendar-alt"></i> <span class="d-none d-md-inline ms-2">Jadwal Kuliah</span></a>
    <a href="/post" title="Forum Kelas"><i class="fas fa-comments"></i> <span class="d-none d-md-inline ms-2">Forum Kelas</span></a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-search">
        <input type="text" class="form-control rounded-pill" placeholder="Cari...">
    </div>
    <div class="topbar-user">
        <div onclick="toggleUserDropdown()" style="cursor: pointer; display: flex; align-items: center; gap: 10px; padding: 8px 15px; border-radius: 50px; transition: all 0.3s ease;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
            <span class="d-none d-md-inline"><strong>{{ auth()->user()->name }}</strong></span>
            @if (auth()->user()->profile_photo)
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="{{ auth()->user()->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
            @else
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
        </div>

        <!-- USER DROPDOWN -->
        <div class="user-dropdown" id="userDropdown">
            <a href="{{ route('profile') }}"><i class="fas fa-user"></i> Profil Saya</a>
            <a href="{{ route('settings') }}"><i class="fas fa-cog"></i> Pengaturan</a>
            <hr>
            <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Registrasi User Baru</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="content">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @yield('content')
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide loading on page load
    const loading = document.getElementById('loading');
    if (loading) {
        loading.classList.remove('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        const userDiv = event.target.closest('.topbar-user');
        
        if (!userDiv) {
            dropdown.classList.remove('show');
        }
    });
});

// Toggle user dropdown menu
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

function setTheme(mode) {
    const body = document.body;
    if (mode === 'dark') {
        body.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        body.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }

    const darkModeToggle = document.getElementById('darkMode');
    if (darkModeToggle) {
        darkModeToggle.checked = body.classList.contains('dark');
    }
}

function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (prefersDark ? 'dark' : 'light');
    setTheme(theme);

    const darkModeToggle = document.getElementById('darkMode');
    if (darkModeToggle) {
        darkModeToggle.checked = document.body.classList.contains('dark');
        darkModeToggle.addEventListener('change', function() {
            setTheme(this.checked ? 'dark' : 'light');
        });
    }
}

function initTopbarSearch() {
    const topbarSearch = document.querySelector('.topbar-search input');
    if (!topbarSearch) {
        return;
    }

    topbarSearch.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const query = this.value.trim();
            const url = query ? '/mahasiswa?q=' + encodeURIComponent(query) : '/mahasiswa';
            window.location.href = url;
        }
    });
}

// Optional: Show loading only on form submit (if needed)
document.addEventListener('submit', function(e) {
    if (e.target.method === 'POST' || e.target.method === 'post') {
        // Don't show loading for quick AJAX requests
        // Uncomment if you want loading for form submissions:
        // document.getElementById('loading').classList.add('show');
    }
});

initTheme();
initTopbarSearch();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>