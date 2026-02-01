@extends('layouts.app')

@section('title','Fee Collection Details')
@section('page_title','Fee Collection Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('fee-collections.index') }}">Fee Collection</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="card card-outline card-info">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">
            <i class="fas fa-receipt mr-1"></i> Receipt: {{ $feeCollection->receipt_no }}
        </h3>
        <div>
            <a href="{{ route('fee-collections.receipt', $feeCollection->id) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-print"></i> Print Receipt
            </a>
            <a href="{{ route('fee-collections.index') }}" class="btn btn-primary btn-sm">
                Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Student Info</h5>
                <table class="table table-bordered">
                    <tr><th>Name</th><td>{{ $feeCollection->student->name }}</td></tr>
                    <tr><th>Unique ID</th><td>{{ $feeCollection->student->unique_id }}</td></tr>
                    <tr><th>Class</th><td>{{ $feeCollection->student->class }}</td></tr>
                </table>
            </div>

            <div class="col-md-6">
                <h5>Payment Info</h5>
                <table class="table table-bordered">
                    <tr><th>Month</th><td>{{ $feeCollection->month }}</td></tr>
                    <tr><th>Date</th><td>{{ $feeCollection->paid_date->format('d M Y') }}</td></tr>
                    <tr><th>Total</th><td>₹{{ number_format($feeCollection->total_amount,2) }}</td></tr>
                    <tr><th>Paid</th><td class="text-success">₹{{ number_format($feeCollection->paid_amount,2) }}</td></tr>
                    <tr><th>Due</th><td class="text-danger">₹{{ number_format($feeCollection->due_amount,2) }}</td></tr>
                </table>
            </div>
        </div>

        <h5 class="mt-3">Fee Items</h5>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Fee Type</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($feeCollection->items as $i => $it)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $it->fee_type }}</td>
                    <td class="text-right">₹{{ number_format($it->amount,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection
