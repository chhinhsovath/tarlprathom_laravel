<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('school_id')->constrained();
            $table->foreignId('class_id')->nullable()->constrained('classes');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('subject', ['math', 'khmer', 'english', 'science', 'social_studies', 'art', 'physical_education', 'life_skills']);
            $table->string('grade_level');
            $table->string('tarl_level')->nullable();
            $table->date('planned_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration_minutes');
            $table->json('learning_outcomes')->nullable();
            $table->json('success_criteria')->nullable();
            $table->json('activities_sequence')->nullable();
            $table->json('teaching_activities_used')->nullable();
            $table->json('materials_needed')->nullable();
            $table->json('assessment_methods')->nullable();
            $table->json('homework_assigned')->nullable();
            $table->text('teacher_reflection')->nullable();
            $table->enum('execution_status', ['planned', 'in_progress', 'completed', 'postponed', 'cancelled'])->default('planned');
            $table->date('actual_date')->nullable();
            $table->integer('students_present')->nullable();
            $table->integer('students_absent')->nullable();
            $table->json('challenges_faced')->nullable();
            $table->json('successes_noted')->nullable();
            $table->json('student_engagement_levels')->nullable();
            $table->json('objectives_achieved')->nullable();
            $table->text('improvements_for_next_time')->nullable();
            $table->boolean('shared_with_colleagues')->default(false);
            $table->integer('times_reused')->default(0);
            $table->decimal('effectiveness_rating', 3, 2)->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_comments')->nullable();
            $table->timestamps();
            
            $table->index(['teacher_id', 'planned_date']);
            $table->index(['school_id', 'subject', 'grade_level']);
            $table->index(['class_id', 'planned_date']);
            $table->index('execution_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_plans');
    }
};