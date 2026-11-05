<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_code',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function subscription()
    {
        return $this->hasOne(SchoolSubscription::class);
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    /**
     * Generate a unique school code
     */
    public static function generateUniqueCode()
    {
        do {
            $code = 'PMRS-' . strtoupper(Str::random(6));
        } while (self::where('school_code', $code)->exists());

        return $code;
    }
}
