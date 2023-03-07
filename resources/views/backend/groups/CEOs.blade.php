@extends('layouts.admin')

@section('title', 'All CEO to {{ $branch->name }} branch')

@section('styles')
    <style>
        #supervisor-image {
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
    {{-- {{ dd($supervisors) }} --}}
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ $branch->name }} branch
                    <span class="text-muted pt-2 font-size-sm d-block">Add CEO to {{ $branch->name }}
                        branch</span>
                </h3>
            </div>
        </div>

        @livewire('add-ceo-to-branch-livewire', [
            'branch' => $branch,
            'supervisors' => $supervisors,
        ])
    </div>
@endsection

@section('scripts')
    <script>
        function add(id, s_id) {
            axios.post('/auto/supervisor-to-branch/' + id + '/supervisor/' + s_id)
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
