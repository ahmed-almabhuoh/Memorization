@extends('layouts.admin')

@section('title', 'Add new group')

@section('styles')



    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Add New group
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
                            After you add a new group system can interact with
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter group name ..." id="name" />
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="status">
                        <option value="0">-- Select group status --</option>
                        @foreach (App\Models\Group::STATUS as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="center_id">Center <span class="text-danger">*</span></label>
                    <select class="form-control" id="center_id">
                        <option value="0">-- Select group center --</option>
                        @foreach ($centers as $center)
                            <option value="{{ $center->id }}">{{ ucfirst($center->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="keeper_id">Keeper <span class="text-danger">*</span></label>
                    <select class="form-control" id="keeper_id">
                        <option value="0">-- Select group keeper --</option>
                        @foreach ($keepers as $keeper)
                            <option value="{{ $keeper->id }}">{{ ucfirst($keeper->full_name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Photo</label>
                    <div></div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" />
                        <label class="custom-file-label" for="image">Choose group photo</label>
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
            formData.append('region', document.getElementById('region').value);
            formData.append('image', document.getElementById('image').files[0]);
            formData.append('center_id', document.getElementById('center_id').value);
            formData.append('keeper_id', document.getElementById('keeper_id').value);

            axios.post('/auto/groups', formData)
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
