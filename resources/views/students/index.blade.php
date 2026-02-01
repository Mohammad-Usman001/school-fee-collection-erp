@extends('layouts.app')

@section('title','Students')
@section('page_title','Students List')

@section('breadcrumb')
    <li class="breadcrumb-item active">Students</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-user-graduate mr-1"></i> Students
        </h3>

        <div>
            <a href="{{ route('students.recycleBin') }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-trash"></i> Recycle Bin
            </a>
            <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Student
            </a>
        </div>
    </div>

    <div class="card-body">

        {{-- Filters --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       placeholder="Search by name / ID / phone">
            </div>

            <div class="col-md-3">
                <select name="class" class="form-control">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c }}" {{ request('class')==$c?'selected':'' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="section" class="form-control">
                    <option value="">All Sections</option>
                    @foreach($sections as $s)
                        <option value="{{ $s }}" {{ request('section')==$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex">
                <button class="btn btn-primary w-100 mr-1">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary w-100">
                    Reset
                </a>
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
                    <th>Section</th>
                    <th>Phone</th>
                    <th width="160">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($students as $i => $student)
                    <tr>
                        <td>{{ $students->firstItem() + $i }}</td>
                        <td><span class="badge badge-dark">{{ $student->unique_id }}</span></td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ $student->section ?? '-' }}</td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        <td>
                            <a href="{{ route('students.show',$student->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('students.edit',$student->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('students.destroy',$student->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Move this student to recycle bin?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No students found.
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
