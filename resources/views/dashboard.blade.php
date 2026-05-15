@extends('layouts.app')

@section('content')

<h4 class="mb-4"><i class="fas fa-wave-square"></i> Selamat Datang, {{ auth()->user()->name }}</h4>

<div class="row mb-4">
    <!-- STAT CARDS -->
    <div class="col-lg-8">
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card-custom p-4 bg-blue text-center">
                    <h6 class="mb-2"><i class="fas fa-users"></i> Total Mahasiswa</h6>
                    <h2 class="mb-0">{{ $jumlahSiswa }}</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card-custom p-4 bg-green text-center">
                    <h6 class="mb-2"><i class="fas fa-chalkboard"></i> Total Kelas</h6>
                    <h2 class="mb-0">{{ $jumlahKelas }}</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card-custom p-4 bg-yellow text-center">
                    <h6 class="mb-2"><i class="fas fa-check-circle"></i> Hadir</h6>
                    <h2 class="mb-0">{{ $hadir }}</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card-custom p-4 bg-red text-center">
                    <h6 class="mb-2"><i class="fas fa-times-circle"></i> Belum</h6>
                    <h2 class="mb-0">{{ $jumlahSiswa - $hadir }}</h2>
                </div>
            </div>
        </div>

        <!-- LINE CHART -->
        <div class="card-custom p-4 mb-4">
            <h6 class="mb-3"><i class="fas fa-chart-line"></i> Statistik Kehadiran (Per Hari)</h6>
            <canvas id="lineChart" style="max-height: 250px;"></canvas>
        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="col-lg-4">
        <!-- DONUT CHART -->
        <div class="card-custom p-4 mb-4">
            <h6 class="mb-3 text-center"><i class="fas fa-chart-pie"></i> Persentase Status</h6>
            <div style="position: relative; height: 250px;">
                <canvas id="donutChart"></canvas>
            </div>
        </div>

        <!-- FORUM SECTION -->
        <div class="card-custom p-4">
            <h6 class="mb-3"><i class="fas fa-comments"></i> Forum Kelas Terbaru</h6>

            <form method="POST" action="/post" class="mb-3">
                @csrf
                <div class="input-group">
                    <input type="text" name="content" class="form-control rounded-start" placeholder="Tulis...">
                    <button class="btn btn-primary rounded-end" type="submit"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>

            <div style="max-height: 300px; overflow-y: auto;">
                @forelse($posts as $p)
                <div class="mb-3 pb-3 border-bottom" style="border-color: #e2e8f0;">
                    <b style="color: #1e293b;">{{ $p->user->name }}</b>
                    <p class="mb-1 small" style="color: #64748b;">{{ $p->content }}</p>
                    <small class="text-muted">
                        <i class="fas fa-heart"></i> {{ $p->likes->count() }}
                        <i class="fas fa-comment ms-2"></i> {{ $p->comments->count() }}
                    </small>
                </div>
                @empty
                <p class="text-muted text-center py-3">Tidak ada postingan</p>
                @endforelse
            </div>

            <a href="/post" class="btn btn-sm btn-outline-primary w-100 mt-3">Lihat Semua Forum</a>
        </div>
    </div>
</div>

<script>
// LINE CHART
fetch('/chart-data')
.then(res => res.json())
.then(data => {
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            datasets: [{
                label: 'Hadir',
                data: data,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e2e8f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});

// DONUT CHART
fetch('/chart-data')
.then(res => res.json())
.then(d => {
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin', 'Sakit', 'Alfa'],
            datasets: [{
                data: d,
                backgroundColor: ['#22c55e', '#f59e0b', '#ef4444', '#6b7280'],
                borderColor: '#fff',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

@endsection