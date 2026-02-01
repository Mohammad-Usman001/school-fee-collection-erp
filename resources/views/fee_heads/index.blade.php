@extends('layouts.app')

@section('title','Fee Heads')
@section('page_title','Fee Heads')

@section('breadcrumb')
    <li class="breadcrumb-item active">Fee Heads</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-list"></i> Fee Heads List
        </h3>

        <a href="{{ route('fee-heads.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Fee Head
        </a>
    </div>

    <div class="card-body">
        <form class="row g-2 mb-3" method="GET">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Search fee head..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="frequency" class="form-control">
                    <option value="">All Frequency</option>
                    <option value="monthly" {{ request('frequency')=='monthly'?'selected':'' }}>Monthly</option>
                    <option value="one_time" {{ request('frequency')=='one_time'?'selected':'' }}>One Time</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-info w-100"><i class="fas fa-search"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('fee-heads.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="70">#</th>
                        <th>Fee Head</th>
                        <th width="140">Frequency</th>
                        <th width="120">Active</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feeHeads as $k => $h)
                        <tr>
                            <td>{{ $feeHeads->firstItem() + $k }}</td>
                            <td><strong>{{ $h->name }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $h->frequency=='monthly'?'primary':'warning' }}">
                                    {{ strtoupper($h->frequency) }}
                                </span>
                            </td>
                            <td>
                                @if($h->is_active)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('fee-heads.edit',$h->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('fee-heads.destroy',$h->id) }}" method="POST"
                                      style="display:inline-block"
                                      onsubmit="return confirm('Delete this fee head?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No Fee Heads Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-2">
            {{ $feeHeads->links() }}
        </div>
    </div>
</div>
@endsection
