@extends('layouts.admin')

@section('title', 'Add new supervision_committee')

@section('styles')

    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Add New supervision_committee
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
                <div class="form-group mb-8">
                    <div class="alert alert-custom alert-default" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                        <div class="alert-text">
                            After you add a new supervision committee system can interact with
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter supervision_committee name ..."
                        id="name" />
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="status">
                        <option value="0">-- Select supervision committee status --</option>
                        @foreach (App\Models\SupervisionCommittee::STATUS as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="type">
                        <option value="0">-- Select supervision committee type --</option>
                        @foreach (App\Models\SupervisionCommittee::TYPES as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label>Photo</label>
                    <div></div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" />
                        <label class="custom-file-label" for="image">Choose supervision committee photo</label>
                    </div>
                </div>

                <div class="form-group mb-1">
                    <label for="region">Local region</label>
                    <textarea class="form-control" id="region" rows="3"></textarea>
                </div>

                <div class="card-footer">
                    <button type="button" onclick="store()" class="btn btn-primary mr-2">Store</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
        </form>

        <!--end::Form-->
    </div>
@endsection

@section('scripts')
    <script>
        function store() {
            const formData = new FormData();
            formData.append('name', document.getElementById('name').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('type', document.getElementById('type').value);
            formData.append('region', document.getElementById('region').value);
            formData.append('image', document.getElementById('image').files[0]);


            axios.post('/auto/supervision_committees', formData)
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
