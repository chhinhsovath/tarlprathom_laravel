#!/usr/bin/env php
<?php

/**
 * Script to fix remaining pilot_school references in the codebase
 * This updates school_id to pilot_school_id where needed
 */
$basePath = __DIR__;

echo "Fixing remaining pilot_school references...\n\n";

$fixes = [
    // MentoringVisitController fixes
    [
        'file' => 'app/Http/Controllers/MentoringVisitController.php',
        'replacements' => [
            ['whereIn(\'school_id\'', 'whereIn(\'pilot_school_id\''],
            ['where(\'school_id\'', 'where(\'pilot_school_id\''],
            ['$teacher->school_id', '$teacher->pilot_school_id'],
            ['$validated[\'school_id\']', '$validated[\'pilot_school_id\']'],
            ['canAccessSchool($mentoringVisit->school_id)', 'canAccessSchool($mentoringVisit->pilot_school_id)'],
            ['\'school_id\'', '\'pilot_school_id\''],
        ],
    ],

    // StudentController fixes
    [
        'file' => 'app/Http/Controllers/StudentController.php',
        'replacements' => [
            ['$student->school_id', '$student->pilot_school_id'],
            ['$user->school_id', '$user->pilot_school_id'],
            ['$teacher->school_id', '$teacher->pilot_school_id'],
        ],
    ],

    // ReportController fixes
    [
        'file' => 'app/Http/Controllers/ReportController.php',
        'replacements' => [
            ['->school_id', '->pilot_school_id'],
            ['\'school_id\'', '\'pilot_school_id\''],
        ],
    ],

    // Update validation rules
    [
        'file' => 'app/Http/Controllers/MentoringVisitController.php',
        'replacements' => [
            ['\'school_id\' =>', '\'pilot_school_id\' =>'],
        ],
    ],
];

foreach ($fixes as $fix) {
    $filePath = $basePath.'/'.$fix['file'];

    if (! file_exists($filePath)) {
        echo "Warning: File not found - $filePath\n";

        continue;
    }

    $content = file_get_contents($filePath);
    $originalContent = $content;

    foreach ($fix['replacements'] as $replacement) {
        [$search, $replace] = $replacement;
        $count = 0;
        $content = str_replace($search, $replace, $content, $count);
        if ($count > 0) {
            echo "  Replaced '$search' with '$replace' ($count occurrences)\n";
        }
    }

    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "✓ Updated: {$fix['file']}\n\n";
    } else {
        echo "  No changes needed in: {$fix['file']}\n\n";
    }
}

echo "\n✅ Fixes completed!\n";
echo "\nNext steps:\n";
echo "1. Run database migrations to ensure pilot_school_id columns exist\n";
echo "2. Clear all caches: php artisan cache:clear && php artisan config:clear\n";
echo "3. Test the application thoroughly\n";
