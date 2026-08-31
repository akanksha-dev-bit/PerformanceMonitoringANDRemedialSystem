<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M5 — Fix students: drop legacy class/section varchars and scope roll_no.
 *
 * The students table originally stored class ("Class 10") and section ("A")
 * as free-text strings alongside the structured classroom_id FK that was
 * added later. This migration removes the ambiguity:
 *
 *   - Drops students.class and students.section (data now in classroom_id)
 *   - Changes roll_no unique constraint from global → per-school (school_id, roll_no)
 *     so two schools can both have roll number "001" without conflict.
 *
 * SAFE TO RUN after the BackfillAcademicStructure command verifies all
 * students have a valid classroom_id.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop legacy free-text columns
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['class', 'section']);
        });

        // 2. Re-scope roll_no uniqueness: was global, now per-school
        Schema::table('students', function (Blueprint $table) {
            // Drop the old global unique index (name may vary by DB)
            try {
                $table->dropUnique(['roll_no']);
            } catch (\Exception $e) {
                // Index may not exist under that exact name — ignore
            }

            // Add scoped composite unique: (school_id, roll_no)
            $table->unique(['school_id', 'roll_no'], 'students_school_roll_unique');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_school_roll_unique');
            $table->string('roll_no')->nullable()->unique()->change();
            $table->string('class')->nullable()->after('school_id');
            $table->string('section')->nullable()->after('class');
        });
    }
};
