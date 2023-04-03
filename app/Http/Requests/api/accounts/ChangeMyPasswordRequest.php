<?php

namespace App\Http\Requests\api\accounts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangeMyPasswordRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', Password::min(6), 'max:25', 'confirmed'],
            'new_password_confirmation' => 'required|string'
        ];
    }
}
