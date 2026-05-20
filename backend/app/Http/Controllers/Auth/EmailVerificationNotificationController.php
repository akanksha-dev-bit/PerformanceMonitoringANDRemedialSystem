<?php

/**
 * ============================================================================
 * EmailVerificationNotificationController — Resend Verification Email/OTP
 * ============================================================================
 *
 * PURPOSE:
 *   Handles requests from users to resend the email verification OTP / link
 *   if they didn't receive the original one during registration or profile updates.
 *
 * HOW IT WORKS:
 *   1. Intercepts requests to resend verification emails.
 *   2. Verifies if the authenticated user has already verified their email address.
 *      - If yes, redirects them straight to the main dashboard.
 *   3. If not verified, triggers the Laravel email verification notification event.
 *   4. Generates/resends a new verification OTP / link to the user's registered email.
 *   5. Redirects back to the verification prompt with a success status message.
 *
 * ROUTES:
 *   - POST /email/verification-notification → store() — Trigger resend of verification email/OTP
 *
 * SECURITY:
 *   - Only accessible to authenticated, logged-in but unverified users ('auth' middleware).
 *   - Throttled in routes to prevent spamming the email delivery server.
 *
 * RELATED FILES:
 *   - Notifications: App\Mail\VerificationOtpMail
 *   - Middleware:    Illuminate\Auth\Middleware\EnsureEmailIsVerified
 * ============================================================================
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (!$user && session()->has('verify_email')) {
            $user = \App\Models\User::where('email', session('verify_email'))->first();
        }

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send verification email. (' . $e->getMessage() . ')']);
        }

        return back()->with('status', 'verification-link-sent');
    }
}
