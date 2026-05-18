<?php

/**
 * ============================================================================
 * RegisteredUserController — School Administrator Self-Registration
 * ============================================================================
 *
 * PURPOSE:
 *   Handles the registration of a new School Admin and the automatic creation
 *   of their respective School entity. In this multi-tenant PMRS system,
 *   a registering administrator establishes a new school tenant upon registration.
 *
 * HOW IT WORKS:
 *   1. Displays the signup/registration screen.
 *   2. Validates incoming request parameters:
 *      - school_name: the name of the new school.
 *      - name, email, password: admin account details.
 *   3. Creates the School record in the DB with a unique, automatically generated PMRS invite code.
 *   4. Creates the Admin User record, linking it to the newly created School via school_id.
 *   5. Dispatches a standard Registered event (triggers verification OTP delivery).
 *   6. Logs the newly registered administrator in automatically.
 *   7. Redirects the admin user directly to the application dashboard.
 *
 * ROUTES:
 *   - GET  /register → create() — Show registration form
 *   - POST /register → store()  — Process and create school tenant + admin account
 *
 * SECURITY:
 *   - Wrapped in 'guest' middleware (only available to non-logged-in visitors).
 *   - Explicitly sets role to 'admin' and sets profile_completed to true.
 *
 * RELATED FILES:
 *   - View:   resources/views/auth/register.blade.php
 *   - Models: App\Models\School, App\Models\User
 * ============================================================================
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $school = School::create([
            'name' => $request->school_name,
            'school_code' => School::generateUniqueCode(),
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'school_id' => $school->id,
            'profile_completed' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
