@extends('layouts.app')

@section('title','Add Student')
@section('page_title','Add Student')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Add New Student</h3>
    </div>

    <form action="{{ route('students.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('students._form')
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Save Student
            </button>
        </div>
    </form>
</div>
@endsection
