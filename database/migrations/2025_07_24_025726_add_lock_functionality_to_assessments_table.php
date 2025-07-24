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
        Schema::table('assessments', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('assessed_at');
            $table->unsignedBigInteger('locked_by')->nullable()->after('is_locked');
            $table->timestamp('locked_at')->nullable()->after('locked_by');
            $table->foreign('locked_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('mentoring_visits', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('follow_up_required');
            $table->unsignedBigInteger('locked_by')->nullable()->after('is_locked');
            $table->timestamp('locked_at')->nullable()->after('locked_by');
            $table->foreign('locked_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign(['locked_by']);
            $table->dropColumn('is_locked');
            $table->dropColumn('locked_by');
            $table->dropColumn('locked_at');
        });

        Schema::table('mentoring_visits', function (Blueprint $table) {
            $table->dropForeign(['locked_by']);
            $table->dropColumn('is_locked');
            $table->dropColumn('locked_by');
            $table->dropColumn('locked_at');
        });
    }
};
