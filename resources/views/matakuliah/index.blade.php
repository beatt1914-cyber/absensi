@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header bg-blue d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <h4 class="card-title text-white mb-0">
                        <i class="fas fa-book me-2"></i> Mata Kuliah
                    </h4>
                    <a href="{{ route('matakuliah.create') }}" class="btn btn-light">
                        <i class="fas fa-plus"></i> Tambah Mata Kuliah
                    </a>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari kode/nama mata kuliah" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2 col-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-2 col-6">
                                <a href="{{ route('matakuliah.index') }}" class="btn btn-secondary w-100">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px">No</th>
                                    <th>Kode</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Semester</th>
                                    <th>Dosen Pengampu</th>
                                    <th class="text-center text-nowrap" style="width: 120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($matakuliah as $key => $item)
                                    <tr>
                                        <td class="text-center">{{ $matakuliah->firstItem() + $key }}</td>
                                        <td><span class="badge bg-dark">{{ $item->kode }}</span></td>
                                        <td><strong>{{ $item->nama }}</strong></td>
                                        <td>{{ $item->sks }}</td>
                                        <td>Semester {{ $item->semester }}</td>
                                        <td>
                                            @if($item->dosen)
                                                <i class="fas fa-user-tie me-1"></i> {{ $item->dosen->nama }}
                                            @else
                                                <span class="text-danger small"><i class="fas fa-exclamation-circle"></i> Belum ditentukan</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="{{ route('matakuliah.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('matakuliah.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin hapus?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada mata kuliah</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $matakuliah->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection