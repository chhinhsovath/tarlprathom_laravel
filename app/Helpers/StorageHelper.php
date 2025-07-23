<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * Get the correct URL for a stored file, handling subdirectory installations
     *
     * @param  string|null  $path
     * @return string|null
     */
    public static function url($path)
    {
        if (! $path) {
            return null;
        }

        // If we're in production with a subdirectory
        if (app()->environment('production') && str_contains(config('app.url'), '/tarl')) {
            // Get the base storage URL
            $url = Storage::url($path);

            // If the URL doesn't already contain /tarl, add it
            if (! str_contains($url, '/tarl') && str_starts_with($url, '/storage')) {
                return '/tarl/public'.$url;
            }

            return $url;
        }

        // For local development or standard installations
        return Storage::url($path);
    }
}
