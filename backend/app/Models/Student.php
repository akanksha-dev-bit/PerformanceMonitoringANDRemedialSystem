<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'user_id', 'school_id', 'classroom_id', 'roll_no',
        'dob', 'gender', 'phone', 'guardian_name', 'is_active',
        'xp_points', 'study_streak', 'last_activity_date',
    ];

    protected $casts = [
        'dob'                => 'date',
        'is_active'          => 'boolean',
        'last_activity_date' => 'date',
    ];

    protected $appends = ['name', 'email', 'overall_attendance'];

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
        if ($this->relationLoaded('marks')) {
            return $this->marks->isNotEmpty();
        }
        if (isset($this->attributes['marks_count'])) {
            return ((int) $this->attributes['marks_count']) > 0;
        }
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

        $marks = $this->marks;
        $failedSubjects = $marks->filter(
            fn($m) => $m->max_marks > 0 && (($m->marks_obtained / $m->max_marks) * 100) < 40
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
            default   => '#9CA3AF',
        };
    }

    /**
     * Increment XP and persist to DB.
     */
    public function addXP(int $amount): void
    {
        $this->increment('xp_points', $amount);
        $this->updateStreak();
    }

    /**
     * Update study streak based on last_activity_date.
     */
    public function updateStreak(): void
    {
        $today = now()->toDateString();

        if ($this->last_activity_date === null) {
            $this->update(['study_streak' => 1, 'last_activity_date' => $today]);
            return;
        }

        $daysSinceLast = now()->startOfDay()->diffInDays($this->last_activity_date->startOfDay());

        if ($daysSinceLast === 0) {
            // Already active today — no change
            return;
        } elseif ($daysSinceLast === 1) {
            // Consecutive day — extend streak
            $this->update([
                'study_streak'       => $this->study_streak + 1,
                'last_activity_date' => $today,
            ]);
        } else {
            // Streak broken — reset
            $this->update(['study_streak' => 1, 'last_activity_date' => $today]);
        }
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'student_id');
    }

    public function getOverallAttendanceAttribute()
    {
        if ($this->relationLoaded('attendanceRecords')) {
            $total = $this->attendanceRecords->count();
            if ($total === 0) return 100.0;
            $present = $this->attendanceRecords->where('status', 'present')->count();
            return round(($present / $total) * 100, 2);
        }

        if (isset($this->attributes['attendance_records_count'])) {
            $total = (int) $this->attributes['attendance_records_count'];
            if ($total === 0) return 100.0;
            $present = isset($this->attributes['present_attendance_records_count'])
                ? (int) $this->attributes['present_attendance_records_count']
                : $this->attendanceRecords()->where('status', 'present')->count();
            return round(($present / $total) * 100, 2);
        }

        $total = $this->attendanceRecords()->count();
        if ($total === 0) return 100.0;
        $present = $this->attendanceRecords()->where('status', 'present')->count();
        return round(($present / $total) * 100, 2);
    }

    public function getSubjectAttendance($subjectId)
    {
        if ($this->relationLoaded('attendanceRecords')) {
            $records = $this->attendanceRecords->filter(function($r) use ($subjectId) {
                return $r->session && $r->session->subject_id == $subjectId;
            });
            $total = $records->count();
            if ($total === 0) return 100.0;
            $present = $records->where('status', 'present')->count();
            return round(($present / $total) * 100, 2);
        }

        $total = $this->attendanceRecords()
            ->whereHas('session', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })->count();

        if ($total === 0) return 100.0;

        $present = $this->attendanceRecords()
            ->whereHas('session', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })
            ->where('status', 'present')
            ->count();

        return round(($present / $total) * 100, 2);
    }

    public function getIsHighRiskAttribute(): bool
    {
        // Low Marks (< 40%) AND Low Attendance (< 75%)
        return $this->overall_attendance < 75 && $this->average_percentage < 40;
    }

    public function getIsLearningDifficultyAttribute(): bool
    {
        // Good Attendance (>= 75%) AND Low Marks (< 40%)
        return $this->overall_attendance >= 75 && $this->average_percentage < 40;
    }
}
