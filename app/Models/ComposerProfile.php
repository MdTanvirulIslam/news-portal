<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComposerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'composer_types',
        'genres',
        'instruments_knowledge',
        'studio_availability',
        'sample_works',
        'experience_years',
        'work_charges_min',
        'work_charges_max',
        'recording_location',
        'booking_email',
        'booking_phone',
        'govt_id',
        'previous_work_docs',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'composer_types' => 'array',
        'genres' => 'array',
        'instruments_knowledge' => 'array',
        'sample_works' => 'array',
        'studio_availability' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the composer profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Available composer types
     */
    public static function getComposerTypes(): array
    {
        return [
            'music_director' => 'Music Director (সঙ্গীত পরিচালক)',
            'beat_maker' => 'Beat Maker (বিট মেকার)',
            'arranger' => 'Arranger (আরেঞ্জার)',
            'background_scorer' => 'Background Scorer (ব্যাকগ্রাউন্ড স্কোরার)',
            'sound_designer' => 'Sound Designer (সাউন্ড ডিজাইনার)',
            'music_producer' => 'Music Producer (মিউজিক প্রডিউসার)',
            'orchestrator' => 'Orchestrator (অর্কেস্ট্রেটর)',
            'film_composer' => 'Film Composer (চলচ্চিত্র সুরকার)',
        ];
    }

    /**
     * Available genres
     */
    public static function getAvailableGenres(): array
    {
        return [
            'classical' => 'Classical (শাস্ত্রীয় সঙ্গীত)',
            'semi_classical' => 'Semi-Classical (আধা-শাস্ত্রীয়)',
            'folk' => 'Folk (লোকসংগীত)',
            'modern' => 'Modern (আধুনিক)',
            'pop' => 'Pop (পপ)',
            'rock' => 'Rock (রক)',
            'electronic' => 'Electronic (ইলেকট্রনিক)',
            'hip_hop' => 'Hip Hop (হিপ হপ)',
            'jazz' => 'Jazz (জ্যাজ)',
            'blues' => 'Blues (ব্লুজ)',
            'film_score' => 'Film Score (চলচ্চিত্র স্কোর)',
            'advertisement' => 'Advertisement (বিজ্ঞাপন)',
            'devotional' => 'Devotional (ধর্মীয়)',
            'patriotic' => 'Patriotic (দেশপ্রেমী)',
            'fusion' => 'Fusion (ফিউশন)',
        ];
    }

    /**
     * Available instruments
     */
    public static function getAvailableInstruments(): array
    {
        return [
            'keyboard' => 'Keyboard (কীবোর্ড)',
            'piano' => 'Piano (পিয়ানো)',
            'guitar' => 'Guitar (গিটার)',
            'violin' => 'Violin (ভায়োলিন)',
            'flute' => 'Flute (বাঁশি)',
            'tabla' => 'Tabla (তবলা)',
            'harmonium' => 'Harmonium (হারমোনিয়াম)',
            'drums' => 'Drums (ড্রামস)',
            'sitar' => 'Sitar (সেতার)',
            'saxophone' => 'Saxophone (স্যাক্সোফোন)',
            'trumpet' => 'Trumpet (ট্রাম্পেট)',
            'cello' => 'Cello (চেলো)',
            'digital_audio_workstation' => 'DAW (ডিজিটাল অডিও ওয়ার্কস্টেশন)',
            'synthesizer' => 'Synthesizer (সিনথেসাইজার)',
            'percussion' => 'Percussion (পারকাশন)',
        ];
    }

    /**
     * Studio availability options
     */
    public static function getStudioAvailabilityOptions(): array
    {
        return [
            'yes' => 'Yes (হ্যাঁ)',
            'no' => 'No (না)',
            'shared' => 'Shared Studio (শেয়ার্ড স্টুডিও)',
            'home_studio' => 'Home Studio (হোম স্টুডিও)',
        ];
    }
}
