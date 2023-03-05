<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class StudentSearchLivewire extends Component
{
    protected $students;
    public $searchTerm;
    public $type = 'all';

    public function __construct()
    {
        $this->students = new \Illuminate\Support\Collection();
    }

    public function mount () {
        $this->students = User::where('position', 'student')->paginate();
    }

    public function render()
    {
        if ($this->type === 'all') {
            $this->students = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'student')
                ->paginate(10);
        } else if ($this->type === 'only_trashed') {
            $this->students = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'student')
                ->where('deleted_at', '!=', null)
                ->paginate(10);
        }else {
            $this->students = User::where(function ($query) {
                $query->where('fname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('tname', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('lname', 'LIKE', '%' . $this->searchTerm . '%');
            })
                ->where('position', 'student')
                ->where('deleted_at', '=', null)
                ->paginate(10);
        }

        return view('livewire.student-search-livewire', [
            'students' => $this->students,
        ]);
    }
}
