<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TeacherSubjectAssignment — Links a teacher to a subject in a specific section/year.
 *
 * Added in this iteration:
 *   - academic_year_id FK (year scoping so allocations are preserved per year)
 *
 * Unique constraint: (teacher_id, subject_id, classroom_id, school_id, academic_year_id)
 * — one teacher per subject per section per year. (The DB constraint was updated
 * in migration M2; this model documents it.)
 *
 * Relationships:
 *   - teacher      → belongs to User
 *   - subject      → belongs to Subject
 *   - classroom    → belongs to Classroom
 *   - academicYear → belongs to AcademicYear
 */
class TeacherSubjectAssignment extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'academic_year_id',
        'teacher_id',
        'subject_id',
        'classroom_id',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'teacher_id', 'teacher_id')
                    ->where('subject_id', $this->subject_id)
                    ->where('classroom_id', $this->classroom_id);
    }
}
