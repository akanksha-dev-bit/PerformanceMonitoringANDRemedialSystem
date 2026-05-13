<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TenantLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $schoolName = $user->school ? $user->school->name : 'No School';
            
            Log::info(sprintf(
                '[School: %s, User: %s, Role: %s] %s %s',
                $schoolName,
                $user->name,
                $user->role,
                $request->method(),
                $request->fullUrl()
            ));
        }

        return $next($request);
    }
}
