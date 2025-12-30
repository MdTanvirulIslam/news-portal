<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_enabled',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_encryption',
        'mail_username',
        'mail_password',
        'mail_from_address',
        'mail_from_name',
        'mail_reply_to',
        'mail_reply_to_name',
        'mail_timeout',
    ];

    protected $casts = [
        'mail_enabled' => 'boolean',
        'mail_port' => 'integer',
        'mail_timeout' => 'integer',
    ];

    protected $hidden = [
        'mail_password',
    ];

    /**
     * Get the singleton settings instance
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'mail_enabled' => false,
                'mail_driver' => 'smtp',
                'mail_host' => 'smtp.gmail.com',
                'mail_port' => 587,
                'mail_encryption' => 'tls',
                'mail_username' => '',
                'mail_password' => '',
                'mail_from_address' => 'noreply@example.com',
                'mail_from_name' => config('app.name', 'Laravel'),
                'mail_reply_to' => 'support@example.com',
                'mail_reply_to_name' => 'Support Team',
                'mail_timeout' => 30,
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
     * Check if email is enabled
     */
    public function isEnabled()
    {
        return $this->mail_enabled;
    }

    /**
     * Get SMTP configuration as array
     */
    public function getSmtpConfig()
    {
        return [
            'driver' => $this->mail_driver,
            'host' => $this->mail_host,
            'port' => $this->mail_port,
            'encryption' => $this->mail_encryption,
            'username' => $this->mail_username,
            'password' => $this->mail_password,
            'timeout' => $this->mail_timeout,
        ];
    }

    /**
     * Get from configuration
     */
    public function getFromConfig()
    {
        return [
            'address' => $this->mail_from_address,
            'name' => $this->mail_from_name,
        ];
    }

    /**
     * Get reply-to configuration
     */
    public function getReplyToConfig()
    {
        return [
            'address' => $this->mail_reply_to,
            'name' => $this->mail_reply_to_name,
        ];
    }
}
