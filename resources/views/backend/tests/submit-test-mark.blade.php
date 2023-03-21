@extends('layouts.admin')

@section('title', 'Submit student test mark')

@section('styles')

    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Submit student test mark
            </h3>
            <div class="card-toolbar">
                <div class="example-tools justify-content-center">
                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                </div>
            </div>
        </div>
        <!--begin::Form-->
    {{--        @livewire('creation-form-block-student-parent-livewire')--}}
    <!--end::Form-->

        <form class="form">
            <div class="card-body">

                @php
                    $counter = 1;
                @endphp

                @foreach($test->questions as $question)
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Question {{$counter}}:</label>
                            <textarea name="ayah" id="question_ayah_{{$counter}}" class="form-control" cols="30"
                                      rows="10">{{json_decode($question->ayah)->text}}</textarea>
                            <span class="form-text text-muted">You can change this question if you need</span>
                        </div>
                        <div class="col-lg-6">
                            <label>Faults Number For Question {{$counter}}:</label>
                            <input type="number" id="question_mark_{{$counter}}" class="form-control"
                                   placeholder="Enter faults number"/>
                            <span
                                class="form-text text-muted">This field will receive the faults number from student</span>
                        </div>
                    </div>
                    @php
                        ++$counter;
                    @endphp
                @endforeach


                <div class="form-group row">
                    <div class="col-lg-6">
                        <label>Notify:</label>
                        <div class="radio-inline">
                            <label class="checkbox checkbox-outline checkbox-success">
                                <input type="checkbox" id="notify" name="Checkboxes15" checked="checked">
                                <span></span>Send notifications for parent and student</label>
                        </div>
                    </div>
                </div>


                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-6">
                            <button type="button" class="btn btn-primary mr-2" onclick="store('{{count($test->questions)}}', '{{\Illuminate\Support\Facades\Crypt::encrypt($test->id)}}')">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                        <div class="col-lg-6 text-lg-right">
                            <button type="button" onclick="deleteTest('{{$test->id}}')" class="btn btn-danger">Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


    </div>
@endsection

@section('scripts')
    <script>
        function store(questions, test_id) {
            const formData = new FormData();
            for (let i = 1; i < questions; i++) {
                // console.log(i);
                formData.append('question_ayah_' + i, document.getElementById('question_ayah_' + i).value);
                formData.append('question_mark_' + i, document.getElementById('question_mark_' + i).value);
            }
            formData.append('notify', document.getElementById('notify').checked);
            formData.append('test_id', test_id);

            axios.post('/auto/mark/submit', formData)
                .then(function (response) {
                    toastr.success(response.data.message);
                    // document.getElementById('creation-form').reset();
                })
                .catch(function (error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>

    @livewireScripts
@endsection
