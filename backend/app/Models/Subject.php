<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Subject — An academic subject taught at a school.
 *
 * Changes in this iteration:
 *   - Removed `class` and `teaching_staff` from $fillable — these legacy
 *     columns are being dropped (see migration M4). Subject-to-classroom
 *     relationships are now expressed via TeacherSubjectAssignment.
 *   - Added BelongsToSchool trait (school_id is already on the table since
 *     migration 2026_05_13_191829; the trait ensures queries are school-scoped).
 *
 * Unique constraint: (school_id, code) — enforced at DB level.
 *
 * Relationships:
 *   - marks                  → has many Mark
 *   - teacherAssignments     → has many TeacherSubjectAssignment
 */
class Subject extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'type',
        'max_marks',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_marks' => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class, 'subject_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
