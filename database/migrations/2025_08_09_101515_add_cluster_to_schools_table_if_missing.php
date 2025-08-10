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
        // Check if cluster column doesn't exist and add it
        if (! Schema::hasColumn('schools', 'cluster')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->string('cluster')->nullable()->after('district');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('cluster');
        });
    }
};
