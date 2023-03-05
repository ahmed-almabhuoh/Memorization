@extends('layouts.admin')

@section('title', 'Update API')

@section('styles')
    <style>
        .branch-image {
            margin-top: 25px;
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Update API
            </h3>
            <div class="card-toolbar">
                <div class="example-tools justify-content-center">
                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <form id="creation-form">
            <div class="card-body">
                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter API name ..." id="name"
                        value="{{ $api->name }}" />
                </div>

                <div class="form-group">
                    <label>API secret <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter API secret ..." id="name" readonly
                        value="{{ $secret }}" />
                </div>

                <div class="form-group">
                    <label>Your Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" placeholder="Enter your password ..." id="password" />
                </div>

                <div class="card-footer">
                    <button type="button" onclick="update('{{ Crypt::encrypt($api->id) }}', '{{ $secret }}')"
                        class="btn btn-primary mr-2">Update</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
        </form>
        <!--end::Form-->
    </div>
@endsection

@section('scripts')
    <script>
        function skip() {
            window.location.href = '/auto/apis';
        }

        function update(id, secret) {
            // console.log(api_secret);
            // alert('Here');
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', document.getElementById('name').value);
            formData.append('password', document.getElementById('password').value);
            formData.append('secret', secret);



            axios.post('/auto/apis/' + id, formData)
                .then(function(response) {
                    toastr.success(response.data.message);
                    document.getElementById('creation-form').reset();
                })
                .catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
