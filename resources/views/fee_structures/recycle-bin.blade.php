@extends('layouts.app')

@section('title','Recycle Bin - Fee Structure')
@section('page_title','Fee Structure Recycle Bin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('fee-structures.index') }}">Fee Structure</a></li>
    <li class="breadcrumb-item active">Recycle Bin</li>
@endsection

@section('content')
<div class="card card-outline card-danger">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title"><i class="fas fa-trash mr-1"></i> Deleted Fee Structures</h3>
        <a href="{{ route('fee-structures.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-10">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="Search deleted fee types...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-danger w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Class</th>
                    <th>Fee Type</th>
                    <th>Frequency</th>
                    <th>Amount</th>
                    <th>Deleted At</th>
                    <th width="220">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($feeStructures as $i => $fee)
                    <tr>
                        <td>{{ $feeStructures->firstItem() + $i }}</td>
                        <td><span class="badge badge-info">{{ $fee->class }}</span></td>
                        <td>{{ $fee->fee_type }}</td>
                        <td>{{ $fee->frequency }}</td>
                        <td><strong>₹{{ number_format($fee->amount,2) }}</strong></td>
                        <td>{{ $fee->deleted_at->format('d M Y h:i A') }}</td>
                        <td>
                            <form action="{{ route('fee-structures.restore', $fee->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm" onclick="return confirm('Restore this fee structure?')">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                            </form>

                            <form action="{{ route('fee-structures.forceDelete', $fee->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Permanently delete? This cannot be undone!')">
                                    <i class="fas fa-times"></i> Delete Forever
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Recycle bin is empty.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $feeStructures->links() }}
        </div>
    </div>
</div>
@endsection
