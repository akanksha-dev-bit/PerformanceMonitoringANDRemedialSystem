<?php

/**
 * ============================================================================
 * ProfileUpdateRequest — Edit Profile Validation
 * ============================================================================
 *
 * PURPOSE:
 *   Validates the profile update form submitted by authenticated users.
 *
 * HOW IT WORKS:
 *   1. authorize(): Always true (authenticated users can edit their own profile).
 *   2. rules():
 *      - name: Required, string, max 255
 *      - email: Required, lowercase, email format, unique to user (ignoring self)
 *
 * RELATED FILES:
 *   - Controller: app/Http/Controllers/Auth/ProfileController.php
 *   - View:       resources/views/profile/edit.blade.php
 * ============================================================================
 */
namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
