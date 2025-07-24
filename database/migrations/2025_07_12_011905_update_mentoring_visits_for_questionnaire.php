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
            // Add new columns for questionnaire structure
            $table->json('questionnaire_data')->nullable()->after('photo');
            $table->string('region')->nullable()->after('photo');
            $table->string('province')->nullable()->after('region');
            $table->string('program_type')->nullable()->after('province');
            $table->boolean('class_in_session')->nullable()->after('program_type');
            $table->string('class_not_in_session_reason')->nullable()->after('class_in_session');
            $table->boolean('full_session_observed')->nullable()->after('class_not_in_session_reason');
            $table->string('grade_group')->nullable()->after('full_session_observed');
            $table->json('grades_observed')->nullable()->after('grade_group');
            $table->string('subject_observed')->nullable()->after('grades_observed');
            $table->json('language_levels_observed')->nullable()->after('subject_observed');
            $table->json('numeracy_levels_observed')->nullable()->after('language_levels_observed');
            $table->text('action_plan')->nullable()->after('numeracy_levels_observed');
            $table->boolean('follow_up_required')->default(false)->after('action_plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentoring_visits', function (Blueprint $table) {
            $table->dropColumn('questionnaire_data');
            $table->dropColumn('region');
            $table->dropColumn('province');
            $table->dropColumn('program_type');
            $table->dropColumn('class_in_session');
            $table->dropColumn('class_not_in_session_reason');
            $table->dropColumn('full_session_observed');
            $table->dropColumn('grade_group');
            $table->dropColumn('grades_observed');
            $table->dropColumn('subject_observed');
            $table->dropColumn('language_levels_observed');
            $table->dropColumn('numeracy_levels_observed');
            $table->dropColumn('action_plan');
            $table->dropColumn('follow_up_required');
        });
    }
};
