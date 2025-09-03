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
        Schema::table('mentoring_visits', function (Blueprint $table) {
            // Add level field after village if it doesn't exist
            if (!Schema::hasColumn('mentoring_visits', 'level')) {
                $table->string('level')->nullable()->after('village');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentoring_visits', function (Blueprint $table) {
            if (Schema::hasColumn('mentoring_visits', 'level')) {
                $table->dropColumn('level');
            }
        });
    }
};