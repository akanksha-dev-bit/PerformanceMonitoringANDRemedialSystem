<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M6 — Add DB-level UNIQUE constraint to timetables.
 *
 * Previously, double-booking was caught only in PHP (Timetable::boot()).
 * This adds a proper database-level unique index so:
 *   - A classroom cannot have two subjects scheduled in the same period
 *     on the same day in the same academic year.
 *   - Enforced even if records are inserted directly or in parallel.
 *
 * The composite key: (school_id, classroom_id, day_of_week, period_number, academic_year_id)
 *
 * Note: SQLite creates a unique index for this rather than a table constraint —
 * both achieve the same enforcement behaviour.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->unique(
                ['school_id', 'classroom_id', 'day_of_week', 'period_number', 'academic_year_id'],
                'timetable_classroom_slot_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropUnique('timetable_classroom_slot_unique');
        });
    }
};
