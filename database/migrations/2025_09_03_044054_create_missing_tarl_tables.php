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
        // Create learning_materials table if not exists
        if (!Schema::hasTable('learning_materials')) {
            Schema::create('learning_materials', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type'); // flashcards, books, worksheets, manipulatives
                $table->string('subject'); // khmer, math
                $table->string('level'); // beginner, intermediate, advanced
                $table->text('description')->nullable();
                $table->integer('quantity')->default(0);
                $table->string('status')->default('available');
                $table->timestamps();
            });
        }
        
        // Create teaching_activities table if not exists
        if (!Schema::hasTable('teaching_activities')) {
            Schema::create('teaching_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('pilot_school_id')->constrained('pilot_schools')->onDelete('cascade');
                $table->string('activity_name');
                $table->date('activity_date');
                $table->integer('duration_minutes');
                $table->integer('students_participated');
                $table->text('materials_used')->nullable();
                $table->text('outcomes')->nullable();
                $table->timestamps();
                
                $table->index(['teacher_id', 'activity_date']);
                $table->index('pilot_school_id');
            });
        }
        
        // Create progress_tracking table if not exists
        if (!Schema::hasTable('progress_tracking')) {
            Schema::create('progress_tracking', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->date('tracking_date');
                $table->integer('attendance_percentage')->default(0);
                $table->string('khmer_level')->nullable();
                $table->string('math_level')->nullable();
                $table->text('teacher_notes')->nullable();
                $table->timestamps();
                
                $table->index(['student_id', 'tracking_date']);
            });
        }
        
        // Create mentor_school table if not exists (many-to-many relationship)
        if (!Schema::hasTable('mentor_school')) {
            Schema::create('mentor_school', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('school_id')->constrained('pilot_schools')->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['user_id', 'school_id']);
                $table->index('user_id');
                $table->index('school_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_tracking');
        Schema::dropIfExists('teaching_activities');
        Schema::dropIfExists('learning_materials');
        Schema::dropIfExists('mentor_school');
    }
};