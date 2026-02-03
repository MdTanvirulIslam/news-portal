<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LyricistProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pen_name',
        'writing_types',
        'languages',
        'portfolio_links',
        'songs_written',
        'years_of_experience',
        'work_email',
        'phone',
        'collaboration_availability',
        'price_range_min',
        'price_range_max',
        'govt_id',
        'copyright_declaration',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'writing_types' => 'array',
        'languages' => 'array',
        'portfolio_links' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the lyricist profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Available writing types
     */
    public static function getWritingTypes(): array
    {
        return [
            'romantic' => 'Romantic (রোমান্টিক)',
            'sad' => 'Sad (দুঃখজনক)',
            'rap' => 'Rap (র‍্যাপ)',
            'devotional' => 'Devotional (ধর্মীয়)',
            'folk' => 'Folk (লোকসংগীত)',
            'patriotic' => 'Patriotic (দেশপ্রেমী)',
            'pop' => 'Pop (পপ)',
            'rock' => 'Rock (রক)',
            'classical' => 'Classical (শাস্ত্রীয়)',
            'modern' => 'Modern (আধুনিক)',
            'social' => 'Social (সামাজিক)',
            'children' => 'Children (শিশুতোষ)',
            'film' => 'Film (চলচ্চিত্র)',
            'tv_serial' => 'TV Serial (টিভি ধারাবাহিক)',
        ];
    }

    /**
     * Available languages
     */
    public static function getAvailableLanguages(): array
    {
        return [
            'bangla' => 'বাংলা',
            'english' => 'English',
            'hindi' => 'हिन्दी',
            'urdu' => 'اردو',
            'arabic' => 'العربية',
            'spanish' => 'Español',
            'regional' => 'Regional Dialects',
        ];
    }

    /**
     * Collaboration availability options
     */
    public static function getCollaborationOptions(): array
    {
        return [
            'available' => 'Available for Collaboration',
            'selective' => 'Selective Collaboration',
            'not_available' => 'Currently Not Available',
            'commission_only' => 'Commission Work Only',
        ];
    }
}
