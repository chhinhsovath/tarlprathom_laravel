<?php

namespace App\Helpers;

class TranslationHelper
{
    protected static $translations = [];
    protected static $loaded = false;
    
    public static function loadTranslations()
    {
        // Temporarily disable caching to force reload
        // if (self::$loaded) {
        //     return;
        // }
        
        $locale = app()->getLocale();
        $paths = [
            resource_path('lang/' . $locale . '.json'),
            base_path('lang/' . $locale . '.json'),
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                $json = file_get_contents($path);
                $decoded = json_decode($json, true);
                if ($decoded && is_array($decoded)) {
                    self::$translations[$locale] = $decoded;
                    self::$loaded = true;
                    break;
                }
            }
        }
    }
    
    public static function trans($key, $locale = null)
    {
        if (!self::$loaded) {
            self::loadTranslations();
        }
        
        $locale = $locale ?: app()->getLocale();
        
        return self::$translations[$locale][$key] ?? $key;
    }
    
    /**
     * Clear the static cache and force reload translations
     */
    public static function clearCache()
    {
        self::$translations = [];
        self::$loaded = false;
    }
}