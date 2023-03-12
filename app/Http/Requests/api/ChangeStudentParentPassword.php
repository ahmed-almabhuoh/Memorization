<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ChangeStudentParentPassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->position === 'student' || Auth::user()->position === 'parent';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', Password::min(8)->letters()->numbers()->symbols()],
            'confirmation_password' => 'required|string',
        ];
    }
}
