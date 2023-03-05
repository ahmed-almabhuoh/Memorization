<?php

namespace App\Http\Livewire;

use App\Models\SupervisionCommittee;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class SupervisionCommitteeSearchLivewire extends Component
{
    // use WithPagination;
    public function __construct()
    {
        $this->supervision_committees = new Collection();
    }


    protected $supervision_committees;
    public $searchTerm;
    public $type = 'all';
    // public $sc_type = '';

    public function mount($supervision_committees)
    {
        $this->supervision_committees = $supervision_committees;
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
            $this->supervision_committees = SupervisionCommittee::where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->withCount('supervisors')
                // ->orWhere('type', 'like', $this->type)
                ->paginate(10);
        } else if ($this->type === 'only_trashed') {
            $this->supervision_committees = SupervisionCommittee::where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('deleted_at', '!=', null)
                ->withCount('supervisors')
                // ->orWhere('type', 'like', $this->type)
                ->paginate(10);
        } else {
            $this->supervision_committees = SupervisionCommittee::where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('deleted_at', '=', null)
                ->withCount('supervisors')
                // ->orWhere('type', 'like', $this->type)
                ->paginate(10);
        }

        return view('livewire.supervision-committee-search-livewire', [
            'supervision_committees' => $this->supervision_committees,
            // 'paginationView' => 'livewire.custom-pagination',
        ]);
    }
}
