<?php

namespace App\Translation;

use App\Models\Translation;
use Illuminate\Translation\FileLoader;

class DatabaseLoader extends FileLoader
{
    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string|null  $namespace
     */
    public function load($locale, $group, $namespace = null): array
    {
        if ($namespace !== null && $namespace !== '*') {
            return parent::load($locale, $group, $namespace);
        }

        // For JSON translations (when $group is '*')
        if ($group === '*') {
            // Load all translations from database
            return Translation::getTranslations($locale);
        }

        // For other groups, just use parent method (file-based)
        return parent::load($locale, $group, $namespace);
    }
}
