<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(Request $request): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'owner' => ['required'],
            'email' => [
                'required',
                Rule::unique('users')->ignore($request->user_id),
                'string',
                'lowercase',
                'email',
                'max:255',
            ],
            'new_password' => ['nullable', Rules\Password::defaults()],
        ];
    }
}
