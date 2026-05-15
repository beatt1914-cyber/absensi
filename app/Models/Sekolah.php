<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = 'sekolah';
    protected $fillable = ['nama_sekolah', 'logo', 'dosen', 'nip_dosen', 'kepsek', 'nip_kepsek'];
}
