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
        // Add indexes to tbl_tarl_schools for better performance
        Schema::table('tbl_tarl_schools', function (Blueprint $table) {
            $table->index('sclProvince', 'idx_schools_province');
            $table->index('sclDistrict', 'idx_schools_district');
            $table->index(['sclProvince', 'sclDistrict'], 'idx_schools_province_district');
            $table->index('sclStatus', 'idx_schools_status');
        });

        // Add indexes to students table for better performance
        Schema::table('students', function (Blueprint $table) {
            $table->index('school_id', 'idx_students_school');
            $table->index(['school_id', 'grade'], 'idx_students_school_grade');
            $table->index('created_at', 'idx_students_created');
        });

        // Add indexes to assessments table for better performance
        Schema::table('assessments', function (Blueprint $table) {
            $table->index('student_id', 'idx_assessments_student');
            $table->index(['student_id', 'cycle', 'subject'], 'idx_assessments_student_cycle_subject');
            $table->index(['cycle', 'subject'], 'idx_assessments_cycle_subject');
            $table->index('assessed_at', 'idx_assessments_date');
        });

        // Add indexes to mentoring_visits table for better performance
        Schema::table('mentoring_visits', function (Blueprint $table) {
            $table->index('school_id', 'idx_visits_school');
            $table->index('mentor_id', 'idx_visits_mentor');
            $table->index('visit_date', 'idx_visits_date');
            $table->index(['school_id', 'visit_date'], 'idx_visits_school_date');
        });

        // Add indexes to users table for better performance
        Schema::table('users', function (Blueprint $table) {
            $table->index('school_id', 'idx_users_school');
            $table->index('role', 'idx_users_role');
            $table->index(['role', 'school_id'], 'idx_users_role_school');
        });

        // Add indexes to provinces table for better lookups
        Schema::table('provinces', function (Blueprint $table) {
            $table->index('name_en', 'idx_provinces_name_en');
            $table->index('name_kh', 'idx_provinces_name_kh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes in reverse order
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropIndex('idx_provinces_name_en');
            $table->dropIndex('idx_provinces_name_kh');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_school');
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_role_school');
        });

        Schema::table('mentoring_visits', function (Blueprint $table) {
            $table->dropIndex('idx_visits_school');
            $table->dropIndex('idx_visits_mentor');
            $table->dropIndex('idx_visits_date');
            $table->dropIndex('idx_visits_school_date');
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->dropIndex('idx_assessments_student');
            $table->dropIndex('idx_assessments_student_cycle_subject');
            $table->dropIndex('idx_assessments_cycle_subject');
            $table->dropIndex('idx_assessments_date');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_school');
            $table->dropIndex('idx_students_school_grade');
            $table->dropIndex('idx_students_created');
        });

        Schema::table('tbl_tarl_schools', function (Blueprint $table) {
            $table->dropIndex('idx_schools_province');
            $table->dropIndex('idx_schools_district');
            $table->dropIndex('idx_schools_province_district');
            $table->dropIndex('idx_schools_status');
        });
    }
};
