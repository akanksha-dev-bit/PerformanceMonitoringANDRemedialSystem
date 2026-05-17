<?php

/**
 * ============================================================================
 * NewPasswordController — Handle Password Reset Completion
 * ============================================================================
 *
 * PURPOSE:
 *   Handles the final stage of the password reset process where the user provides
 *   their email, a new password, and their valid password reset token to complete
 *   the password update.
 *
 * HOW IT WORKS:
 *   1. Displays the reset password form showing a secure password entry screen.
 *   2. Validates incoming input (token, email, password match/strength).
 *   3. Calls Laravel's built-in password broker to validate reset token eligibility.
 *   4. Updates the user's password using standard Hash security measures.
 *   5. Dispatches a PasswordReset event.
 *   6. Redirects the user to the login screen with a success state message.
 *
 * ROUTES:
 *   - GET  /reset-password/{token} → create() — Render the password reset page
 *   - POST /reset-password         → store()  — Process and save updated password
 *
 * SECURITY:
 *   - Strictly guarded by the password reset token signature.
 *   - Password confirmation and minimum character validation rules are applied.
 *
 * RELATED FILES:
 *   - View:       resources/views/auth/reset-password.blade.php
 *   - Controller: App\Http\Controllers\Auth\PasswordResetLinkController (token request)
 * ============================================================================
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
