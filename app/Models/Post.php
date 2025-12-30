<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'post_type',
        'title_en',
        'title_bn',
        'slug_en',
        'slug_bn',
        'content_en',
        'content_bn',
        'excerpt_en',
        'excerpt_bn',
        'featured_image',
        'video_url',
        'audio_file',
        'meta_title_en',
        'meta_title_bn',
        'meta_description_en',
        'meta_description_bn',
        'meta_keywords_en',
        'meta_keywords_bn',
        'status',
        'is_featured',
        'is_breaking',
        'allow_comments',
        'published_at',
        'scheduled_at',
        'views_count',
        'likes_count',
        'reviewed_by',
        'reviewed_at',
        'reject_reason',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_breaking' => 'boolean',
        'allow_comments' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'views_count' => 'integer',
        'likes_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            // Generate slug_en if title_en exists and slug_en is empty
            if (!empty($post->title_en) && empty($post->slug_en)) {
                $post->slug_en = static::generateUniqueSlug($post->title_en, 'slug_en');
            } elseif (empty($post->title_en)) {
                $post->slug_en = null;
            }

            // Generate slug_bn if title_bn exists and slug_bn is empty
            if (!empty($post->title_bn) && empty($post->slug_bn)) {
                $post->slug_bn = static::generateUniqueSlug($post->title_bn, 'slug_bn');
            } elseif (empty($post->title_bn)) {
                $post->slug_bn = null;
            }
        });

        static::updating(function ($post) {
            // Update slug_en if title_en changed
            if (!empty($post->title_en) && $post->isDirty('title_en') && empty($post->slug_en)) {
                $post->slug_en = static::generateUniqueSlug($post->title_en, 'slug_en', $post->id);
            } elseif (empty($post->title_en)) {
                $post->slug_en = null;
            }

            // Update slug_bn if title_bn changed
            if (!empty($post->title_bn) && $post->isDirty('title_bn') && empty($post->slug_bn)) {
                $post->slug_bn = static::generateUniqueSlug($post->title_bn, 'slug_bn', $post->id);
            } elseif (empty($post->title_bn)) {
                $post->slug_bn = null;
            }
        });
    }

    /**
     * Generate a unique slug by checking if it already exists
     */
    protected static function generateUniqueSlug($title, $column, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::slugExists($slug, $column, $ignoreId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if a slug already exists
     */
    protected static function slugExists($slug, $column, $ignoreId = null)
    {
        $query = static::where($column, $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Many-to-many relationship with categories
     * Pivot table: post_category (without timestamps)
     */
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'post_category',    // Pivot table name (YOUR actual table)
            'post_id',          // This model's foreign key in pivot
            'category_id'       // Related model's foreign key in pivot
        );
        // Note: withTimestamps() removed because pivot table doesn't have created_at/updated_at
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function media()
    {
        return $this->hasMany(PostMedia::class)->orderBy('order');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '>', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBreaking($query)
    {
        return $query->where('is_breaking', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('post_type', $type);
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('views_count', 'desc')->limit($limit);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('published_at', 'desc')->limit($limit);
    }

    /**
     * Scope to filter posts by category (handles many-to-many)
     * Usage: Post::inCategory($categoryId)->get()
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    /**
     * Scope to filter posts by multiple categories
     * Usage: Post::inCategories([1, 2, 3])->get()
     */
    public function scopeInCategories($query, array $categoryIds)
    {
        return $query->whereHas('categories', function($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        });
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at && $this->published_at <= now();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isArticle(): bool
    {
        return $this->post_type === 'article';
    }

    public function isVideo(): bool
    {
        return $this->post_type === 'video';
    }

    public function isGallery(): bool
    {
        return $this->post_type === 'gallery';
    }

    public function canEdit($user): bool
    {
        return $user->id === $this->user_id ||
            in_array($user->role, ['admin', 'editor']);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content_en ?: $this->content_bn));
        return ceil($words / 200); // Average: 200 words per minute
    }

    public function getExcerptAttribute(): string
    {
        return $this->excerpt_en ?: $this->excerpt_bn ?: Str::limit(strip_tags($this->content_en ?: $this->content_bn), 200);
    }

    // ============================================
    // CATEGORY HELPER METHODS
    // ============================================

    /**
     * Check if post belongs to a specific category
     * Usage: if ($post->hasCategory(1)) { ... }
     */
    public function hasCategory($categoryId): bool
    {
        return $this->categories->contains('id', $categoryId);
    }

    /**
     * Get all category IDs for this post
     * Usage: $categoryIds = $post->getCategoryIds(); // [1, 2, 3]
     */
    public function getCategoryIds(): array
    {
        return $this->categories->pluck('id')->toArray();
    }

    /**
     * Get all category names for display
     * Usage: $names = $post->getCategoryNames('bn'); // ["খেলা", "বাংলাদেশ"]
     */
    public function getCategoryNames($locale = 'en'): array
    {
        $nameColumn = $locale === 'bn' ? 'name_bn' : 'name_en';
        return $this->categories->pluck($nameColumn)->toArray();
    }

    /**
     * Sync categories (replace all existing with new ones)
     * Usage in admin: $post->syncCategories([1, 2, 3]);
     */
    public function syncCategories(array $categoryIds): void
    {
        $this->categories()->sync($categoryIds);
    }

    /**
     * Add a single category without removing existing ones
     * Usage: $post->addCategory(1);
     */
    public function addCategory($categoryId): void
    {
        if (!$this->hasCategory($categoryId)) {
            $this->categories()->attach($categoryId);
        }
    }

    /**
     * Remove a specific category
     * Usage: $post->removeCategory(1);
     */
    public function removeCategory($categoryId): void
    {
        $this->categories()->detach($categoryId);
    }

    /**
     * Remove all categories
     * Usage: $post->removeAllCategories();
     */
    public function removeAllCategories(): void
    {
        $this->categories()->detach();
    }

    /**
     * Get primary category (first assigned category)
     * Usage: $primaryCategory = $post->primary_category;
     */
    public function getPrimaryCategoryAttribute()
    {
        return $this->categories->first();
    }
}
