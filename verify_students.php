<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Mahasiswa;

$students = Mahasiswa::where('kelas_id', 3)->orderBy('nama')->get(['id', 'nama', 'nim']);

echo "\n✓ Successfully added " . $students->count() . " students to informatika a\n\n";
echo "Student List:\n";
echo str_repeat("=", 70) . "\n";
echo sprintf("%-5s %-40s %s\n", "No", "Nama", "NIM");
echo str_repeat("=", 70) . "\n";

foreach ($students as $key => $student) {
    $nim = $student->nim ?: "-";
    echo sprintf("%-5d %-40s %s\n", $key + 1, substr($student->nama, 0, 38), $nim);
}

echo str_repeat("=", 70) . "\n";
?>
