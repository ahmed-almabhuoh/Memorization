<?php

namespace App\Http\Livewire;

use App\Models\Test;
use Livewire\Component;

class TestSearchLivewire extends Component
{
    protected $tests;
    public $searchTerm;

    public function render()
    {
        $this->tests = Test::own()->paginate();

        return view('livewire.test-search-livewire', [
            'tests' => $this->tests,
        ]);
    }
}
