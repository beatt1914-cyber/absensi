<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JadwalKuliah::with(['mataKuliah', 'dosen', 'kelas']);

        // Filter berdasarkan parameter
        if ($request->has('hari') && $request->hari) {
            $query->where('hari', $request->hari);
        }

        if ($request->has('kelas') && $request->kelas) {
            $query->whereHas('kelas', function($q) use ($request) {
                $q->where('nama_kelas', 'like', '%' . $request->kelas . '%');
            });
        }

        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('mataKuliah', function($q2) use ($request) {
                      $q2->where('nama', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('dosen', function($q2) use ($request) {
                      $q2->where('nama', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Urutkan berdasarkan hari dan jam mulai
        $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
              ->orderBy('jam_mulai');

        $jadwal = $query->paginate(10);

        $hariOptions = JadwalKuliah::getHariOptions();
        $semesterOptions = JadwalKuliah::getSemesterOptions();

        return view('jadwal.index', compact('jadwal', 'hariOptions', 'semesterOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hariOptions = JadwalKuliah::getHariOptions();
        $semesterOptions = JadwalKuliah::getSemesterOptions();
        $mataKuliahs = MataKuliah::orderBy('nama')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('jadwal.create', compact('hariOptions', 'semesterOptions', 'mataKuliahs', 'dosens', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required|string|max:50',
            'semester' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek bentrok jadwal (Hari, Jam, dan Ruangan/Kelas/Dosen)
        $bentrok = JadwalKuliah::where('hari', $request->hari)
            ->where(function($query) use ($request) {
                // Logika Overlap: (Mulai_A < Selesai_B) AND (Selesai_A > Mulai_B)
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->where(function($query) use ($request) {
                $query->where('ruangan', $request->ruangan)
                      ->orWhere('kelas_id', $request->kelas_id)
                      ->orWhere('dosen_id', $request->dosen_id);
            })
            ->first();

        if ($bentrok) {
            $pesan = "Jadwal bentrok!";
            if ($bentrok->ruangan == $request->ruangan) $pesan = "Ruangan " . $request->ruangan . " sudah digunakan oleh " . $bentrok->mataKuliah->nama;
            if ($bentrok->kelas_id == $request->kelas_id) $pesan = "Kelas sudah memiliki jadwal lain: " . $bentrok->mataKuliah->nama;
            if ($bentrok->dosen_id == $request->dosen_id) $pesan = "Dosen sudah mengajar di kelas lain: " . $bentrok->mataKuliah->nama;

            return redirect()->back()
                ->withErrors(['bentrok' => $pesan])
                ->withInput();
        }

        JadwalKuliah::create($request->all());

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal kuliah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalKuliah $jadwal)
    {
        return view('jadwal.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalKuliah $jadwal)
    {
        $hariOptions = JadwalKuliah::getHariOptions();
        $semesterOptions = JadwalKuliah::getSemesterOptions();
        $mataKuliahs = MataKuliah::orderBy('nama')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('jadwal.edit', compact('jadwal', 'hariOptions', 'semesterOptions', 'mataKuliahs', 'dosens', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalKuliah $jadwal)
    {
        $validator = Validator::make($request->all(), [
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required|string|max:50',
            'semester' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek bentrok jadwal (kecuali jadwal ini sendiri)
        $bentrok = JadwalKuliah::where('id', '!=', $jadwal->id)
            ->where('hari', $request->hari)
            ->where(function($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->where(function($query) use ($request) {
                $query->where('ruangan', $request->ruangan)
                      ->orWhere('kelas_id', $request->kelas_id)
                      ->orWhere('dosen_id', $request->dosen_id);
            })
            ->first();

        if ($bentrok) {
            $pesan = "Jadwal bentrok!";
            if ($bentrok->ruangan == $request->ruangan) $pesan = "Ruangan " . $request->ruangan . " sudah digunakan oleh " . $bentrok->mataKuliah->nama;
            if ($bentrok->kelas_id == $request->kelas_id) $pesan = "Kelas sudah memiliki jadwal lain: " . $bentrok->mataKuliah->nama;
            if ($bentrok->dosen_id == $request->dosen_id) $pesan = "Dosen sudah mengajar di kelas lain: " . $bentrok->mataKuliah->nama;

            return redirect()->back()
                ->withErrors(['bentrok' => $pesan])
                ->withInput();
        }

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal kuliah berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalKuliah $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal kuliah berhasil dihapus');
    }

    /**
     * Download jadwal as PDF
     */
    public function downloadPdf(Request $request)
    {
        $query = JadwalKuliah::with(['mataKuliah', 'dosen', 'kelas']);

        // Apply filters if any
        if ($request->has('hari') && $request->hari) {
            $query->where('hari', $request->hari);
        }

        if ($request->has('kelas') && $request->kelas) {
            $query->whereHas('kelas', function($q) use ($request) {
                $q->where('nama_kelas', 'like', '%' . $request->kelas . '%');
            });
        }

        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('mataKuliah', function($q2) use ($request) {
                      $q2->where('nama', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('dosen', function($q2) use ($request) {
                      $q2->where('nama', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $jadwal = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                        ->orderBy('jam_mulai')
                        ->get();

        // Group by hari
        $jadwalByHari = $jadwal->groupBy('hari');

        $pdf = Pdf::loadView('jadwal.pdf', compact('jadwal', 'jadwalByHari'));

        return $pdf->download('jadwal-kuliah-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export rekap absensi as PDF
     */
    public function rekapPdf(Request $request)
    {
        $data = \App\Models\Absensi::with('mahasiswa')
            ->when($request->tanggal, fn($q) => $q->whereDate('tanggal', $request->tanggal))
            ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
            ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun))
            ->get();

        $pdf = Pdf::loadView('rekap.pdf', compact('data'));

        return $pdf->download('rekap-absensi-' . date('Y-m-d') . '.pdf');
    }
}