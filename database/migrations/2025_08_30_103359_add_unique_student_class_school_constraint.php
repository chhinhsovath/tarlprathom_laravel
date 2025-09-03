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
        // First, let's check for existing duplicates and handle them
        $this->handleExistingDuplicates();
        
        // Add unique constraint to ensure each student has only one class assignment
        Schema::table('students', function (Blueprint $table) {
            // Since student_code is already unique, this ensures each student is only in one class
            // But let's add a constraint to prevent duplicate enrollments per school per class
            // This will prevent multiple students with same name in same class in same school
            $table->unique(['name', 'school_id', 'class'], 'unique_student_name_school_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('unique_student_name_school_class');
        });
    }

    /**
     * Handle any existing duplicate student-class combinations
     */
    private function handleExistingDuplicates(): void
    {
        // Find students with duplicate name + school_id + class combinations
        $duplicates = DB::select("
            SELECT name, school_id, class, COUNT(*) as count
            FROM students 
            GROUP BY name, school_id, class 
            HAVING COUNT(*) > 1
        ");

        if (!empty($duplicates)) {
            echo "Found " . count($duplicates) . " duplicate student-class combinations. Fixing...\n";
            
            foreach ($duplicates as $duplicate) {
                // Get all students with this duplicate combination
                $students = DB::table('students')
                    ->where('name', $duplicate->name)
                    ->where('school_id', $duplicate->school_id)
                    ->where('class', $duplicate->class)
                    ->orderBy('id')
                    ->get();

                // Keep the first one, modify names for the others to make them unique
                foreach ($students->skip(1) as $index => $student) {
                    $newName = $student->name . ' (' . ($index + 2) . ')';
                    
                    // Update student's name to make it unique
                    DB::table('students')
                        ->where('id', $student->id)
                        ->update(['name' => $newName]);
                    
                    echo "Renamed duplicate student {$student->student_code} to {$newName}\n";
                }
            }
        } else {
            echo "No duplicate student-class combinations found.\n";
        }
    }
};