<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'name', 'code', 'class', 'max_marks', 'teaching_staff', 'type', 'is_active',
    ];

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}

