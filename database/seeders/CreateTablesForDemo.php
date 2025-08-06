<?php

namespace Database\Seeders;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CreateTablesForDemo extends Seeder
{
    public function run(): void
    {
        // Create schools table
        if (! Schema::hasTable('schools')) {
            Schema::create('schools', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('address')->nullable();
                $table->string('contact_person')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('province')->nullable();
                $table->string('district')->nullable();
                $table->string('subdistrict')->nullable();
                $table->string('postal_code')->nullable();
                $table->string('school_code')->unique();
                $table->string('education_service_area')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        // Create users table if not exists
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['admin', 'teacher', 'mentor', 'coordinator', 'viewer'])->default('teacher');
                $table->foreignId('school_id')->nullable()->constrained()->onDelete('set null');
                $table->string('phone')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('profile_photo')->nullable();
                $table->string('sex')->nullable();
                $table->string('holding_classes')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Create classes table
        if (! Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('school_id')->constrained()->onDelete('cascade');
                $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('grade')->nullable();
                $table->string('section')->nullable();
                $table->integer('academic_year')->nullable();
                $table->timestamps();
            });
        }

        // Create students table
        if (! Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained()->onDelete('cascade');
                $table->string('student_code')->unique()->nullable();
                $table->string('name')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('nickname')->nullable();
                $table->date('birthdate')->nullable();
                $table->enum('gender', ['male', 'female'])->nullable();
                $table->string('sex')->nullable();
                $table->integer('grade');
                $table->integer('age')->nullable();
                $table->string('class')->nullable();
                $table->string('section')->nullable();
                $table->integer('student_number')->nullable();
                $table->enum('status', ['active', 'inactive', 'transferred'])->default('active');
                $table->string('parent_name')->nullable();
                $table->string('parent_phone')->nullable();
                $table->text('address')->nullable();
                $table->string('subdistrict')->nullable();
                $table->string('district')->nullable();
                $table->string('province')->nullable();
                $table->string('postal_code')->nullable();
                $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
                $table->string('photo')->nullable();
                $table->timestamps();
            });
        }

        // Create mentor_school pivot table
        if (! Schema::hasTable('mentor_school')) {
            Schema::create('mentor_school', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['school_id', 'user_id']);
            });
        }

        // Create student_teacher pivot table
        if (! Schema::hasTable('student_teacher')) {
            Schema::create('student_teacher', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->foreignId('teacher_id')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['student_id', 'teacher_id']);
            });
        }

        // Create assessments table
        if (! Schema::hasTable('assessments')) {
            Schema::create('assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->enum('cycle', ['baseline', 'midline', 'endline']);
                $table->enum('subject', ['math', 'khmer']);
                $table->string('level');
                $table->float('score')->nullable();
                $table->date('assessed_at');
                $table->boolean('is_locked')->default(false);
                $table->unsignedBigInteger('locked_by')->nullable();
                $table->timestamp('locked_at')->nullable();
                $table->foreign('locked_by')->references('id')->on('users')->nullOnDelete();
                $table->foreignId('assessor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // Create mentoring_visits table
        if (! Schema::hasTable('mentoring_visits')) {
            Schema::create('mentoring_visits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
                $table->date('visit_date');
                $table->text('observations')->nullable();
                $table->text('feedback')->nullable();
                $table->text('action_items')->nullable();
                $table->json('questionnaire_data')->nullable();
                $table->boolean('follow_up_required')->default(false);
                $table->boolean('is_locked')->default(false);
                $table->unsignedBigInteger('locked_by')->nullable();
                $table->timestamp('locked_at')->nullable();
                $table->foreign('locked_by')->references('id')->on('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // Create assessment_histories table
        if (! Schema::hasTable('assessment_histories')) {
            Schema::create('assessment_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->enum('assessment_type', ['baseline', 'midline', 'endline']);
                $table->enum('subject', ['math', 'khmer']);
                $table->string('level');
                $table->float('score')->nullable();
                $table->date('assessed_at');
                $table->foreignId('assessor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->json('assessment_data')->nullable();
                $table->boolean('is_locked')->default(false);
                $table->unsignedBigInteger('locked_by')->nullable();
                $table->timestamp('locked_at')->nullable();
                $table->foreign('locked_by')->references('id')->on('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // Create student_assessment_eligibility table
        if (! Schema::hasTable('student_assessment_eligibility')) {
            Schema::create('student_assessment_eligibility', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->enum('assessment_type', ['midline', 'endline']);
                $table->boolean('is_eligible')->default(false);
                $table->date('checked_at');
                $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->unique(['student_id', 'assessment_type']);
            });
        }

        // Create resources table
        if (! Schema::hasTable('resources')) {
            Schema::create('resources', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('file_path')->nullable();
                $table->string('url')->nullable();
                $table->string('type')->nullable();
                $table->string('category')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // Create translations table
        if (! Schema::hasTable('translations')) {
            Schema::create('translations', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('km')->nullable();
                $table->text('en')->nullable();
                $table->string('group')->default('general');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index('key');
                $table->index('group');
                $table->index('is_active');
            });
        }

        // Create cache table
        if (! Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        // Create cache_locks table
        if (! Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        // Create sessions table
        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // Create jobs table
        if (! Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        // Create failed_jobs table
        if (! Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        $this->command->info('Tables created successfully!');
    }
}
