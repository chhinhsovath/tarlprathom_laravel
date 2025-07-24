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
        Schema::table('schools', function (Blueprint $table) {
            // Baseline assessment dates
            $table->date('baseline_start_date')->nullable()->after('cluster');
            $table->date('baseline_end_date')->nullable()->after('baseline_start_date');

            // Midline assessment dates
            $table->date('midline_start_date')->nullable()->after('baseline_end_date');
            $table->date('midline_end_date')->nullable()->after('midline_start_date');

            // Endline assessment dates
            $table->date('endline_start_date')->nullable()->after('midline_end_date');
            $table->date('endline_end_date')->nullable()->after('endline_start_date');

            // Add indexes for better query performance
            $table->index(['baseline_start_date', 'baseline_end_date']);
            $table->index(['midline_start_date', 'midline_end_date']);
            $table->index(['endline_start_date', 'endline_end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex('schools_baseline_start_date_baseline_end_date_index');
            $table->dropIndex('schools_midline_start_date_midline_end_date_index');
            $table->dropIndex('schools_endline_start_date_endline_end_date_index');

            $table->dropColumn('baseline_start_date');
            $table->dropColumn('baseline_end_date');
            $table->dropColumn('midline_start_date');
            $table->dropColumn('midline_end_date');
            $table->dropColumn('endline_start_date');
            $table->dropColumn('endline_end_date');
        });
    }
};
