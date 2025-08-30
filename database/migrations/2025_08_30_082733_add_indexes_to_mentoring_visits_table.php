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
        Schema::table('mentoring_visits', function (Blueprint $table) {
            // Add additional indexes (some already exist from foreign keys)
            // Skip teacher_id and mentor_id as they already have indexes
            $table->index('visit_date', 'idx_mentoring_visits_date');
            $table->index('score', 'idx_mentoring_visits_score');
            $table->index(['mentor_id', 'visit_date'], 'idx_mentoring_visits_mentor_date');
            $table->index(['teacher_id', 'visit_date'], 'idx_mentoring_visits_teacher_date');
            $table->index(['school_id', 'visit_date'], 'idx_mentoring_visits_school_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentoring_visits', function (Blueprint $table) {
            // Drop only the indexes we created
            $table->dropIndex('idx_mentoring_visits_school_date');
            $table->dropIndex('idx_mentoring_visits_teacher_date');
            $table->dropIndex('idx_mentoring_visits_mentor_date');
            $table->dropIndex('idx_mentoring_visits_score');
            $table->dropIndex('idx_mentoring_visits_date');
        });
    }
};
