<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi - PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-hadir { color: #155724; font-weight: bold; }
        .status-izin { color: #0dcaf0; font-weight: bold; }
        .status-sakit { color: #ffc107; font-weight: bold; }
        .status-alpha { color: #dc3545; font-weight: bold; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAP ABSENSI</h1>
        <div style="font-size: 11px; color: #555; margin-top: 5px;">
            @if($filters['tanggal']) Filter Tanggal: {{ $filters['tanggal'] }} | @endif
            @if($filters['bulan']) Bulan: {{ $filters['bulan'] }} | @endif
            @if($filters['tahun']) Tahun: {{ $filters['tahun'] }} | @endif
            @if($filters['mata_kuliah']) Mata Kuliah: {{ $filters['mata_kuliah'] }} | @endif
            @if($filters['dosen']) Dosen: {{ $filters['dosen'] }} @endif
        </div>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    @foreach($dataByDate as $date => $items)
    <div style="margin-top: 25px;">
        <h3 style="background-color: #f0f0f0; padding: 5px 10px; border-left: 4px solid #333; margin-bottom: 10px;">
            Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
        </h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px">No</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Nama Mahasiswa</th>
                    <th>Kelas</th>
                    <th style="width: 80px">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $key => $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->mataKuliah->nama ?? '-' }}</td>
                    <td>{{ $d->mataKuliah->dosen->nama ?? '-' }}</td>
                    <td>{{ $d->mahasiswa->nama ?? 'N/A' }}</td>
                    <td>{{ $d->mahasiswa->kelas->nama_kelas ?? ($d->mahasiswa->kelas->nama ?? '-') }}</td>
                    <td>
                        @switch($d->status)
                            @case('H')
                                <span class="status-hadir">Hadir</span>
                                @break
                            @case('I')
                                <span class="status-izin">Izin</span>
                                @break
                            @case('S')
                                <span class="status-sakit">Sakit</span>
                                @break
                            @case('A')
                                <span class="status-alpha">Alpha</span>
                                @break
                            @default
                                {{ $d->status }}
                        @endswitch
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

    @if($data->count() > 0)
    <div class="summary">
        <strong>Ringkasan:</strong>
        <ul>
            <li>Hadir: {{ $data->where('status', 'H')->count() }}</li>
            <li>Izin: {{ $data->where('status', 'I')->count() }}</li>
            <li>Sakit: {{ $data->where('status', 'S')->count() }}</li>
            <li>Alpha: {{ $data->where('status', 'A')->count() }}</li>
            <li>Total: {{ $data->count() }}</li>
        </ul>
    </div>
    @endif

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Sistem' }} | {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>