<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $jadwal = \App\Models\JadwalKuliah::all();
        
        // Ambil semester yang ada di jadwal
        $semesterOptions = $jadwal->pluck('semester')->unique()->sort()->values();
        
        // Ambil kelas yang ada di jadwal
        $kelasIds = $jadwal->pluck('kelas_id')->unique();
        $kelas = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get();
        
        // Ambil dosen yang ada di jadwal
        $dosenIds = $jadwal->pluck('dosen_id')->unique();
        $dosen = Dosen::whereIn('id', $dosenIds)->orderBy('nama', 'asc')->get();
        
        // Ambil mata kuliah yang ada di jadwal
        $mkIds = $jadwal->pluck('mata_kuliah_id')->unique();
        $matakuliah = MataKuliah::whereIn('id', $mkIds)->orderBy('nama', 'asc')->get();
        
        $selectedJadwal = null;
        if ($request->has('jadwal_id')) {
            $selectedJadwal = \App\Models\JadwalKuliah::find($request->jadwal_id);
        }

        return view('absen.index', compact('kelas', 'dosen', 'matakuliah', 'selectedJadwal', 'semesterOptions'));
    }

    /**
     * Mapping nama hari Indonesia ke angka (0=Minggu, 1=Senin, ..., 6=Sabtu)
     */
    private function hariToNumber(string $hari): int
    {
        $map = [
            'Minggu' => 0,
            'Senin'  => 1,
            'Selasa' => 2,
            'Rabu'   => 3,
            'Kamis'  => 4,
            'Jumat'  => 5,
            'Sabtu'  => 6,
        ];
        return $map[$hari] ?? -1;
    }

    public function getSiswa(Request $request)
    {
        $kelas_id       = $request->kelas_id;
        $mata_kuliah_id = $request->mata_kuliah_id;
        $dosen_id       = $request->dosen_id;
        $tanggal        = $request->tanggal; // tanggal yang dipilih user

        $jadwal = \App\Models\JadwalKuliah::where('kelas_id', $kelas_id)
            ->where('mata_kuliah_id', $mata_kuliah_id)
            ->where('dosen_id', $dosen_id)
            ->first();

        if (!$jadwal) {
            return response()->json(['error' => 'Jadwal tidak ditemukan! Tidak bisa input absen.'], 404);
        }

        // Cek hari dari tanggal yang dipilih (bukan hari ini)
        $hariJadwal   = $this->hariToNumber($jadwal->hari);
        $hariTanggal  = $tanggal
            ? (int) \Carbon\Carbon::parse($tanggal)->format('w')
            : (int) now()->format('w');

        if ($hariJadwal !== $hariTanggal) {
            $namahariTanggal = \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd');
            return response()->json([
                'error'        => "Tanggal yang dipilih adalah hari {$namahariTanggal}, sedangkan jadwal ini hanya ada pada hari {$jadwal->hari}. Silakan pilih tanggal yang sesuai.",
                'hari_jadwal'  => $jadwal->hari,
            ], 403);
        }

        $mahasiswa = Mahasiswa::with('kelas')
            ->where('kelas_id', $kelas_id)
            ->orderBy('nama', 'asc')
            ->get();
        return response()->json([
            'mahasiswa'   => $mahasiswa,
            'hari_jadwal' => $jadwal->hari,
        ]);
    }

    public function getJadwalOptions(Request $request)
    {
        $query = \App\Models\JadwalKuliah::query();

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('mata_kuliah_id')) {
            $query->where('mata_kuliah_id', $request->mata_kuliah_id);
        }

        $jadwal = $query->get();

        $kelasIds = $jadwal->pluck('kelas_id')->unique();
        $mkIds = $jadwal->pluck('mata_kuliah_id')->unique();
        $dosenIds = $jadwal->pluck('dosen_id')->unique();

        return response()->json([
            'kelas' => Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get(),
            'matakuliah' => MataKuliah::whereIn('id', $mkIds)->orderBy('nama', 'asc')->get(),
            'dosen' => Dosen::whereIn('id', $dosenIds)->orderBy('nama', 'asc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $jadwal = \App\Models\JadwalKuliah::where('kelas_id', $request->kelas_id)
            ->where('mata_kuliah_id', $request->mata_kuliah_id)
            ->where('dosen_id', $request->dosen_id)
            ->first();

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan. Tidak dapat menyimpan absensi.');
        }

        // Validasi server-side: hari dari tanggal yang dipilih harus sesuai hari jadwal
        $hariJadwal  = $this->hariToNumber($jadwal->hari);
        $hariTanggal = (int) \Carbon\Carbon::parse($request->tanggal)->format('w');

        if ($hariJadwal !== $hariTanggal) {
            $namaHariTanggal = \Carbon\Carbon::parse($request->tanggal)->locale('id')->isoFormat('dddd');
            return back()->with('error', "Tanggal yang dipilih adalah hari {$namaHariTanggal}, sedangkan jadwal ini hanya ada pada hari {$jadwal->hari}.");
        }

        foreach ($request->status as $mahasiswa_id => $status) {
            Absensi::updateOrCreate(
                [
                    'mahasiswa_id'   => $mahasiswa_id,
                    'tanggal'        => $request->tanggal,
                    'mata_kuliah_id' => $request->mata_kuliah_id,
                ],
                [
                    'kelas_id' => $request->kelas_id,
                    'status'   => $status,
                ]
            );
        }

        return back()->with('success', 'Absensi berhasil disimpan.');
    }
}