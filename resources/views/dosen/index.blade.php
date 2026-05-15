@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('import_errors'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5><i class="fas fa-exclamation-triangle"></i> Beberapa baris gagal diimpor:</h5>
                <ul class="mb-0">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="card card-custom">
                <div class="card-header bg-info d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-white mb-0">
                        <i class="fas fa-user-tie me-2"></i> Dosen
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dosen.import') }}" class="btn btn-warning text-white">
                            <i class="fas fa-file-import"></i> Impor Daftar
                        </a>
                        <a href="{{ route('dosen.create') }}" class="btn btn-light">
                            <i class="fas fa-plus"></i> Tambah Dosen
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari nip/nama/jurusan" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('dosen.index') }}" class="btn btn-secondary w-100">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px">No</th>
                                    <th>NIP</th>
                                    <th>Nama Dosen</th>
                                    <th>Email</th>
                                    <th>No HP</th>
                                    <th>Jurusan</th>
                                    <th class="text-center" style="width: 120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dosen as $key => $item)
                                    <tr>
                                        <td class="text-center">{{ $dosen->firstItem() + $key }}</td>
                                        <td><span class="badge bg-secondary">{{ $item->nip }}</span></td>
                                        <td><strong>{{ $item->nama }}</strong></td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->no_hp ?? '-' }}</td>
                                        <td>{{ $item->jurusan }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('dosen.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('dosen.destroy', $item->id) }}" method="POST" class="d-inline">
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
                                            <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada dosen</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $dosen->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection