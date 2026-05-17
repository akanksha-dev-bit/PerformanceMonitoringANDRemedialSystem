<?php

/**
 * ============================================================================
 * StudentProfileController — First-Time Student Profile Completion
 * ============================================================================
 *
 * PURPOSE:
 *   When a student registers via the invite link (/join/{code}), their
 *   account is created with profile_completed = false. Before they can
 *   access any dashboard or features, they MUST complete their academic
 *   profile (class, section, roll number, phone). This controller handles
 *   that one-time onboarding step.
 *
 * HOW IT WORKS:
 *   1. Student registers and logs in → EnsureProfileCompleted middleware
 *      detects profile_completed = false → redirects to /complete-profile.
 *   2. Student fills in: class, section, roll number, phone.
 *   3. On submit, a Student record is created (or updated) linked to
 *      the authenticated User, and profile_completed is set to true.
 *   4. Student is redirected to the dashboard and can now use the system.
 *
 * ROUTES:
 *   GET  /complete-profile  → create() — Show the profile completion form
 *   POST /complete-profile  → store()  — Save profile and redirect to dashboard
 *
 * SECURITY:
 *   - Requires 'auth' + 'verified' middleware.
 *   - Only students with profile_completed = false will be redirected here.
 *   - Uses updateOrCreate to prevent duplicate Student records.
 *
 * RELATED FILES:
 *   - View:       resources/views/students/complete-profile.blade.php
 *   - Middleware:  App\Http\Middleware\EnsureProfileCompleted
 *   - Model:      App\Models\Student (via User->studentProfile())
 *   - Routes:     routes/web.php → 'complete-profile', 'complete-profile.store'
 * ============================================================================
 */
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function create()
    {
        return view('students.complete-profile');
    }

    public function store(Request $request)
    {
        $request->validate([
            'class' => ['required', 'string', 'max:255'],
            'section' => ['required', 'string', 'max:255'],
            'roll_number' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user = auth()->user();

        $user->studentProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'school_id' => $user->school_id,
                'class' => $request->class,
                'section' => $request->section,
                'roll_no' => $request->roll_number,
                'phone' => $request->phone,
                'is_active' => true,
            ]
        );

        $user->update(['profile_completed' => true]);

        return redirect()->route('dashboard');
    }
}
