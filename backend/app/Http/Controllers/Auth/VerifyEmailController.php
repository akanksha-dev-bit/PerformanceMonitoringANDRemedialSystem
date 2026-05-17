<?php

/**
 * ============================================================================
 * VerifyEmailController — OTP & Link Email Verification Controller
 * ============================================================================
 *
 * PURPOSE:
 *   Handles email verification checks for newly registered users or updated emails.
 *   Supports both standard Laravel signature-based links AND custom OTP codes.
 *
 * HOW IT WORKS:
 *   - Link-based: (__invoke) Standard Laravel email verification request.
 *     Checks link signature. If valid, marks email as verified, fires Verified event.
 *   - OTP-based: (verifyOtp) Checks the user-entered 6-character OTP.
 *     If correct, marks user as verified, clears stored otp, fires Verified event.
 *
 * ROUTES:
 *   - GET  /verify-email/{id}/{hash} → __invoke() — Verify email via signed link
 *   - POST /verify-otp               → verifyOtp() — Verify email via 6-digit OTP code
 *
 * SECURITY:
 *   - Assures only authenticated, unverified users can submit OTPs.
 *   - Limits OTP length and composition.
 *
 * RELATED FILES:
 *   - View:   resources/views/auth/verify-email.blade.php
 *   - Mailer: App\Mail\VerificationOtpMail
 *   - Model:  App\Models\User
 * ============================================================================
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
   
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }

    /**
     * Mark the authenticated user's email address as verified using OTP.
     */
    public function verifyOtp(\Illuminate\Http\Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->email_verification_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'The provided OTP is incorrect or expired.']);
        }

        if ($request->user()->markEmailAsVerified()) {
            $request->user()->email_verification_otp = null;
            $request->user()->save();
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
