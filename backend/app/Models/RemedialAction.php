<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemedialAction extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'student_id', 'school_id', 'action_type', 'title', 'description',
        'status', 'scheduled_date', 'completed_date', 'outcome', 'assigned_by',
        'due_date', 'max_score', 'min_words', 'max_words',
    ];

    protected $casts = [
        'scheduled_date'  => 'date',
        'completed_date'  => 'date',
        'due_date'        => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function submission()
    {
        return $this->hasOne(\App\Models\RemedialSubmission::class);
    }

    public function assignedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_by');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'completed'   => '#00C48C',
            'in_progress' => '#6C5CE7',
            'pending'     => '#F59E0B',
            'cancelled'   => '#FF5252',
            default       => '#888',
        };
    }

    /**
     * Computed at runtime — never stored in DB.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'completed' || $this->status === 'cancelled') {
            return false;
        }
        return $this->due_date && $this->due_date->lt(now()->startOfDay());
    }

    /**
     * True for task types that have an in-app submission workspace.
     */
    public function getIsInteractiveAttribute(): bool
    {
        return in_array($this->action_type, [
            'written_assignment', 'essay', 'file_upload',
            'quiz_test', 'practice_session', 'assignment',
        ]);
    }

    /**
     * Human-readable type label.
     */
    public function getActionTypeLabelAttribute(): string
    {
        return match($this->action_type) {
            'extra_class'         => '📚 Extra Class',
            'counseling'          => '🤝 Counseling',
            'peer_tutoring'       => '👥 Peer Tutoring',
            'assignment'          => '📝 Assignment',
            'parent_meeting'      => '👪 Parent Meeting',
            'quiz_test'           => '🧩 Quiz / Test',
            'practice_session'    => '🔁 Practice Session',
            'written_assignment'  => '✍️ Written Assignment',
            'essay'               => '📄 Essay',
            'file_upload'         => '📎 File Upload',
            default               => '📌 General Task',
        };
    }
}

