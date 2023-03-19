@extends('layouts.admin')

@section('title', 'Add new test')

@section('styles')

@endsection

@section('content')
    {{-- {{ dd($branches) }} --}}
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Your do not have any group or student yet!
            </h3>
            <div class="card-toolbar">
                <div class="example-tools justify-content-test">
                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
