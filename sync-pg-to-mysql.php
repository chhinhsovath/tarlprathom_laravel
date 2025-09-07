<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "==========================================\n";
echo "PostgreSQL to MySQL Complete Data Sync\n";
echo "==========================================\n\n";

// Configure PostgreSQL connection
config(['database.connections.pgsql_source' => [
    'driver' => 'pgsql',
    'host' => '157.10.73.52',
    'port' => '5432',
    'database' => 'tarl_prathom',
    'username' => 'admin',
    'password' => 'P@ssw0rd',
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'schema' => 'public',
    'sslmode' => 'prefer',
]]);

// Configure MySQL connection (already configured as default)
$mysqlConnection = DB::connection('mysql');
$pgConnection = DB::connection('pgsql_source');

// Tables to sync (in order to respect foreign keys)
$tables = [
    'provinces',
    'pilot_provinces',
    'geographic',
    'schools',
    'pilot_schools',
    'users',
    'classes',
    'students',
    'assessments',
    'assessment_histories',
    'mentoring_visits',
    'translations',
    'sessions',
    'mentor_school',
    'student_assessment_eligibility',
    'resources',
    'attendance_records',
    'learning_outcomes',
    'student_learning_outcomes',
    'intervention_programs',
    'student_interventions',
    'teaching_activities',
    'lesson_plans',
    'progress_tracking',
];

// Disable foreign key checks
$mysqlConnection->statement('SET FOREIGN_KEY_CHECKS=0');

$totalSynced = 0;

foreach ($tables as $table) {
    echo "Processing $table...\n";
    
    try {
        // Get count from PostgreSQL
        $pgCount = $pgConnection->table($table)->count();
        
        if ($pgCount == 0) {
            echo "  ⚠ No data in PostgreSQL table\n";
            continue;
        }
        
        // Clear MySQL table
        $mysqlConnection->table($table)->truncate();
        
        // Process in chunks to avoid memory issues
        $chunkSize = 500;
        $offset = 0;
        $insertedCount = 0;
        
        while ($offset < $pgCount) {
            // Get chunk from PostgreSQL
            $pgData = $pgConnection->table($table)
                ->offset($offset)
                ->limit($chunkSize)
                ->get();
            
            if ($pgData->isEmpty()) {
                break;
            }
            
            // Convert data to array and handle boolean/null values
            $dataArray = json_decode(json_encode($pgData), true);
            
            // Process data for MySQL compatibility
            $processedData = array_map(function($row) {
                foreach ($row as $key => $value) {
                    // Convert PostgreSQL booleans
                    if ($value === 't' || $value === true) {
                        $row[$key] = 1;
                    } elseif ($value === 'f' || $value === false) {
                        $row[$key] = 0;
                    }
                    // Handle null values
                    if ($value === '') {
                        $row[$key] = null;
                    }
                }
                return $row;
            }, $dataArray);
            
            // Insert batch
            $mysqlConnection->table($table)->insert($processedData);
            $insertedCount += count($processedData);
            
            // Show progress for large tables
            if ($pgCount > 5000) {
                echo "    Processed: $insertedCount / $pgCount\n";
            }
            
            $offset += $chunkSize;
        }
        
        // Verify
        $mysqlCount = $mysqlConnection->table($table)->count();
        
        if ($pgCount == $mysqlCount) {
            echo "  ✓ Synced: $mysqlCount records\n";
            $totalSynced += $mysqlCount;
        } else {
            echo "  ✗ Mismatch: PostgreSQL=$pgCount, MySQL=$mysqlCount\n";
        }
        
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
        
        // Try to get more details
        if (strpos($e->getMessage(), 'exist') !== false) {
            echo "    Table might not exist in one of the databases\n";
        }
    }
}

// Re-enable foreign key checks
$mysqlConnection->statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n==========================================\n";
echo "Verification Summary\n";
echo "==========================================\n\n";

// Final verification for key tables
$keyTables = [
    'users',
    'students',
    'pilot_schools',
    'schools',
    'assessments',
    'assessment_histories',
    'mentoring_visits',
    'translations',
    'learning_outcomes',
    'intervention_programs',
    'teaching_activities'
];

$allMatch = true;

foreach ($keyTables as $table) {
    try {
        $pgCount = $pgConnection->table($table)->count();
        $mysqlCount = $mysqlConnection->table($table)->count();
        
        if ($pgCount == $mysqlCount) {
            echo sprintf("%-25s PostgreSQL: %6d | MySQL: %6d ✓\n", $table . ":", $pgCount, $mysqlCount);
        } else {
            echo sprintf("%-25s PostgreSQL: %6d | MySQL: %6d ✗\n", $table . ":", $pgCount, $mysqlCount);
            $allMatch = false;
        }
    } catch (Exception $e) {
        echo sprintf("%-25s Error checking table\n", $table . ":");
    }
}

echo "\n==========================================\n";
if ($allMatch) {
    echo "✓ SUCCESS: All tables perfectly synced!\n";
    echo "Total records synced: $totalSynced\n";
} else {
    echo "⚠ WARNING: Some tables have mismatched counts\n";
    echo "Please check the tables marked with ✗\n";
}
echo "==========================================\n\n";

// Special check for translations
$translationCount = $mysqlConnection->table('translations')->count();
if ($translationCount == 0) {
    echo "⚠ ALERT: Translations table is empty!\n";
    echo "Attempting to restore translations from PostgreSQL...\n";
    
    // Force sync translations
    $translations = $pgConnection->table('translations')->get();
    if ($translations->count() > 0) {
        $mysqlConnection->table('translations')->truncate();
        foreach ($translations as $trans) {
            $data = (array)$trans;
            // Convert booleans
            if (isset($data['is_active'])) {
                $data['is_active'] = $data['is_active'] === 't' || $data['is_active'] === true ? 1 : 0;
            }
            $mysqlConnection->table('translations')->insert($data);
        }
        echo "✓ Restored " . $translations->count() . " translations\n";
    }
}

echo "\nSync complete!\n";