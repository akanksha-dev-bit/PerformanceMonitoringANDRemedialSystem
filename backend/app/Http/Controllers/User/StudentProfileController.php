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
use App\Services\PlanCatalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function create()
    {
        $classrooms = \App\Models\Classroom::where('school_id', auth()->user()->school_id)
            ->with('academicClass')->orderBy('name')->get();
        return view('students.complete-profile', compact('classrooms'));
    }

    public function store(Request $request, PlanCatalog $plans)
    {
        $user = auth()->user();
        if (config('plans.enforcement_enabled') && $user->school?->subscription
            && !$user->school->subscription->isActive() && $user->school->subscription->status !== 'provisional') {
            abort(403, 'This school must activate its subscription before completing student enrollment.');
        }
        $validated = $request->validate([
            'classroom_id' => ['required', Rule::exists('classrooms', 'id')->where('school_id', $user->school_id)],
            'roll_number' => ['required', 'string', 'max:255', Rule::unique('students', 'roll_no')->where('school_id', $user->school_id)->ignore($user->studentProfile?->id)],
            'phone' => ['required', 'string', 'max:20'],
        ]);
        DB::transaction(function () use ($user, $validated, $plans) {
            if (!$user->studentProfile?->is_active) {
                $plans->assertStudentCapacity($user->school_id);
            }
            $user->studentProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'school_id' => $user->school_id,
                    'classroom_id' => $validated['classroom_id'],
                    'roll_no' => $validated['roll_number'],
                    'phone' => $validated['phone'],
                    'is_active' => true,
                ]
            );
            $user->update(['profile_completed' => true, 'is_active' => true]);
        });

        return redirect()->route('dashboard');
    }
}
