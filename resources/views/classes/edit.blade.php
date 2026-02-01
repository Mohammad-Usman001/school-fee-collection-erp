@extends('layouts.app')

@section('title','Edit Class')
@section('page_title','Edit Class')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Classes</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Class</h3>
    </div>

    <form method="POST" action="{{ route('classes.update',$class->id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Class Name <span class="text-danger">*</span></label>
                <input type="text" name="name"
                       value="{{ old('name', $class->name) }}"
                       class="form-control @error('name') is-invalid @enderror"
                       required>
                @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order',$class->sort_order) }}"
                       class="form-control">
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('classes.index') }}" class="btn btn-secondary">Back</a>
            <button class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
        </div>
    </form>
</div>
@endsection
