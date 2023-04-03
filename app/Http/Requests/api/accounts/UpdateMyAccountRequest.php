<?php

namespace App\Http\Requests\api\accounts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateMyAccountRequest extends FormRequest
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
            // Material Status, DOB, Classroom
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'nullable|min:2|max:20',
            'tname' => 'nullable|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'nullable|min:7|max:13',
            'email' => 'nullable|email|unique:users,email,' . $this->user()->id,
            'identity_no' => 'required|string|min:9|max:9|unique:users,email,' . $this->user()->id,
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
        ];
    }
}
