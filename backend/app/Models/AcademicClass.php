<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicClass extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'name',
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'academic_class_id');
    }
}
