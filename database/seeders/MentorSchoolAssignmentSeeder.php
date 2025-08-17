<?php

namespace Database\Seeders;

use App\Models\PilotSchool;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MentorSchoolAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all mentors
        $mentors = User::where('role', 'mentor')->get();

        if ($mentors->isEmpty()) {
            $this->command->info('No mentors found in the system.');

            return;
        }

        // Get all pilot schools
        $pilotSchools = PilotSchool::all();

        if ($pilotSchools->isEmpty()) {
            $this->command->error('No pilot schools found in the system.');

            return;
        }

        $this->command->info('Found '.$mentors->count().' mentors and '.$pilotSchools->count().' pilot schools.');

        foreach ($mentors as $mentor) {
            // Check if mentor already has assignments
            $existingAssignments = DB::table('mentor_school')
                ->where('user_id', $mentor->id)
                ->whereNotNull('pilot_school_id')
                ->count();

            if ($existingAssignments > 0) {
                $this->command->info('Mentor '.$mentor->email.' already has '.$existingAssignments.' pilot school assignments.');

                continue;
            }

            // Check for old school_id assignments that need to be migrated
            $oldAssignments = DB::table('mentor_school')
                ->where('user_id', $mentor->id)
                ->whereNull('pilot_school_id')
                ->get();

            if ($oldAssignments->count() > 0) {
                $this->command->info('Migrating '.$oldAssignments->count().' old assignments for mentor '.$mentor->email);

                foreach ($oldAssignments as $oldAssignment) {
                    // Try to find corresponding pilot school
                    $pilotSchool = PilotSchool::where('id', $oldAssignment->school_id)->first();

                    if ($pilotSchool) {
                        DB::table('mentor_school')
                            ->where('id', $oldAssignment->id)
                            ->update(['pilot_school_id' => $pilotSchool->id]);

                        $this->command->info('  - Migrated school ID '.$oldAssignment->school_id.' to pilot school '.$pilotSchool->school_name);
                    }
                }
            } else {
                // No existing assignments, create new ones
                // Assign mentor to schools based on their email pattern or assign default schools

                if (strpos($mentor->email, 'mentor1') !== false) {
                    // Assign first 11 schools to mentor1
                    $schoolsToAssign = $pilotSchools->take(11);
                } elseif (strpos($mentor->email, 'mentor2') !== false) {
                    // Assign next 10 schools to mentor2
                    $schoolsToAssign = $pilotSchools->skip(11)->take(10);
                } elseif (strpos($mentor->email, 'mentor3') !== false) {
                    // Assign remaining schools to mentor3
                    $schoolsToAssign = $pilotSchools->skip(21);
                } else {
                    // Default: assign 5 random schools
                    $schoolsToAssign = $pilotSchools->random(min(5, $pilotSchools->count()));
                }

                foreach ($schoolsToAssign as $school) {
                    DB::table('mentor_school')->insertOrIgnore([
                        'user_id' => $mentor->id,
                        'school_id' => $school->id, // Keep for backward compatibility
                        'pilot_school_id' => $school->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $this->command->info('Assigned '.$schoolsToAssign->count().' schools to mentor '.$mentor->email);
            }
        }

        // Verify assignments
        $this->command->info("\nVerifying mentor assignments:");
        foreach ($mentors as $mentor) {
            $assignedCount = DB::table('mentor_school')
                ->where('user_id', $mentor->id)
                ->whereNotNull('pilot_school_id')
                ->count();

            $this->command->info('  - '.$mentor->email.': '.$assignedCount.' schools assigned');
        }
    }
}
