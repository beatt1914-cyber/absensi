<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tanggal_lahir',
        'jenis_kelamin',
        'setuju_syarat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'setuju_syarat' => 'boolean',
    ];

    // Relasi ke posts
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    // Relasi ke komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Mendapatkan semua published posts milik user
    public function publishedPosts()
    {
        return $this->posts()->published();
    }

    // Mendapatkan total views dari semua posts user
    public function getTotalViewsAttribute()
    {
        return $this->posts()->sum('views');
    }

    // Scope untuk user dengan posts terbanyak
    public function scopeWithMostPosts($query, $limit = 10)
    {
        return $query->withCount('posts')
                     ->orderBy('posts_count', 'desc')
                     ->limit($limit);
    }
}