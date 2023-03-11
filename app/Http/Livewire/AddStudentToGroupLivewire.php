<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class AddStudentToGroupLivewire extends Component
{
    public $group_students;
    protected $students;
    public $group;
    public $searchTerm;

    public function mount ($group, $students, $group_students) {
        $this->group = $group;
        $this->students = User::students()->paginate();
        $this->group_students = $group_students;
    }

    public function render()
    {
        return view('livewire.add-student-to-group-livewire', [
            'students' => $this->students,
        ]);
    }
}
