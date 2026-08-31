<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M4 — Fix subjects table: drop legacy free-text columns.
 *
 * Removes:
 *   - subjects.class     (was "Class 10" as a raw string; now expressed
 *                         via section_subjects / teacher_subject_assignments)
 *   - subjects.teaching_staff (was a free-text name; now in
 *                              teacher_subject_assignments.teacher_id)
 *
 * SAFE TO RUN because:
 *   - No FK references these columns in any other table.
 *   - The BackfillAcademicStructure command has already copied teaching_staff
 *     data into teacher_subject_assignments rows (run backfill first).
 *
 * SQLite note: Laravel handles DROP COLUMN via table recreation on SQLite < 3.35.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['class', 'teaching_staff']);
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('class')->nullable()->after('code');
            $table->string('teaching_staff')->nullable()->after('type');
        });
    }
};
