<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'student_id', 'subject_id', 'marks_obtained',
        'max_marks', 'exam_type', 'academic_year', 'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getPercentageAttribute(): float
    {
        return $this->max_marks > 0
            ? round(($this->marks_obtained / $this->max_marks) * 100, 2)
            : 0;
    }

    public function getIsPassAttribute(): bool
    {
        return $this->percentage >= 40;
    }
}

