@extends('layouts.app')

@section('title','Sections')
@section('page_title','Section Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Sections</li>
@endsection

@section('content')
<div class="card card-outline card-info">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-layer-group mr-1"></i> Sections</h3>

        <a href="{{ route('sections.create') }}" class="btn btn-info btn-sm">
            <i class="fas fa-plus"></i> Add Section
        </a>
    </div>

    <div class="card-body">
        <form class="row mb-3" method="GET">
            <div class="col-md-8 mb-2">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Search section name...">
            </div>
            <div class="col-md-4 mb-2 d-flex">
                <button class="btn btn-info w-100 mr-2"><i class="fas fa-search"></i></button>
                <a href="{{ route('sections.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Section</th>
                        <th>Sort Order</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $i => $s)
                    <tr>
                        <td>{{ $sections->firstItem() + $i }}</td>
                        <td><strong>{{ $s->name }}</strong></td>
                        <td>{{ $s->sort_order }}</td>
                        <td>
                            <a href="{{ route('sections.edit', $s->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('sections.destroy', $s->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this section?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No sections found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $sections->links() }}
    </div>
</div>
@endsection
