<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PilotSchool;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Find mentor1
$mentor = User::where('email', 'mentor1@prathaminternational.org')->first();

if (! $mentor) {
    echo "Mentor not found!\n";
    exit(1);
}

// Get already assigned school IDs
$assignedSchoolIds = DB::table('mentor_school')
    ->where('user_id', $mentor->id)
    ->whereNotNull('pilot_school_id')
    ->pluck('pilot_school_id')
    ->toArray();

echo 'Mentor currently has '.count($assignedSchoolIds)." schools assigned.\n";

// Get an unassigned school
$unassignedSchool = PilotSchool::whereNotIn('id', $assignedSchoolIds)->first();

if (! $unassignedSchool) {
    echo "No unassigned schools available!\n";
    exit(1);
}

// Check if there's a matching record in the schools table
$schoolRecord = DB::table('schools')->where('id', $unassignedSchool->id)->first();
if (! $schoolRecord) {
    // If no matching school record, we need to use a different approach
    // Find any valid school_id from the schools table (just for the FK constraint)
    $validSchoolId = DB::table('schools')->first()->id ?? 1;

    DB::table('mentor_school')->insert([
        'user_id' => $mentor->id,
        'school_id' => $validSchoolId, // Use a valid school_id for the FK
        'pilot_school_id' => $unassignedSchool->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
} else {
    DB::table('mentor_school')->insert([
        'user_id' => $mentor->id,
        'school_id' => $schoolRecord->id,
        'pilot_school_id' => $unassignedSchool->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

echo 'Assigned school: '.$unassignedSchool->school_name.' (ID: '.$unassignedSchool->id.") to mentor.\n";

// Verify
$newCount = DB::table('mentor_school')
    ->where('user_id', $mentor->id)
    ->whereNotNull('pilot_school_id')
    ->count();

echo 'Mentor now has '.$newCount." schools assigned.\n";

// List all assigned schools
$assignedSchools = DB::table('mentor_school')
    ->join('pilot_schools', 'mentor_school.pilot_school_id', '=', 'pilot_schools.id')
    ->where('mentor_school.user_id', $mentor->id)
    ->select('pilot_schools.school_name', 'pilot_schools.id')
    ->get();

echo "\nAssigned schools:\n";
foreach ($assignedSchools as $school) {
    echo '  - '.$school->school_name.' (ID: '.$school->id.")\n";
}
