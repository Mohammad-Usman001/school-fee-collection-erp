@extends('layouts.app')

@section('title','Edit Section')
@section('page_title','Edit Section')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Sections</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-warning">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-edit mr-1"></i> Edit Section
        </h3>

        <a href="{{ route('sections.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('sections.update', $section->id) }}">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Section Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           value="{{ old('name', $section->name) }}"
                           class="form-control @error('name') is-invalid @enderror"
                           required>

                    @error('name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order"
                           value="{{ old('sort_order', $section->sort_order) }}"
                           class="form-control">
                </div>

            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Update Section
            </button>
        </div>
    </form>
</div>
@endsection
