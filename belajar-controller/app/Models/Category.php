<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'color', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Relasi ke posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Mendapatkan published posts dalam kategori ini
    public function publishedPosts()
    {
        return $this->posts()->published();
    }

    // Mendapatkan jumlah posts dalam kategori (hanya published)
    public function getPublishedCountAttribute()
    {
        return $this->publishedPosts()->count();
    }

    // Scope untuk kategori aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk kategori dengan posts terbanyak
    public function scopeWithMostPosts($query)
    {
        return $query->withCount('posts')
                     ->orderBy('posts_count', 'desc');
    }
}