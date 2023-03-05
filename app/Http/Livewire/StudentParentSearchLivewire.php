<?php

namespace App\Http\Livewire;

use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class StudentParentSearchLivewire extends Component
{
    protected $parents;
    public $searchTerm;
    public $type = 'all';

    public function __construct()
    {
        $this->parents = new \Illuminate\Support\Collection();
    }

    public function mount () {
        $this->parents = User::where('position', 'parent')->paginate();
    }

    public function render()
    {
        if ($this->type === 'all') {
            $this->parents = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'parent')
                ->paginate(10);
        } else if ($this->type === 'only_trashed') {
            $this->parents = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'parent')
                ->where('deleted_at', '!=', null)
                ->paginate(10);
        }else {
            $this->parents = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'parent')
                ->where('deleted_at', '=', null)
                ->paginate(10);
        }

        return view('livewire.student-parent-search-livewire', [
            'parents' => $this->parents,
        ]);
    }
}
