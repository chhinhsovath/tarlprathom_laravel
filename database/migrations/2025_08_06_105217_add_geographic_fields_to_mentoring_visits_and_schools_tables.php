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
        // Add commune field back to schools table if it doesn't exist
        Schema::table('schools', function (Blueprint $table) {
            if (! Schema::hasColumn('schools', 'district')) {
                $table->string('district')->nullable();
            }
            if (! Schema::hasColumn('schools', 'commune')) {
                $table->string('commune')->nullable();
            }
            if (! Schema::hasColumn('schools', 'village')) {
                $table->string('village')->nullable();
            }
        });

        // Add geographic fields to mentoring_visits table
        Schema::table('mentoring_visits', function (Blueprint $table) {
            if (! Schema::hasColumn('mentoring_visits', 'province')) {
                $table->string('province')->nullable();
            }
            if (! Schema::hasColumn('mentoring_visits', 'district')) {
                $table->string('district')->nullable();
            }
            if (! Schema::hasColumn('mentoring_visits', 'commune')) {
                $table->string('commune')->nullable();
            }
            if (! Schema::hasColumn('mentoring_visits', 'village')) {
                $table->string('village')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (Schema::hasColumn('schools', 'commune')) {
                $table->dropColumn('commune');
            }
            if (Schema::hasColumn('schools', 'village')) {
                $table->dropColumn('village');
            }
        });

        Schema::table('mentoring_visits', function (Blueprint $table) {
            if (Schema::hasColumn('mentoring_visits', 'commune')) {
                $table->dropColumn('commune');
            }
            if (Schema::hasColumn('mentoring_visits', 'village')) {
                $table->dropColumn('village');
            }
        });
    }
};
