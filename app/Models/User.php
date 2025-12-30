<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'profile_picture',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

        ];
    }

    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Role check methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function isReporter(): bool
    {
        return $this->role === 'reporter';
    }

    public function isContributor(): bool
    {
        return $this->role === 'contributor';
    }

    public function hasAccess(string $role): bool
    {
        $roleHierarchy = [
            'admin' => 4,
            'editor' => 3,
            'reporter' => 2,
            'contributor' => 1,
        ];

        return $roleHierarchy[$this->role] >= $roleHierarchy[$role];
    }
}
