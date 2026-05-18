<?php

/**
 * ============================================================================
 * ConfirmablePasswordController — Password Confirmation Verification Flow
 * ============================================================================
 *
 * PURPOSE:
 *   Handles verification of a user's password before allowing access to
 *   sensitive areas/actions of the application (e.g. account deletion or major profile
 *   updates).
 *
 * HOW IT WORKS:
 *   1. Intercepts users requesting access to routes protected by the 'password.confirm' middleware.
 *   2. Displays a secure password confirmation screen.
 *   3. Validates the entered password against the user's hashed password in the DB.
 *   4. Stores confirmation timestamp in the session ('auth.password_confirmed_at').
 *   5. Redirects the user back to their originally intended secure URL.
 *
 * ROUTES:
 *   - GET  /confirm-password → show()  — Display password confirmation form
 *   - POST /confirm-password → store() — Validate and confirm password
 *
 * RELATED FILES:
 *   - View:       resources/views/auth/confirm-password.blade.php
 *   - Middleware: Illuminate\Auth\Middleware\EnsurePasswordIsConfirmed
 * ============================================================================
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
