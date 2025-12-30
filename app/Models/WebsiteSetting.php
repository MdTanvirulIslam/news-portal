<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_title',
        'show_loader',
        'loader_type',
        'loader_image',
        'base_color',
        'footer_color',
        'copyright_color',
        'header_text_color',
        'link_color',
        'link_hover_color',
        'heading_font',
        'body_font',
        'timezone',
        'posts_per_page',
        'google_search_console',
        'google_adsense',
        'google_analytics',
        'facebook_pixel',
        'maintenance_mode',
    ];

    protected $casts = [
        'show_loader' => 'boolean',
        'maintenance_mode' => 'boolean',
        'posts_per_page' => 'integer',
    ];

    /**
     * Get the singleton settings instance
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'website_title' => config('app.name', 'Laravel'),
                'show_loader' => true,
                'loader_type' => 'spinner',
                'loader_image' => null,
                'base_color' => '#667eea',
                'footer_color' => '#2d3748',
                'copyright_color' => '#1a202c',
                'header_text_color' => '#ffffff',
                'link_color' => '#667eea',
                'link_hover_color' => '#764ba2',
                'heading_font' => 'Poppins',
                'body_font' => 'Roboto',
                'timezone' => 'UTC',
                'posts_per_page' => 10,
                'google_search_console' => null,
                'google_adsense' => null,
                'google_analytics' => null,
                'facebook_pixel' => null,
                'maintenance_mode' => false,
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
     * Get loader image URL
     */
    public function getLoaderImageUrl()
    {
        if ($this->loader_image) {
            return url(Storage::url($this->loader_image));
        }
        return null;
    }

    /**
     * Delete loader image
     */
    public function deleteLoaderImage()
    {
        if ($this->loader_image && Storage::disk('public')->exists($this->loader_image)) {
            Storage::disk('public')->delete($this->loader_image);
        }
    }

    /**
     * Check if loader image exists
     */
    public function hasLoaderImage()
    {
        return $this->loader_image && Storage::disk('public')->exists($this->loader_image);
    }

    /**
     * Get all color settings as array
     */
    public function getColors()
    {
        return [
            'base_color' => $this->base_color,
            'footer_color' => $this->footer_color,
            'copyright_color' => $this->copyright_color,
            'header_text_color' => $this->header_text_color,
            'link_color' => $this->link_color,
            'link_hover_color' => $this->link_hover_color,
        ];
    }

    /**
     * Check if maintenance mode is enabled
     */
    public function isMaintenanceMode()
    {
        return $this->maintenance_mode;
    }

    /**
     * Check if loader is enabled
     */
    public function isLoaderEnabled()
    {
        return $this->show_loader;
    }
}
