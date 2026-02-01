@extends('layouts.app')

@section('title','Due Fees')
@section('page_title','Pending / Due Fees Report')

@section('content')

<form method="GET" class="row mb-3">
    <div class="col-md-3">
        <select name="class" class="form-control">
            <option value="">All Classes</option>
            @foreach($classes as $c)
                <option value="{{ $c }}" {{ $class==$c?'selected':'' }}>
                    Class {{ $c }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-warning">Filter</button>
    </div>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports</a>
</form>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Month</th>
                    <th class="text-right">Due Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td>{{ $inv->student->name }}</td>
                    <td>{{ $inv->student->class }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m',$inv->month)->format('M Y') }}</td>
                    <td class="text-right text-danger">₹{{ number_format($inv->due_amount,2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">No dues found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
