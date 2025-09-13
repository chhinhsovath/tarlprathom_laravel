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
            // Add field to track which teacher added the student - only if it doesn't exist
            if (!Schema::hasColumn('students', 'added_by')) {
                $table->unsignedBigInteger('added_by')->nullable()->after('school_id');
                $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['added_by']);
            }
            
            // Add index for faster queries using 'class' field instead of 'grade'
            if (!Schema::hasIndex('students', 'students_school_id_class_index')) {
                $table->index(['school_id', 'class']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'added_by')) {
                $table->dropForeign(['added_by']);
                $table->dropIndex(['added_by']);
                $table->dropColumn('added_by');
            }
            
            if (Schema::hasIndex('students', 'students_school_id_class_index')) {
                $table->dropIndex(['school_id', 'class']);
            }
        });
    }
};