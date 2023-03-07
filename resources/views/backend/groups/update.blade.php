@extends('layouts.admin')

@section('title', 'Update Group')

@section('styles')
    <style>
        .group-image {
            margin-top: 25px;
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Update Group
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
                    <input type="text" class="form-control" placeholder="Enter group name ..." id="name"
                        value="{{ $group->name }}" />
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="status">
                        <option value="0">-- Select group status --</option>
                        @foreach (App\Models\Group::STATUS as $status)
                            <option value="{{ $status }}" @if ($group->status === $status) selected @endif>
                                {{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="center_id">Center <span class="text-danger">*</span></label>
                    <select class="form-control" id="center_id">
                        <option value="0">-- Select group center --</option>
                        @foreach ($centers as $center)
                            <option value="{{ $center->id }}" @if ($group->center_id === $center->id) selected @endif>
                                {{ ucfirst($center->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="keeper_id">Keeper <span class="text-danger">*</span></label>
                    <select class="form-control" id="keeper_id">
                        <option value="0">-- Select group keeper --</option>
                        @foreach ($keepers as $keeper)
                            <option value="{{ $keeper->id }}" @if ($group->keeper_id === $keeper->id) selected @endif>{{ ucfirst($keeper->full_name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-1">
                    <label for="region">Local region</label>
                    <textarea class="form-control" id="region" rows="3">{{ $group->region }}</textarea>
                </div>

                <div class="form-group  mb-1 row group-image">
                    <label class="col-form-label col-3 text-left" style="width: fit-content;">Group Images</label>
                    <div class="col-9">
                        <div class="image-input image-input-empty image-input-outline" id="kt_user_edit_avatar"
                            style="background-image: url('{{ Storage::url($group->image) }}')">
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
                    <button type="button" onclick="update('{{ Crypt::encrypt($group->id) }}')"
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
            window.location.href = '/auto/groups';
        }

        function update(id) {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', document.getElementById('name').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('region', document.getElementById('region').value);
            formData.append('image', document.getElementById('image').files[0]);
            formData.append('center_id', document.getElementById('center_id').value);
            formData.append('keeper_id', document.getElementById('keeper_id').value);

            axios.post('/auto/groups/' + id, formData)
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
