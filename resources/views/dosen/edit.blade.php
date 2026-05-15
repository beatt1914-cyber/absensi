@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header bg-warning">
                    <h4 class="card-title text-white mb-0">
                        <i class="fas fa-edit me-2"></i> Edit Dosen
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dosen.update', $dosen->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                                    <input type="text" name="nip" class="form-control" required value="{{ old('nip', $dosen->nip) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Dosen <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" required value="{{ old('nama', $dosen->nama) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required value="{{ old('email', $dosen->email) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">No HP</label>
                                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $dosen->no_hp) }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <input type="text" name="jurusan" class="form-control" required value="{{ old('jurusan', $dosen->jurusan) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $dosen->alamat) }}</textarea>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection