@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header bg-blue d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-white mb-0">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Kuliah
                    </h4>
                    <a href="{{ route('jadwal.create') }}" class="btn btn-light">
                        <i class="fas fa-plus"></i> Tambah Jadwal
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('jadwal.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control" placeholder="Cari mata kuliah/dosen" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="hari" class="form-select">
                                    <option value="">Semua Hari</option>
                                    @foreach($hariOptions as $hari)
                                        <option value="{{ $hari }}" {{ request('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="kelas" class="form-control" placeholder="Kelas" value="{{ request('kelas') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="semester" class="form-select">
                                    <option value="">Semua Semester</option>
                                    @foreach($semesterOptions as $semester)
                                        <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>Semester {{ $semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('jadwal.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-reset"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Download PDF Button -->
                    <div class="mb-3 text-end">
                        <a href="{{ route('jadwal.downloadPdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px">No</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen</th>
                                    <th>Kelas</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Ruangan</th>
                                    <th>Semester</th>
                                    <th>Keterangan</th>
                                    <th class="text-center" style="width: 120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwal as $key => $item)
                                    <tr>
                                        <td class="text-center">{{ $jadwal->firstItem() + $key }}</td>
                                        <td><strong>{{ $item->mataKuliah->nama ?? '-' }}</strong></td>
                                        <td>{{ $item->dosen->nama ?? '-' }}</td>
                                        <td><span class="badge bg-info">{{ $item->kelas->nama_kelas ?? '-' }}</span></td>
                                        <td>{{ $item->hari }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</td>
                                        <td>{{ $item->ruangan }}</td>
                                        <td>Semester {{ $item->semester }}</td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('absen?jadwal_id='.$item->id) }}" class="btn btn-sm btn-success" title="Input Absen">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <a href="{{ route('jadwal.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('jadwal.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin hapus jadwal ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada jadwal kuliah</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $jadwal->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection