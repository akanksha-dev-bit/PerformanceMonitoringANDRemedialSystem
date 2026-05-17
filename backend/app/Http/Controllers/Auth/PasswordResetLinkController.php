<?php

/**
 * ============================================================================
 * PasswordResetLinkController — Handle Password Reset Token Generation
 * ============================================================================
 *
 * PURPOSE:
 *   Handles requests from users who forgot their passwords and want to trigger a
 *   password reset process. Generates and sends a secure reset link.
 *
 * HOW IT WORKS:
 *   1. Displays the forgot-password screen requesting the user's email.
 *   2. Validates the incoming email address.
 *   3. Calls Laravel's built-in password broker to create a secure password reset token.
 *   4. Generates a secure URL pointing to /reset-password/{token}.
 *   5. Dispatches an email containing the URL to the user.
 *   6. Redirects back to the request form with a success or failure status message.
 *
 * ROUTES:
 *   - GET  /forgot-password → create() — Render forgot password form
 *   - POST /forgot-password → store()  — Generate and mail the reset link
 *
 * SECURITY:
 *   - Wrapped in 'guest' middleware (only available to non-logged-in users).
 *   - Rate limited in routes to prevent bulk token generation.
 *
 * RELATED FILES:
 *   - View:       resources/views/auth/forgot-password.blade.php
 *   - Controller: App\Http\Controllers\Auth\NewPasswordController (processes token)
 * ============================================================================
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
