<?php

namespace App\Http\Livewire\Keeps;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class KeepsSearchLivewire extends Component
{
    protected $keeps;
    public $student;
    public $group;

    public  function mount ($student, $group, $keeps) {
        $this->keeps = $keeps;
        $this->student = $student;
        $this->group = $group;
    }

    public function render()
    {
        return view('livewire.keeps.keeps-search-livewire', [
            'keeps' => $this->keeps,
        ]);
    }
}
