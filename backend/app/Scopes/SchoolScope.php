<?php

/**
 * ============================================================================
 * SchoolScope — Multi-Tenant Data Isolation Scope
 * ============================================================================
 *
 * PURPOSE:
 *   Automatically filters all Eloquent queries to show ONLY records belonging
 *   to the authenticated user's school. Enforces strict data isolation.
 *
 * HOW IT WORKS:
 *   1. Fired on every query for models with this scope.
 *   2. Checks if user has school_id in session/token.
 *   3. Appends WHERE school_id = ? to every query automatically.
 *
 * RELATED FILES:
 *   - Models: App\Models\User, App\Models\School
 *   - Middlewares: TenantLoggerMiddleware.php (logs school activity)
 * ============================================================================
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SchoolScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::hasUser() && Auth::user()->school_id) {
            $builder->where($model->getTable() . '.school_id', Auth::user()->school_id);
        }
    }
}
