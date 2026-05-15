<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First truncate existing data or deal with it. To avoid errors, we'll clear data.
        DB::table('jadwal_kuliah')->truncate();

        Schema::table('jadwal_kuliah', function (Blueprint $table) {
            $table->dropColumn(['mata_kuliah', 'nama_dosen', 'kelas']);
            $table->foreignId('mata_kuliah_id')->after('id')->constrained('mata_kuliah')->onDelete('cascade');
            $table->foreignId('dosen_id')->after('mata_kuliah_id')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('kelas_id')->after('dosen_id')->constrained('kelas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_kuliah', function (Blueprint $table) {
            $table->dropForeign(['mata_kuliah_id']);
            $table->dropForeign(['dosen_id']);
            $table->dropForeign(['kelas_id']);
            $table->dropColumn(['mata_kuliah_id', 'dosen_id', 'kelas_id']);
            $table->string('mata_kuliah', 200);
            $table->string('nama_dosen', 200);
            $table->string('kelas', 50);
        });
    }
};
