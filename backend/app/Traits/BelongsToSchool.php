<?php

namespace App\Traits;

use App\Scopes\SchoolScope;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

trait BelongsToSchool
{
    /**
     * Boot the BelongsToSchool trait for a model.
     *
     * @return void
     */
    protected static function bootBelongsToSchool()
    {
        static::addGlobalScope(new SchoolScope);

        static::creating(function ($model) {
            if (Auth::hasUser() && Auth::user()->school_id && empty($model->school_id)) {
                $model->school_id = Auth::user()->school_id;
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
