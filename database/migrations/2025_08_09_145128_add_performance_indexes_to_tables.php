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
        // Add indexes to tbl_tarl_schools for better performance (skip if table doesn't exist)
        if (Schema::hasTable('tbl_tarl_schools')) {
            Schema::table('tbl_tarl_schools', function (Blueprint $table) {
                $table->index('sclProvince', 'idx_schools_province');
                $table->index('sclDistrict', 'idx_schools_district');
                $table->index(['sclProvince', 'sclDistrict'], 'idx_schools_province_district');
                $table->index('sclStatus', 'idx_schools_status');
            });
        }

        // Add indexes to students table for better performance
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'students' AND indexname = 'idx_students_school'"))->count()) {
                    $table->index('school_id', 'idx_students_school');
                }
                // Use 'class' column instead of 'grade' since that's what exists
                if (Schema::hasColumn('students', 'class') && !collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'students' AND indexname = 'idx_students_school_class'"))->count()) {
                    $table->index(['school_id', 'class'], 'idx_students_school_class');
                }
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'students' AND indexname = 'idx_students_created'"))->count()) {
                    $table->index('created_at', 'idx_students_created');
                }
            });
        }

        // Add indexes to assessments table for better performance
        if (Schema::hasTable('assessments')) {
            Schema::table('assessments', function (Blueprint $table) {
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'assessments' AND indexname = 'idx_assessments_student'"))->count()) {
                    $table->index('student_id', 'idx_assessments_student');
                }
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'assessments' AND indexname = 'idx_assessments_student_cycle_subject'"))->count()) {
                    $table->index(['student_id', 'cycle', 'subject'], 'idx_assessments_student_cycle_subject');
                }
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'assessments' AND indexname = 'idx_assessments_cycle_subject'"))->count()) {
                    $table->index(['cycle', 'subject'], 'idx_assessments_cycle_subject');
                }
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'assessments' AND indexname = 'idx_assessments_date'"))->count()) {
                    $table->index('assessed_at', 'idx_assessments_date');
                }
            });
        }

        // Add indexes to mentoring_visits table for better performance
        if (Schema::hasTable('mentoring_visits')) {
            Schema::table('mentoring_visits', function (Blueprint $table) {
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'mentoring_visits' AND indexname = 'idx_visits_school'"))->count()) {
                    $table->index('school_id', 'idx_visits_school');
                }
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'mentoring_visits' AND indexname = 'idx_visits_mentor'"))->count()) {
                    $table->index('mentor_id', 'idx_visits_mentor');
                }
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'mentoring_visits' AND indexname = 'idx_visits_date'"))->count()) {
                    $table->index('visit_date', 'idx_visits_date');
                }
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'mentoring_visits' AND indexname = 'idx_visits_school_date'"))->count()) {
                    $table->index(['school_id', 'visit_date'], 'idx_visits_school_date');
                }
            });
        }

        // Add indexes to users table for better performance
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'school_id') && !collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'users' AND indexname = 'idx_users_school'"))->count()) {
                    $table->index('school_id', 'idx_users_school');
                }
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'users' AND indexname = 'idx_users_role'"))->count()) {
                    $table->index('role', 'idx_users_role');
                }
                if (Schema::hasColumn('users', 'school_id') && !collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'users' AND indexname = 'idx_users_role_school'"))->count()) {
                    $table->index(['role', 'school_id'], 'idx_users_role_school');
                }
            });
        }

        // Add indexes to provinces table for better lookups
        if (Schema::hasTable('provinces')) {
            Schema::table('provinces', function (Blueprint $table) {
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'provinces' AND indexname = 'idx_provinces_name_en'"))->count()) {
                    $table->index('name_en', 'idx_provinces_name_en');
                }
                if (!collect(DB::select("SELECT * FROM pg_indexes WHERE tablename = 'provinces' AND indexname = 'idx_provinces_name_kh'"))->count()) {
                    $table->index('name_kh', 'idx_provinces_name_kh');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes in reverse order
        if (Schema::hasTable('provinces')) {
            Schema::table('provinces', function (Blueprint $table) {
                $table->dropIndex('idx_provinces_name_en');
                $table->dropIndex('idx_provinces_name_kh');
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('idx_users_school');
                $table->dropIndex('idx_users_role');
                $table->dropIndex('idx_users_role_school');
            });
        }

        if (Schema::hasTable('mentoring_visits')) {
            Schema::table('mentoring_visits', function (Blueprint $table) {
                $table->dropIndex('idx_visits_school');
                $table->dropIndex('idx_visits_mentor');
                $table->dropIndex('idx_visits_date');
                $table->dropIndex('idx_visits_school_date');
            });
        }

        if (Schema::hasTable('assessments')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->dropIndex('idx_assessments_student');
                $table->dropIndex('idx_assessments_student_cycle_subject');
                $table->dropIndex('idx_assessments_cycle_subject');
                $table->dropIndex('idx_assessments_date');
            });
        }

        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropIndex('idx_students_school');
                $table->dropIndex('idx_students_school_class');
                $table->dropIndex('idx_students_created');
            });
        }

        if (Schema::hasTable('tbl_tarl_schools')) {
            Schema::table('tbl_tarl_schools', function (Blueprint $table) {
                $table->dropIndex('idx_schools_province');
                $table->dropIndex('idx_schools_district');
                $table->dropIndex('idx_schools_province_district');
                $table->dropIndex('idx_schools_status');
            });
        }
    }
};
