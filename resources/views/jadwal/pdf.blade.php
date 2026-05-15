<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Jadwal Kuliah - PDF</title>
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
        .hari-group {
            margin-top: 20px;
        }
        .hari-title {
            background-color: #e0e0e0;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>JADWAL KULIAH</h1>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    @foreach($jadwalByHari as $hari => $jadwalItems)
    <div class="hari-group" style="margin-bottom: 30px;">
        <div class="hari-title">{{ $hari }}</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px">No</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Kelas</th>
                    <th>Jam</th>
                    <th>Ruangan</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwalItems as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->mataKuliah->nama ?? '-' }}</td>
                    <td>{{ $item->dosen->nama ?? '-' }}</td>
                    <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</td>
                    <td>{{ $item->ruangan }}</td>
                    <td>{{ $item->semester }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Sistem' }} | {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>