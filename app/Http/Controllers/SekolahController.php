<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::first();
        return view('sekolah.index', compact('sekolah'));
    }

    public function store(Request $request)
    {
        Sekolah::updateOrCreate(
            ['id' => 1], // Assuming single record
            $request->all()
        );
        return back();
    }
}
