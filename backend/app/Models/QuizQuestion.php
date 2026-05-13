<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'quiz_id', 'question', 'option_a', 'option_b',
        'option_c', 'option_d', 'correct_answer', 'explanation', 'marks',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}

