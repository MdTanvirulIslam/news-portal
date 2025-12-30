<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_bn',
        'slug_en',
        'slug_bn',
        'description_en',
        'description_bn',
        'is_active',
        'meta_title_en',
        'meta_title_bn',
        'meta_description_en',
        'meta_description_bn',
        'meta_keywords_en',
        'meta_keywords_bn',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Auto-generate slugs on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug_en)) {
                $tag->slug_en = Str::slug($tag->name_en);
            }
            if (empty($tag->slug_bn)) {
                $tag->slug_bn = Str::slug($tag->name_bn);
            }
        });
    }

    /**
     * Relationship: Tags belong to many posts
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag')
            ->withTimestamps();
    }

    /**
     * Scope: Get only active tags
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get popular tags (with post count)
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->active()
            ->withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit($limit);
    }

    /**
     * Get translated name
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'name_' . $locale} ?? $this->name_bn;
    }

    /**
     * Get translated slug
     */
    public function getSlugAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'slug_' . $locale} ?? $this->slug_bn;
    }
}
