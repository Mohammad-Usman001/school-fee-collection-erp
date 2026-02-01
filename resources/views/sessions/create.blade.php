@extends('layouts.app')

@section('title','Add Session')
@section('page_title','Add Academic Session')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sessions.index') }}">Sessions</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Add Session</h3>
        <a href="{{ route('sessions.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>

    <form method="POST" action="{{ route('sessions.store') }}">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Session Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="e.g 2025-26" required>
                    @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <div class="icheck-primary mt-4">
                        <input type="checkbox" id="is_active" name="is_active">
                        <label for="is_active">Set as Active Session</label>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer text-right">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
    </form>
</div>
@endsection
