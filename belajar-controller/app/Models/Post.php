<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image',
        'views',
        'is_featured',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->excerpt) && !empty($post->body)) {
                $post->excerpt = Str::limit(strip_tags($post->body), 150);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->comments()->approved();
    }

    public function pendingComments()
    {
        return $this->comments()->pending();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'published' => '<span class="badge bg-success">Published</span>',
            'draft' => '<span class="badge bg-warning">Draft</span>',
            'archived' => '<span class="badge bg-secondary">Archived</span>',
            default => '<span class="badge bg-light">Unknown</span>',
        };
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->body));
        $minutes = ceil($words / 200);
        return $minutes . ' menit';
    }

    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        return Str::limit(strip_tags($this->body), 150);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeOfCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeWithTag($query, $tagId)
    {
        return $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('tags.id', $tagId);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('body', 'like', "%{$search}%");
        });
    }

    public function incrementViews()
    {
        $this->increment('views');
        return $this;
    }

    public function publish()
    {
        $this->status = 'published';
        $this->published_at = now();
        $this->save();
        return $this;
    }

    public function addTag($tagId)
    {
        return $this->tags()->syncWithoutDetaching([$tagId]);
    }

    public function removeTag($tagId)
    {
        return $this->tags()->detach($tagId);
    }

    public function syncTags(array $tagIds)
    {
        return $this->tags()->sync($tagIds);
    }
}
