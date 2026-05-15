<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Mahasiswa;
use App\Models\Kelas;

echo "\n=== CHECKING DATABASE ===\n\n";

// Check kelas
$kelasInformatika = Kelas::where('nama_kelas', 'informatika a')->first();
if ($kelasInformatika) {
    echo "✓ Kelas ditemukan: " . $kelasInformatika->nama_kelas . " (ID: " . $kelasInformatika->id . ")\n\n";
} else {
    echo "✗ Kelas informatika a tidak ditemukan\n\n";
}

// Check mahasiswa
$mahasiswa = Mahasiswa::where('kelas_id', 3)->with('kelas')->get();

echo "Total Mahasiswa di Kelas Informatika A: " . $mahasiswa->count() . "\n\n";

if ($mahasiswa->count() > 0) {
    echo "Daftar Mahasiswa:\n";
    echo str_repeat("=", 80) . "\n";
    echo sprintf("%-5s %-20s %-45s %s\n", "No", "NIM", "Nama", "Kelas");
    echo str_repeat("=", 80) . "\n";
    
    foreach ($mahasiswa as $key => $m) {
        $nim = $m->nim ?: "-";
        $kelas = $m->kelas ? $m->kelas->nama_kelas : "N/A";
        echo sprintf("%-5d %-20s %-45s %s\n", $key + 1, $nim, substr($m->nama, 0, 43), $kelas);
    }
    echo str_repeat("=", 80) . "\n";
} else {
    echo "Tidak ada mahasiswa di kelas informatika a\n";
}

// Also check all mahasiswa count
$allMahasiswa = Mahasiswa::count();
echo "\nTotal Mahasiswa dalam sistem: " . $allMahasiswa . "\n";
?>
