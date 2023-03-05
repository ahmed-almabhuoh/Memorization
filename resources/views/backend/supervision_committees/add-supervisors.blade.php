@extends('layouts.admin')

@section('title', 'Add supervisor for {{ $sc->name }}')

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
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ $sc->name }} committee
                    <span class="text-muted pt-2 font-size-sm d-block">Add supervisors to {{ $sc->name }}
                        committee</span>
                </h3>
            </div>
        </div>

        @livewire('add-supervisor-to-supervision-committee-livewire', [
            'sc' => $sc,
            'supervisors' => $supervisors,
        ])
    </div>
@endsection

@section('scripts')
    <script>
        function add(id, s_id) {
            axios.post('/auto/supervisor-to-sc/' + id + '/supervisor/' + s_id)
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
