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
        Schema::table('mentor_school', function (Blueprint $table) {
            // Add pilot_school_id column
            $table->foreignId('pilot_school_id')->nullable()->after('school_id')
                ->constrained('pilot_schools')->onDelete('cascade');

            // Add index for performance
            $table->index('pilot_school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentor_school', function (Blueprint $table) {
            $table->dropForeign(['pilot_school_id']);
            $table->dropColumn('pilot_school_id');
        });
    }
};
