<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use function Illuminate\Session\userAgent;

class StudentBelongsToKeeper implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(String $attribute, mixed $value, Closure $fail): void
    {
        //

        if (!auth()->user()->position == 'keeper'){
            $fail('You are not authorized for this action');
        }

        // Get the teacher's ID
        $keeper_id = auth()->id();

        // Check if the student with the given ID belongs to the teacher
        return User::whereHas('groups', function ($query) use ($keeper_id, $value) {
            $query->where('keeper_id', $keeper_id)->whereHas('students', function ($query) use ($value) {
                $query->where('id', $value);
            });
        })->exists();
    }

    public function message()
    {
        return 'The selected student does not exist or does not belong to you.';
    }
}
