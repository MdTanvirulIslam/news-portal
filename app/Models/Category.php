<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name_en',
        'name_bn',
        'slug_en',
        'slug_bn',
        'description_en',
        'description_bn',
        'image',
        'icon',
        'order',
        'is_active',
        'show_in_menu',
        'meta_title_en',
        'meta_title_bn',
        'meta_description_en',
        'meta_description_bn',
        'meta_keywords_en',
        'meta_keywords_bn',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'order' => 'integer',
    ];

    // Auto-generate slugs
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            // Only generate slug_en if name_en exists and slug_en is empty
            if (!empty($category->name_en) && empty($category->slug_en)) {
                $category->slug_en = Str::slug($category->name_en);
            } elseif (empty($category->name_en)) {
                $category->slug_en = null; // Set to NULL instead of empty string
            }

            // Only generate slug_bn if name_bn exists and slug_bn is empty
            if (!empty($category->name_bn) && empty($category->slug_bn)) {
                $category->slug_bn = Str::slug($category->name_bn);
            } elseif (empty($category->name_bn)) {
                $category->slug_bn = null; // Set to NULL instead of empty string
            }
        });

        static::updating(function ($category) {
            // Only generate slug_en if name_en exists
            if (!empty($category->name_en) && empty($category->slug_en)) {
                $category->slug_en = Str::slug($category->name_en);
            } elseif (empty($category->name_en)) {
                $category->slug_en = null;
            }

            // Only generate slug_bn if name_bn exists
            if (!empty($category->name_bn) && empty($category->slug_bn)) {
                $category->slug_bn = Str::slug($category->name_bn);
            } elseif (empty($category->name_bn)) {
                $category->slug_bn = null;
            }
        });
    }

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    /**
     * ✅ FIXED: Uses belongsToMany WITHOUT withTimestamps()
     * Because your post_category table doesn't have timestamp columns
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category');
    }

    public function rssFeeds()
    {
        return $this->hasMany(RssFeed::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeShowInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }

    // Helper Methods
    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    public function getFullNameAttribute(): string
    {
        $names = [];
        $category = $this;

        while ($category) {
            array_unshift($names, $category->name_en ?? $category->name_bn);
            $category = $category->parent;
        }

        return implode(' > ', $names);
    }

    public function getLevel(): int
    {
        $level = 0;
        $category = $this;

        while ($category->parent) {
            $level++;
            $category = $category->parent;
        }

        return $level;
    }
}
