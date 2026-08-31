<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

/**
 * Timetable — Schedules a teacher/subject/section combination into a period slot.
 *
 * Changes in this iteration:
 *   - academic_year_id added (timetables are year-scoped)
 *   - DB-level UNIQUE on (school_id, classroom_id, day_of_week, period_number, academic_year_id)
 *     means the PHP boot() checks are now a redundant safety net rather than
 *     the primary guard. They remain for human-readable error messages.
 *
 * Conflict checks in boot():
 *   1. Teacher double-booking (same teacher, overlapping times, same day/year)
 *   2. Classroom double-booking (same room, overlapping times, same day/year)
 *   3. Period/time mismatch (period number must map to consistent times in a room)
 *
 * Relationships:
 *   - classroom    → belongs to Classroom
 *   - subject      → belongs to Subject
 *   - teacher      → belongs to User
 *   - academicYear → belongs to AcademicYear
 */
class Timetable extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'academic_year_id',
        'classroom_id',
        'subject_id',
        'teacher_id',
        'day_of_week',
        'period_number',
        'start_time',
        'end_time',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    // ── Conflict Validation ───────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Timetable $timetable) {
            if (empty($timetable->school_id) && auth()->check()) {
                $timetable->school_id = auth()->user()->school_id;
            }

            $schoolId = $timetable->school_id;
            $yearId   = $timetable->academic_year_id;
            $day      = $timetable->day_of_week;
            $start    = $timetable->start_time;
            $end      = $timetable->end_time;
            $period   = $timetable->period_number;

            // 1. Teacher Double-Booking Check (time overlap, same day+year)
            $teacherConflict = Timetable::where('school_id', $schoolId)
                ->where('teacher_id', $timetable->teacher_id)
                ->where('day_of_week', $day)
                ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                ->where('id', '!=', $timetable->id)
                ->where(fn($q) => $q->where('start_time', '<', $end)
                                    ->where('end_time', '>', $start))
                ->first();

            if ($teacherConflict) {
                $teacherName = User::find($timetable->teacher_id)?->name ?? 'Teacher';
                throw ValidationException::withMessages([
                    'teacher_id' => "Conflict: {$teacherName} is already scheduled in " .
                        ($teacherConflict->classroom?->display_name ?? 'another section') .
                        " during this time ({$teacherConflict->start_time}–{$teacherConflict->end_time}) on {$day}.",
                ]);
            }

            // 2. Classroom Double-Booking Check (time overlap, same day+year)
            $roomConflict = Timetable::where('school_id', $schoolId)
                ->where('classroom_id', $timetable->classroom_id)
                ->where('day_of_week', $day)
                ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                ->where('id', '!=', $timetable->id)
                ->where(fn($q) => $q->where('start_time', '<', $end)
                                    ->where('end_time', '>', $start))
                ->first();

            if ($roomConflict) {
                $subjectName = Subject::find($roomConflict->subject_id)?->name ?? 'another subject';
                throw ValidationException::withMessages([
                    'classroom_id' => "Conflict: Section already has {$subjectName} scheduled " .
                        "during this time ({$roomConflict->start_time}–{$roomConflict->end_time}) on {$day}.",
                ]);
            }

            // 3. Period/Time Consistency Check (period number must use same times in this room)
            $periodConflict = Timetable::where('school_id', $schoolId)
                ->where('classroom_id', $timetable->classroom_id)
                ->where('day_of_week', $day)
                ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                ->where('id', '!=', $timetable->id)
                ->where(fn($q) => $q
                    ->where(fn($sub) => $sub->where('period_number', $period)
                        ->where(fn($t) => $t->where('start_time', '!=', $start)
                                            ->orWhere('end_time', '!=', $end)))
                    ->orWhere(fn($sub) => $sub->where('period_number', '!=', $period)
                        ->where('start_time', $start)
                        ->where('end_time', $end)))
                ->first();

            if ($periodConflict) {
                throw ValidationException::withMessages([
                    'period_number' => "Conflict: Period configuration mismatch. " .
                        "Period {$periodConflict->period_number} is already defined as " .
                        "{$periodConflict->start_time}–{$periodConflict->end_time} on {$day}.",
                ]);
            }
        });
    }
}
