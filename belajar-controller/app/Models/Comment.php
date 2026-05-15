<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_id', 'author_name', 'author_email', 'content', 'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    // Relasi ke post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relasi ke User (opsional jika komentar dari user terdaftar)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk komentar yang disetujui
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope untuk komentar yang pending
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function getStatusAttribute()
    {
        return $this->is_approved ? 'approved' : 'pending';
    }

    // Approve komentar
    public function approve()
    {
        $this->update(['is_approved' => true]);
        return $this;
    }
}