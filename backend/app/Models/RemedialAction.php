<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemedialAction extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'student_id', 'action_type', 'title', 'description',
        'status', 'scheduled_date', 'completed_date', 'outcome', 'assigned_by',
    ];

    protected $casts = [
        'scheduled_date'  => 'date',
        'completed_date'  => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
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
}

