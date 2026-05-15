<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $matakuliah = MataKuliah::orderBy('nama', 'asc')->get();
        $dosen = Dosen::orderBy('nama', 'asc')->get();

        $data = Absensi::with(['mahasiswa', 'mataKuliah.dosen', 'kelas'])
            ->when($request->tanggal, fn($q)=>$q->whereDate('tanggal',$request->tanggal))
            ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
            ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun))
            ->when($request->mata_kuliah_id, fn($q) => $q->where('mata_kuliah_id', $request->mata_kuliah_id))
            ->when($request->dosen_id, function($q) use ($request) {
                $q->whereHas('mataKuliah', function($query) use ($request) {
                    $query->where('dosen_id', $request->dosen_id);
                });
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('mata_kuliah_id')
            ->get();

        return view('rekap.index', compact('data', 'matakuliah', 'dosen'));
    }

    public function downloadPdf(Request $request)
    {
        $data = Absensi::with(['mahasiswa', 'mataKuliah.dosen', 'kelas'])
            ->when($request->tanggal, fn($q) => $q->whereDate('tanggal', $request->tanggal))
            ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
            ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun))
            ->when($request->mata_kuliah_id, fn($q) => $q->where('mata_kuliah_id', $request->mata_kuliah_id))
            ->when($request->dosen_id, function($q) use ($request) {
                $q->whereHas('mataKuliah', function($query) use ($request) {
                    $query->where('dosen_id', $request->dosen_id);
                });
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        $dataByDate = $data->groupBy('tanggal');
        $filters = [
            'tanggal' => $request->tanggal ? \Carbon\Carbon::parse($request->tanggal)->format('d F Y') : null,
            'bulan' => $request->bulan ? \Carbon\Carbon::create()->month($request->bulan)->format('F') : null,
            'tahun' => $request->tahun,
            'mata_kuliah' => $request->mata_kuliah_id ? \App\Models\MataKuliah::find($request->mata_kuliah_id)->nama : null,
            'dosen' => $request->dosen_id ? \App\Models\Dosen::find($request->dosen_id)->nama : null,
        ];

        $pdf = Pdf::loadView('rekap.pdf', compact('data', 'dataByDate', 'filters'));

        return $pdf->download('rekap-absensi-' . date('Y-m-d') . '.pdf');
    }
}