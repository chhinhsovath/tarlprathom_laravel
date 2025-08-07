<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->foreignId('assessor_id')->nullable()->after('student_id')->constrained('users');
            $table->foreignId('school_id')->nullable()->after('assessor_id');
            $table->foreignId('class_id')->nullable()->after('school_id')->constrained('classes');
            $table->string('academic_year')->nullable()->after('class_id');
            $table->integer('term')->nullable()->after('academic_year');
            $table->enum('assessment_method', ['oral', 'written', 'practical', 'observation', 'mixed'])->default('written')->after('subject');
            $table->integer('total_questions')->nullable()->after('level');
            $table->integer('correct_answers')->nullable()->after('total_questions');
            $table->decimal('percentage_score', 5, 2)->nullable()->after('score');
            $table->enum('performance_level', ['excellent', 'good', 'satisfactory', 'needs_improvement', 'poor'])->nullable()->after('percentage_score');
            $table->json('skill_scores')->nullable()->after('performance_level');
            $table->integer('time_taken_minutes')->nullable()->after('skill_scores');
            $table->boolean('completed_assessment')->default(true)->after('time_taken_minutes');
            $table->text('incomplete_reason')->nullable()->after('completed_assessment');
            $table->json('question_responses')->nullable()->after('incomplete_reason');
            $table->json('mistakes_analysis')->nullable()->after('question_responses');
            $table->text('assessor_comments')->nullable()->after('mistakes_analysis');
            $table->text('recommendations')->nullable()->after('assessor_comments');
            $table->boolean('requires_intervention')->default(false)->after('recommendations');
            $table->string('intervention_type')->nullable()->after('requires_intervention');
            $table->boolean('parent_informed')->default(false)->after('intervention_type');
            $table->date('parent_informed_date')->nullable()->after('parent_informed');
            $table->text('parent_feedback')->nullable()->after('parent_informed_date');
            $table->decimal('improvement_from_last', 5, 2)->nullable()->after('parent_feedback');
            $table->string('learning_style')->nullable()->after('improvement_from_last');
            $table->json('strengths')->nullable()->after('learning_style');
            $table->json('weaknesses')->nullable()->after('strengths');
            $table->json('topics_mastered')->nullable()->after('weaknesses');
            $table->json('topics_struggling')->nullable()->after('topics_mastered');
            $table->boolean('homework_completion')->nullable()->after('topics_struggling');
            $table->boolean('class_participation')->nullable()->after('homework_completion');
            $table->enum('behavior_during_assessment', ['excellent', 'good', 'fair', 'poor'])->nullable()->after('class_participation');
            $table->boolean('used_assistance')->default(false)->after('behavior_during_assessment');
            $table->string('assistance_type')->nullable()->after('used_assistance');
            $table->json('accommodations_provided')->nullable()->after('assistance_type');
            $table->boolean('reassessment_needed')->default(false)->after('accommodations_provided');
            $table->date('reassessment_date')->nullable()->after('reassessment_needed');
            $table->string('benchmark_comparison')->nullable()->after('reassessment_date');
            $table->integer('peer_rank')->nullable()->after('benchmark_comparison');
            $table->integer('peer_total')->nullable()->after('peer_rank');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->index(['student_id', 'cycle', 'subject']);
            $table->index(['school_id', 'academic_year', 'term']);
            $table->index(['assessed_at', 'cycle']);
            $table->index('performance_level');
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign(['assessor_id']);
            $table->dropForeign(['school_id']);
            $table->dropForeign(['class_id']);
            $table->dropColumn([
                'assessor_id', 'school_id', 'class_id', 'academic_year', 'term',
                'assessment_method', 'total_questions', 'correct_answers', 'percentage_score',
                'performance_level', 'skill_scores', 'time_taken_minutes', 'completed_assessment',
                'incomplete_reason', 'question_responses', 'mistakes_analysis', 'assessor_comments',
                'recommendations', 'requires_intervention', 'intervention_type', 'parent_informed',
                'parent_informed_date', 'parent_feedback', 'improvement_from_last', 'learning_style',
                'strengths', 'weaknesses', 'topics_mastered', 'topics_struggling',
                'homework_completion', 'class_participation', 'behavior_during_assessment',
                'used_assistance', 'assistance_type', 'accommodations_provided',
                'reassessment_needed', 'reassessment_date', 'benchmark_comparison',
                'peer_rank', 'peer_total'
            ]);
        });
    }
};