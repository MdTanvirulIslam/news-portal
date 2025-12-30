<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_en', 'subject_bn', 'content_en', 'content_bn',
        'sent_by', 'status', 'scheduled_at', 'sent_at',
        'total_subscribers', 'successful_sends', 'failed_sends'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function sender() { return $this->belongsTo(User::class, 'sent_by'); }
    public function scopeDraft($query) { return $query->where('status', 'draft'); }
    public function scopeSent($query) { return $query->where('status', 'sent'); }
}
