<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

echo "=== Translation Status Report ===\n\n";

// Get overall statistics
$totalTranslations = Translation::count();
$translatedCount = Translation::whereNotNull('km')
    ->where('km', '!=', '')
    ->whereRaw('km != en')
    ->where('km', 'NOT LIKE', '%[%')
    ->count();

$untranslatedCount = $totalTranslations - $translatedCount;
$overallPercentage = $totalTranslations > 0 ? round(($translatedCount / $totalTranslations) * 100, 2) : 0;

echo "Overall Statistics:\n";
echo "- Total translations: {$totalTranslations}\n";
echo "- Translated to Khmer: {$translatedCount}\n";
echo "- Remaining: {$untranslatedCount}\n";
echo "- Completion: {$overallPercentage}%\n\n";

// Get statistics by group
$groups = Translation::select('group')
    ->groupBy('group')
    ->orderBy('group')
    ->get();

echo "Statistics by Group:\n";
echo str_pad("Group", 20) . str_pad("Total", 10) . str_pad("Translated", 12) . str_pad("Remaining", 12) . "Completion\n";
echo str_repeat("-", 70) . "\n";

foreach ($groups as $group) {
    $groupTotal = Translation::where('group', $group->group)->count();
    $groupTranslated = Translation::where('group', $group->group)
        ->whereNotNull('km')
        ->where('km', '!=', '')
        ->whereRaw('km != en')
        ->where('km', 'NOT LIKE', '%[%')
        ->count();
    
    $groupRemaining = $groupTotal - $groupTranslated;
    $groupPercentage = $groupTotal > 0 ? round(($groupTranslated / $groupTotal) * 100, 2) : 0;
    
    echo str_pad($group->group, 20);
    echo str_pad($groupTotal, 10);
    echo str_pad($groupTranslated, 12);
    echo str_pad($groupRemaining, 12);
    echo "{$groupPercentage}%\n";
}

echo "\n";

// Show sample of untranslated keys
echo "Sample of untranslated keys (first 20):\n";
echo str_repeat("-", 70) . "\n";

$untranslated = Translation::where(function($q) {
        $q->whereNull('km')
          ->orWhere('km', '')
          ->orWhere('km', 'LIKE', '%[%')
          ->orWhereRaw('km = en');
    })
    ->limit(20)
    ->get();

foreach ($untranslated as $trans) {
    echo "- [{$trans->group}] {$trans->key}\n";
}

// Clear cache one more time
Translation::clearCache();
echo "\nCache cleared.\n";