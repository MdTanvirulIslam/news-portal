<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RssFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'feed_url',
        'category_id',
        'is_active',
        'auto_import',
        'import_limit',
        'last_fetched_at',
        'total_imported'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_import' => 'boolean',
        'last_fetched_at' => 'datetime',
    ];

    /**
     * Get the category that owns the RSS feed
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope for active feeds
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for auto-import feeds
     */
    public function scopeAutoImport($query)
    {
        return $query->where('auto_import', true);
    }

    /**
     * Check if feed should be fetched (based on last fetch time)
     */
    public function shouldFetch()
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->last_fetched_at) {
            return true;
        }

        // Fetch if last fetch was more than 1 hour ago
        return $this->last_fetched_at->lt(now()->subHour());
    }

    /**
     * Update last fetched time and total imported count
     */
    public function updateFetchStats($importedCount = 0)
    {
        $this->last_fetched_at = now();
        $this->total_imported += $importedCount;
        $this->save();
    }
}
