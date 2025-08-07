<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_code')->unique()->nullable()->after('id');
            $table->date('date_of_birth')->nullable()->after('age');
            $table->string('nationality')->default('Cambodian')->after('date_of_birth');
            $table->string('ethnicity')->nullable()->after('nationality');
            $table->string('religion')->nullable()->after('ethnicity');
            $table->string('guardian_name')->nullable()->after('photo');
            $table->string('guardian_relationship')->nullable()->after('guardian_name');
            $table->string('guardian_phone')->nullable()->after('guardian_relationship');
            $table->string('guardian_occupation')->nullable()->after('guardian_phone');
            $table->text('home_address')->nullable()->after('guardian_occupation');
            $table->string('home_village')->nullable()->after('home_address');
            $table->string('home_commune')->nullable()->after('home_village');
            $table->string('home_district')->nullable()->after('home_commune');
            $table->string('home_province')->nullable()->after('home_district');
            $table->decimal('distance_from_school', 5, 2)->nullable()->after('home_province');
            $table->enum('transportation_method', ['walk', 'bicycle', 'motorcycle', 'car', 'bus', 'other'])->nullable()->after('distance_from_school');
            $table->boolean('has_disability')->default(false)->after('transportation_method');
            $table->string('disability_type')->nullable()->after('has_disability');
            $table->boolean('receives_scholarship')->default(false)->after('disability_type');
            $table->decimal('scholarship_amount', 10, 2)->nullable()->after('receives_scholarship');
            $table->date('enrollment_date')->nullable()->after('scholarship_amount');
            $table->enum('enrollment_status', ['active', 'dropped_out', 'transferred', 'graduated', 'suspended'])->default('active')->after('enrollment_date');
            $table->date('status_change_date')->nullable()->after('enrollment_status');
            $table->text('status_change_reason')->nullable()->after('status_change_date');
            $table->integer('previous_year_grade')->nullable()->after('status_change_reason');
            $table->string('previous_school')->nullable()->after('previous_year_grade');
            $table->decimal('attendance_rate', 5, 2)->nullable()->after('previous_school');
            $table->integer('days_absent')->default(0)->after('attendance_rate');
            $table->json('health_conditions')->nullable()->after('days_absent');
            $table->date('last_health_check')->nullable()->after('health_conditions');
            $table->decimal('height_cm', 5, 2)->nullable()->after('last_health_check');
            $table->decimal('weight_kg', 5, 2)->nullable()->after('height_cm');
            $table->enum('nutrition_status', ['normal', 'underweight', 'overweight', 'malnourished'])->nullable()->after('weight_kg');
            $table->boolean('receives_meal_support')->default(false)->after('nutrition_status');
            $table->json('learning_difficulties')->nullable()->after('receives_meal_support');
            $table->json('special_needs')->nullable()->after('learning_difficulties');
            $table->text('teacher_notes')->nullable()->after('special_needs');
            $table->json('extra_activities')->nullable()->after('teacher_notes');
            $table->json('achievements')->nullable()->after('extra_activities');
            $table->integer('siblings_count')->default(0)->after('achievements');
            $table->integer('sibling_position')->nullable()->after('siblings_count');
            $table->enum('family_income_level', ['very_low', 'low', 'medium', 'high'])->nullable()->after('sibling_position');
            $table->boolean('has_birth_certificate')->default(true)->after('family_income_level');
            $table->string('birth_certificate_number')->nullable()->after('has_birth_certificate');
            $table->index(['school_id', 'enrollment_status']);
            $table->index(['grade', 'class_id']);
            $table->index('student_code');
            $table->index('enrollment_date');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'student_code', 'date_of_birth', 'nationality', 'ethnicity', 'religion',
                'guardian_name', 'guardian_relationship', 'guardian_phone', 'guardian_occupation',
                'home_address', 'home_village', 'home_commune', 'home_district', 'home_province',
                'distance_from_school', 'transportation_method', 'has_disability', 'disability_type',
                'receives_scholarship', 'scholarship_amount', 'enrollment_date', 'enrollment_status',
                'status_change_date', 'status_change_reason', 'previous_year_grade', 'previous_school',
                'attendance_rate', 'days_absent', 'health_conditions', 'last_health_check',
                'height_cm', 'weight_kg', 'nutrition_status', 'receives_meal_support',
                'learning_difficulties', 'special_needs', 'teacher_notes', 'extra_activities',
                'achievements', 'siblings_count', 'sibling_position', 'family_income_level',
                'has_birth_certificate', 'birth_certificate_number'
            ]);
        });
    }
};