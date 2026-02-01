@extends('layouts.app')

@section('title','Student Ledger')
@section('page_title','Student Ledger (Session Wise)')

@section('breadcrumb')
    <li class="breadcrumb-item active">Ledger</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-book mr-1"></i> Student Ledger
        </h3>
    </div>

    <div class="card-body">

        <form method="GET" class="row mb-3">
            <div class="col-md-4 mb-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control"
                       placeholder="Search by name / ID / phone">
            </div>

            <div class="col-md-3 mb-2">
                <select name="session" class="form-control">
                    @foreach($sessions as $ses)
                        <option value="{{ $ses->name }}" {{ $session==$ses->name?'selected':'' }}>
                            {{ $ses->name }} {{ $ses->is_active?'(Active)':'' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-2 d-flex">
                <button class="btn btn-primary w-100 mr-2"><i class="fas fa-search"></i> Search</button>
                <a href="{{ route('ledger.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Unique ID</th>
                    <th>Class</th>
                    <th width="160">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($students as $i => $st)
                    <tr>
                        <td>{{ $students->firstItem() + $i }}</td>
                        <td><strong>{{ $st->name }}</strong></td>
                        <td>{{ $st->unique_id }}</td>
                        <td>{{ $st->class }} {{ $st->section ? '-'.$st->section : '' }}</td>
                        <td>
                            <a href="{{ route('ledger.show', [$st->id, 'session' => $session]) }}"
                               class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Ledger
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No students found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $students->links() }}
    </div>
</div>
@endsection
