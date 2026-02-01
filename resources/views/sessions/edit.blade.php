@extends('layouts.app')

@section('title','Edit Session')
@section('page_title','Edit Academic Session')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sessions.index') }}">Sessions</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-warning">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Session</h3>
        <a href="{{ route('sessions.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>

    <form method="POST" action="{{ route('sessions.update',$session->id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Session Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name',$session->name) }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date"
                           value="{{ old('start_date', $session->start_date) }}"
                           class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label>End Date</label>
                    <input type="date" name="end_date"
                           value="{{ old('end_date', $session->end_date) }}"
                           class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <div class="icheck-primary mt-4">
                        <input type="checkbox" id="is_active" name="is_active" {{ $session->is_active ? 'checked':'' }}>
                        <label for="is_active">Set as Active Session</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
        </div>
    </form>
</div>
@endsection
