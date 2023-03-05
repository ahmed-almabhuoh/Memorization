<?php

namespace App\Http\Livewire;

use App\Models\Supervisor;
use App\Models\User;
use Livewire\Component;

class AddCeoToBranchLivewire extends Component
{
    protected $supervisors;
    public $branch;
    public $searchTerm;

    public function mount($branch)
    {
        $this->branch = $branch;
        // $this->supervisors = $supervisors;
        $this->supervisors = User::whereDoesntHave('sc')
            ->whereDoesntHave('branch', function ($query) use ($branch) {
                $query->where('id', '!=', $branch->id);
            })
            ->where('position', 'supervisor')
            ->paginate();
    }
    public function render()
    {
        $branch = $this->branch;
        $this->supervisors = User::where(function ($query) {
            $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
        })->whereDoesntHave('branch', function ($query) use ($branch) {
            $query->where('id', '!=', $branch->id);
        })->whereDoesntHave('sc')
            ->where('position', 'supervisor')
            ->paginate();

        return view('livewire.add-ceo-to-branch-livewire', [
            'supervisors' => $this->supervisors,
        ]);
    }
}
