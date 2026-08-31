<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M2 — Add academic_year_id (nullable) to the three structural tables.
 *
 * Adding nullable so existing rows aren't broken; the BackfillAcademicStructure
 * artisan command will populate these values after this migration runs.
 * Phase 3 will make them NOT NULL once the backfill is verified.
 */
return new class extends Migration
{
    public function up(): void
    {
        // classrooms — already has school_id + academic_class_id
        Schema::table('classrooms', function (Blueprint $table) {
            $table->foreignId('academic_year_id')
                  ->nullable()
                  ->after('academic_class_id')
                  ->constrained('academic_years')
                  ->onDelete('set null');
        });

        // teacher_subject_assignments
        Schema::table('teacher_subject_assignments', function (Blueprint $table) {
            $table->foreignId('academic_year_id')
                  ->nullable()
                  ->after('school_id')
                  ->constrained('academic_years')
                  ->onDelete('set null');
        });

        // timetables
        Schema::table('timetables', function (Blueprint $table) {
            $table->foreignId('academic_year_id')
                  ->nullable()
                  ->after('school_id')
                  ->constrained('academic_years')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });

        Schema::table('teacher_subject_assignments', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });

        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
