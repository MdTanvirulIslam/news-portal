<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'address',
        'phone',
        'profile_picture',
        'bio',
        'designation',
        'country',
        'city',
        'terms_accepted',
        'copyright_accepted',
        'profile_completed',
        'profile_completed_at',
        'last_login_at',
        'last_login_ip',
        'email_verification_token',
        'email_verification_sent_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'terms_accepted' => 'boolean',
            'copyright_accepted' => 'boolean',
            'profile_completed' => 'boolean',
            'profile_completed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Available user roles
     */
    const ROLES = [
        'admin' => 'Admin',
        'editor' => 'Editor',
        'reporter' => 'Reporter',
        'contributor' => 'Contributor',
        'listener' => 'Listener',
        'artist' => 'Artist',
        'lyricist' => 'Lyricist',
        'composer' => 'Composer',
        'label' => 'Label/Owner',
        'publisher' => 'Publisher',
    ];

    /**
     * Roles that require admin approval
     */
    const APPROVAL_REQUIRED_ROLES = ['reporter', 'artist', 'lyricist', 'composer', 'label', 'publisher'];

    /**
     * Check if user's role requires admin approval
     */
    public function requiresApproval(): bool
    {
        return in_array($this->role, self::APPROVAL_REQUIRED_ROLES);
    }

    /**
     * Check if user has completed their profile
     */
    public function hasCompletedProfile(): bool
    {
        return $this->profile_completed;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is editor
     */
    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    /**
     * Get role display name
     */
    public function getRoleNameAttribute(): string
    {
        return self::ROLES[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Posts relationship
     */
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
}
