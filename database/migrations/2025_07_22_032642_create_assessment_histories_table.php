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
        Schema::create('assessment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('assessment_id')->nullable()->constrained('assessments')->nullOnDelete();
            $table->enum('cycle', ['baseline', 'midline', 'endline']);
            $table->enum('subject', ['math', 'khmer']);
            $table->string('level');
            $table->float('score')->nullable();
            $table->date('assessed_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('action', ['created', 'updated']);
            $table->json('previous_data')->nullable();
            $table->timestamps();

            // Add indexes for better query performance
            $table->index(['student_id', 'subject', 'cycle']);
            $table->index(['assessed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_histories');
    }
};
