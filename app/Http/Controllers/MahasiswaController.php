<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
        return view('mahasiswa.index', compact('kelas', 'mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|min:2',
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'kelas_id' => 'required|integer|exists:kelas,id',
            'semester' => 'required|string|max:20'
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi',
            'nama.min' => 'Nama mahasiswa minimal 2 karakter',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah digunakan',
            'kelas_id.required' => 'Kelas wajib dipilih',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid',
            'semester.required' => 'Semester wajib dipilih'
        ]);

        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'kelas_id' => $request->kelas_id,
            'semester' => $request->semester
        ]);

        return back()->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Mahasiswa::findOrFail($id)->delete();
        return back();
    }
}