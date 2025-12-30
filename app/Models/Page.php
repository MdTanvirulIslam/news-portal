<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_en',
        'title_bn',
        'slug_en',
        'slug_bn',
        'content_en',
        'content_bn',
        'meta_title_en',
        'meta_title_bn',
        'meta_description_en',
        'meta_description_bn',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot method to auto-generate slugs
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug_en)) {
                $page->slug_en = Str::slug($page->title_en);
            }
            if (empty($page->slug_bn)) {
                $page->slug_bn = Str::slug($page->title_bn);
            }
        });
    }

    /**
     * Scope to get only active pages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by custom order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
