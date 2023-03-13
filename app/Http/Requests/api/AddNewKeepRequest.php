<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;

class AddNewKeepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->position === 'keeper';
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
            'from_juz' => 'required|integer|min:1|max:30',
            'to_juz' => 'required|integer|min:1|max:30',
            'from_surah' => 'required|integer',
            'to_surah' => 'required|integer',
            'from_ayah' => 'required|integer',
            'to_ayah' => 'required|integer',
            'faults_number' => 'required|integer|min:0',
        ];
    }
}
