<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AcademicClass — Represents a grade level (e.g. "Class 10").
 *
 * An AcademicClass is a school-wide, year-independent concept.
 * Year-specific groupings (sections) are stored in the Classroom model.
 *
 * Relationships:
 *   - school     → belongs to School (via BelongsToSchool trait)
 *   - classrooms → has many Classroom (the year-specific sections)
 */
class AcademicClass extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'name',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'academic_class_id');
    }

    /**
     * Classrooms for a specific academic year.
     * Usage: $class->classroomsForYear($yearId)
     */
    public function classroomsForYear(int $yearId)
    {
        return $this->classrooms()->where('academic_year_id', $yearId);
    }
}
