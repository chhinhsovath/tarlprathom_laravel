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
        // Check if column doesn't exist before adding (for both MySQL and PostgreSQL)
        if (!Schema::hasColumn('users', 'pilot_school_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('pilot_school_id')->nullable()->after('email');
                $table->index('pilot_school_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'pilot_school_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['pilot_school_id']);
                $table->dropColumn('pilot_school_id');
            });
        }
    }
};