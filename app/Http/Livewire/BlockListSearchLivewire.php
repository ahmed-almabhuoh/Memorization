<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BlockListSearchLivewire extends Component
{
    public $position;
    protected $blocks;
    protected $user;

    public function mount($position, $blocks, $user)
    {
        $this->blocks = $blocks;
        $this->position = $position;
        $this->user = $user;
    }
    
    public function render()
    {
        return view('livewire.block-list-search-livewire', [
            'blocks' => $this->blocks,
            'user' => $this->user,
        ]);
    }
}
