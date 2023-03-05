@extends('layouts.admin')

@section('title', 'Add new student_parent')

@section('styles')

    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Add New StudentParent
            </h3>
            <div class="card-toolbar">
                <div class="example-tools justify-content-center">
                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        @livewire('creation-form-block-student-parent-livewire')
        <!--end::Form-->
    </div>
@endsection

@section('scripts')
    <script>
        function store() {
            const formData = new FormData();
            formData.append('fname', document.getElementById('fname').value);
            formData.append('sname', document.getElementById('sname').value);
            formData.append('tname', document.getElementById('tname').value);
            formData.append('lname', document.getElementById('lname').value);
            formData.append('identity_no', document.getElementById('identity_no').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('gender', document.getElementById('gender').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('password', document.getElementById('password').value);
            formData.append('image', document.getElementById('image').files[0]);
            formData.append('local_region', document.getElementById('local_region').value);
            formData.append('description', document.getElementById('description').value);
            if (document.getElementById('is_blocked')) {
                formData.append('is_blocked', document.getElementById('is_blocked').checked ? 1 : 0);
                formData.append('block_description', document.getElementById('block_description').value);
                if (document.getElementById('is_blocked').checked) {
                    formData.append('from_date', document.getElementById('from_date').value);
                    formData.append('to_date', document.getElementById('to_date').value);
                }
            }


            axios.post('/auto/parents', formData)
                .then(function(response) {
                    toastr.success(response.data.message);
                    document.getElementById('creation-form').reset();
                })
                .catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>

    @livewireScripts
@endsection
