<?php

namespace App\Http\Livewire;

use App\Models\Center;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CenterSearchLivewire extends Component
{
     // use WithPagination;
     public function __construct()
     {
         $this->centers = new Collection();
     }


     protected $centers;
     public $searchTerm;
     public $type = 'all';

     public function mount($centers)
     {
         $this->centers = $centers;
     }

     public function render()
     {
         if ($this->type === 'all') {
             $this->centers = Center::where(function ($query) {
                 $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                     ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
             })->paginate(10);
         } else if ($this->type === 'only_trashed') {
             $this->centers = Center::where(function ($query) {
                 $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                     ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
             })
                 ->where('deleted_at', '!=', null)
                 ->paginate(10);
         } else {
             $this->centers = Center::where(function ($query) {
                 $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                     ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
             })
                 ->where('deleted_at', '=', null)
                 ->paginate(10);
         }

         return view('livewire.center-search-livewire', [
             'centers' => $this->centers,
             // 'paginationView' => 'livewire.custom-pagination',
         ]);
     }
}
