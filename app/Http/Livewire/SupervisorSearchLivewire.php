<?php

namespace App\Http\Livewire;

use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class SupervisorSearchLivewire extends Component
{
    protected $supervisors;
    public $searchTerm;
    public $type = 'all';

    public function __construct()
    {
        $this->supervisors = new \Illuminate\Support\Collection();
    }

    public function mount () {
        $this->supervisors = User::where('position', 'supervisor')->paginate();
    }

    public function render()
    {
        if ($this->type === 'all') {
            $this->supervisors = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'supervisor')
                ->paginate(10);
        } else if ($this->type === 'only_trashed') {
            $this->supervisors = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'supervisor')
                ->where('deleted_at', '!=', null)
                ->paginate(10);
        }else {
            $this->supervisors = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'supervisor')
                ->where('deleted_at', '=', null)
                ->paginate(10);
        }

        return view('livewire.supervisor-search-livewire', [
            'supervisors' => $this->supervisors,
        ]);
    }
}
