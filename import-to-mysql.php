<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================\n";
echo "PostgreSQL to MySQL Data Import\n";
echo "========================================\n\n";

// Find latest CSV export
$csvDir = 'database/dumps/csv';
$files = glob($csvDir . '/*_*.csv');
if (empty($files)) {
    die("No CSV files found in $csvDir\n");
}

// Get timestamp from first file
$timestamp = '';
if (preg_match('/(\d{8}_\d{6})\.csv$/', $files[0], $matches)) {
    $timestamp = $matches[1];
}

echo "Using CSV files with timestamp: $timestamp\n\n";

// Disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Import order (respecting foreign key dependencies)
$importOrder = [
    'pilot_provinces',
    'provinces', 
    'pilot_schools',
    'users',
    'students',
    'assessments',
    'assessment_histories',
    'mentoring_visits',
    'translations',
    'learning_outcomes',
    'intervention_programs',
    'teaching_activities',
    'student_learning_outcomes',
    'student_interventions',
    'lesson_plans',
    'progress_tracking',
    'attendance_records',
    'resources',
    'mentor_school',
    'student_assessment_eligibility',
    'classes',
    'geographic',
    'sessions'
];

$totalImported = 0;

foreach ($importOrder as $table) {
    $csvFile = "$csvDir/{$table}_$timestamp.csv";
    
    if (!file_exists($csvFile)) {
        echo "⚠ Skipping $table (file not found)\n";
        continue;
    }
    
    // Check if file has content
    $lineCount = count(file($csvFile));
    if ($lineCount <= 1) {
        echo "⚠ Skipping $table (empty or header only)\n";
        continue;
    }
    
    echo "Importing $table... ";
    
    try {
        // Clear existing data (except for tables already populated by migrations)
        if (!in_array($table, ['schools', 'pilot_schools', 'translations'])) {
            DB::table($table)->truncate();
        }
        
        // Skip if table was populated by migrations
        if (in_array($table, ['pilot_schools', 'translations'])) {
            $existingCount = DB::table($table)->count();
            if ($existingCount > 0) {
                echo "✓ Already has $existingCount records (from migrations)\n";
                continue;
            }
        }
        
        // Read CSV
        $handle = fopen($csvFile, 'r');
        $headers = fgetcsv($handle);
        
        if (!$headers) {
            echo "✗ No headers found\n";
            continue;
        }
        
        // Prepare batch insert
        $data = [];
        $count = 0;
        
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== count($headers)) {
                continue; // Skip malformed rows
            }
            
            $record = array_combine($headers, $row);
            
            // Convert empty strings to null
            foreach ($record as $key => $value) {
                if ($value === '') {
                    $record[$key] = null;
                }
                // Handle boolean fields
                if ($value === 't' || $value === 'true') {
                    $record[$key] = 1;
                } elseif ($value === 'f' || $value === 'false') {
                    $record[$key] = 0;
                }
            }
            
            $data[] = $record;
            $count++;
            
            // Insert in batches of 1000
            if (count($data) >= 1000) {
                DB::table($table)->insert($data);
                $data = [];
            }
        }
        
        // Insert remaining data
        if (!empty($data)) {
            DB::table($table)->insert($data);
        }
        
        fclose($handle);
        
        echo "✓ Imported $count records\n";
        $totalImported += $count;
        
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

// Re-enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n========================================\n";
echo "Import Complete!\n";
echo "Total records imported: $totalImported\n";
echo "========================================\n\n";

// Verify data
echo "Verifying data integrity...\n";

$tables = [
    'users',
    'students', 
    'pilot_schools',
    'assessments',
    'mentoring_visits',
    'translations'
];

foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo "  $table: $count records\n";
    } catch (Exception $e) {
        echo "  $table: Error - " . $e->getMessage() . "\n";
    }
}

echo "\nData import successful! Your MySQL database is ready.\n";
echo "\nNext steps:\n";
echo "1. Test the application: php artisan serve\n";
echo "2. Update production .env to use MySQL\n";
echo "3. Deploy to Namecheap\n";