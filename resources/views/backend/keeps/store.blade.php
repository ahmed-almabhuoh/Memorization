@extends('layouts.admin')

@section('title', 'Add new keep')

@section('styles')



    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Add New keep for {{ $student->fname }} in {{ $group->name }} group
            </h3>
            <div class="card-toolbar">
                <div class="example-tools justify-content-center">
                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        @livewire('keeps.create-livewire', [
        'student' => $student,
        'group' => $group,
        ])

        <!--end::Form-->
    </div>
@endsection

@section('scripts')
    <script>
        function store(student_id, group_id) {
            const formData = new FormData();
            formData.append('student_id', student_id);
            formData.append('group_id', group_id);
            formData.append('from_juz', document.getElementById('from_juz').value);
            formData.append('to_juz', document.getElementById('to_juz').value);
            formData.append('from_surah', document.getElementById('from_surah').value);
            formData.append('to_surah', document.getElementById('to_surah').value);
            formData.append('from_ayah', document.getElementById('from_ayah').value);
            formData.append('to_ayah', document.getElementById('to_ayah').value);
            formData.append('fault_number', document.getElementById('fault_number').value);

            axios.post('/auto/keeps/store', formData)
                .then(function (response) {
                    toastr.success(response.data.message);
                    document.getElementById('creation-form').reset();
                })
                .catch(function (error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>

    @livewireScripts
@endsection
