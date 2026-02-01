@extends('layouts.app')

@section('title','Edit Student')
@section('page_title','Edit Student')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Student</h3>
    </div>

    <form action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('students._form', ['student' => $student])
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <button class="btn btn-warning">
                <i class="fas fa-save"></i> Update Student
            </button>
        </div>
    </form>
</div>
@endsection
