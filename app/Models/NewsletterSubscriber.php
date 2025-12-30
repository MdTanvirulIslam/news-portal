<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'status',
        'is_verified',
        'token',
        'verified_at',
        'subscribed_at',
        'unsubscribed_at',
        'ip_address',
        'user_agent',
        'source',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (empty($subscriber->token)) {
                $subscriber->token = Str::random(32);
            }

            if (empty($subscriber->subscribed_at)) {
                $subscriber->subscribed_at = now();
            }
        });
    }

    /**
     * Scope for active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for verified subscribers
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for unsubscribed
     */
    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }

    /**
     * Scope for active and verified (ready to send emails)
     */
    public function scopeActiveAndVerified($query)
    {
        return $query->where('status', 'active')
            ->where('is_verified', true);
    }

    /**
     * Mark subscriber as verified
     */
    public function markAsVerified()
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }

    /**
     * Unsubscribe this subscriber
     */
    public function unsubscribe(): void
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);
    }

    /**
     * Resubscribe this subscriber
     */
    public function resubscribe(): void
    {
        $this->update([
            'status' => 'active',
            'unsubscribed_at' => null,
            'subscribed_at' => now(),
        ]);
    }

    /**
     * Mark as bounced
     */
    public function markAsBounced()
    {
        $this->update([
            'status' => 'bounced',
        ]);
    }

    /**
     * Check if subscriber is active and verified
     */
    public function isActiveAndVerified()
    {
        return $this->status === 'active' && $this->is_verified;
    }
}
