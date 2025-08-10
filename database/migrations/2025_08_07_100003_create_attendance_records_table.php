<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes');
            $table->foreignId('teacher_id')->nullable()->constrained('users');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late', 'excused', 'sick', 'holiday']);
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->integer('minutes_late')->nullable();
            $table->string('late_reason')->nullable();
            $table->string('absence_reason')->nullable();
            $table->boolean('parent_notified')->default(false);
            $table->string('notification_method')->nullable();
            $table->text('notes')->nullable();
            $table->enum('period', ['morning', 'afternoon', 'full_day'])->default('full_day');
            $table->boolean('participated_in_activities')->nullable();
            $table->json('subjects_attended')->nullable();
            $table->json('subjects_missed')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users');
            $table->timestamp('recorded_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'attendance_date', 'period']);
            $table->index(['school_id', 'attendance_date']);
            $table->index(['class_id', 'attendance_date']);
            $table->index(['teacher_id', 'attendance_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
