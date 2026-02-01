@extends('layouts.app')

@section('title','Academic Sessions')
@section('page_title','Academic Sessions')

@section('breadcrumb')
    <li class="breadcrumb-item active">Sessions</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Academic Sessions</h3>

        <a href="{{ route('sessions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Session
        </a>
    </div>

    <div class="card-body">

        <form class="row mb-3" method="GET">
            <div class="col-md-8 mb-2">
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}" placeholder="Search session...">
            </div>
            <div class="col-md-4 mb-2 d-flex">
                <button class="btn btn-primary w-100 mr-2"><i class="fas fa-search"></i></button>
                <a href="{{ route('sessions.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Session</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($sessions as $i => $s)
                    <tr>
                        <td>{{ $sessions->firstItem() + $i }}</td>
                        <td><strong>{{ $s->name }}</strong></td>
                        <td>{{ $s->start_date ? \Carbon\Carbon::parse($s->start_date)->format('d M Y') : '-' }}</td>
                        <td>{{ $s->end_date ? \Carbon\Carbon::parse($s->end_date)->format('d M Y') : '-' }}</td>
                        <td>
                            @if($s->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('sessions.edit',$s->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('sessions.destroy',$s->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this session?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No sessions found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $sessions->links() }}
    </div>
</div>
@endsection
