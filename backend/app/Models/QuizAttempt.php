<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'assignment_id', 'student_id', 'score', 'total_marks',
        'percentage', 'answers', 'completed_at', 'duration_taken',
    ];

    protected $casts = [
        'answers'      => 'array',
        'completed_at' => 'datetime',
        'percentage'   => 'decimal:2',
    ];

    public function assignment()
    {
        return $this->belongsTo(StudentQuizAssignment::class, 'assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

