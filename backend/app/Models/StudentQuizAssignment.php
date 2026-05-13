<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuizAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 'student_id', 'assigned_by',
        'start_date', 'end_date', 'repeat_days', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'assignment_id');
    }

    /**
     * How many attempts have been completed for this assignment.
     */
    public function getAttemptsUsedAttribute()
    {
        return $this->attempts()->whereNotNull('completed_at')->count();
    }

    /**
     * How many attempts remain (based on repeat_days).
     */
    public function getAttemptsRemainingAttribute()
    {
        return max(0, $this->repeat_days - $this->attempts_used);
    }

    /**
     * Whether the student can attempt today.
     */
    public function getTodayAttemptAvailableAttribute()
    {
        if ($this->status !== 'active') return false;
        if (now()->lt($this->start_date) || now()->gt($this->end_date)) return false;
        if ($this->attempts_used >= $this->repeat_days) return false;

        // Check if already attempted today
        $attemptedToday = $this->attempts()
            ->whereDate('created_at', today())
            ->whereNotNull('completed_at')
            ->exists();

        return !$attemptedToday;
    }

    /**
     * Get the latest attempt score percentage.
     */
    public function getLatestScoreAttribute()
    {
        $latest = $this->attempts()->whereNotNull('completed_at')->latest()->first();
        return $latest ? $latest->percentage : null;
    }
}
