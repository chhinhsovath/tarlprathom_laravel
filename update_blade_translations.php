<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\File;

class BladeTranslationUpdater
{
    private $patterns = [
        // Common UI elements
        '/\>Dashboard\</i' => '>{{ __("Dashboard") }}<',
        '/\>Home\</i' => '>{{ __("Home") }}<',
        '/\>Profile\</i' => '>{{ __("Profile") }}<',
        '/\>Settings\</i' => '>{{ __("Settings") }}<',
        '/\>Help\</i' => '>{{ __("Help") }}<',
        '/\>Logout\</i' => '>{{ __("Logout") }}<',
        '/\>Login\</i' => '>{{ __("Login") }}<',
        '/\>Register\</i' => '>{{ __("Register") }}<',
        
        // Actions
        '/\>Add\</i' => '>{{ __("Add") }}<',
        '/\>Edit\</i' => '>{{ __("Edit") }}<',
        '/\>Delete\</i' => '>{{ __("Delete") }}<',
        '/\>Save\</i' => '>{{ __("Save") }}<',
        '/\>Cancel\</i' => '>{{ __("Cancel") }}<',
        '/\>Submit\</i' => '>{{ __("Submit") }}<',
        '/\>Search\</i' => '>{{ __("Search") }}<',
        '/\>Filter\</i' => '>{{ __("Filter") }}<',
        '/\>Export\</i' => '>{{ __("Export") }}<',
        '/\>Import\</i' => '>{{ __("Import") }}<',
        '/\>Download\</i' => '>{{ __("Download") }}<',
        '/\>Upload\</i' => '>{{ __("Upload") }}<',
        '/\>Back\</i' => '>{{ __("Back") }}<',
        '/\>View\</i' => '>{{ __("View") }}<',
        '/\>Close\</i' => '>{{ __("Close") }}<',
        '/\>Create\</i' => '>{{ __("Create") }}<',
        '/\>Update\</i' => '>{{ __("Update") }}<',
        '/\>Clear\</i' => '>{{ __("Clear") }}<',
        
        // Navigation
        '/\>Users\</i' => '>{{ __("Users") }}<',
        '/\>Schools\</i' => '>{{ __("Schools") }}<',
        '/\>Students\</i' => '>{{ __("Students") }}<',
        '/\>Assessments\</i' => '>{{ __("Assessments") }}<',
        '/\>Reports\</i' => '>{{ __("Reports") }}<',
        '/\>Mentoring\</i' => '>{{ __("Mentoring") }}<',
        '/\>Resources\</i' => '>{{ __("Resources") }}<',
        '/\>Classes\</i' => '>{{ __("Classes") }}<',
        '/\>Administration\</i' => '>{{ __("Administration") }}<',
        '/\>Assessment Management\</i' => '>{{ __("Assessment Management") }}<',
        
        // Form labels
        '/\>Name\</i' => '>{{ __("Name") }}<',
        '/\>Email\</i' => '>{{ __("Email") }}<',
        '/\>Password\</i' => '>{{ __("Password") }}<',
        '/\>Confirm Password\</i' => '>{{ __("Confirm Password") }}<',
        '/\>Phone\</i' => '>{{ __("Phone") }}<',
        '/\>Role\</i' => '>{{ __("Role") }}<',
        '/\>Status\</i' => '>{{ __("Status") }}<',
        '/\>Description\</i' => '>{{ __("Description") }}<',
        '/\>Actions\</i' => '>{{ __("Actions") }}<',
        '/\>Date\</i' => '>{{ __("Date") }}<',
        '/\>School\</i' => '>{{ __("School") }}<',
        '/\>Province\</i' => '>{{ __("Province") }}<',
        '/\>District\</i' => '>{{ __("District") }}<',
        '/\>Commune\</i' => '>{{ __("Commune") }}<',
        '/\>Grade\</i' => '>{{ __("Grade") }}<',
        '/\>Subject\</i' => '>{{ __("Subject") }}<',
        '/\>Score\</i' => '>{{ __("Score") }}<',
        '/\>Level\</i' => '>{{ __("Level") }}<',
        
        // Common phrases
        '/\>All\</i' => '>{{ __("All") }}<',
        '/\>None\</i' => '>{{ __("None") }}<',
        '/\>Yes\</i' => '>{{ __("Yes") }}<',
        '/\>No\</i' => '>{{ __("No") }}<',
        '/\>Active\</i' => '>{{ __("Active") }}<',
        '/\>Inactive\</i' => '>{{ __("Inactive") }}<',
        '/\>Total\</i' => '>{{ __("Total") }}<',
        '/\>Loading\.\.\.\</i' => '>{{ __("Loading...") }}<',
        '/\>No data available\</i' => '>{{ __("No data available") }}<',
        '/\>No records found\</i' => '>{{ __("No records found") }}<',
        
        // Status messages
        '/\>Created successfully\</i' => '>{{ __("Created successfully") }}<',
        '/\>Updated successfully\</i' => '>{{ __("Updated successfully") }}<',
        '/\>Deleted successfully\</i' => '>{{ __("Deleted successfully") }}<',
        '/\>Saved successfully\</i' => '>{{ __("Saved successfully") }}<',
        
        // Placeholder attributes
        '/placeholder="Search\.\.\."/i' => 'placeholder="{{ __("Search...") }}"',
        '/placeholder="Enter ([^"]+)"/i' => 'placeholder="{{ __("Enter $1") }}"',
        '/placeholder="Select ([^"]+)"/i' => 'placeholder="{{ __("Select $1") }}"',
    ];
    
    private $filesUpdated = 0;
    private $totalReplacements = 0;
    
    public function update()
    {
        echo "Starting Blade file translation updates...\n\n";
        
        $viewPath = resource_path('views');
        $files = File::allFiles($viewPath);
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $this->updateFile($file->getPathname());
            }
        }
        
        echo "\nUpdate complete!\n";
        echo "Files updated: {$this->filesUpdated}\n";
        echo "Total replacements: {$this->totalReplacements}\n";
    }
    
    private function updateFile($filePath)
    {
        $content = File::get($filePath);
        $originalContent = $content;
        $replacements = 0;
        
        foreach ($this->patterns as $pattern => $replacement) {
            $count = 0;
            $content = preg_replace($pattern, $replacement, $content, -1, $count);
            $replacements += $count;
        }
        
        // Update specific patterns for forms
        $content = $this->updateFormLabels($content, $replacements);
        
        // Update button text
        $content = $this->updateButtonText($content, $replacements);
        
        // Update headings
        $content = $this->updateHeadings($content, $replacements);
        
        if ($content !== $originalContent) {
            File::put($filePath, $content);
            $this->filesUpdated++;
            $this->totalReplacements += $replacements;
            echo "Updated: " . str_replace(resource_path(), '', $filePath) . " ({$replacements} replacements)\n";
        }
    }
    
    private function updateFormLabels($content, &$replacements)
    {
        // Update label tags
        $patterns = [
            '/<label[^>]*>Name<\/label>/i' => '<label$1>{{ __("Name") }}</label>',
            '/<label[^>]*>Email<\/label>/i' => '<label$1>{{ __("Email") }}</label>',
            '/<label[^>]*>Password<\/label>/i' => '<label$1>{{ __("Password") }}</label>',
            '/<label[^>]*>Phone<\/label>/i' => '<label$1>{{ __("Phone") }}</label>',
            '/<label[^>]*>Role<\/label>/i' => '<label$1>{{ __("Role") }}</label>',
            '/<label[^>]*>School<\/label>/i' => '<label$1>{{ __("School") }}</label>',
            '/<label[^>]*>Province<\/label>/i' => '<label$1>{{ __("Province") }}</label>',
            '/<label[^>]*>District<\/label>/i' => '<label$1>{{ __("District") }}</label>',
            '/<label[^>]*>Grade<\/label>/i' => '<label$1>{{ __("Grade") }}</label>',
            '/<label[^>]*>Subject<\/label>/i' => '<label$1>{{ __("Subject") }}</label>',
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $count = 0;
            $content = preg_replace($pattern, $replacement, $content, -1, $count);
            $replacements += $count;
        }
        
        return $content;
    }
    
    private function updateButtonText($content, &$replacements)
    {
        // Update button text
        $patterns = [
            '/<button([^>]*)>Save<\/button>/i' => '<button$1>{{ __("Save") }}</button>',
            '/<button([^>]*)>Cancel<\/button>/i' => '<button$1>{{ __("Cancel") }}</button>',
            '/<button([^>]*)>Submit<\/button>/i' => '<button$1>{{ __("Submit") }}</button>',
            '/<button([^>]*)>Delete<\/button>/i' => '<button$1>{{ __("Delete") }}</button>',
            '/<button([^>]*)>Edit<\/button>/i' => '<button$1>{{ __("Edit") }}</button>',
            '/<button([^>]*)>Create<\/button>/i' => '<button$1>{{ __("Create") }}</button>',
            '/<button([^>]*)>Update<\/button>/i' => '<button$1>{{ __("Update") }}</button>',
            '/<button([^>]*)>Search<\/button>/i' => '<button$1>{{ __("Search") }}</button>',
            '/<button([^>]*)>Filter<\/button>/i' => '<button$1>{{ __("Filter") }}</button>',
            '/<button([^>]*)>Export<\/button>/i' => '<button$1>{{ __("Export") }}</button>',
            '/<button([^>]*)>Import<\/button>/i' => '<button$1>{{ __("Import") }}</button>',
            '/<button([^>]*)>Back<\/button>/i' => '<button$1>{{ __("Back") }}</button>',
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $count = 0;
            $content = preg_replace($pattern, $replacement, $content, -1, $count);
            $replacements += $count;
        }
        
        return $content;
    }
    
    private function updateHeadings($content, &$replacements)
    {
        // Update common headings
        $patterns = [
            '/<h1([^>]*)>Dashboard<\/h1>/i' => '<h1$1>{{ __("Dashboard") }}</h1>',
            '/<h2([^>]*)>Users<\/h2>/i' => '<h2$1>{{ __("Users") }}</h2>',
            '/<h2([^>]*)>Schools<\/h2>/i' => '<h2$1>{{ __("Schools") }}</h2>',
            '/<h2([^>]*)>Students<\/h2>/i' => '<h2$1>{{ __("Students") }}</h2>',
            '/<h2([^>]*)>Assessments<\/h2>/i' => '<h2$1>{{ __("Assessments") }}</h2>',
            '/<h2([^>]*)>Reports<\/h2>/i' => '<h2$1>{{ __("Reports") }}</h2>',
            '/<h2([^>]*)>Settings<\/h2>/i' => '<h2$1>{{ __("Settings") }}</h2>',
            '/<h3([^>]*)>Create New ([^<]+)<\/h3>/i' => '<h3$1>{{ __("Create New $2") }}</h3>',
            '/<h3([^>]*)>Edit ([^<]+)<\/h3>/i' => '<h3$1>{{ __("Edit $2") }}</h3>',
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $count = 0;
            $content = preg_replace($pattern, $replacement, $content, -1, $count);
            $replacements += $count;
        }
        
        return $content;
    }
}

// Run the updater
$updater = new BladeTranslationUpdater();
$updater->update();