<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentParentAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->position === 'student' || auth()->user()->position === 'parent';
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
            'fname' => 'required|string',
            'tname' => 'required|string',
            'sname' => 'required|string',
            'lname' => 'required|string',
            'local_region' => 'nullable',
            'description' => 'nullable',
            'gender' => 'required|string|in:male,female',
            'email' => 'nullable|email|unique:users,email,' . auth()->user()->id,
            'phone' => 'required|string|unique:users,phone,' . auth()->user()->id,
            'image' => 'nullable|image',
        ];
    }
}
