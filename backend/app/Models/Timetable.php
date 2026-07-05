<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Timetable extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'classroom_id',
        'subject_id',
        'teacher_id',
        'day_of_week',
        'period_number',
        'start_time',
        'end_time',
    ];

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

    /**
     * Perform strict conflict validations before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Timetable $timetable) {
            // Ensure school_id matches across the board
            if (empty($timetable->school_id) && auth()->check()) {
                $timetable->school_id = auth()->user()->school_id;
            }

            $schoolId = $timetable->school_id;
            $day = $timetable->day_of_week;
            $start = $timetable->start_time;
            $end = $timetable->end_time;
            $period = $timetable->period_number;

            // 1. Teacher Double-Booking Check
            // A teacher cannot teach two classes at the same time on the same day.
            $teacherConflict = Timetable::where('school_id', $schoolId)
                ->where('teacher_id', $timetable->teacher_id)
                ->where('day_of_week', $day)
                ->where('id', '!=', $timetable->id) // ignore self on update
                ->where(function ($query) use ($start, $end) {
                    $query->where('start_time', '<', $end)
                          ->where('end_time', '>', $start);
                })
                ->first();

            if ($teacherConflict) {
                $teacherName = User::find($timetable->teacher_id)?->name ?? 'Teacher';
                throw ValidationException::withMessages([
                    'teacher_id' => "Conflict: {$teacherName} is already scheduled in classroom " . 
                                    ($teacherConflict->classroom?->display_name ?? 'another classroom') . 
                                    " during this time ({$teacherConflict->start_time} - {$teacherConflict->end_time}) on {$day}."
                ]);
            }

            // 2. Classroom Double-Booking Check
            // A classroom cannot host two subjects/teachers at the same time on the same day.
            $roomConflict = Timetable::where('school_id', $schoolId)
                ->where('classroom_id', $timetable->classroom_id)
                ->where('day_of_week', $day)
                ->where('id', '!=', $timetable->id)
                ->where(function ($query) use ($start, $end) {
                    $query->where('start_time', '<', $end)
                          ->where('end_time', '>', $start);
                })
                ->first();

            if ($roomConflict) {
                $subjectName = Subject::find($roomConflict->subject_id)?->name ?? 'another subject';
                throw ValidationException::withMessages([
                    'classroom_id' => "Conflict: Classroom is already booked for {$subjectName} during this time ({$roomConflict->start_time} - {$roomConflict->end_time}) on {$day}."
                ]);
            }

            // 3. Overlapping Periods Check
            // Check if period number matches but has conflicting times, or times match but period numbers differ.
            $periodConflict = Timetable::where('school_id', $schoolId)
                ->where('classroom_id', $timetable->classroom_id)
                ->where('day_of_week', $day)
                ->where('id', '!=', $timetable->id)
                ->where(function ($query) use ($period, $start, $end) {
                    $query->where(function($q) use ($period, $start, $end) {
                        $q->where('period_number', $period)
                          ->where(function($sub) use ($start, $end) {
                              $sub->where('start_time', '!=', $start)
                                  ->orWhere('end_time', '!=', $end);
                          });
                    })->orWhere(function($q) use ($period, $start, $end) {
                        $q->where('period_number', '!=', $period)
                          ->where('start_time', $start)
                          ->where('end_time', $end);
                    });
                })
                ->first();

            if ($periodConflict) {
                throw ValidationException::withMessages([
                    'period_number' => "Conflict: Period configuration mismatch. Period {$periodConflict->period_number} is already defined as {$periodConflict->start_time} - {$periodConflict->end_time} on {$day}."
                ]);
            }
        });
    }
}
