@extends('layouts.app')

@section('title','Classes')
@section('page_title','Class Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Classes</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-school mr-1"></i> Classes</h3>

        <a href="{{ route('classes.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Class
        </a>
    </div>

    <div class="card-body">
        <form class="row mb-3" method="GET">
            <div class="col-md-8 mb-2">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Search class name...">
            </div>
            <div class="col-md-4 mb-2 d-flex">
                <button class="btn btn-primary w-100 mr-2"><i class="fas fa-search"></i></button>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Sort Order</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $i => $c)
                    <tr>
                        <td>{{ $classes->firstItem() + $i }}</td>
                        <td><strong>{{ $c->name }}</strong></td>
                        <td>{{ $c->sort_order }}</td>
                        <td>
                            <a href="{{ route('classes.edit', $c->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('classes.destroy', $c->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this class?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No classes found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $classes->links() }}
    </div>
</div>
@endsection
