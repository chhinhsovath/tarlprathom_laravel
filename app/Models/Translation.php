<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'km',
        'en',
        'group',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all translations for a specific locale
     */
    public static function getTranslations($locale = 'km')
    {
        return Cache::remember("translations.{$locale}", 3600, function () use ($locale) {
            return self::where('is_active', true)
                ->pluck($locale, 'key')
                ->filter()
                ->toArray();
        });
    }

    /**
     * Get translation by key and locale
     */
    public static function getTranslation($key, $locale = 'km')
    {
        $translations = self::getTranslations($locale);
        return $translations[$key] ?? $key;
    }

    /**
     * Clear translation cache
     */
    public static function clearCache()
    {
        Cache::forget('translations.km');
        Cache::forget('translations.en');
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when translations are updated
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }

    /**
     * Get available groups
     */
    public static function getGroups()
    {
        return self::distinct('group')
            ->orderBy('group')
            ->pluck('group')
            ->toArray();
    }

    /**
     * Search translations
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('key', 'like', "%{$search}%")
                ->orWhere('km', 'like', "%{$search}%")
                ->orWhere('en', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Filter by group
     */
    public function scopeByGroup($query, $group)
    {
        if ($group && $group !== 'all') {
            return $query->where('group', $group);
        }
        return $query;
    }
}