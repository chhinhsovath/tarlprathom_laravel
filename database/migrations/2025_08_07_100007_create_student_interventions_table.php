<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('intervention_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referred_by')->nullable()->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->date('enrollment_date');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['referred', 'enrolled', 'in_progress', 'completed', 'discontinued', 'graduated']);
            $table->text('referral_reason');
            $table->json('baseline_data')->nullable();
            $table->json('progress_data')->nullable();
            $table->json('outcome_data')->nullable();
            $table->integer('sessions_attended')->default(0);
            $table->integer('sessions_total')->nullable();
            $table->decimal('attendance_rate', 5, 2)->nullable();
            $table->decimal('progress_score', 5, 2)->nullable();
            $table->boolean('goal_achieved')->nullable();
            $table->text('exit_reason')->nullable();
            $table->date('exit_date')->nullable();
            $table->text('post_intervention_plan')->nullable();
            $table->boolean('parent_consent')->default(false);
            $table->date('parent_consent_date')->nullable();
            $table->json('parent_involvement')->nullable();
            $table->json('accommodations')->nullable();
            $table->json('modifications')->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->text('student_feedback')->nullable();
            $table->text('parent_feedback')->nullable();
            $table->boolean('requires_follow_up')->default(false);
            $table->date('follow_up_date')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['intervention_program_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_interventions');
    }
};
