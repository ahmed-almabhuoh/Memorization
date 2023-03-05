@extends('layouts.admin')

@section('title', 'Update Branch')

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
                Update Branch
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
                    <input type="text" class="form-control" placeholder="Enter branch name ..." id="name"
                        value="{{ $branch->name }}" />
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="status">
                        <option value="0">-- Select branch status --</option>
                        @foreach (App\Models\Branch::STATUS as $status)
                            <option value="{{ $status }}" @if ($branch->status === $status) selected @endif>
                                {{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-1">
                    <label for="region">Local region</label>
                    <textarea class="form-control" id="region" rows="3">{{ $branch->region }}</textarea>
                </div>

                <div class="form-group  mb-1 row branch-image">
                    <label class="col-form-label col-3 text-left" style="width: fit-content;">Branch Images</label>
                    <div class="col-9">
                        <div class="image-input image-input-empty image-input-outline" id="kt_user_edit_avatar"
                            style="background-image: url('{{ Storage::url($branch->image) }}')">
                            <div class="image-input-wrapper"></div>
                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                data-action="change" data-toggle="tooltip" title=""
                                data-original-title="Change avatar">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="profile_avatar" id="image" accept=".png, .jpg, .jpeg">
                                <input type="hidden" name="profile_avatar_remove">
                            </label>
                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                data-action="cancel" data-toggle="tooltip" title=""
                                data-original-title="Cancel avatar">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                            </span>
                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                data-action="remove" data-toggle="tooltip" title=""
                                data-original-title="Remove avatar">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="button" onclick="update('{{ Crypt::encrypt($branch->id) }}')"
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
            window.location.href = '/auto/branches';
        }

        function update(id) {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', document.getElementById('name').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('region', document.getElementById('region').value);
            formData.append('image', document.getElementById('image').files[0]);


            axios.post('/auto/branches/' + id, formData)
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
