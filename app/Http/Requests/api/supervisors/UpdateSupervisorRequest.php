<?php

namespace App\Http\Requests\api\supervisors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateSupervisorRequest extends FormRequest
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;

        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->position == 'admin' || auth()->user()->position == 'manager';
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
            'sname' => 'required|string|min:2|max:20',
            'tname' => 'required|string|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'required|string|min:7|max:13|unique:users,phone,' . $this->user->id,
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'gender' => 'required|string|in:male,female',
            'position' => 'required|string|in:supervisor',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no,' . $this->user->id,
            'password' => ['nullable', Password::min(8)->uncompromised()->letters()->numbers(), 'max:25'],
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
        ];
    }

    /**
     * Validate the request
     *
     * @return void
     */
    public function validate()
    {
        parent::validate($this->rules());
    }
}
