<?php

namespace App\Http\Livewire;

use App\Models\APIKEY;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ApiSearchLivewire extends Component
{
    // use WithPagination;
    public function __construct()
    {
        $this->apis = new Collection();
    }


    protected $apis;
    public $searchTerm;
    public $type = 'all';

    public function mount($apis)
    {
        $this->apis = $apis;
    }

    public function render()
    {

        if ($this->type === 'all') {
            $this->apis = APIKEY::where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('key', 'LIKE', '%' . $this->searchTerm . '%');
            })->paginate(10);
        } else if ($this->type === 'only_trashed') {
            $this->apis = APIKEY::where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('key', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('deleted_at', '!=', null)
                ->paginate(10);
        } else {
            $this->apis = APIKEY::where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('key', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('deleted_at', '=', null)
                ->paginate(10);
        }

        return view('livewire.api-search-livewire', [
            'apis' => $this->apis,
        ]);
    }
}
