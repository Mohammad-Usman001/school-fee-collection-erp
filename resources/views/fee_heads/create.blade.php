@extends('layouts.app')

@section('title', 'Add Fee Head')
@section('page_title', 'Add Fee Head')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('fee-heads.index') }}">Fee Heads</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><i class="fas fa-plus"></i> Add Fee Head</h3>
            <a href="{{ route('fee-heads.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <form action="{{ route('fee-heads.store') }}" method="POST">
            @csrf
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Fee Head Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="Tuition Fee" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Frequency <span class="text-danger">*</span></label>
                        <select name="frequency" class="form-control" required>
                            <option value="monthly">Monthly</option>
                            <option value="one_time">One Time</option>
                            <option value="quarterly">Quarterly</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Active</label><br>
                        <input type="checkbox" name="is_active" checked style="transform:scale(1.2)">
                    </div>
                </div>

            </div>

            <div class="card-footer text-right">
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Fee Head
                </button>
            </div>
        </form>
    </div>
@endsection
