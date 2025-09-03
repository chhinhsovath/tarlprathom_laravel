<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->addPilotSchoolColumns();
        $this->migrateDataToPilotSchools();
        $this->updateConstraintsAndIndexes();
        $this->cleanupLegacyData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be fully reversed as it cleans up data
        echo "This migration cannot be fully reversed as it removes legacy data.\n";
        
        // We can at least remove the pilot_school_id columns
        $tables = ['students', 'mentoring_visits', 'users', 'assessments'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'pilot_school_id')) {
                        $table->dropForeign(['pilot_school_id']);
                        $table->dropColumn('pilot_school_id');
                    }
                });
            }
        }
    }

    /**
     * Add pilot_school_id columns to relevant tables
     */
    private function addPilotSchoolColumns(): void
    {
        echo "Adding pilot_school_id columns...\n";
        
        // Students table
        if (Schema::hasTable('students') && !Schema::hasColumn('students', 'pilot_school_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->unsignedBigInteger('pilot_school_id')->nullable()->after('school_id');
                $table->foreign('pilot_school_id')->references('id')->on('pilot_schools')->onDelete('cascade');
                $table->index('pilot_school_id');
            });
        }

        // Mentoring visits table
        if (Schema::hasTable('mentoring_visits') && !Schema::hasColumn('mentoring_visits', 'pilot_school_id')) {
            Schema::table('mentoring_visits', function (Blueprint $table) {
                $table->unsignedBigInteger('pilot_school_id')->nullable()->after('school_id');
                $table->foreign('pilot_school_id')->references('id')->on('pilot_schools')->onDelete('cascade');
                $table->index('pilot_school_id');
            });
        }

        // Users table (for teachers/mentors assigned to schools)
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'pilot_school_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('pilot_school_id')->nullable()->after('school_id');
                $table->foreign('pilot_school_id')->references('id')->on('pilot_schools')->onDelete('set null');
                $table->index('pilot_school_id');
            });
        }

        // Assessments table
        if (Schema::hasTable('assessments') && !Schema::hasColumn('assessments', 'pilot_school_id')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->unsignedBigInteger('pilot_school_id')->nullable()->after('school_id');
                $table->foreign('pilot_school_id')->references('id')->on('pilot_schools')->onDelete('cascade');
                $table->index('pilot_school_id');
            });
        }

        // Update mentor_school pivot table
        if (Schema::hasTable('mentor_school') && !Schema::hasColumn('mentor_school', 'pilot_school_id')) {
            Schema::table('mentor_school', function (Blueprint $table) {
                $table->unsignedBigInteger('pilot_school_id')->nullable()->after('school_id');
                $table->foreign('pilot_school_id')->references('id')->on('pilot_schools')->onDelete('cascade');
                $table->index('pilot_school_id');
            });
        }
    }

    /**
     * Migrate existing data to use pilot schools
     */
    private function migrateDataToPilotSchools(): void
    {
        echo "Migrating data to pilot schools...\n";

        // Update students - map by student_code to pilot school
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("
                UPDATE students 
                SET pilot_school_id = ps.id
                FROM pilot_schools ps 
                WHERE students.student_code LIKE ps.school_code || '%'
            ");
        } else {
            DB::statement("
                UPDATE students s 
                JOIN pilot_schools ps ON s.student_code LIKE CONCAT(ps.school_code, '%')
                SET s.pilot_school_id = ps.id
            ");
        }
        echo "Updated students with pilot_school_id\n";

        // Update mentoring visits - we'll assign them to pilot schools based on user assignments
        $mentoringVisits = DB::table('mentoring_visits')->whereNull('pilot_school_id')->get();
        foreach ($mentoringVisits as $visit) {
            // Try to find pilot school from visit data or assign to first pilot school as default
            $pilotSchoolId = DB::table('pilot_schools')->first()->id ?? 1;
            DB::table('mentoring_visits')
                ->where('id', $visit->id)
                ->update(['pilot_school_id' => $pilotSchoolId]);
        }
        echo "Updated mentoring visits with pilot_school_id\n";

        // Update users (teachers/mentors) - assign them to pilot schools
        $users = DB::table('users')->whereNotNull('school_id')->get();
        foreach ($users as $user) {
            // Assign users to first pilot school as default (can be updated manually later)
            $pilotSchoolId = DB::table('pilot_schools')->first()->id ?? 1;
            DB::table('users')
                ->where('id', $user->id)
                ->update(['pilot_school_id' => $pilotSchoolId]);
        }
        echo "Updated users with pilot_school_id\n";

        // Update mentor_school pivot table
        $mentorSchools = DB::table('mentor_school')->get();
        foreach ($mentorSchools as $ms) {
            $pilotSchoolId = DB::table('pilot_schools')->first()->id ?? 1;
            DB::table('mentor_school')
                ->where('id', $ms->id)
                ->update(['pilot_school_id' => $pilotSchoolId]);
        }
        echo "Updated mentor_school with pilot_school_id\n";
    }

    /**
     * Update constraints and indexes to focus on pilot schools
     */
    private function updateConstraintsAndIndexes(): void
    {
        echo "Updating constraints to use pilot schools...\n";
        
        // Update the unique constraint on students to use pilot_school_id
        if (Schema::hasTable('students')) {
            try {
                Schema::table('students', function (Blueprint $table) {
                    $table->dropUnique('unique_student_name_school_class');
                });
            } catch (Exception $e) {
                // Constraint might not exist
            }
            
            Schema::table('students', function (Blueprint $table) {
                $table->unique(['name', 'pilot_school_id', 'class'], 'unique_student_name_pilot_school_class');
            });
            echo "Updated student unique constraint to use pilot_school_id\n";
        }
    }

    /**
     * Clean up legacy data that doesn't relate to pilot schools
     */
    private function cleanupLegacyData(): void
    {
        echo "Cleaning up non-pilot school data...\n";
        
        // Remove students not linked to pilot schools
        $deletedStudents = DB::table('students')->whereNull('pilot_school_id')->delete();
        echo "Deleted {$deletedStudents} students not linked to pilot schools\n";
        
        // Remove mentoring visits not linked to pilot schools  
        $deletedVisits = DB::table('mentoring_visits')->whereNull('pilot_school_id')->delete();
        echo "Deleted {$deletedVisits} mentoring visits not linked to pilot schools\n";
        
        // Clean up assessments not linked to students in pilot schools
        if (DB::getDriverName() === 'pgsql') {
            $deletedAssessments = DB::statement("
                DELETE FROM assessments 
                WHERE student_id IN (
                    SELECT a.student_id 
                    FROM assessments a 
                    LEFT JOIN students s ON a.student_id = s.id 
                    WHERE s.id IS NULL OR s.pilot_school_id IS NULL
                )
            ");
        } else {
            $deletedAssessments = DB::statement("
                DELETE a FROM assessments a 
                LEFT JOIN students s ON a.student_id = s.id 
                WHERE s.id IS NULL OR s.pilot_school_id IS NULL
            ");
        }
        echo "Cleaned up orphaned assessments\n";
        
        echo "Data cleanup completed. System now focuses on pilot schools only.\n";
    }
};