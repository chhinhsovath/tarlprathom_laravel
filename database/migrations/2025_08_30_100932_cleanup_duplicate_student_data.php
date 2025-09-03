<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove duplicate students - keep only the first record of each duplicate group
        $this->removeDuplicateStudents();
        
        // Optional: Add unique constraint to prevent future duplicates
        // Uncomment the line below if you want to enforce uniqueness
        // Schema::table('students', function (Blueprint $table) {
        //     $table->unique(['name', 'school_id'], 'unique_student_per_school');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be safely reversed as it deletes data
        // If you need to restore data, you'll need a backup
        echo "This migration cannot be reversed - data has been permanently deleted.\n";
    }

    /**
     * Remove duplicate students, keeping only the first record of each group
     */
    private function removeDuplicateStudents(): void
    {
        // Get all duplicate groups (students with same name and school_id)
        $duplicateGroups = DB::select("
            SELECT name, school_id, COUNT(*) as duplicate_count
            FROM students 
            GROUP BY name, school_id 
            HAVING COUNT(*) > 1
        ");

        $totalRemoved = 0;
        
        foreach ($duplicateGroups as $group) {
            // Get all student IDs for this duplicate group
            $studentIds = DB::table('students')
                ->where('name', $group->name)
                ->where('school_id', $group->school_id)
                ->orderBy('id')
                ->pluck('id')
                ->toArray();

            // Keep the first student (lowest ID), remove the rest
            array_shift($studentIds);
            $removeIds = $studentIds;

            if (!empty($removeIds)) {
                // Remove related records first to maintain referential integrity
                DB::table('assessments')->whereIn('student_id', $removeIds)->delete();
                DB::table('student_assessment_eligibility')->whereIn('student_id', $removeIds)->delete();
                
                // Check for other related tables and clean them up
                $relatedTables = [
                    'attendance_records',
                    'student_learning_outcomes', 
                    'student_interventions',
                    'progress_tracking'
                ];

                foreach ($relatedTables as $table) {
                    if (Schema::hasTable($table)) {
                        DB::table($table)->whereIn('student_id', $removeIds)->delete();
                    }
                }

                // Finally remove the duplicate student records
                $removed = DB::table('students')->whereIn('id', $removeIds)->delete();
                $totalRemoved += $removed;

                echo "Removed {$removed} duplicates for: {$group->name} (School ID: {$group->school_id})\n";
            }
        }

        echo "Total duplicate student records removed: {$totalRemoved}\n";
        echo "Remaining unique students: " . DB::table('students')->count() . "\n";
    }
};