<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode',
        'nama',
        'sks',
        'semester',
        'deskripsi',
        'dosen_id',
    ];

    protected $casts = [
        'sks' => 'integer',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}