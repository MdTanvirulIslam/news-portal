<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LanguageLogo extends Model
{
    use HasFactory;

    protected $fillable = [
        'english_logo',
        'english_logo_alt',
        'bangla_logo',
        'bangla_logo_alt',
    ];

    /**
     * Get the singleton settings instance
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'english_logo' => null,
                'english_logo_alt' => 'English Logo',
                'bangla_logo' => null,
                'bangla_logo_alt' => 'বাংলা লোগো',
            ]);
        }

        return $settings;
    }

    /**
     * Update settings
     */
    public static function updateSettings(array $data)
    {
        $settings = self::getSettings();
        $settings->update($data);
        return $settings;
    }

    /**
     * Get English logo URL
     */
    public function getEnglishLogoUrl()
    {
        if ($this->english_logo) {
            return url(Storage::url($this->english_logo));
        }
        return null;
    }

    /**
     * Get Bangla logo URL
     */
    public function getBanglaLogoUrl()
    {
        if ($this->bangla_logo) {
            return url(Storage::url($this->bangla_logo));
        }
        return null;
    }

    /**
     * Get logo URL by language code
     */
    public function getLogoByLanguage($languageCode)
    {
        $languageCode = strtolower($languageCode);
        
        if ($languageCode === 'en' || $languageCode === 'english') {
            return $this->getEnglishLogoUrl();
        }
        
        if ($languageCode === 'bn' || $languageCode === 'bangla' || $languageCode === 'bengali') {
            return $this->getBanglaLogoUrl();
        }
        
        return null;
    }

    /**
     * Delete English logo file
     */
    public function deleteEnglishLogo()
    {
        if ($this->english_logo && Storage::disk('public')->exists($this->english_logo)) {
            Storage::disk('public')->delete($this->english_logo);
        }
    }

    /**
     * Delete Bangla logo file
     */
    public function deleteBanglaLogo()
    {
        if ($this->bangla_logo && Storage::disk('public')->exists($this->bangla_logo)) {
            Storage::disk('public')->delete($this->bangla_logo);
        }
    }

    /**
     * Delete all logos
     */
    public function deleteAllLogos()
    {
        $this->deleteEnglishLogo();
        $this->deleteBanglaLogo();
    }

    /**
     * Check if English logo exists
     */
    public function hasEnglishLogo()
    {
        return $this->english_logo && Storage::disk('public')->exists($this->english_logo);
    }

    /**
     * Check if Bangla logo exists
     */
    public function hasBanglaLogo()
    {
        return $this->bangla_logo && Storage::disk('public')->exists($this->bangla_logo);
    }
}
