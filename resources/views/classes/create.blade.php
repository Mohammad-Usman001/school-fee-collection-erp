@extends('layouts.app')

@section('title','Add Class')
@section('page_title','Add Class')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Classes</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Add Class</h3>
    </div>

    <form method="POST" action="{{ route('classes.store') }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Class Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="e.g 1, 2, KG, Nursery" required>
                @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order',0) }}"
                       class="form-control" placeholder="0">
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('classes.index') }}" class="btn btn-secondary">Back</a>
            <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
    </form>
</div>
@endsection
