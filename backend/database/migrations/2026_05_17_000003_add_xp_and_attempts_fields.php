<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('xp_points')->default(0)->after('is_active');
            $table->unsignedSmallInteger('study_streak')->default(0)->after('xp_points');
            $table->date('last_activity_date')->nullable()->after('study_streak');
        });

        Schema::table('student_quiz_assignments', function (Blueprint $table) {
            $table->unsignedTinyInteger('max_attempts')->default(1)->after('repeat_days');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['xp_points', 'study_streak', 'last_activity_date']);
        });

        Schema::table('student_quiz_assignments', function (Blueprint $table) {
            $table->dropColumn('max_attempts');
        });
    }
};
