<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('siswa', 'mahasiswa');
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->renameColumn('nis', 'nim');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->renameColumn('nim', 'nis');
        });
        Schema::rename('mahasiswa', 'siswa');
    }
};
