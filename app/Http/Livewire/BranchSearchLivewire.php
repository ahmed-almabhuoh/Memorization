<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class BranchSearchLivewire extends Component
{
    // use WithPagination;
    public function __construct()
    {
        $this->branches = new Collection();
    }


    protected $branches;
    public $searchTerm;
    public $type = 'all';

    public function mount($branches)
    {
        $this->branches = $branches;
    }

    public function render()
    {
        // $this->admins = Admin::where(function ($query) {
        //     $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
        //         ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
        //         ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
        //         ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
        // })->paginate(10);


        if ($this->type === 'all') {
            $this->branches = Branch::with('supervisor')->where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
            })->paginate(10);
        } else if ($this->type === 'only_trashed') {
            $this->branches = Branch::with('supervisor')->where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('deleted_at', '!=', null)
                ->paginate(10);
        } else {
            $this->branches = Branch::with('supervisor')->where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('deleted_at', '=', null)
                ->paginate(10);
        }

        return view('livewire.branch-search-livewire', [
            'branches' => $this->branches,
            // 'paginationView' => 'livewire.custom-pagination',
        ]);
    }
}
