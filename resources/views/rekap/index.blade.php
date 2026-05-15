@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header bg-green d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <h4 class="card-title text-white mb-0">
                        <i class="fas fa-file-excel me-2"></i> Rekap Absensi
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-2 col-12">
                                <label for="tanggal" class="form-label">Filter Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ request('tanggal') }}">
                            </div>
                            <div class="col-md-2 col-12">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Semua Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Semua Tahun</option>
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <label for="mata_kuliah_id" class="form-label">Mata Kuliah</label>
                                <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-select">
                                    <option value="">Semua Mata Kuliah</option>
                                    @foreach($matakuliah as $mk)
                                        <option value="{{ $mk->id }}" {{ request('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>{{ $mk->nama }} ({{ $mk->kode }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <label for="dosen_id" class="form-label">Dosen</label>
                                <select name="dosen_id" id="dosen_id" class="form-select">
                                    <option value="">Semua Dosen</option>
                                    @foreach($dosen as $d)
                                        <option value="{{ $d->id }}" {{ request('dosen_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <button type="submit" class="btn btn-primary mt-md-4 mt-2 w-100">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('rekap.downloadPdf', request()->query()) }}" class="btn btn-danger mt-2 w-100 d-md-inline-block w-md-auto" target="_blank" style="max-width: 200px;">
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px">No</th>
                                    <th class="text-nowrap">Tanggal</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen</th>
                                    <th>Nama Mahasiswa</th>
                                    <th class="text-nowrap">NIM</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $key => $d)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d F Y') }}</td>
                                        <td>{{ $d->mataKuliah->nama ?? '-' }}</td>
                                        <td>{{ $d->mataKuliah->dosen->nama ?? '-' }}</td>
                                        <td>{{ $d->mahasiswa->nama ?? 'N/A' }}</td>
                                        <td>{{ $d->mahasiswa->nim ?? '-' }}</td>
                                        <td>{{ $d->mahasiswa->kelas->nama ?? '-' }}</td>
                                        <td>
                                            @switch($d->status)
                                                @case('H')
                                                    <span class="badge bg-success">Hadir</span>
                                                    @break
                                                @case('I')
                                                    <span class="badge bg-info">Izin</span>
                                                    @break
                                                @case('S')
                                                    <span class="badge bg-warning">Sakit</span>
                                                    @break
                                                @case('A')
                                                    <span class="badge bg-danger">Alpha</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $d->status }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Tidak ada data absensi</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection