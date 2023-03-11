@extends('layouts.admin')

@section('title', 'All CEO to ' .  $group->name . ' group')

@section('styles')
    <style>
        #student-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 1px solid rgb(44, 192, 214);
            padding: 3px;
        }
    </style>
    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ $group->name }} group
                    <span class="text-muted pt-2 font-size-sm d-block">Add CEO to {{ $group->name }}
                        group</span>
                </h3>
            </div>
        </div>

        @livewire('add-student-to-group-livewire', [
            'group' => $group,
            'students' => $students,
            'group_students' => $group_students,
        ])
    </div>
@endsection

@section('scripts')
    <script>
        function add(id, student_id) {
            axios.post('/auto/student-to-group/' + id + '/student/' + student_id)
                .then(function(response) {
                    // handle success
                    // showDeletingMessage(response.data);
                    toastr.success(response.data.message);
                })
                .catch(function(error) {
                    // handle error
                    // showDeletingMessage(error.response.data);
                    toastr.error(error.response.data.message);
                })
                .then(function() {
                    // always executed
                });
        }
    </script>

    @livewireScripts
@endsection
