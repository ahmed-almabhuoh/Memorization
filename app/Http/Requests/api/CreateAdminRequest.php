<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'required|string|min:2|max:20',
            'tname' => 'required|string|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'required|string|min:7|max:13|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|string|in:male,female',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no',
            'password' => ['required', Password::min(8)->uncompromised()->letters()->numbers(), 'string', 'max:25'],
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
        ];

        return $rules;
    }
}
