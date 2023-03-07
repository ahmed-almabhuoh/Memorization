<?php

namespace App\Http\Livewire;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class GroupSearchLivewire extends Component
{
  // use WithPagination;
  public function __construct()
  {
      $this->groups = new Collection();
  }


  protected $groups;
  public $searchTerm;
  public $type = 'all';

  public function mount($groups)
  {
      $this->groups = $groups;
  }

  public function render()
  {
      if ($this->type === 'all') {
          $this->groups = Group::with('keeper')->where(function ($query) {
              $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                  ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
          })->paginate(10);
      } else if ($this->type === 'only_trashed') {
          $this->groups = Group::with('keeper')->where(function ($query) {
              $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                  ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
          })
              ->where('deleted_at', '!=', null)
              ->paginate(10);
      } else {
          $this->groups = Group::with('keeper')->where(function ($query) {
              $query->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                  ->orWhere('region', 'LIKE', '%' . $this->searchTerm . '%');
          })
              ->where('deleted_at', '=', null)
              ->paginate(10);
      }

      return view('livewire.group-search-livewire', [
          'groups' => $this->groups,
      ]);
  }
}
