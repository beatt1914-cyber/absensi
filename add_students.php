<?php
// Direct database connection
$host = '127.0.0.1';
$db = 'db_absensi';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection Error: " . $e->getMessage() . "\n";
    exit(1);
}

$students = [
    ['nama' => 'Monica Syahrianti', 'nim' => '2024230015'],
    ['nama' => 'Cesty Novica Dwi Putri', 'nim' => '2024230001'],
    ['nama' => 'Verlin Patimah', 'nim' => '2024230019'],
    ['nama' => 'Tri Agustin', 'nim' => '2024230016'],
    ['nama' => 'Nayla Puspita Sari', 'nim' => '2024230005'],
    ['nama' => 'Muhammad Saputra', 'nim' => '2024230009'],
    ['nama' => 'Rhado Fahrel Pratama Nasution', 'nim' => '2024230003'],
    ['nama' => 'Nelshen Zariko Apral', 'nim' => '2024230017'],
    ['nama' => 'Dimas Fitrian Saputra', 'nim' => '2024230021'],
    ['nama' => 'Rhafa Karta Wijaya', 'nim' => null],
    ['nama' => 'Zaharani Putri', 'nim' => '2024230007'],
    ['nama' => 'Dini Aprilianti', 'nim' => '2024230014'],
    ['nama' => 'Fitriani', 'nim' => '2024230010'],
    ['nama' => 'Intan Purnama', 'nim' => '2024230012'],
    ['nama' => 'Anthera Akbar Valentino', 'nim' => '2024230020'],
    ['nama' => 'Andea Farhan Pratama', 'nim' => '2024230011'],
    ['nama' => 'Cepriyanto', 'nim' => '2024230004'],
    ['nama' => 'Piki Saputra', 'nim' => '2024230018'],
];

$added = 0;
$failed = 0;

foreach ($students as $s) {
    try {
        Mahasiswa::create([
            'nama' => $s['nama'],
            'nim' => $s['nim'],
            'kelas_id' => 3
        ]);
        $added++;
        echo "✓ Added: " . $s['nama'] . "\n";
    } catch (\Exception $e) {
        $failed++;
        echo "✗ Failed: " . $s['nama'] . " - " . $e->getMessage() . "\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Total Added: $added\n";
echo "Total Failed: $failed\n";
echo "Total Mahasiswa in informatika a: " . Mahasiswa::where('kelas_id', 3)->count() . "\n";
echo str_repeat("=", 60) . "\n";
?>
