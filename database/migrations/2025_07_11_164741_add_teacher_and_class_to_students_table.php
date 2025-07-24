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
        Schema::table('students', function (Blueprint $table) {
            // Add teacher_id foreign key
            $table->foreignId('teacher_id')->nullable()->after('school_id')->constrained('users')->onDelete('set null');

            // Add class_id foreign key
            $table->foreignId('class_id')->nullable()->after('teacher_id')->constrained('classes')->onDelete('set null');

            // Add indexes for performance
            $table->index(['teacher_id', 'class_id']);
            $table->index(['school_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['class_id']);
            $table->dropColumn('teacher_id');
            $table->dropColumn('class_id');
        });
    }
};
