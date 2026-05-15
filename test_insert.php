<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $dosen = App\Models\Dosen::create([
        'nip' => '888888',
        'nama' => 'Test Dosen CLI',
        'email' => 'cli'.time().'@test.com',
        'jurusan' => 'Teknik Informatika'
    ]);
    echo "BERHASIL - ID: " . $dosen->id . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}