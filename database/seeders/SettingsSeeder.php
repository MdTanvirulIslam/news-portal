<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Carbon\Carbon;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'News Portal',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Website name',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'site_title',
                'value' => 'Latest News & Updates',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Website tagline/title',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'site_description',
                'value' => 'Your trusted source for breaking news and in-depth analysis',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Website description',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'base_color',
                'value' => '#4361ee',
                'type' => 'color',
                'group' => 'general',
                'description' => 'Primary brand color',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'secondary_color',
                'value' => '#3a0ca3',
                'type' => 'color',
                'group' => 'general',
                'description' => 'Secondary brand color',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@newsportal.com',
                'type' => 'email',
                'group' => 'general',
                'description' => 'Contact email address',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'contact_phone',
                'value' => '+880 1234 567890',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Contact phone number',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'items_per_page',
                'value' => '12',
                'type' => 'number',
                'group' => 'general',
                'description' => 'Number of items per page',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Logo Settings
            [
                'key' => 'logo',
                'value' => 'logo.png',
                'type' => 'image',
                'group' => 'logo',
                'description' => 'Main website logo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'footer_logo',
                'value' => 'footer-logo.png',
                'type' => 'image',
                'group' => 'logo',
                'description' => 'Footer logo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'favicon',
                'value' => 'favicon.ico',
                'type' => 'image',
                'group' => 'logo',
                'description' => 'Website favicon',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'banner_ad_top',
                'value' => '',
                'type' => 'image',
                'group' => 'logo',
                'description' => 'Top banner advertisement',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'banner_ad_sidebar',
                'value' => '',
                'type' => 'image',
                'group' => 'logo',
                'description' => 'Sidebar banner advertisement',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // SEO Settings
            [
                'key' => 'meta_title',
                'value' => 'News Portal - Latest News & Updates',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Default meta title',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'meta_description',
                'value' => 'Get the latest breaking news, analysis, and updates from around the world',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Default meta description',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'news, breaking news, latest news, world news, local news',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Default meta keywords',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'google_analytics',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Google Analytics tracking ID (e.g., G-XXXXXXXXXX)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'google_adsense',
                'value' => '',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Google AdSense code',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'facebook_pixel',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Facebook Pixel ID',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Social Media Settings
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/newsportal',
                'type' => 'url',
                'group' => 'social',
                'description' => 'Facebook page URL',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/newsportal',
                'type' => 'url',
                'group' => 'social',
                'description' => 'Twitter/X profile URL',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/newsportal',
                'type' => 'url',
                'group' => 'social',
                'description' => 'Instagram profile URL',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'youtube_url',
                'value' => 'https://youtube.com/@newsportal',
                'type' => 'url',
                'group' => 'social',
                'description' => 'YouTube channel URL',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'linkedin_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'description' => 'LinkedIn profile URL',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'whatsapp_number',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'description' => 'WhatsApp number with country code',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // SMTP Email Settings
            [
                'key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'type' => 'text',
                'group' => 'smtp',
                'description' => 'SMTP server host',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'smtp_port',
                'value' => '587',
                'type' => 'number',
                'group' => 'smtp',
                'description' => 'SMTP server port',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'smtp_username',
                'value' => '',
                'type' => 'text',
                'group' => 'smtp',
                'description' => 'SMTP username/email',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'smtp_password',
                'value' => '',
                'type' => 'text',
                'group' => 'smtp',
                'description' => 'SMTP password',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'smtp_encryption',
                'value' => 'tls',
                'type' => 'text',
                'group' => 'smtp',
                'description' => 'SMTP encryption (tls/ssl)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'smtp_from_email',
                'value' => 'noreply@newsportal.com',
                'type' => 'email',
                'group' => 'smtp',
                'description' => 'From email address',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'smtp_from_name',
                'value' => 'News Portal',
                'type' => 'text',
                'group' => 'smtp',
                'description' => 'From name',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert all settings
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Settings seeded successfully!');
    }
}
