@extends('layouts.app')

@section('title','Student Details')
@section('page_title','Student Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="card card-outline card-info">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title"><i class="fas fa-user mr-1"></i> Student Details</h3>
        <div>
            <a href="{{ route('students.edit',$student->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
                Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr><th width="200">Unique ID</th><td>{{ $student->unique_id }}</td></tr>
            <tr><th width="200">Session</th><td>{{ $student->session }}</td></tr>
            <tr><th>Name</th><td>{{ $student->name }}</td></tr>
            <tr><th>Father Name</th><td>{{ $student->father_name ?? '-' }}</td></tr>
            <tr><th>Class</th><td>{{ $student->class }}</td></tr>
            <tr><th>Section</th><td>{{ $student->section ?? '-' }}</td></tr>
            <tr><th>Phone</th><td>{{ $student->phone ?? '-' }}</td></tr>
            <tr><th>Address</th><td>{{ $student->address ?? '-' }}</td></tr>
        </table>
    </div>
</div>
@endsection
