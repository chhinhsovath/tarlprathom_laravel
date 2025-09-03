<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'coordinator', 'mentor', 'teacher', 'viewer'))");
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'teacher'");
        } else {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'coordinator', 'mentor', 'teacher', 'viewer') DEFAULT 'teacher'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'mentor', 'teacher', 'viewer'))");
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'teacher'");
        } else {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'mentor', 'teacher', 'viewer') DEFAULT 'teacher'");
        }
    }
};
