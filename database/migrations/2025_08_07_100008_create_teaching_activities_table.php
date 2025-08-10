<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity_code')->unique();
            $table->string('activity_name');
            $table->text('description');
            $table->enum('subject', ['math', 'khmer', 'english', 'science', 'social_studies', 'art', 'physical_education', 'life_skills']);
            $table->string('grade_level');
            $table->string('tarl_level')->nullable();
            $table->enum('activity_type', ['individual', 'pair', 'small_group', 'whole_class', 'mixed']);
            $table->integer('duration_minutes');
            $table->json('learning_objectives')->nullable();
            $table->json('materials_required')->nullable();
            $table->json('preparation_steps')->nullable();
            $table->json('implementation_steps')->nullable();
            $table->json('assessment_strategies')->nullable();
            $table->json('differentiation_strategies')->nullable();
            $table->json('extension_activities')->nullable();
            $table->json('keywords')->nullable();
            $table->enum('difficulty_level', ['easy', 'medium', 'hard']);
            $table->boolean('requires_technology')->default(false);
            $table->json('technology_requirements')->nullable();
            $table->boolean('indoor_activity')->default(true);
            $table->json('space_requirements')->nullable();
            $table->integer('minimum_students')->default(1);
            $table->integer('maximum_students')->nullable();
            $table->json('skills_developed')->nullable();
            $table->decimal('effectiveness_rating', 3, 2)->nullable();
            $table->integer('usage_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['subject', 'grade_level']);
            $table->index('tarl_level');
            $table->index('activity_type');
            $table->index(['is_approved', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_activities');
    }
};
