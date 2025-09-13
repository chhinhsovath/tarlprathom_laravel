<?php

use App\Helpers\TranslationHelper;

if (!function_exists('trans_km')) {
    /**
     * Translate using our custom translator
     */
    function trans_km($key)
    {
        return TranslationHelper::trans($key);
    }
}

// Override the default __ function to use our custom translator
if (!function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    function __($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }
        
        // First try our custom translator for JSON translations
        $translated = TranslationHelper::trans($key, $locale);
        
        // If we found a translation (not the same as the key), use it
        if ($translated !== $key) {
            // Handle replacements
            if (!empty($replace)) {
                foreach ($replace as $search => $replacement) {
                    $translated = str_replace(':' . $search, $replacement, $translated);
                }
            }
            return $translated;
        }
        
        // Fall back to Laravel's translator for PHP file translations
        return trans($key, $replace, $locale);
    }
}