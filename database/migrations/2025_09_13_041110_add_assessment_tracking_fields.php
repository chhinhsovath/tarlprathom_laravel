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
        Schema::table('assessments', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('assessments', 'status')) {
                $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('in_progress')->after('assessed_at');
            }
            
            if (!Schema::hasColumn('assessments', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('assessed_at');
            }
            
            if (!Schema::hasColumn('assessments', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('started_at');
            }
            
            if (!Schema::hasColumn('assessments', 'assessed_by')) {
                $table->unsignedBigInteger('assessed_by')->nullable()->after('assessor_id');
                $table->foreign('assessed_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Add index for faster queries if not exists
            if (!Schema::hasIndex('assessments', 'assessments_student_id_cycle_status_index')) {
                $table->index(['student_id', 'cycle', 'status']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            if (Schema::hasColumn('assessments', 'assessed_by')) {
                $table->dropForeign(['assessed_by']);
                $table->dropColumn('assessed_by');
            }
            
            if (Schema::hasIndex('assessments', 'assessments_student_id_cycle_status_index')) {
                $table->dropIndex(['student_id', 'cycle', 'status']);
            }
            
            $columnsToRemove = ['status', 'started_at', 'completed_at'];
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('assessments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};