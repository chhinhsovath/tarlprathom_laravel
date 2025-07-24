<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\File;

class RemainingTranslationUpdater
{
    // Files that need special attention
    private $targetFiles = [
        'users/create.blade.php',
        'users/edit.blade.php',
        'users/index.blade.php',
        'schools/create.blade.php',
        'schools/edit.blade.php',
        'schools/index.blade.php',
        'assessments/create.blade.php',
        'assessments/index.blade.php',
        'mentoring/create.blade.php',
        'mentoring/index.blade.php',
        'reports/index.blade.php',
        'auth/login.blade.php',
        'auth/register.blade.php',
        'profile/edit.blade.php',
    ];
    
    private $replacements = 0;
    
    public function update()
    {
        echo "Updating remaining Blade files with translation helpers...\n\n";
        
        foreach ($this->targetFiles as $relPath) {
            $fullPath = resource_path('views/' . $relPath);
            if (file_exists($fullPath)) {
                $this->updateFile($fullPath);
            }
        }
        
        echo "\nTotal replacements made: {$this->replacements}\n";
    }
    
    private function updateFile($filePath)
    {
        $content = File::get($filePath);
        $originalContent = $content;
        $localReplacements = 0;
        
        // Update common patterns
        $patterns = [
            // Headings and titles
            '/(<h[1-6][^>]*>)Users(<\/h[1-6]>)/i' => '$1{{ __("Users") }}$2',
            '/(<h[1-6][^>]*>)Create User(<\/h[1-6]>)/i' => '$1{{ __("Create User") }}$2',
            '/(<h[1-6][^>]*>)Edit User(<\/h[1-6]>)/i' => '$1{{ __("Edit User") }}$2',
            '/(<h[1-6][^>]*>)User Details(<\/h[1-6]>)/i' => '$1{{ __("User Details") }}$2',
            '/(<h[1-6][^>]*>)Schools(<\/h[1-6]>)/i' => '$1{{ __("Schools") }}$2',
            '/(<h[1-6][^>]*>)Create School(<\/h[1-6]>)/i' => '$1{{ __("Create School") }}$2',
            '/(<h[1-6][^>]*>)Edit School(<\/h[1-6]>)/i' => '$1{{ __("Edit School") }}$2',
            '/(<h[1-6][^>]*>)School Details(<\/h[1-6]>)/i' => '$1{{ __("School Details") }}$2',
            '/(<h[1-6][^>]*>)Assessments(<\/h[1-6]>)/i' => '$1{{ __("Assessments") }}$2',
            '/(<h[1-6][^>]*>)Create Assessment(<\/h[1-6]>)/i' => '$1{{ __("Create Assessment") }}$2',
            '/(<h[1-6][^>]*>)Mentoring Visits(<\/h[1-6]>)/i' => '$1{{ __("Mentoring Visits") }}$2',
            '/(<h[1-6][^>]*>)Create Mentoring Visit(<\/h[1-6]>)/i' => '$1{{ __("Create Mentoring Visit") }}$2',
            '/(<h[1-6][^>]*>)Reports(<\/h[1-6]>)/i' => '$1{{ __("Reports") }}$2',
            
            // Form labels without existing translations
            '/<label([^>]*)>([^{<]+)<\/label>/i' => function($matches) {
                $attrs = $matches[1];
                $text = trim($matches[2]);
                
                // Skip if already translated or contains blade syntax
                if (strpos($text, '{{') !== false || strpos($text, '@') !== false) {
                    return $matches[0];
                }
                
                // Skip if it's just whitespace
                if (empty($text)) {
                    return $matches[0];
                }
                
                return '<label' . $attrs . '>{{ __("' . $text . '") }}</label>';
            },
            
            // Table headers
            '/<th([^>]*)>([^{<]+)<\/th>/i' => function($matches) {
                $attrs = $matches[1];
                $text = trim($matches[2]);
                
                // Skip if already translated or contains blade syntax
                if (strpos($text, '{{') !== false || strpos($text, '@') !== false) {
                    return $matches[0];
                }
                
                // Skip if it's just whitespace or too short
                if (empty($text) || strlen($text) < 2) {
                    return $matches[0];
                }
                
                return '<th' . $attrs . '>{{ __("' . $text . '") }}</th>';
            },
            
            // Button text
            '/<button([^>]*)>([^{<]+)<\/button>/i' => function($matches) {
                $attrs = $matches[1];
                $text = trim($matches[2]);
                
                // Skip if already translated or contains blade syntax
                if (strpos($text, '{{') !== false || strpos($text, '@') !== false) {
                    return $matches[0];
                }
                
                // Skip if it's just whitespace
                if (empty($text)) {
                    return $matches[0];
                }
                
                return '<button' . $attrs . '>{{ __("' . $text . '") }}</button>';
            },
            
            // Placeholder attributes
            '/placeholder="([^"]+)"/i' => function($matches) {
                $text = $matches[1];
                
                // Skip if already translated
                if (strpos($text, '{{') !== false) {
                    return $matches[0];
                }
                
                return 'placeholder="{{ __(\'' . $text . '\') }}"';
            },
            
            // Common status/role options
            '/<option([^>]*)>Admin<\/option>/i' => '<option$1>{{ __("Admin") }}</option>',
            '/<option([^>]*)>Teacher<\/option>/i' => '<option$1>{{ __("Teacher") }}</option>',
            '/<option([^>]*)>Mentor<\/option>/i' => '<option$1>{{ __("Mentor") }}</option>',
            '/<option([^>]*)>Coordinator<\/option>/i' => '<option$1>{{ __("Coordinator") }}</option>',
            '/<option([^>]*)>Viewer<\/option>/i' => '<option$1>{{ __("Viewer") }}</option>',
            '/<option([^>]*)>Male<\/option>/i' => '<option$1>{{ __("Male") }}</option>',
            '/<option([^>]*)>Female<\/option>/i' => '<option$1>{{ __("Female") }}</option>',
            '/<option([^>]*)>Active<\/option>/i' => '<option$1>{{ __("Active") }}</option>',
            '/<option([^>]*)>Inactive<\/option>/i' => '<option$1>{{ __("Inactive") }}</option>',
            
            // Confirmation messages
            '/confirm\([\'"]([^\'"]+)[\'"]\)/i' => function($matches) {
                $message = $matches[1];
                
                // Skip if already translated
                if (strpos($message, '{{') !== false) {
                    return $matches[0];
                }
                
                return "confirm('{{ __(\"" . $message . "\") }}')";
            },
            
            // Alert messages
            '/alert\([\'"]([^\'"]+)[\'"]\)/i' => function($matches) {
                $message = $matches[1];
                
                // Skip if already translated
                if (strpos($message, '{{') !== false) {
                    return $matches[0];
                }
                
                return "alert('{{ __(\"" . $message . "\") }}')";
            },
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            if (is_callable($replacement)) {
                $count = 0;
                $content = preg_replace_callback($pattern, $replacement, $content, -1, $count);
                $localReplacements += $count;
            } else {
                $count = 0;
                $content = preg_replace($pattern, $replacement, $content, -1, $count);
                $localReplacements += $count;
            }
        }
        
        if ($content !== $originalContent) {
            File::put($filePath, $content);
            $this->replacements += $localReplacements;
            echo "Updated: " . basename(dirname($filePath)) . '/' . basename($filePath) . " ({$localReplacements} replacements)\n";
        }
    }
}

// Run the updater
$updater = new RemainingTranslationUpdater();
$updater->update();