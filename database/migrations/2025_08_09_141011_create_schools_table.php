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
        if (Schema::hasTable('schools')) {
            return;
        }
        
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_code', 20)->unique();
            $table->string('name_en');
            $table->string('name_kh');
            $table->string('cluster_en')->nullable();
            $table->string('cluster_kh')->nullable();

            // Geographic relationships
            $table->unsignedBigInteger('province_id')->nullable();
            $table->integer('province_code')->nullable();
            $table->string('district_en')->nullable();
            $table->string('district_kh')->nullable();
            $table->integer('district_code')->nullable();
            $table->integer('commune_code')->nullable();
            $table->integer('village_code')->nullable();

            // School details
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('principal_name')->nullable();
            $table->string('principal_phone')->nullable();

            // School statistics
            $table->integer('total_students')->default(0);
            $table->integer('total_teachers')->default(0);
            $table->integer('total_classrooms')->default(0);

            // Status and type
            $table->enum('type', ['primary', 'secondary', 'high', 'combined'])->default('primary');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->boolean('is_pilot')->default(false);

            // Coordinates for mapping
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('school_code');
            $table->index('province_id');
            $table->index('province_code');
            $table->index('district_code');
            $table->index('commune_code');
            $table->index('village_code');
            $table->index('status');
            $table->index('is_pilot');

            // Foreign keys
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
