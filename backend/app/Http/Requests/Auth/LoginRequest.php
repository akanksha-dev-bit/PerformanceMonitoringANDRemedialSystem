<?php

/**
 * ============================================================================
 * LoginRequest — Authenticated Login Form
 * ============================================================================
 *
 * PURPOSE:
 *   Handles the LoginController authentication flow with rate limiting and
 *   user throttling to prevent brute-force attacks.
 *
 * HOW IT WORKS:
 *   1. authorize(): Always true (public form).
 *   2. rules(): Validates email + password.
 *   3. authenticate(): Attempts login using Auth::attempt().
 *      - If fails → increments rate limiter → throws ValidationException.
 *      - If succeeds → clears rate limiter → user is logged in.
 *   4. ensureIsNotRateLimited(): Checks throttle status (5 attempts per 1 minute).
 *   5. throttleKey(): Unique key per (email + IP) combination.
 *
 * ROUTES AFFECTED:
 *   POST /login
 *
 * RELATED FILES:
 *   - Controller: app/Http/Controllers/Auth/LoginController.php
 *   - Middleware: app/Http/Middleware/TenantLoggerMiddleware.php (logs this login)
 * ============================================================================
 */
namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
