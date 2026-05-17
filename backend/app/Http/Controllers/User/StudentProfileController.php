<?php

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
