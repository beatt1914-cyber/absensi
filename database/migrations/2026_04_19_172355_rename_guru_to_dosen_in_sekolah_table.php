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
        Schema::table('sekolah', function (Blueprint $table) {
            $table->renameColumn('guru', 'dosen');
            $table->renameColumn('nip_guru', 'nip_dosen');
        });
    }

    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->renameColumn('dosen', 'guru');
            $table->renameColumn('nip_dosen', 'nip_guru');
        });
    }
};
