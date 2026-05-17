<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemedialSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'remedial_action_id', 'student_id', 'school_id',
        'content', 'word_count',
        'file_path', 'file_original_name', 'file_mime_type',
        'submitted_at', 'teacher_feedback', 'teacher_score',
        'reviewed_by', 'reviewed_at', 'submission_status',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function remedialAction()
    {
        return $this->belongsTo(RemedialAction::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getIsSubmittedAttribute(): bool
    {
        return $this->submitted_at !== null;
    }

    public function getCanEditAttribute(): bool
    {
        return in_array($this->submission_status, ['draft', 'needs_improvement']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->submission_status) {
            'draft'              => 'Draft',
            'submitted'          => 'Submitted',
            'reviewed'           => 'Reviewed',
            'needs_improvement'  => 'Needs Improvement',
            default              => ucfirst($this->submission_status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->submission_status) {
            'draft'              => '#94a3b8',
            'submitted'          => '#6C5CE7',
            'reviewed'           => '#10b981',
            'needs_improvement'  => '#f59e0b',
            default              => '#94a3b8',
        };
    }
}
