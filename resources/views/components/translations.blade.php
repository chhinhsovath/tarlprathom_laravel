@php
// Translation helper component - Include this in any view that needs translations
if (!function_exists('trans_km')) {
    function trans_km($key) {
        static $translations = null;
        
        if ($translations === null) {
            $locale = app()->getLocale();
            
            // Try multiple paths
            $paths = [
                resource_path('lang/' . $locale . '.json'),
                base_path('lang/' . $locale . '.json'),
                base_path('resources/lang/' . $locale . '.json'),
            ];
            
            foreach ($paths as $jsonPath) {
                if (file_exists($jsonPath)) {
                    $translations = json_decode(file_get_contents($jsonPath), true);
                    if ($translations) {
                        break;
                    }
                }
            }
            
            // Fallback to empty array if no translations found
            if (!$translations) {
                $translations = [];
            }
        }
        
        // Return translation or key if not found
        return isset($translations[$key]) ? $translations[$key] : $key;
    }
}

// Also create a simpler t() function alias
if (!function_exists('t')) {
    function t($key) {
        return trans_km($key);
    }
}
@endphp