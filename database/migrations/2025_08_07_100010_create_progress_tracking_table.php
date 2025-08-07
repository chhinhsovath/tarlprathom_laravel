<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('teacher_id')->nullable()->constrained('users');
            $table->string('academic_year');
            $table->integer('term');
            $table->integer('week_number');
            $table->date('week_start_date');
            $table->date('week_end_date');
            $table->enum('subject', ['math', 'khmer', 'both']);
            $table->string('starting_level');
            $table->string('current_level');
            $table->boolean('level_changed')->default(false);
            $table->string('new_level')->nullable();
            $table->date('level_change_date')->nullable();
            $table->integer('activities_completed')->default(0);
            $table->integer('activities_attempted')->default(0);
            $table->decimal('completion_rate', 5, 2)->nullable();
            $table->decimal('accuracy_rate', 5, 2)->nullable();
            $table->integer('practice_minutes')->default(0);
            $table->json('skills_practiced')->nullable();
            $table->json('skills_mastered')->nullable();
            $table->json('areas_of_difficulty')->nullable();
            $table->decimal('engagement_score', 3, 2)->nullable();
            $table->decimal('effort_score', 3, 2)->nullable();
            $table->boolean('homework_completed')->nullable();
            $table->boolean('participated_actively')->nullable();
            $table->integer('peer_support_given')->default(0);
            $table->integer('peer_support_received')->default(0);
            $table->text('teacher_observations')->nullable();
            $table->json('breakthrough_moments')->nullable();
            $table->json('intervention_notes')->nullable();
            $table->boolean('parent_communication')->default(false);
            $table->text('parent_feedback')->nullable();
            $table->decimal('weekly_improvement', 5, 2)->nullable();
            $table->decimal('cumulative_progress', 5, 2)->nullable();
            $table->string('next_week_focus')->nullable();
            $table->json('recommended_activities')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'academic_year', 'term', 'week_number', 'subject']);
            $table->index(['school_id', 'academic_year', 'term']);
            $table->index(['teacher_id', 'week_start_date']);
            $table->index('current_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_tracking');
    }
};