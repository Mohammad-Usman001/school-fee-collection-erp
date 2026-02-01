@extends('layouts.app')

@section('title', 'Fee Details')
@section('page_title', 'Fee Structure Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('fee-structures.index') }}">Fee Structure</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
    <div class="card card-outline card-info">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title"><i class="fas fa-eye mr-1"></i> Fee Details</h3>
            <div>
                <a href="{{ route('fee-structures.edit', $feeStructure->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('fee-structures.index') }}" class="btn btn-secondary btn-sm">
                    Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">Session</th>
                    <td>
                        <span class="badge badge-success">{{ $feeStructure->session ?? 'N/A' }}</span>
                    </td>
                </tr>

                <tr>
                    <th>Class</th>
                    <td>{{ $feeStructure->class }}</td>
                </tr>
                <tr>
                    <th>Fee Type</th>
                    <td>{{ $feeStructure->fee_type }}</td>
                </tr>

                <tr>
                    <th>Frequency</th>
                    <td>
                        @if ($feeStructure->frequency == 'monthly')
                            <span class="badge badge-success">Monthly</span>
                        @else
                            <span class="badge badge-warning">One Time</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Amount</th>
                    <td><strong>₹{{ number_format($feeStructure->amount, 2) }}</strong></td>
                </tr>
            </table>

        </div>
    </div>
@endsection
