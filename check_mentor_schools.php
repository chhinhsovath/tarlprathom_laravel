<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Assessment;
use App\Models\PilotSchool;
use App\Models\Student;
use App\Models\User;

// Find mentor1
$mentor = User::where('email', 'mentor1@prathaminternational.org')->first();

if (! $mentor) {
    echo "Mentor not found!\n";
    exit(1);
}

echo 'Checking mentor: '.$mentor->email."\n";
echo 'Role: '.$mentor->role."\n\n";

// Check using the relationship
$assignedSchools = $mentor->assignedPilotSchools()->get();
echo 'Schools assigned via relationship: '.$assignedSchools->count()."\n";

foreach ($assignedSchools as $school) {
    echo '  - '.$school->school_name.' (ID: '.$school->id.")\n";

    // Check if there are students in this school
    $studentCount = Student::where('pilot_school_id', $school->id)->count();
    echo '    Students: '.$studentCount."\n";

    // Check assessments for baseline
    $baselineCount = Assessment::whereHas('student', function ($q) use ($school) {
        $q->where('pilot_school_id', $school->id);
    })->where('cycle', 'baseline')->count();

    echo '    Baseline assessments: '.$baselineCount."\n";
}

// Check using getAccessibleSchoolIds method
$accessibleIds = $mentor->getAccessibleSchoolIds();
echo "\nAccessible School IDs from method: ".count($accessibleIds)."\n";
echo 'IDs: '.implode(', ', $accessibleIds)."\n";

// Get pilot schools for these IDs
$accessibleSchools = PilotSchool::whereIn('id', $accessibleIds)->get();
echo "\nAccessible Schools:\n";
foreach ($accessibleSchools as $school) {
    echo '  - '.$school->school_name.' (ID: '.$school->id.")\n";
}
