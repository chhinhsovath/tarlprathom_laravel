<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description');
            $table->enum('subject', ['math', 'khmer', 'science', 'social_studies', 'english', 'other']);
            $table->string('grade_level');
            $table->enum('domain', ['knowledge', 'skills', 'attitudes', 'values']);
            $table->enum('cognitive_level', ['remember', 'understand', 'apply', 'analyze', 'evaluate', 'create']);
            $table->integer('sequence_order')->default(0);
            $table->string('parent_outcome_code')->nullable();
            $table->json('assessment_criteria')->nullable();
            $table->json('performance_indicators')->nullable();
            $table->integer('minimum_mastery_score')->default(60);
            $table->integer('target_weeks_to_achieve')->nullable();
            $table->json('prerequisite_outcomes')->nullable();
            $table->json('related_outcomes')->nullable();
            $table->json('teaching_strategies')->nullable();
            $table->json('learning_resources')->nullable();
            $table->boolean('is_core_outcome')->default(false);
            $table->boolean('is_measurable')->default(true);
            $table->string('measurement_method')->nullable();
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('intermediate');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['subject', 'grade_level']);
            $table->index('domain');
            $table->index('cognitive_level');
            $table->index('parent_outcome_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_outcomes');
    }
};
