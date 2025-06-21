<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'description'
    ];

    protected $casts = [
        'value' => 'array'
    ];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $type = 'string', $category = 'general', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'category' => $category,
                'description' => $description
            ]
        );
    }

    public static function getLayoutSettings()
    {
        return static::where('category', 'layout')->get()->pluck('value', 'key');
    }

    public static function getColorSettings()
    {
        return static::where('category', 'colors')->get()->pluck('value', 'key');
    }
}
