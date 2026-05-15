<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::query();

        if ($request->has('search') && $request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
        }

        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }

        $matakuliah = $query->orderBy('semester')->orderBy('nama')->paginate(10);

        return view('matakuliah.index', compact('matakuliah'));
    }

    public function create()
    {
        $dosen = Dosen::orderBy('nama', 'asc')->get();
        return view('matakuliah.create', compact('dosen'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:mata_kuliah,kode',
            'nama' => 'required|string|max:200',
            'sks' => 'required|integer|min:1|max:10',
            'semester' => 'required|string|max:20',
            'dosen_id' => 'nullable|exists:dosen,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        MataKuliah::create($request->all());

        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan');
    }

    public function edit(MataKuliah $matakuliah)
    {
        $dosen = Dosen::orderBy('nama', 'asc')->get();
        return view('matakuliah.edit', compact('matakuliah', 'dosen'));
    }

    public function update(Request $request, MataKuliah $matakuliah)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:mata_kuliah,kode,' . $matakuliah->id,
            'nama' => 'required|string|max:200',
            'sks' => 'required|integer|min:1|max:10',
            'semester' => 'required|string|max:20',
            'dosen_id' => 'nullable|exists:dosen,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $matakuliah->update($request->all());

        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui');
    }

    public function destroy(MataKuliah $matakuliah)
    {
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus');
    }
}