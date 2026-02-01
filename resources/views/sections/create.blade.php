@extends('layouts.app')

@section('title','Add Section')
@section('page_title','Add Section')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Sections</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="card card-outline card-info">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-plus mr-1"></i> Add Section
        </h3>

        <a href="{{ route('sections.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('sections.store') }}">
        @csrf

        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Section Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="e.g A, B, C"
                           required>

                    @error('name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order"
                           value="{{ old('sort_order', 0) }}"
                           class="form-control"
                           placeholder="0">
                    <small class="text-muted">Section ordering for dropdown display</small>
                </div>

            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-info">
                <i class="fas fa-save"></i> Save Section
            </button>
        </div>
    </form>
</div>
@endsection
