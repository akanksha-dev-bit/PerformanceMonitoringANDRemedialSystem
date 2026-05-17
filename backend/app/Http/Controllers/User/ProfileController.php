<?php

/**
 * ============================================================================
 * ProfileController — Authenticated User Account Management
 * ============================================================================
 *
 * PURPOSE:
 *   Handles the "My Profile" page where any logged-in user (Admin, Teacher,
 *   or Student) can view and edit their personal account details, or
 *   permanently delete their account.
 *
 * HOW IT WORKS:
 *   1. User clicks their avatar/name in the navbar → "Profile" dropdown item.
 *   2. The edit page shows their current name and email (pre-filled).
 *   3. If the user changes their email, the system clears email_verified_at,
 *      forcing them to re-verify via OTP before accessing protected routes.
 *   4. Account deletion requires the user to confirm their current password,
 *      then logs them out, destroys the session, and permanently deletes
 *      their User record from the database.
 *
 * ROUTES:
 *   GET    /profile  → edit()    — Display the profile edit form
 *   PATCH  /profile  → update()  — Save updated name/email
 *   DELETE /profile  → destroy() — Permanently delete the user account
 *
 * SECURITY:
 *   - All routes require 'auth' + 'verified' middleware.
 *   - Account deletion requires current password confirmation.
 *   - Email change triggers re-verification (email_verified_at = null).
 *
 * RELATED FILES:
 *   - View:       resources/views/profile/edit.blade.php
 *   - Request:    App\Http\Requests\ProfileUpdateRequest (validation rules)
 *   - Routes:     routes/web.php → 'profile.edit', 'profile.update', 'profile.destroy'
 * ============================================================================
 */
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
