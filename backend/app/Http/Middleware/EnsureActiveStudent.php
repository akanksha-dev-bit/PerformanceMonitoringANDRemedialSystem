<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureActiveStudent
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user?->role === 'student' && (!$user->is_active
            || ($user->profile_completed && !$user->studentProfile?->is_active))) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['code' => 'STUDENT_INACTIVE', 'message' => 'This student account is inactive.'], 403);
            }

            return redirect()->route('login')->withErrors(['email' => 'This student account is inactive.']);
        }

        return $next($request);
    }
}
