<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class LogoSetting extends Model
{
    protected $fillable = [
        'main_logo',
        'footer_logo',
        'favicon',
        'lazy_banner',
        'og_image',
        'main_logo_alt',
        'footer_logo_alt',
        'main_logo_width',
        'main_logo_height',
    ];

    /**
     * Get the logo settings (singleton pattern)
     */
    public static function getSettings()
    {
        return Cache::rememberForever('logo_settings', function () {
            return static::first() ?? new static();
        });
    }

    /**
     * Update and clear cache
     */
    public static function updateSettings(array $data)
    {
        $settings = static::first();

        if (!$settings) {
            $settings = static::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('logo_settings');
        return $settings;
    }

    /**
     * Get logo URL with FULL absolute path including domain
     *
     * @param string $type Logo type (main_logo, footer_logo, etc.)
     * @return string Full URL to logo or default image
     */
    public function getLogoUrl($type = 'main_logo')
    {
        if ($this->$type) {
            // Use url() helper to ensure full URL with domain
            // Returns: http://127.0.0.1:8000/storage/logos/...
            return url(Storage::url($this->$type));
        }

        // Return default logo if none exists
        return asset('assets/images/default-logo.png');
    }

    /**
     * Get all logo URLs
     *
     * @return array Array of all logo URLs
     */
    public function getAllLogos()
    {
        return [
            'main_logo' => $this->getLogoUrl('main_logo'),
            'footer_logo' => $this->getLogoUrl('footer_logo'),
            'favicon' => $this->getLogoUrl('favicon'),
            'lazy_banner' => $this->getLogoUrl('lazy_banner'),
            'og_image' => $this->getLogoUrl('og_image'),
        ];
    }

    /**
     * Get logo path (storage path, not URL)
     *
     * @param string $type Logo type
     * @return string|null Storage path
     */
    public function getLogoPath($type = 'main_logo')
    {
        return $this->$type;
    }

    /**
     * Check if logo exists
     *
     * @param string $type Logo type
     * @return bool
     */
    public function hasLogo($type = 'main_logo')
    {
        return !empty($this->$type) && Storage::disk('public')->exists($this->$type);
    }

    /**
     * Delete old image from storage
     *
     * @param string $field Field name (main_logo, footer_logo, etc.)
     * @return bool
     */
    public function deleteOldImage($field)
    {
        if ($this->$field && Storage::disk('public')->exists($this->$field)) {
            return Storage::disk('public')->delete($this->$field);
        }
        return false;
    }

    /**
     * Delete all logos from storage
     *
     * @return void
     */
    public function deleteAllLogos()
    {
        $fields = ['main_logo', 'footer_logo', 'favicon', 'lazy_banner', 'og_image'];

        foreach ($fields as $field) {
            $this->deleteOldImage($field);
        }
    }

    /**
     * Get logo dimensions
     *
     * @param string $type Logo type
     * @return array|null ['width' => int, 'height' => int]
     */
    public function getLogoDimensions($type = 'main_logo')
    {
        if ($this->hasLogo($type)) {
            $path = Storage::disk('public')->path($this->$type);

            if (file_exists($path)) {
                $size = @getimagesize($path);

                if ($size) {
                    return [
                        'width' => $size[0],
                        'height' => $size[1],
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Clear cache on save
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('logo_settings');
        });

        static::deleted(function ($model) {
            Cache::forget('logo_settings');
            // Delete all logo files when model is deleted
            $model->deleteAllLogos();
        });
    }
}
