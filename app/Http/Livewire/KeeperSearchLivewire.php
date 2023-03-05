<?php

namespace App\Http\Livewire;

use App\Models\Keeper;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class KeeperSearchLivewire extends Component
{
    protected $keepers;
    public $searchTerm;
    public $type = 'all';

    public function __construct()
    {
        $this->keepers = new \Illuminate\Support\Collection();
    }

    public function mount () {
        $this->keepers = User::where('position', 'keeper')->paginate();
    }

    public function render()
    {
        if ($this->type === 'all') {
            $this->keepers = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'keeper')
                ->paginate(10);
        } else if ($this->type === 'only_trashed') {
            $this->keepers = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'keeper')
                ->where('deleted_at', '!=', null)
                ->paginate(10);
        }else {
            $this->keepers = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'keeper')
                ->where('deleted_at', '=', null)
                ->paginate(10);
        }

        return view('livewire.keeper-search-livewire', [
            'keepers' => $this->keepers,
        ]);
    }
}
