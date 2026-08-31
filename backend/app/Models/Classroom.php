<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classroom — A section (e.g. "Class 10 - A") within an academic year.
 *
 * A Classroom is a year-specific grouping of students inside an AcademicClass.
 * The name field holds the section identifier ("A", "B", "10A") — the full
 * human-readable label is available via the display_name accessor.
 *
 * Added in this iteration:
 *   - academic_year_id FK  (year scoping)
 *   - capacity             (optional headcount limit)
 *
 * Relationships:
 *   - academicClass          → belongs to AcademicClass
 *   - academicYear           → belongs to AcademicYear
 *   - students               → has many Student
 *   - timetables             → has many Timetable
 *   - teacherSubjectAssignments → has many TeacherSubjectAssignment
 *   - attendanceSessions     → has many AttendanceSession
 */
class Classroom extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'academic_class_id',
        'academic_year_id',
        'name',
        'capacity',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'academic_class_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'classroom_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'classroom_id');
    }

    public function teacherSubjectAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class, 'classroom_id');
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class, 'classroom_id');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Human-readable label: "Class 10 - A" or just "10A" if the section
     * name already contains the class name.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->academicClass) {
            $className = $this->academicClass->name;
            if (str_contains(strtolower($this->name), strtolower($className))) {
                return $this->name;
            }
            return $className . ' - ' . $this->name;
        }
        return $this->name;
    }

    /**
     * Whether the section is at or over capacity.
     * Returns null if no capacity limit is set.
     */
    public function getIsFullAttribute(): ?bool
    {
        if (is_null($this->capacity)) {
            return null;
        }
        return $this->students()->count() >= $this->capacity;
    }
}
