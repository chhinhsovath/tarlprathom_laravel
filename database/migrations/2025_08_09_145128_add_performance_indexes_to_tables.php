<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to students table for better performance
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                // These will be created if they don't exist, MySQL/PostgreSQL will handle duplicates
                try {
                    $table->index('school_id', 'idx_students_school');
                } catch (\Exception $e) {
                    // Index already exists
                }
                
                try {
                    if (Schema::hasColumn('students', 'class')) {
                        $table->index(['school_id', 'class'], 'idx_students_school_class');
                    }
                } catch (\Exception $e) {
                    // Index already exists
                }
                
                try {
                    $table->index('created_at', 'idx_students_created');
                } catch (\Exception $e) {
                    // Index already exists
                }
            });
        }

        // Add indexes to assessments table for better performance
        if (Schema::hasTable('assessments')) {
            Schema::table('assessments', function (Blueprint $table) {
                try {
                    $table->index('student_id', 'idx_assessments_student');
                } catch (\Exception $e) {}
                
                try {
                    $table->index(['student_id', 'cycle', 'subject'], 'idx_assessments_student_cycle_subject');
                } catch (\Exception $e) {}
                
                try {
                    $table->index(['cycle', 'subject'], 'idx_assessments_cycle_subject');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('assessed_at', 'idx_assessments_date');
                } catch (\Exception $e) {}
            });
        }

        // Add indexes to mentoring_visits table for better performance
        if (Schema::hasTable('mentoring_visits')) {
            Schema::table('mentoring_visits', function (Blueprint $table) {
                try {
                    $table->index('school_id', 'idx_visits_school');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('mentor_id', 'idx_visits_mentor');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('visit_date', 'idx_visits_date');
                } catch (\Exception $e) {}
                
                try {
                    $table->index(['school_id', 'visit_date'], 'idx_visits_school_date');
                } catch (\Exception $e) {}
            });
        }

        // Add indexes to users table for better performance
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                try {
                    if (Schema::hasColumn('users', 'school_id')) {
                        $table->index('school_id', 'idx_users_school');
                    }
                } catch (\Exception $e) {}
                
                try {
                    $table->index('role', 'idx_users_role');
                } catch (\Exception $e) {}
                
                try {
                    if (Schema::hasColumn('users', 'school_id')) {
                        $table->index(['role', 'school_id'], 'idx_users_role_school');
                    }
                } catch (\Exception $e) {}
            });
        }

        // Add indexes to provinces table for better lookups
        if (Schema::hasTable('provinces')) {
            Schema::table('provinces', function (Blueprint $table) {
                try {
                    $table->index('name_en', 'idx_provinces_name_en');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('name_kh', 'idx_provinces_name_kh');
                } catch (\Exception $e) {}
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes safely
        if (Schema::hasTable('provinces')) {
            Schema::table('provinces', function (Blueprint $table) {
                try { $table->dropIndex('idx_provinces_name_en'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_provinces_name_kh'); } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                try { $table->dropIndex('idx_users_school'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_users_role'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_users_role_school'); } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('mentoring_visits')) {
            Schema::table('mentoring_visits', function (Blueprint $table) {
                try { $table->dropIndex('idx_visits_school'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_visits_mentor'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_visits_date'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_visits_school_date'); } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('assessments')) {
            Schema::table('assessments', function (Blueprint $table) {
                try { $table->dropIndex('idx_assessments_student'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_assessments_student_cycle_subject'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_assessments_cycle_subject'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_assessments_date'); } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                try { $table->dropIndex('idx_students_school'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_students_school_class'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_students_created'); } catch (\Exception $e) {}
            });
        }
    }
};