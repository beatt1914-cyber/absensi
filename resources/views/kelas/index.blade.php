@extends('layouts.app')

@section('content')

<h3>Daftar Kelas</h3>

<div class="card p-4">

    <h5>Tambah Kelas Baru</h5>

    <form method="POST" action="{{ route('kelas.store') }}" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control" name="nama_kelas" placeholder="Nama Kelas" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Tambah Kelas</button>
            </div>
        </div>
    </form>

    <h5>Daftar Kelas</h5>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kelas</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $k)
            <tr>
                <td>{{ $k->id }}</td>
                <td>{{ $k->nama_kelas }}</td>
                <td>{{ $k->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection