<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intervention_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_name');
            $table->text('description');
            $table->enum('type', ['academic', 'behavioral', 'social_emotional', 'attendance', 'health', 'nutrition']);
            $table->enum('intensity', ['tier1_universal', 'tier2_targeted', 'tier3_intensive']);
            $table->json('target_criteria')->nullable();
            $table->json('objectives')->nullable();
            $table->integer('duration_weeks')->nullable();
            $table->integer('sessions_per_week')->nullable();
            $table->integer('minutes_per_session')->nullable();
            $table->string('delivery_method')->nullable();
            $table->json('materials_needed')->nullable();
            $table->json('success_metrics')->nullable();
            $table->decimal('success_threshold', 5, 2)->nullable();
            $table->json('implementation_steps')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('intensity');
            $table->index(['is_active', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervention_programs');
    }
};
