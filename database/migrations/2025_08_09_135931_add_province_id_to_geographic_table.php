<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('geographic', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id')->nullable()->after('province_code');
            $table->index('province_id');
        });

        // Update geographic table to set province_id based on province_code
        DB::statement('
            UPDATE geographic g
            JOIN provinces p ON g.province_code = p.province_code
            SET g.province_id = p.id
        ');

        // Add foreign key constraint
        Schema::table('geographic', function (Blueprint $table) {
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('geographic', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropColumn('province_id');
        });
    }
};
