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
        Schema::create('student_assessment_eligibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('assessment_type', ['midline', 'endline']);
            $table->foreignId('selected_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_eligible')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure unique combination of student and assessment type
            $table->unique(['student_id', 'assessment_type']);

            // Add indexes for performance
            $table->index('student_id');
            $table->index('assessment_type');
            $table->index('is_eligible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_assessment_eligibility');
    }
};
