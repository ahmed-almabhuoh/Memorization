@extends('layouts.admin')

@section('title', 'Add new test')

@section('styles')

    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Add New test
            </h3>
            <div class="card-toolbar">
                <div class="example-tools justify-content-test">
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
                            After complete your selection process and create a new test, we will notify you when the
                            question is ready.
                            <br>
                            Make sure you cannot update this test after creating process.
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="student_id">Student <span class="text-danger">*</span></label>
                    <select class="form-control" id="student_id">
                        <option value="0">-- Select student --</option>
                        @foreach ($group_students->students as $student)
                            <option value="{{ $student->id }}">{{ ucfirst($student->full_name) }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label for="from_juz">From Juz <span class="text-danger">*</span></label>
                    <select class="form-control" id="from_juz">
                        <option value="0">-- Select starting juz --</option>
                        @for($i = 1; $i <=30; ++$i)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label for="to_juz">To Juz <span class="text-danger">*</span></label>
                    <select class="form-control" id="to_juz">
                        <option value="0">-- Select ending juz --</option>
                        @for($i = 1; $i <=30; ++$i)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>


                <div class="form-group mb-1">
                    <label for="region">Test Description</label>
                    <textarea class="form-control" id="description" rows="3"></textarea>
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
            formData.append('student_id', document.getElementById('student_id').value);
            formData.append('from_juz', document.getElementById('from_juz').value);
            formData.append('to_juz', document.getElementById('to_juz').value);
            formData.append('description', document.getElementById('description').value);


            axios.post('/auto/tests', formData)
                .then(function (response) {
                    toastr.info(response.data.message);
                    // document.getElementById('creation-form').reset();
                    // window.location.href = '/auto/tests/' + response.data.test.id;
                    setTimeout(function () {
                        window.location.href = '/auto/tests/' + response.data.test.id;
                    }, 6000); // 6 seconds delay
                })
                .catch(function (error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>

    @livewireScripts
@endsection
