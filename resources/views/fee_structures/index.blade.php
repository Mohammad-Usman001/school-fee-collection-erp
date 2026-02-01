@extends('layouts.app')

@section('title','Fee Structure')
@section('page_title','Fee Structure')

@section('content')
<div class="card card-outline card-primary">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title mb-0"><i class="fas fa-list"></i> Fee Structure</h3>

        <a href="{{ route('fee-structures.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Structure
        </a>
    </div>

    <div class="card-body">

        <form class="row g-2 mb-3" method="GET">
            <div class="col-md-3">
                <select name="session" class="form-control">
                    <option value="">All Sessions</option>
                    @foreach($sessions as $s)
                        <option value="{{ $s->name }}" {{ request('session')==$s->name?'selected':'' }}>
                            {{ $s->name }} {{ $s->is_active?'(Active)':'' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="class" class="form-control">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->name }}" {{ request('class')==$c->name?'selected':'' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="frequency" class="form-control">
                    <option value="">All Types</option>
                    <option value="monthly" {{ request('frequency')=='monthly'?'selected':'' }}>Monthly</option>
                    <option value="one_time" {{ request('frequency')=='one_time'?'selected':'' }}>One Time</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Search by fee head">
            </div>

            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Session</th>
                        <th>Class</th>
                        <th>Fee Head</th>
                        <th>Type</th>
                        <th class="text-right">Amount</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($feeStructures as $fs)
                    <tr>
                        <td>{{ $fs->session }}</td>
                        <td>{{ $fs->class }}</td>
                        <td><strong>{{ $fs->head->name ?? '-' }}</strong></td>
                        <td>
                            <span class="badge badge-{{ ($fs->head->frequency ?? '')=='monthly'?'primary':'warning' }}">
                                {{ strtoupper($fs->head->frequency ?? '-') }}
                            </span>
                        </td>
                        <td class="text-right">₹{{ number_format($fs->amount,2) }}</td>
                        <td>
                            <a href="{{ route('fee-structures.edit',$fs->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('fee-structures.destroy',$fs->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No structure found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $feeStructures->links() }}
    </div>
</div>
@endsection
