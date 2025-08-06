<?php

use App\Models\Translation;

if (!function_exists('trans_db')) {
    /**
     * Translate the given key from database.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function trans_db($key, $replace = [], $locale = null)
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }

        // Get translation from database
        $translation = Translation::getTranslation($key, $locale);

        // Replace placeholders
        foreach ($replace as $placeholder => $value) {
            $translation = str_replace(':' . $placeholder, $value, $translation);
        }

        return $translation;
    }
}

if (!function_exists('setLocale')) {
    /**
     * Set the application locale
     *
     * @param string $locale
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

if (!function_exists('getLocale')) {
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