@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header bg-info">
                    <h4 class="card-title text-white mb-0">
                        <i class="fas fa-file-import me-2"></i> Impor Daftar Dosen
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-info-circle"></i> Petunjuk:</h5>
                        <p class="mb-1">Masukkan data dosen dengan format: <strong>NIP;Nama;Email;Jurusan;NoHP;Alamat</strong></p>
                        <p class="mb-1">Gunakan titik koma (<strong>;</strong>) sebagai pemisah. Satu baris untuk satu dosen.</p>
                        <p class="mb-0">Contoh: <code>19800101;Dr. John Doe;john@univ.ac.id;Informatika;0812345678;Jl. Kampus No. 1</code></p>
                    </div>

                    <form method="POST" action="{{ route('dosen.processImport') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Data Dosen (Satu per baris)</label>
                            <textarea name="data_dosen" class="form-control" rows="15" placeholder="19800101;Dr. John Doe;john@univ.ac.id;Informatika;0812345678;Jl. Kampus No. 1" required>{{ old('data_dosen') }}</textarea>
                        </div>
                        
                        <div class="text-end">
                            <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Impor Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
