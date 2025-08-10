<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('learning_outcome_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users');
            $table->enum('mastery_level', ['not_attempted', 'emerging', 'developing', 'proficient', 'advanced']);
            $table->decimal('achievement_score', 5, 2)->nullable();
            $table->date('achieved_date')->nullable();
            $table->integer('attempts_count')->default(0);
            $table->date('first_attempt_date')->nullable();
            $table->date('last_attempt_date')->nullable();
            $table->json('evidence_artifacts')->nullable();
            $table->text('teacher_observations')->nullable();
            $table->json('support_provided')->nullable();
            $table->boolean('requires_remediation')->default(false);
            $table->string('remediation_plan')->nullable();
            $table->integer('practice_hours')->default(0);
            $table->json('practice_activities')->nullable();
            $table->decimal('improvement_rate', 5, 2)->nullable();
            $table->string('learning_path')->nullable();
            $table->json('peer_assessment')->nullable();
            $table->json('self_assessment')->nullable();
            $table->boolean('portfolio_included')->default(false);
            $table->string('portfolio_link')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'learning_outcome_id']);
            $table->index(['mastery_level', 'achieved_date']);
            $table->index('teacher_id');
            $table->index('assessment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_learning_outcomes');
    }
};
