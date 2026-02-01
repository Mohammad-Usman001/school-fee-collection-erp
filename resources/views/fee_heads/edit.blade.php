@extends('layouts.app')

@section('title','Edit Fee Head')
@section('page_title','Edit Fee Head')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('fee-heads.index') }}">Fee Heads</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-info">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0"><i class="fas fa-edit"></i> Edit Fee Head</h3>

        <a href="{{ route('fee-heads.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('fee-heads.update',$feeHead->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Fee Head Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           value="{{ old('name', $feeHead->name) }}"
                           class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Frequency <span class="text-danger">*</span></label>
                    <select name="frequency" class="form-control" required>
                        <option value="monthly" {{ $feeHead->frequency=='monthly'?'selected':'' }}>Monthly</option>
                        <option value="one_time" {{ $feeHead->frequency=='one_time'?'selected':'' }}>One Time</option>
                        <option value="quarterly" {{ $feeHead->frequency=='quarterly'?'selected':'' }}>Quarterly</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label>Active</label><br>
                    <input type="checkbox" name="is_active"
                           {{ $feeHead->is_active ? 'checked' : '' }}
                           style="transform:scale(1.2)">
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button class="btn btn-success">
                <i class="fas fa-save"></i> Update Fee Head
            </button>
        </div>
    </form>
</div>
@endsection
