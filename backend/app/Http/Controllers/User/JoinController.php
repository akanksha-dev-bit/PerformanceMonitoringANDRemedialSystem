<?php

/**
 * ============================================================================
 * JoinController — Student Invite-Link Registration
 * ============================================================================
 *
 * PURPOSE:
 *   Handles the public student self-registration flow via school-specific
 *   invite links. When an Admin shares a URL like `/join/PMRS-ABCD1234`,
 *   this controller powers the entire sign-up experience for new students
 *   joining that specific school.
 *
 * HOW IT WORKS:
 *   1. Admin copies their school's invite link from the Admin Dashboard.
 *   2. Admin shares the link with students (e.g. via WhatsApp, email, notice board).
 *   3. Student opens the link → sees a branded registration form for that school.
 *   4. Student fills name, email, password → account is created with:
 *        - role = 'student'
 *        - school_id = the school from the invite link
 *        - profile_completed = false (they must complete profile next)
 *   5. Laravel's Registered event fires → triggers OTP email verification.
 *   6. Student is auto-logged in and redirected to the dashboard.
 *
 * ROUTES:
 *   GET  /join/{school_code}  → create()  — Show the registration form
 *   POST /join/{school_code}  → store()   — Process registration & login
 *
 * SECURITY:
 *   - These routes are wrapped in 'guest' middleware (only unauthenticated users).
 *   - The POST route is throttled (10 requests per minute) to prevent abuse.
 *   - If the school_code is invalid, a 404 is returned automatically.
 *
 * RELATED FILES:
 *   - View:       resources/views/auth/student-register.blade.php
 *   - Model:      App\Models\School (validates school_code)
 *   - Routes:     routes/web.php → 'join.create', 'join.store'
 *   - Middleware:  'guest', 'throttle:10,1'
 * ============================================================================
 */
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class JoinController extends Controller
{
    public function create($school_code)
    {
        $school = School::where('school_code', $school_code)->firstOrFail();
        return view('auth.student-register', compact('school'));
    }

    public function store(Request $request, $school_code)
    {
        $school = School::where('school_code', $school_code)->firstOrFail();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'school_id' => $school->id,
            'profile_completed' => false,
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
