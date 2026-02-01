@extends('layouts.app')

@section('title','Recycle Bin - Students')
@section('page_title','Recycle Bin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Recycle Bin</li>
@endsection

@section('content')
<div class="card card-outline card-danger">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-trash mr-1"></i> Deleted Students
        </h3>

        <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form method="GET" class="row mb-3">
            <div class="col-md-10">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="Search deleted students...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-danger w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Unique ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Deleted At</th>
                    <th width="220">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($students as $i => $student)
                    <tr>
                        <td>{{ $students->firstItem() + $i }}</td>
                        <td><span class="badge badge-dark">{{ $student->unique_id }}</span></td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ $student->deleted_at->format('d M Y h:i A') }}</td>
                        <td>
                            <form action="{{ route('students.restore', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm"
                                        onclick="return confirm('Restore this student?')">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                            </form>

                            <form action="{{ route('students.forceDelete', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Permanently delete this student? This cannot be undone!')">
                                    <i class="fas fa-times"></i> Delete Forever
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Recycle bin is empty.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
