<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'subject_id', 'created_by',
        'difficulty_level', 'duration_minutes', 'is_adaptive', 'school_id',
    ];

    protected $casts = [
        'is_adaptive' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function assignments()
    {
        return $this->hasMany(StudentQuizAssignment::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function getTotalMarksAttribute()
    {
        return $this->questions()->sum('marks');
    }

    public function getQuestionCountAttribute()
    {
        return $this->questions()->count();
    }

    public function getDifficultyBadgeColorAttribute()
    {
        return match($this->difficulty_level) {
            'easy'   => '#10b981',
            'medium' => '#f59e0b',
            'hard'   => '#ef4444',
            default  => '#64748b',
        };
    }
}
