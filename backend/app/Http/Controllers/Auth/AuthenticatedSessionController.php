<?php
/**
 * ============================================================================
 * AuthenticatedSessionController — Login & Logout Management
 * ============================================================================
 *
 * PURPOSE:
 *   Handles user authentication and session management.
 *   Manages login, logout, and session regeneration.
 *
 * SECURITY FEATURES:
 *   - Username/email + password authentication
 *   - Session regeneration to prevent fixation attacks
 *   - Logout with session invalidation
 *   - Throttle protection (via LoginRequest)
 *
 * ROUTES:
 *   - GET    /login         → create() - Show login form
 *   - POST   /login         → store()  - Authenticate user
 *   - POST   /logout        → destroy() - Logout user
 *
 * RELATED FILES:
 *   - Requests:     App\Http\Requests\Auth\LoginRequest.php
 *   - Middleware:   App\Http\Middleware\EnsureProfileCompleted.php
 *   - Models:       App\Models\User.php
 *   - Views:        resources/views/auth/login.blade.php
 * ============================================================================
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
