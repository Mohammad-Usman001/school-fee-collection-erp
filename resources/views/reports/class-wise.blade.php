@extends('layouts.app')

@section('title','Class Wise Report')
@section('page_title','Class Wise Fee Report')

@section('content')

<form method="GET" class="row mb-3">
    <div class="col-md-3">
        <input type="month" name="month" value="{{ $month }}" class="form-control">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary">Filter</button>
    </div>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports</a>
</form>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Class</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Paid</th>
                    <th class="text-right">Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                <tr>
                    <td>{{ $r->class }}</td>
                    <td class="text-right">₹{{ number_format($r->total,2) }}</td>
                    <td class="text-right text-success">₹{{ number_format($r->paid,2) }}</td>
                    <td class="text-right text-danger">₹{{ number_format($r->due,2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
