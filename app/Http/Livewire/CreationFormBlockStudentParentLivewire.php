<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CreationFormBlockStudentParentLivewire extends Component
{
    public $account_status;
    public $user_date_and_time = true;

    public function render()
    {
        return view('livewire.creation-form-block-student-parent-livewire');
    }
}
