<?php

namespace App\Http\Livewire;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class AdminSearchLivewire extends Component
{
    protected $admins;
    public $searchTerm;
    public $type = 'all';

    public function __construct()
    {
        $this->admins = new \Illuminate\Support\Collection();
    }

    public function mount () {
        $this->admins = User::where('position', 'admin')->paginate();
    }

    public function render()
    {
        if ($this->type === 'all') {
            $this->admins = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'admin')
                ->paginate(10);
        } else if ($this->type === 'only_trashed') {
            $this->admins = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'admin')
                ->where('deleted_at', '!=', null)
                ->paginate(10);
        }else {
            $this->admins = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'admin')
                ->where('deleted_at', '=', null)
                ->paginate(10);
        }

        return view('livewire.admin-search-livewire', [
            'admins' => $this->admins,
        ]);
    }
}
