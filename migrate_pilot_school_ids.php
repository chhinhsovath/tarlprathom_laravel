<?php

use App\Models\PilotSchool;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting migration of pilot_school_id values...\n\n";

// Get all pilot schools
$pilotSchools = PilotSchool::all();

if ($pilotSchools->count() == 0) {
    echo "No pilot schools found. Exiting.\n";
    exit;
}

// For simplicity, we'll map by matching school names or use the first pilot school
$defaultPilotSchool = $pilotSchools->first();

echo "Using default pilot school: {$defaultPilotSchool->school_name} (ID: {$defaultPilotSchool->id})\n\n";

// Update users table
echo "Updating users table...\n";
$usersUpdated = DB::table('users')
    ->whereNull('pilot_school_id')
    ->update(['pilot_school_id' => $defaultPilotSchool->id]);
echo "Updated $usersUpdated users\n";

// Update students table
echo "\nUpdating students table...\n";
$studentsUpdated = DB::table('students')
    ->whereNull('pilot_school_id')
    ->update(['pilot_school_id' => $defaultPilotSchool->id]);
echo "Updated $studentsUpdated students\n";

// Update classes table
echo "\nUpdating classes table...\n";
$classesUpdated = DB::table('classes')
    ->whereNull('pilot_school_id')
    ->update(['pilot_school_id' => $defaultPilotSchool->id]);
echo "Updated $classesUpdated classes\n";

// Update mentoring_visits table
echo "\nUpdating mentoring_visits table...\n";
$visitsUpdated = DB::table('mentoring_visits')
    ->whereNull('pilot_school_id')
    ->update(['pilot_school_id' => $defaultPilotSchool->id]);
echo "Updated $visitsUpdated mentoring visits\n";

// For mentors, distribute them across multiple schools
echo "\nDistributing mentors across schools...\n";
$mentors = DB::table('users')->where('role', 'mentor')->get();
$schoolIndex = 0;

foreach ($mentors as $mentor) {
    // Assign mentor to a school in round-robin fashion
    $assignedSchool = $pilotSchools[$schoolIndex % $pilotSchools->count()];

    DB::table('users')
        ->where('id', $mentor->id)
        ->update(['pilot_school_id' => $assignedSchool->id]);

    echo "Assigned mentor {$mentor->name} to school {$assignedSchool->school_name}\n";

    $schoolIndex++;
}

echo "\n✅ Migration completed successfully!\n";
