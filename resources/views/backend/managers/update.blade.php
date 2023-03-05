@extends('layouts.admin')

@section('title', 'Update Manager')

@section('styles')
    <style>
        .manager-image {
            margin-top: 25px;
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Update Manager
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
                    <label>First name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter manager first name ..." id="fname"
                        value="{{ $manager->fname }}" />
                </div>

                <div class="form-group">
                    <label>Second name <span class="text-danger">*</span></label>
                    <input type="text" value="{{ $manager->sname }}" class="form-control"
                        placeholder="Enter manager second name ..." id="sname" />
                </div>

                <div class="form-group">
                    <label>Third name <span class="text-danger">*</span></label>
                    <input type="text" value="{{ $manager->tname }}" class="form-control"
                        placeholder="Enter manager third name ..." id="tname" />
                </div>

                <div class="form-group">
                    <label>Last name <span class="text-danger">*</span></label>
                    <input type="text" value="{{ $manager->lname }}" class="form-control"
                        placeholder="Enter manager last name ..." id="lname" />
                </div>

                <div class="form-group">
                    <label>Identity No. <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter manager identity No. ..." id="identity_no"
                        value="{{ $manager->identity_no }}" />
                    <span class="form-text text-muted">We'll never share your identity No. with anyone else.</span>
                </div>

                <div class="form-group">
                    <label>Phone No. <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $manager->phone }}"
                        placeholder="Enter manager phone No. ..." id="phone" />
                    <span class="form-text text-muted">We'll never share your Phone No. with anyone else.</span>
                </div>

                <div class="form-group">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" value="{{ $manager->email }}"
                        placeholder="Enter manager email ..." id="email" />
                    <span class="form-text text-muted">We'll never share your email with anyone else.</span>
                </div>

                <div class="form-group">
                    <label for="gedner">Gender <span class="text-danger">*</span></label>
                    <select class="form-control" id="gender">
                        <option value="0">-- Select manager gender --</option>
                        @foreach (App\Models\User::GENDER as $gender)
                            <option value="{{ $gender }}" @if ($manager->gender == $gender) selected @endif>
                                {{ ucfirst($gender) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="status">
                        <option value="0">-- Select manager account status --</option>
                        @foreach (App\Models\User::STATUS as $status)
                            <option value="{{ $status }}" @if ($manager->status == $status) selected @endif>
                                {{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" placeholder="Manager password ..." />
                    <span class="form-text text-muted">If you do not need to change manager password, let this field
                        empty.</span>
                </div>

                <div class="form-group  mb-1 row manager-image">
                    <label class="col-form-label col-3 text-left" style="width: fit-content;">Manager Photo</label>
                    <div class="col-9">
                        <div class="image-input image-input-empty image-input-outline" id="kt_user_edit_avatar"
                            style="background-image: url('{{ Storage::url($manager->image) }}')">
                            <div class="image-input-wrapper"></div>
                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                data-action="change" data-toggle="tooltip" title=""
                                data-original-title="Change avatar">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg" id="image">
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

                <div class="form-group mb-1">
                    <label for="local_region">Local region</label>
                    <textarea class="form-control" id="local_region" rows="3">{{ $manager->local_region }}</textarea>
                </div>

                <div class="form-group mb-1">
                    <label for="description">Manager description</label>
                    <textarea class="form-control" id="description" rows="5">{{ $manager->description }}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" onclick="update('{{ Crypt::encrypt($manager->id) }}')"
                    class="btn btn-primary mr-2">Update</button>
                <button type="button" onclick="skip()" class="btn btn-secondary">Skip</button>
            </div>
        </form>
        <!--end::Form-->
    </div>
@endsection

@section('scripts')
    <script>
        function skip() {
            window.location.href = '/auto/managers';
        }

        function update(id) {
            const formData = new FormData();
            formData.append('_method', 'PUT');
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


            axios.post('/auto/managers/' + id, formData)
                .then(function(response) {
                    toastr.success(response.data.message);
                    window.location.href = '/auto/managers';
                })
                .catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
