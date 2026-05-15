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
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->renameColumn('siswa_id', 'mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->renameColumn('mahasiswa_id', 'siswa_id');
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
        });
    }
};
