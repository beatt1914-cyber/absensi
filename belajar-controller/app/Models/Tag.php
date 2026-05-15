<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted()
    {
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // Relasi many-to-many ke posts
    public function posts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    // Mendapatkan published posts dengan tag ini
    public function publishedPosts()
    {
        return $this->posts()->published();
    }

    // Scope untuk tag dengan posts terbanyak
    public function scopePopular($query)
    {
        return $query->withCount('posts')
                     ->orderBy('posts_count', 'desc');
    }
}