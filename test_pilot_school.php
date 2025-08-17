<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing PilotSchool Model Relationships\n";
echo "========================================\n\n";

// Test 1: Check if model loads
echo "1. Loading PilotSchool model...\n";
$school = App\Models\PilotSchool::first();
if (! $school) {
    exit("No schools found in database\n");
}
echo "   ✓ School loaded: {$school->school_name}\n\n";

// Test 2: Check if methods exist
echo "2. Checking if relationship methods exist...\n";
$methods = ['users', 'students', 'teachers', 'classes', 'assignedMentors', 'mentoringVisits', 'assessments'];
foreach ($methods as $method) {
    $exists = method_exists($school, $method);
    echo "   - {$method}(): ".($exists ? '✓ EXISTS' : '✗ MISSING')."\n";
}
echo "\n";

// Test 3: Test each relationship
echo "3. Testing relationship queries...\n";
foreach ($methods as $method) {
    if (method_exists($school, $method)) {
        try {
            $count = $school->$method()->count();
            echo "   - {$method}(): {$count} records\n";
        } catch (Exception $e) {
            echo "   - {$method}(): ERROR - ".$e->getMessage()."\n";
        }
    }
}
echo "\n";

// Test 4: Test withCount
echo "4. Testing withCount...\n";
try {
    $result = App\Models\PilotSchool::withCount(['users', 'students'])->first();
    echo "   ✓ withCount worked!\n";
    echo "   - users_count: {$result->users_count}\n";
    echo "   - students_count: {$result->students_count}\n";
} catch (Exception $e) {
    echo '   ✗ withCount failed: '.$e->getMessage()."\n";
}

echo "\n✅ All tests completed!\n";
