<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // First, clear any existing data
        DB::table('schools')->truncate();
        
        Schema::table('schools', function (Blueprint $table) {
            // Drop commune column if it exists
            if (Schema::hasColumn('schools', 'commune')) {
                $table->dropColumn('commune');
            }
            
            // Add new columns if they don't exist
            if (!Schema::hasColumn('schools', 'school_name')) {
                $table->string('school_name')->after('cluster');
            }
            if (!Schema::hasColumn('schools', 'school_code')) {
                $table->string('school_code')->unique()->after('school_name');
            }
        });
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Drop new columns if they exist
            if (Schema::hasColumn('schools', 'school_name')) {
                $table->dropColumn('school_name');
            }
            if (Schema::hasColumn('schools', 'school_code')) {
                $table->dropColumn('school_code');
            }
            
            // Restore commune column
            if (!Schema::hasColumn('schools', 'commune')) {
                $table->string('commune')->after('district');
            }
        });
    }
};