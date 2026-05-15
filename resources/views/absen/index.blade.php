@extends('layouts.app')

@section('content')

<h3>Input Absensi</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card p-4">
    <form action="{{ url('absen') }}" method="POST">
    @csrf

    <div class="row mb-3">
        <div class="col-md-2">
            <label class="form-label">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" required value="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Hari</label>
            <input type="text" id="hari_display" class="form-control" readonly
                   style="background:#f8f9fa; font-weight:600; color:#0d6efd;"
                   placeholder="Otomatis">
        </div>
        <div class="col-md-2">
            <label class="form-label">Semester</label>
            <select id="semester" name="semester" class="form-control" required>
                <option value="">-- Semester --</option>
                @foreach($semesterOptions as $sem)
                <option value="{{$sem}}" {{ (isset($selectedJadwal) && $selectedJadwal->semester == $sem) ? 'selected' : '' }}>Semester {{$sem}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Pilih Kelas</label>
            <select id="kelas" name="kelas_id" class="form-control" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                <option value="{{$k->id}}" {{ (isset($selectedJadwal) && $selectedJadwal->kelas_id == $k->id) ? 'selected' : '' }}>{{$k->nama_kelas}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Mata Kuliah</label>
            <select id="mata_kuliah" name="mata_kuliah_id" class="form-control" required>
                <option value="">-- Pilih Mata Kuliah --</option>
                @foreach($matakuliah as $mk)
                <option value="{{$mk->id}}" {{ (isset($selectedJadwal) && $selectedJadwal->mata_kuliah_id == $mk->id) ? 'selected' : '' }}>{{$mk->nama}} ({{$mk->kode}})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Dosen</label>
            <select id="dosen" name="dosen_id" class="form-control" required>
                <option value="">-- Pilih Dosen --</option>
                @foreach($dosen as $d)
                <option value="{{$d->id}}" {{ (isset($selectedJadwal) && $selectedJadwal->dosen_id == $d->id) ? 'selected' : '' }}>{{$d->nama}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="error-container"></div>

    <div id="siswa-container" style="display: none;">
        <div id="hari-jadwal-info" class="alert alert-info mb-3" style="display:none;"></div>
        <h5>Daftar Mahasiswa</h5>
        <table id="tabel" class="table table-striped"></table>
        <button type="submit" class="btn btn-primary mt-3">Simpan Absensi</button>
    </div>

    </form>
</div>

<script>
// Nama hari dalam Bahasa Indonesia
const namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

function updateHariDisplay() {
    const tanggalVal = document.getElementById('tanggal').value;
    const hariInput  = document.getElementById('hari_display');
    if (tanggalVal) {
        // parse lokal agar tidak kena offset timezone
        const parts = tanggalVal.split('-');
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        hariInput.value = namaHari[d.getDay()];
    } else {
        hariInput.value = '';
    }
}

function checkAndFetch() {
    const semester       = document.getElementById('semester').value;
    const kelas_id       = document.getElementById('kelas').value;
    const mata_kuliah_id = document.getElementById('mata_kuliah').value;
    const dosen_id       = document.getElementById('dosen').value;
    const tanggal        = document.getElementById('tanggal').value;

    const errorContainer    = document.getElementById('error-container');
    const hariJadwalInfo    = document.getElementById('hari-jadwal-info');
    const siswaContainer    = document.getElementById('siswa-container');

    if (kelas_id && mata_kuliah_id && dosen_id && tanggal) {
        const url = `/get-siswa?kelas_id=${kelas_id}&mata_kuliah_id=${mata_kuliah_id}&dosen_id=${dosen_id}&tanggal=${tanggal}&semester=${semester}`;

        fetch(url)
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            if (res.status === 404 || res.status === 403) {
                siswaContainer.style.display = 'none';
                let alertClass = res.status === 403 ? 'alert-warning' : 'alert-danger';
                let icon       = res.status === 403 ? '⚠️ Bukan Hari Jadwal!' : '❌ Jadwal Tidak Ditemukan!';

                // Tampilkan info hari jadwal seharusnya jika ada
                let infoHari = '';
                if (res.body.hari_jadwal) {
                    infoHari = `<br><small>Jadwal mata kuliah ini hanya tersedia pada hari <strong>${res.body.hari_jadwal}</strong>.</small>`;
                }

                errorContainer.innerHTML = `<div class="alert ${alertClass} mt-3">
                    <strong>${icon}</strong> ${res.body.error}${infoHari}
                </div>`;
            } else {
                errorContainer.innerHTML = '';

                // Tampilkan badge hari jadwal
                if (res.body.hari_jadwal) {
                    hariJadwalInfo.style.display = 'block';
                    hariJadwalInfo.innerHTML = `📅 Jadwal <strong>${res.body.hari_jadwal}</strong> — daftar mahasiswa berhasil dimuat.`;
                } else {
                    hariJadwalInfo.style.display = 'none';
                }

                siswaContainer.style.display = 'block';

                let html = '<thead><tr><th>NIM</th><th>Nama</th><th>Kelas</th><th>Semester</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alfa</th></tr></thead><tbody>';
                res.body.mahasiswa.forEach(s => {
                    html += `<tr>
                        <td>${s.nim}</td>
                        <td>${s.nama}</td>
                        <td>${s.kelas ? s.kelas.nama_kelas : '-'}</td>
                        <td>Semester ${s.semester || '-'}</td>
                        <td><input type="radio" name="status[${s.id}]" value="H" required></td>
                        <td><input type="radio" name="status[${s.id}]" value="I"></td>
                        <td><input type="radio" name="status[${s.id}]" value="S"></td>
                        <td><input type="radio" name="status[${s.id}]" value="A"></td>
                    </tr>`;
                });
                html += '</tbody>';
                document.getElementById('tabel').innerHTML = html;
            }
        });
    } else {
        siswaContainer.style.display = 'none';
        errorContainer.innerHTML = '';
        hariJadwalInfo.style.display = 'none';
    }
}

function updateOptions(level) {
    const semester       = document.getElementById('semester').value;
    const kelas_id       = document.getElementById('kelas').value;
    const mata_kuliah_id = document.getElementById('mata_kuliah').value;

    let url = `/get-jadwal-options?semester=${semester}`;
    if (level >= 2 && kelas_id) url += `&kelas_id=${kelas_id}`;
    if (level >= 3 && mata_kuliah_id) url += `&mata_kuliah_id=${mata_kuliah_id}`;

    fetch(url)
    .then(r => r.json())
    .then(data => {
        if (level === 1) {
            refreshSelect('kelas', data.kelas, '-- Pilih Kelas --', 'nama_kelas');
            refreshSelect('mata_kuliah', data.matakuliah, '-- Pilih Mata Kuliah --', 'nama_mk');
            refreshSelect('dosen', data.dosen, '-- Pilih Dosen --', 'nama');
        } else if (level === 2) {
            refreshSelect('mata_kuliah', data.matakuliah, '-- Pilih Mata Kuliah --', 'nama_mk');
            refreshSelect('dosen', data.dosen, '-- Pilih Dosen --', 'nama');
        } else if (level === 3) {
            refreshSelect('dosen', data.dosen, '-- Pilih Dosen --', 'nama');
        }
    });
}

function refreshSelect(id, items, placeholder, type) {
    const select = document.getElementById(id);
    const currentVal = select.value;
    select.innerHTML = `<option value="">${placeholder}</option>`;
    items.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        if (type === 'nama_kelas') {
            opt.textContent = item.nama_kelas;
        } else if (type === 'nama_mk') {
            opt.textContent = `${item.nama} (${item.kode})`;
        } else {
            opt.textContent = item.nama;
        }
        if (item.id == currentVal) opt.selected = true;
        select.appendChild(opt);
    });
}

// Event listeners
document.getElementById('tanggal').addEventListener('change', function() {
    updateHariDisplay();
    checkAndFetch();
});

document.getElementById('semester').onchange = function() {
    updateOptions(1);
    checkAndFetch();
};

document.getElementById('kelas').onchange = function() {
    updateOptions(2);
    checkAndFetch();
};

document.getElementById('mata_kuliah').onchange = function() {
    updateOptions(3);
    checkAndFetch();
};

document.getElementById('dosen').onchange = checkAndFetch;

// Init saat halaman pertama dibuka
updateHariDisplay();

@if(isset($selectedJadwal))
    // Trigger otomatis jika datang dari halaman Jadwal Kuliah
    if (document.getElementById('kelas').value !== '') {
        checkAndFetch();
    }
@endif
</script>

@endsection