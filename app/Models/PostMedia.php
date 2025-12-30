<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'media_type',
        'file_path',
        'order',
        'file_size',
        'mime_type',
    ];

    protected $casts = [
        'order' => 'integer',
        'file_size' => 'integer',
    ];

    // Relationships
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
