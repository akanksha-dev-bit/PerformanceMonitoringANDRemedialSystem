<?php

/**
 * ============================================================================
 * PasswordController — In-App Password Update
 * ============================================================================
 *
 * PURPOSE:
 *   Handles password changes for authenticated users when they are logged
 *   into the application and want to update their password from their profile settings page.
 *
 * HOW IT WORKS:
 *   1. Intercepts the request to update password.
 *   2. Validates:
 *      - current_password: must match the user's active password in DB.
 *      - password: new password matching the confirmation fields.
 *   3. Hashes the new password and updates the User model record.
 *   4. Redirects back to the profile settings page with a success message.
 *
 * ROUTES:
 *   - PUT /password → update() — Update the current authenticated user's password
 *
 * SECURITY:
 *   - Accessible only to logged-in users ('auth' middleware).
 *   - Standard Laravel Rules\Password are enforced.
 *   - Bag validation structure used ('updatePassword') to separate validation errors in the view.
 *
 * RELATED FILES:
 *   - View:       resources/views/profile/edit.blade.php (renders the password form)
 *   - Controller: App\Http\Controllers\User\ProfileController
 * ============================================================================
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
