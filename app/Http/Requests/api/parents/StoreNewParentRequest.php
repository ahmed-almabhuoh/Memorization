<?php

namespace App\Http\Requests\api\parents;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreNewParentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->position == 'admin' || auth()->user()->position == 'manager' || auth()->user()->position == 'supervisor';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'nullable|min:2|max:20',
            'tname' => 'nullable|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable',
            'gender' => 'required|string|in:male,female',
            'position' => 'required|string|in:parent',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no',
            'password' => ['required', 'string', Password::min(6)->numbers(), 'max:25'],
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
        ];
    }
}
