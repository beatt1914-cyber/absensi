@extends('layouts.app')

@section('content')

<h3>Daftar Mahasiswa</h3>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card p-4 mb-4">
    <h5>Tambah Mahasiswa Baru</h5>

    <form method="POST" action="/mahasiswa">
        @csrf

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Pilih Kelas</label>
                <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                    <option value="" disabled selected>-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
                @error('kelas_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label>NIM</label>
                <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" placeholder="Masukkan NIM" required>
                @error('nim')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan nama" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2">
                <label>Semester</label>
                <select name="semester" class="form-control @error('semester') is-invalid @enderror" required>
                    <option value="" disabled selected>-- Semester --</option>
                    @for($i=1; $i<=8; $i++)
                    <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                    @endfor
                </select>
                @error('semester')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-primary w-100">Simpan</button>
            </div>
        </div>
    </form>
</div>

<div class="card p-4">
    <h5>Daftar Mahasiswa</h5>

    <input type="text" id="search" class="form-control mb-3" placeholder="Cari mahasiswa..." value="{{ request('q') }}">

    <table class="table table-striped" id="table">
    <thead>
    <tr>
    <th>NIM</th>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Semester</th>
    <th>Aksi</th>
    </tr>
    </thead>
    <tbody></tbody>
    </table>
</div>

<script>
function loadData(q=''){
 fetch('/mahasiswa-data?q='+q)
 .then(res=>res.json())
 .then(data=>{
  let html='';
  data.forEach(s=>{
    html+=`
    <tr>
    <td>${s.nim}</td>
    <td>${s.nama}</td>
    <td>${s.kelas ? s.kelas.nama_kelas : 'N/A'}</td>
    <td>Semester ${s.semester || '-'}</td>
    <td>
    <button onclick="hapus(${s.id})" class="btn btn-danger btn-sm">Hapus</button>
    </td>
    </tr>`;
  });
  document.querySelector('#table tbody').innerHTML=html;
 });
}

function hapus(id){
 if(confirm('Hapus data mahasiswa ini? Data yang dihapus tidak dapat dikembalikan.')){
  fetch('/mahasiswa/'+id,{
    method:'DELETE',
    headers:{
      'X-CSRF-TOKEN':'{{ csrf_token() }}'
    }
  }).then(res => {
    if(res.ok) {
        loadData(document.getElementById('search').value);
        // Show success message
        showMessage('Mahasiswa berhasil dihapus', 'success');
    } else {
        showMessage('Gagal menghapus mahasiswa', 'danger');
    }
  }).catch(() => {
    showMessage('Terjadi kesalahan saat menghapus', 'danger');
  });
 }
}

function showMessage(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

document.getElementById('search').addEventListener('keyup',function(){
 loadData(this.value);
});

loadData();
</script>

<script>
// Clear form on successful submission
@if(session('success'))
document.addEventListener('DOMContentLoaded', function() {
    // Clear form fields
    document.querySelector('select[name="kelas_id"]').selectedIndex = 0;
    document.querySelector('select[name="semester"]').selectedIndex = 0;
    document.querySelector('input[name="nim"]').value = '';
    document.querySelector('input[name="nama"]').value = '';

    // Auto-hide success message after 3 seconds
    setTimeout(function() {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 3000);
});
@endif

// Form validation enhancement
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...';

    // Re-enable after 2 seconds (in case of error)
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }, 2000);
});
</script>

@endsection