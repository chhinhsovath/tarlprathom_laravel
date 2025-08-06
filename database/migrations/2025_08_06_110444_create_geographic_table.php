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
        Schema::create('geographic', function (Blueprint $table) {
            $table->id();
            $table->integer('province_code')->nullable();
            $table->string('province_name_kh')->nullable();
            $table->string('province_name_en')->nullable();
            $table->bigInteger('district_code')->nullable();
            $table->string('district_name_kh')->nullable();
            $table->string('district_name_en')->nullable();
            $table->bigInteger('commune_code')->nullable();
            $table->string('commune_name_kh')->nullable();
            $table->string('commune_name_en')->nullable();
            $table->bigInteger('village_code')->nullable();
            $table->string('village_name_kh')->nullable();
            $table->string('village_name_en')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('province_code');
            $table->index('province_name_kh');
            $table->index('district_code');
            $table->index('district_name_kh');
            $table->index('commune_code');
            $table->index('commune_name_kh');
            $table->index('village_code');
            $table->index('village_name_kh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geographic');
    }
};
