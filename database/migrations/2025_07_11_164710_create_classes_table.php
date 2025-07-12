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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Grade 1A", "Grade 2B"
            $table->integer('grade_level'); // 1, 2, 3, etc.
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('academic_year')->nullable(); // e.g., "2024-2025"
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Add index for common queries
            $table->index(['school_id', 'teacher_id']);
            $table->index(['school_id', 'grade_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
