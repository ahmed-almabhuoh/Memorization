@extends('layouts.admin')

@section('title', 'Add API Key')

@section('styles')

    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Generate API Key
            </h3>
            <div class="card-toolbar">
                <div class="example-tools justify-content-center">
                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                </div>
            </div>
        </div>
        <!--begin::Form-->


        @livewire('create-a-p-i-form-livrwire')


        <!--end::Form-->
    </div>
@endsection

@section('scripts')
    <script>
        function store(uuid, key, secret) {

            axios.post('/auto/apis', {
                    name: document.getElementById('name').value,
                    status: document.getElementById('status').value,
                    rate_limit: document.getElementById('rate_limit').value,
                    uuid: uuid,
                    key: key,
                    secret: secret,
                })
                .then(function(response) {
                    toastr.success(response.data.message);
                    // document.getElementById('creation-form').reset();
                    window.location.href = '/auto/apis/create';
                })
                .catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>

    @livewireScripts
@endsection
