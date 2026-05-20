<?php

/**
 * ============================================================================
 * EmailVerificationPromptController — Email Verification Intercept & Prompt
 * ============================================================================
 *
 * PURPOSE:
 *   Intercepts authenticated users who have not yet verified their email
 *   addresses and displays the OTP verification prompt screen.
 *
 * HOW IT WORKS:
 *   1. Intercepts incoming requests when users attempt to access pages protected
 *      by email verification check.
 *   2. Checks if the current authenticated user's email is already verified.
 *      - If verified, redirects them to their intended dashboard location.
 *      - If not verified, renders the 'auth.verify-email' view containing the
 *        OTP input screen.
 *
 * ROUTES:
 *   - GET /verify-email → __invoke() — Intercept and show OTP verification screen
 *
 * SECURITY:
 *   - Single invoke method controller strictly managed by Laravel route system.
 *   - Requires user authentication ('auth' middleware).
 *
 * RELATED FILES:
 *   - View:       resources/views/auth/verify-email.blade.php
 *   - Controller: App\Http\Controllers\Auth\VerifyEmailController (OTP verification logic)
 * ============================================================================
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        if (!$user && session()->has('verify_email')) {
            $user = \App\Models\User::where('email', session('verify_email'))->first();
        }

        if (!$user) {
            return redirect()->route('login');
        }

        return $user->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
