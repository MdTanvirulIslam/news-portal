<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stage_name',
        'gender',
        'nationality',
        'artist_type',
        'genres',
        'years_of_experience',
        'vocal_type',
        'instruments',
        'portfolio_links',
        'demo_audio',
        'previous_albums',
        'performance_videos',
        'manager_name',
        'manager_phone',
        'booking_email',
        'live_show_price_min',
        'live_show_price_max',
        'studio_recording_fee',
        'location_availability',
        'govt_id',
        'artist_contract',
        'copyright_declaration',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'genres' => 'array',
        'languages' => 'array',
        'instruments' => 'array',
        'portfolio_links' => 'array',
        'previous_albums' => 'array',
        'performance_videos' => 'array',
        'location_availability' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the artist profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Available genres
     */
    public static function getAvailableGenres(): array
    {
        return [
            'pop' => 'Pop',
            'rock' => 'Rock',
            'classical' => 'Classical',
            'folk' => 'Folk',
            'rap' => 'Rap',
            'hip_hop' => 'Hip Hop',
            'jazz' => 'Jazz',
            'blues' => 'Blues',
            'country' => 'Country',
            'electronic' => 'Electronic',
            'qawwali' => 'Qawwali',
            'bandari' => 'Bandari',
            'devotional' => 'Devotional',
            'indie' => 'Indie',
            'metal' => 'Metal',
            'reggae' => 'Reggae',
        ];
    }

    /**
     * Available languages
     */
    public static function getAvailableLanguages(): array
    {
        return [
            'english' => 'English',
            'bangla' => 'বাংলা',
            'hindi' => 'हिन्दी',
            'urdu' => 'اردو',
            'arabic' => 'العربية',
            'spanish' => 'Español',
            'french' => 'Français',
            'german' => 'Deutsch',
            'italian' => 'Italiano',
            'portuguese' => 'Português',
        ];
    }

    /**
     * Available instruments
     */
    public static function getAvailableInstruments(): array
    {
        return [
            'guitar' => 'Guitar',
            'piano' => 'Piano',
            'drums' => 'Drums',
            'bass' => 'Bass',
            'violin' => 'Violin',
            'flute' => 'Flute',
            'saxophone' => 'Saxophone',
            'tabla' => 'Tabla',
            'harmonium' => 'Harmonium',
            'sitar' => 'Sitar',
            'keyboard' => 'Keyboard',
            'trumpet' => 'Trumpet',
        ];
    }
}
