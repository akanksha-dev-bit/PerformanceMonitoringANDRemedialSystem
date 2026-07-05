<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'academic_class_id',
        'name',
    ];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'academic_class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'classroom_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'classroom_id');
    }

    public function teacherSubjectAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class, 'classroom_id');
    }

    public function getDisplayNameAttribute()
    {
        if ($this->academicClass) {
            // e.g. "Class 10 - A" or "Class 10 - 10A"
            $className = $this->academicClass->name;
            if (str_contains(strtolower($this->name), strtolower($className))) {
                return $this->name;
            }
            return $className . ' - ' . $this->name;
        }
        return $this->name;
    }
}
