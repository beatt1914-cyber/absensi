@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header bg-warning">
                    <h4 class="card-title text-white mb-0">
                        <i class="fas fa-edit me-2"></i> Edit Mata Kuliah
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('matakuliah.update', $matakuliah->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Mata Kuliah <span class="text-danger">*</span></label>
                                    <input type="text" name="kode" class="form-control" required value="{{ old('kode', $matakuliah->kode) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" required value="{{ old('nama', $matakuliah->nama) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">SKS <span class="text-danger">*</span></label>
                                    <input type="number" name="sks" class="form-control" required min="1" max="10" value="{{ old('sks', $matakuliah->sks) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                                    <select name="semester" class="form-select" required>
                                        @for($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}" {{ old('semester', $matakuliah->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Dosen Pengampu</label>
                                    <select name="dosen_id" class="form-select">
                                        <option value="">Pilih Dosen</option>
                                        @foreach($dosen as $d)
                                            <option value="{{ $d->id }}" {{ old('dosen_id', $matakuliah->dosen_id) == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $matakuliah->deskripsi) }}</textarea>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('matakuliah.index') }}" class="btn btn-secondary">
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