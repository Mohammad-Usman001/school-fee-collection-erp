@extends('layouts.app')

@section('title','Monthly Fee Report')
@section('page_title','Monthly Collection Report')

@section('content')

<form method="GET" class="row mb-3">
    <div class="col-md-3">
        <input type="month" name="month" value="{{ $month }}" class="form-control">
    </div>
    <div class="col-md-3">
        <select name="session" class="form-control">
            @foreach($sessions as $ses)
                <option value="{{ $ses->name }}" {{ $session==$ses->name?'selected':'' }}>
                    {{ $ses->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary">
            <i class="fas fa-search"></i> Filter
        </button>
    </div>
    <div class="col-md-4 text-right">
        <a href="{{ route('reports.monthlyPdf',['month'=>$month,'session'=>$session]) }}"
           class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> PDF
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports</a>
    </div>

</form>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Paid</th>
                    <th class="text-right">Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td>{{ $inv->student->name }} ({{ $inv->student->unique_id }})</td>
                    <td>{{ $inv->student->class }}</td>
                    <td class="text-right">₹{{ number_format($inv->total_amount,2) }}</td>
                    <td class="text-right text-success">₹{{ number_format($inv->paid_amount,2) }}</td>
                    <td class="text-right text-danger">₹{{ number_format($inv->due_amount,2) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
