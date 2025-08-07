<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('school_type')->nullable()->after('school_code');
            $table->enum('school_level', ['primary', 'lower_secondary', 'upper_secondary', 'combined'])->default('primary')->after('school_type');
            $table->string('director_name')->nullable()->after('school_level');
            $table->string('director_phone')->nullable()->after('director_name');
            $table->string('director_email')->nullable()->after('director_phone');
            $table->date('establishment_date')->nullable()->after('director_email');
            $table->string('land_ownership')->nullable()->after('establishment_date');
            $table->decimal('land_area_sqm', 10, 2)->nullable()->after('land_ownership');
            $table->integer('total_buildings')->default(0)->after('land_area_sqm');
            $table->integer('total_classrooms')->default(0)->after('total_buildings');
            $table->integer('usable_classrooms')->default(0)->after('total_classrooms');
            $table->integer('total_toilets')->default(0)->after('usable_classrooms');
            $table->integer('toilets_for_girls')->default(0)->after('total_toilets');
            $table->integer('toilets_for_boys')->default(0)->after('toilets_for_girls');
            $table->boolean('has_electricity')->default(false)->after('toilets_for_boys');
            $table->string('electricity_source')->nullable()->after('has_electricity');
            $table->boolean('has_water_supply')->default(false)->after('electricity_source');
            $table->string('water_source')->nullable()->after('has_water_supply');
            $table->boolean('has_internet')->default(false)->after('water_source');
            $table->string('internet_type')->nullable()->after('has_internet');
            $table->integer('internet_speed_mbps')->nullable()->after('internet_type');
            $table->boolean('has_library')->default(false)->after('internet_speed_mbps');
            $table->integer('library_books_count')->default(0)->after('has_library');
            $table->boolean('has_computer_lab')->default(false)->after('library_books_count');
            $table->integer('computers_count')->default(0)->after('has_computer_lab');
            $table->integer('working_computers_count')->default(0)->after('computers_count');
            $table->boolean('has_science_lab')->default(false)->after('working_computers_count');
            $table->boolean('has_playground')->default(false)->after('has_science_lab');
            $table->boolean('has_sports_field')->default(false)->after('has_playground');
            $table->boolean('has_fence')->default(false)->after('has_sports_field');
            $table->boolean('has_gate')->default(false)->after('has_fence');
            $table->boolean('has_security_guard')->default(false)->after('has_gate');
            $table->boolean('has_first_aid')->default(false)->after('has_security_guard');
            $table->boolean('has_school_bus')->default(false)->after('has_first_aid');
            $table->integer('school_buses_count')->default(0)->after('has_school_bus');
            $table->decimal('latitude', 10, 8)->nullable()->after('school_buses_count');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('nearest_health_center')->nullable()->after('longitude');
            $table->decimal('distance_to_health_center', 5, 2)->nullable()->after('nearest_health_center');
            $table->integer('total_staff')->default(0)->after('distance_to_health_center');
            $table->integer('total_teachers')->default(0)->after('total_staff');
            $table->integer('female_teachers')->default(0)->after('total_teachers');
            $table->integer('male_teachers')->default(0)->after('female_teachers');
            $table->integer('contract_teachers')->default(0)->after('male_teachers');
            $table->integer('volunteer_teachers')->default(0)->after('contract_teachers');
            $table->decimal('annual_budget', 15, 2)->nullable()->after('volunteer_teachers');
            $table->decimal('government_funding', 15, 2)->nullable()->after('annual_budget');
            $table->decimal('ngo_funding', 15, 2)->nullable()->after('government_funding');
            $table->decimal('community_funding', 15, 2)->nullable()->after('ngo_funding');
            $table->json('partner_organizations')->nullable()->after('community_funding');
            $table->json('programs_implemented')->nullable()->after('partner_organizations');
            $table->enum('shift_system', ['single', 'double', 'triple'])->default('single')->after('programs_implemented');
            $table->time('morning_shift_start')->nullable()->after('shift_system');
            $table->time('morning_shift_end')->nullable()->after('morning_shift_start');
            $table->time('afternoon_shift_start')->nullable()->after('morning_shift_end');
            $table->time('afternoon_shift_end')->nullable()->after('afternoon_shift_start');
            $table->integer('school_days_per_year')->default(200)->after('afternoon_shift_end');
            $table->boolean('provides_meals')->default(false)->after('school_days_per_year');
            $table->string('meal_provider')->nullable()->after('provides_meals');
            $table->json('special_programs')->nullable()->after('meal_provider');
            $table->text('achievements')->nullable()->after('special_programs');
            $table->text('challenges')->nullable()->after('achievements');
            $table->enum('accreditation_status', ['pending', 'accredited', 'expired', 'none'])->default('none')->after('challenges');
            $table->date('last_inspection_date')->nullable()->after('accreditation_status');
            $table->string('inspection_score')->nullable()->after('last_inspection_date');
            $table->index(['province', 'district', 'commune']);
            $table->index('school_type');
            $table->index('school_level');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'school_type', 'school_level', 'director_name', 'director_phone', 'director_email',
                'establishment_date', 'land_ownership', 'land_area_sqm', 'total_buildings',
                'total_classrooms', 'usable_classrooms', 'total_toilets', 'toilets_for_girls',
                'toilets_for_boys', 'has_electricity', 'electricity_source', 'has_water_supply',
                'water_source', 'has_internet', 'internet_type', 'internet_speed_mbps',
                'has_library', 'library_books_count', 'has_computer_lab', 'computers_count',
                'working_computers_count', 'has_science_lab', 'has_playground', 'has_sports_field',
                'has_fence', 'has_gate', 'has_security_guard', 'has_first_aid', 'has_school_bus',
                'school_buses_count', 'latitude', 'longitude', 'nearest_health_center',
                'distance_to_health_center', 'total_staff', 'total_teachers', 'female_teachers',
                'male_teachers', 'contract_teachers', 'volunteer_teachers', 'annual_budget',
                'government_funding', 'ngo_funding', 'community_funding', 'partner_organizations',
                'programs_implemented', 'shift_system', 'morning_shift_start', 'morning_shift_end',
                'afternoon_shift_start', 'afternoon_shift_end', 'school_days_per_year',
                'provides_meals', 'meal_provider', 'special_programs', 'achievements',
                'challenges', 'accreditation_status', 'last_inspection_date', 'inspection_score'
            ]);
        });
    }
};