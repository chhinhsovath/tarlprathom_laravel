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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->integer('province_code')->unique();
            $table->string('name_en');
            $table->string('name_kh');
            $table->timestamps();
            $table->softDeletes();

            $table->index('province_code');
        });

        // Insert provinces from geographic table
        DB::statement('
            INSERT INTO provinces (province_code, name_en, name_kh, created_at, updated_at)
            SELECT DISTINCT 
                province_code,
                province_name_en,
                province_name_kh,
                NOW(),
                NOW()
            FROM geographic
            WHERE province_code IS NOT NULL
            ORDER BY province_code
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
