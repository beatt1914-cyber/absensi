@extends('layouts.app')

@section('content')

<h4 class="mb-3">Pengaturan Sekolah</h4>

<div class="card p-4">

    <form method="POST" action="{{ url('pengaturan') }}">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Nama Sekolah</label>
                <input type="text" name="nama_sekolah" class="form-control" value="{{ $sekolah->nama_sekolah ?? '' }}">
            </div>
            <div class="col-md-6">
                <label>Logo</label>
                <input type="text" name="logo" class="form-control" value="{{ $sekolah->logo ?? '' }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Guru</label>
                <input type="text" name="guru" class="form-control" value="{{ $sekolah->guru ?? '' }}">
            </div>
            <div class="col-md-6">
                <label>NIP Guru</label>
                <input type="text" name="nip_guru" class="form-control" value="{{ $sekolah->nip_guru ?? '' }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Kepala Sekolah</label>
                <input type="text" name="kepsek" class="form-control" value="{{ $sekolah->kepsek ?? '' }}">
            </div>
            <div class="col-md-6">
                <label>NIP Kepala Sekolah</label>
                <input type="text" name="nip_kepsek" class="form-control" value="{{ $sekolah->nip_kepsek ?? '' }}">
            </div>
        </div>

        <button class="btn btn-primary">Simpan</button>
    </form>

</div>

@endsection