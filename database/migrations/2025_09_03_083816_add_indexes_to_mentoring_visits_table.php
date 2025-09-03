<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add indexes for improved query performance on mentoring visits
     */
    public function up(): void
    {
        Schema::table('mentoring_visits', function (Blueprint $table) {
            // Composite index for common queries
            $table->index(['pilot_school_id', 'visit_date'], 'idx_school_date');
            
            // Index for mentor queries with date filtering
            $table->index(['mentor_id', 'visit_date'], 'idx_mentor_date');
            
            // Index for teacher queries
            $table->index(['teacher_id', 'visit_date'], 'idx_teacher_date');
            
            // Index for date range queries
            $table->index('visit_date', 'idx_visit_date');
            
            // Index for locked status filtering
            if (Schema::hasColumn('mentoring_visits', 'is_locked')) {
                $table->index('is_locked', 'idx_locked_status');
            }
            
            // Index for subject filtering
            $table->index('subject_observed', 'idx_subject');
            
            // Composite index for school-teacher relationship
            $table->index(['pilot_school_id', 'teacher_id'], 'idx_school_teacher');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentoring_visits', function (Blueprint $table) {
            $table->dropIndex('idx_school_date');
            $table->dropIndex('idx_mentor_date');
            $table->dropIndex('idx_teacher_date');
            $table->dropIndex('idx_visit_date');
            
            if (Schema::hasColumn('mentoring_visits', 'is_locked')) {
                $table->dropIndex('idx_locked_status');
            }
            
            $table->dropIndex('idx_subject');
            $table->dropIndex('idx_school_teacher');
        });
    }
};