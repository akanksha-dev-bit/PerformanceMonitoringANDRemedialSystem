<?php

namespace App\Console\Commands;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Student;
use App\Models\TeacherSubjectAssignment;
use App\Models\Timetable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * BackfillAcademicStructure — Phase 2 of the academic structure migration.
 *
 * What this command does:
 *
 *   1. For each school that has classrooms but no academic years, creates a
 *      default academic year "2025-26" (01 Jun 2025 → 31 Mar 2026) and marks
 *      it as current.
 *
 *   2. For each classroom that has no academic_year_id set, assigns it the
 *      school's current academic year.
 *
 *   3. For each teacher_subject_assignment with no academic_year_id, assigns
 *      the school's current year.
 *
 *   4. For each timetable row with no academic_year_id, assigns the school's
 *      current year.
 *
 *   5. Reports any students who still have no classroom_id so they can be
 *      reviewed manually.
 *
 * Run after migrations M1 and M2 have been applied.
 * Review the output before running migrations M4 and M5 (column drops).
 *
 * Usage:
 *   php artisan pmrs:backfill-academic-structure
 *   php artisan pmrs:backfill-academic-structure --dry-run
 */
class BackfillAcademicStructure extends Command
{
    protected $signature = 'pmrs:backfill-academic-structure
                            {--dry-run : Preview what would be changed without writing anything}
                            {--year= : Override the default year label (default: 2025-26)}
                            {--starts-on= : Override starts_on date (default: 2025-06-01)}
                            {--ends-on= : Override ends_on date (default: 2026-03-31)}';

    protected $description = 'Phase 2 backfill: create default academic years and populate academic_year_id on structural tables.';

    public function handle(): int
    {
        $isDryRun   = $this->option('dry-run');
        $yearLabel  = $this->option('year')      ?? '2025-26';
        $startsOn   = $this->option('starts-on') ?? '2025-06-01';
        $endsOn     = $this->option('ends-on')   ?? '2026-03-31';

        if ($isDryRun) {
            $this->warn('DRY RUN — no changes will be written to the database.');
        }

        $this->info('');
        $this->info('═══════════════════════════════════════════════════════');
        $this->info('  PMRS Academic Structure Backfill');
        $this->info('═══════════════════════════════════════════════════════');
        $this->info('');

        $schools = School::all();

        foreach ($schools as $school) {
            $this->line("School: <comment>{$school->name}</comment> (ID {$school->id})");

            // ── Step 1: Ensure a current academic year exists ─────────────────

            $currentYear = AcademicYear::where('school_id', $school->id)
                ->where('is_current', true)
                ->first();

            if (!$currentYear) {
                $this->line("  → No current academic year found. Will create '{$yearLabel}'.");

                if (!$isDryRun) {
                    $currentYear = DB::transaction(function () use ($school, $yearLabel, $startsOn, $endsOn) {
                        // Unset any stale is_current flags
                        AcademicYear::where('school_id', $school->id)->update(['is_current' => false]);

                        return AcademicYear::create([
                            'school_id'  => $school->id,
                            'label'      => $yearLabel,
                            'starts_on'  => $startsOn,
                            'ends_on'    => $endsOn,
                            'is_current' => true,
                        ]);
                    });

                    $this->info("  ✓ Created academic year '{$yearLabel}' (ID {$currentYear->id}).");
                }
            } else {
                $this->line("  → Current year: <info>{$currentYear->label}</info> (ID {$currentYear->id})");
            }

            if ($isDryRun) {
                continue; // Skip write operations
            }

            // ── Step 2: Backfill classroom.academic_year_id ───────────────────

            $classroomsUpdated = Classroom::where('school_id', $school->id)
                ->whereNull('academic_year_id')
                ->update(['academic_year_id' => $currentYear->id]);

            if ($classroomsUpdated > 0) {
                $this->info("  ✓ Backfilled academic_year_id on {$classroomsUpdated} section(s).");
            } else {
                $this->line("  · All sections already have academic_year_id set.");
            }

            // ── Step 3: Backfill teacher_subject_assignments ──────────────────

            $tsaUpdated = TeacherSubjectAssignment::where('school_id', $school->id)
                ->whereNull('academic_year_id')
                ->update(['academic_year_id' => $currentYear->id]);

            if ($tsaUpdated > 0) {
                $this->info("  ✓ Backfilled academic_year_id on {$tsaUpdated} allocation(s).");
            } else {
                $this->line("  · All allocations already have academic_year_id set.");
            }

            // ── Step 4: Backfill timetables ───────────────────────────────────

            $timetablesUpdated = Timetable::where('school_id', $school->id)
                ->whereNull('academic_year_id')
                ->update(['academic_year_id' => $currentYear->id]);

            if ($timetablesUpdated > 0) {
                $this->info("  ✓ Backfilled academic_year_id on {$timetablesUpdated} timetable slot(s).");
            } else {
                $this->line("  · All timetable slots already have academic_year_id set.");
            }

            // ── Step 5: Flag students with no classroom_id ────────────────────

            $unlinkedStudents = Student::where('school_id', $school->id)
                ->whereNull('classroom_id')
                ->with('user')
                ->get();

            if ($unlinkedStudents->isNotEmpty()) {
                $this->warn("  ⚠ {$unlinkedStudents->count()} student(s) have no classroom_id — review manually:");
                foreach ($unlinkedStudents as $student) {
                    $this->line("    - [{$student->id}] {$student->name} ({$student->email})");
                }
            } else {
                $this->info("  ✓ All students have a classroom_id.");
            }

            $this->line('');
        }

        $this->info('Backfill complete.');
        $this->line('');
        $this->line('Next steps:');
        $this->line('  1. Verify the output above looks correct.');
        $this->line('  2. Resolve any students flagged with no classroom_id.');
        $this->line('  3. Run migrations M4 (drop subjects legacy columns) and M5 (drop students legacy columns).');
        $this->line('');

        return self::SUCCESS;
    }
}
