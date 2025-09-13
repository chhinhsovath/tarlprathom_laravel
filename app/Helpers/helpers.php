<?php

use App\Models\Translation;

if (! function_exists('trans_db')) {
    /**
     * Translate the given key from database.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    function trans_db($key, $replace = [], $locale = null)
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }

        // First, try our custom JSON translations
        if (function_exists('trans_km')) {
            $jsonTranslation = trans_km($key);
            if ($jsonTranslation !== $key) {
                // Apply replacements if needed
                foreach ($replace as $placeholder => $value) {
                    $jsonTranslation = str_replace(':'.$placeholder, $value, $jsonTranslation);
                }
                return $jsonTranslation;
            }
        }

        // Get translation from database
        $translation = Translation::getTranslation($key, $locale);

        // If translation not found and we're not in English, try English as fallback
        if ($translation === $key && $locale !== 'en') {
            $translation = Translation::getTranslation($key, 'en');
        }

        // If still not found, try Laravel's translation system as final fallback
        if ($translation === $key) {
            $laravelTranslation = trans($key, $replace, $locale);
            // Ensure we don't get an array back from Laravel's trans()
            if (! is_array($laravelTranslation) && $laravelTranslation !== $key) {
                $translation = $laravelTranslation;
            }
        }

        // Ensure translation is always a string
        if (is_array($translation)) {
            // If it's an array, return the key as fallback
            $translation = $key;
        }

        // Replace placeholders
        foreach ($replace as $placeholder => $value) {
            $translation = str_replace(':'.$placeholder, $value, $translation);
        }

        return $translation;
    }
}

if (! function_exists('setLocale')) {
    /**
     * Set the application locale
     *
     * @param  string  $locale
     * @return void
     */
    function setLocale($locale)
    {
        if (in_array($locale, ['km', 'en'])) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }
    }
}

if (! function_exists('getLocale')) {
    /**
     * Get the current application locale
     *
     * @return string
     */
    function getLocale()
    {
        return session('locale', config('app.locale', 'km'));
    }
}
