<?php

namespace App\Http\Livewire;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class ManagerSearchLivewire extends Component
{
    protected $managers;
    public $searchTerm;
    public $type = 'all';

    public function __construct()
    {
        $this->managers = new Collection();
    }

    public function mount () {
        $this->managers = User::where('position', 'manager')->paginate();
    }

    public function render()
    {
        if ($this->type === 'all') {
            $this->managers = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'manager')
                ->paginate(10);
        } else if ($this->type === 'only_trashed') {
            $this->managers = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'manager')
                ->where('deleted_at', '!=', null)
                ->paginate(10);
        }else {
            $this->managers = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'manager')
                ->where('deleted_at', '=', null)
                ->paginate(10);
        }

        return view('livewire.manager-search-livewire', [
            'managers' => $this->managers,
        ]);
    }
}
