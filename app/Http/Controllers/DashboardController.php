<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahSiswa = Mahasiswa::count();
        $jumlahKelas = Kelas::count();
        $hadir = Absensi::where('status','H')->count();
        $posts = Post::with('user', 'comments', 'likes')->latest()->limit(5)->get();

        return view('dashboard', compact('jumlahSiswa','jumlahKelas','hadir','posts'));
    }
}