<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileVerificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_type',
        'admin_id',
        'action',
        'reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user being verified
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin who performed the action
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get profile type label
     */
    public function getProfileTypeLabelAttribute()
    {
        return ucfirst($this->profile_type);
    }

    /**
     * Get action label with icon
     */
    public function getActionLabelAttribute()
    {
        $labels = [
            'verified' => '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Verified</span>',
            'unverified' => '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Unverified</span>',
        ];

        return $labels[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Scope for verified actions
     */
    public function scopeVerified($query)
    {
        return $query->where('action', 'verified');
    }

    /**
     * Scope for unverified actions
     */
    public function scopeUnverified($query)
    {
        return $query->where('action', 'unverified');
    }

    /**
     * Scope for specific profile type
     */
    public function scopeProfileType($query, $type)
    {
        return $query->where('profile_type', $type);
    }

    /**
     * Get recent verification logs for a user
     */
    public static function getUserLogs($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
