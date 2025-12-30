<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    // Static method to get setting value
    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    // Static method to set setting value
    public static function set(string $key, $value, string $type = 'text', string $group = 'general'): void
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );

        Cache::forget("setting.{$key}");
        Cache::forever("setting.{$key}", $value);
    }

    // Clear all settings cache
    public static function clearCache(): void
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("setting.{$setting->key}");
        }
    }

    // Get all settings by group
    public static function getByGroup(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }
}
