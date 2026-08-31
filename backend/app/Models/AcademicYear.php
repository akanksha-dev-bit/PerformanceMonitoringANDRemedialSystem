<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AcademicYear — Scopes all structural entities to a specific school year.
 *
 * Every school manages its own academic years independently (multi-tenant).
 * The is_current flag indicates the active year; helpers on this model
 * make it easy to fetch the current year for a school.
 *
 * Relationships:
 *   - school     → belongs to School
 *   - classrooms → has many Classroom (sections scoped to this year)
 *   - teacherSubjectAssignments → has many TeacherSubjectAssignment
 *   - timetables → has many Timetable
 */
class AcademicYear extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'label',
        'starts_on',
        'ends_on',
        'is_current',
    ];

    protected $casts = [
        'starts_on'  => 'date',
        'ends_on'    => 'date',
        'is_current' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'academic_year_id');
    }

    public function teacherSubjectAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class, 'academic_year_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'academic_year_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Scope to retrieve only the current academic year for a given school.
     * Usage: AcademicYear::current($schoolId)->first()
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Get the currently active AcademicYear for a school, or null if none set.
     */
    public static function currentFor(int $schoolId): ?self
    {
        return static::where('school_id', $schoolId)
                     ->where('is_current', true)
                     ->first();
    }

    /**
     * Set this year as current (unsets any previously current year for the school).
     * Uses a transaction to prevent the school from ever having no current year.
     */
    public function setAsCurrent(): void
    {
        \DB::transaction(function () {
            static::where('school_id', $this->school_id)
                  ->where('id', '!=', $this->id)
                  ->update(['is_current' => false]);

            $this->update(['is_current' => true]);
        });
    }
}
