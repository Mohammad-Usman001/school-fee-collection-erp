@extends('layouts.app')

@section('title', 'Fee Payments')
@section('page_title', 'Fee Payments')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Fee Payments</h3>
        <a href="{{ route('fees.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Collect Fee
        </a>
    </div>

    <div class="card-body">

        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control" placeholder="Search receipt / student name / phone">
                <div class="input-group-append">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Receipt</th>
                    <th>Student</th>
                    <th>Date</th>
                    <th>Mode</th>
                    <th class="text-right">Amount</th>
                    <th width="120">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $p->receipt_no }}</strong></td>
                        <td>
                            {{ $p->student->name ?? '' }}
                            <br><small class="text-muted">{{ $p->student->unique_id ?? '' }}</small>
                        </td>
                        <td>{{ $p->paid_date }}</td>
                        <td>{{ strtoupper($p->payment_mode) }}</td>
                        <td class="text-right text-success"><strong>₹{{ number_format($p->paid_amount,2) }}</strong></td>
                        <td>
                            <a href="{{ route('fees.receipt', $p->id) }}" target="_blank"
                               class="btn btn-sm btn-secondary">
                                <i class="fas fa-print"></i> 
                            </a>
                            <form action="{{ route('fees.delete', $p->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No payment found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $payments->links() }}
    </div>
</div>
@endsection
