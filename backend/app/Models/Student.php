<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'user_id', 'school_id', 'roll_no', 'class', 'section',
        'dob', 'gender', 'phone', 'guardian_name', 'is_active',
    ];

    protected $casts = [
        'dob' => 'date',
        'is_active' => 'boolean',
    ];

    protected $appends = ['name', 'email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : 'Unknown';
    }

    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : '';
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function remedialActions()
    {
        return $this->hasMany(RemedialAction::class);
    }

    public function quizAssignments()
    {
        return $this->hasMany(StudentQuizAssignment::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.is_active', true);
    }

    public function scopeOrderedByName($query)
    {
        return $query->select('students.*')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name');
    }

    public function getHasMarksAttribute(): bool
    {
        return $this->marks()->exists();
    }

    public function getAveragePercentageAttribute(): float
    {
        if (!$this->has_marks) return 0;

        $marks = $this->marks;
        $totalObtained = $marks->sum('marks_obtained');
        $totalMax      = $marks->sum('max_marks');

        return $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
    }

    public function getIsSlowLearnerAttribute(): bool
    {
        if (!$this->has_marks) return false;

        $avg = $this->average_percentage;
        if ($avg < 40) return true;

        $failedSubjects = $this->marks->filter(
            fn($m) => ($m->marks_obtained / $m->max_marks * 100) < 40
        )->count();

        return $failedSubjects >= 2;
    }

    public function getPerformanceLabelAttribute(): string
    {
        if (!$this->has_marks) return 'Not Evaluated';

        $avg = $this->average_percentage;
        return match(true) {
            $avg >= 60 => 'Good',
            $avg >= 40 => 'At Risk',
            default    => 'Slow Learner',
        };
    }

    public function getPerformanceStatusAttribute(): string
    {
        if (!$this->has_marks) return 'no_data';
        
        $avg = $this->average_percentage;
        return match(true) {
            $avg >= 60 => 'good',
            $avg >= 40 => 'at_risk',
            default    => 'slow',
        };
    }

    public function getPerformanceColorAttribute(): string
    {
        return match($this->performance_status) {
            'good'    => '#00C48C',
            'at_risk' => '#F59E0B',
            'slow'    => '#FF5252',
            default   => '#9CA3AF', // Gray for no_data
        };
    }
}

