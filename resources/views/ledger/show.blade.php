@extends('layouts.app')

@section('title','Ledger Details')
@section('page_title','Student Ledger')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ledger.index') }}">Ledger</a></li>
    <li class="breadcrumb-item active">{{ $student->name }}</li>
@endsection

@section('content')
<div class="card card-outline card-info">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h3 class="card-title mb-0">
                <i class="fas fa-user-graduate mr-1"></i>
                {{ $student->name }} ({{ $student->unique_id }})
            </h3>
            <small class="text-muted">
                Class: <strong>{{ $student->class }}</strong>
                {{ $student->section ? ' | Section: '.$student->section : '' }}
                {{ $student->phone ? ' | Phone: '.$student->phone : '' }}
            </small>
        </div>

        <div class="d-flex align-items-center mt-2 mt-md-0">

            {{-- Session Filter --}}
            <form method="GET" class="mr-2">
                <select name="session" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach($sessions as $ses)
                        <option value="{{ $ses->name }}" {{ $session==$ses->name?'selected':'' }}>
                            {{ $ses->name }} {{ $ses->is_active ? '(Active)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('ledger.pdf', [$student->id, 'session' => $session]) }}"
               class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> PDF
            </a>

            <a href="{{ route('ledger.index') }}" class="btn btn-secondary btn-sm ml-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>


    <div class="card-body">

        {{-- SUMMARY --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h4>₹{{ number_format($totalFees,2) }}</h4>
                        <p class="mb-0">Total Fees (Session)</p>
                    </div>
                    <div class="icon"><i class="fas fa-wallet"></i></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h4>₹{{ number_format($totalPaid,2) }}</h4>
                        <p class="mb-0">Total Paid</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h4>₹{{ number_format($totalDue,2) }}</h4>
                        <p class="mb-0">Total Due</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h5 style="font-weight:700; margin-bottom:3px;">
                            {{ $lastPaymentDate ? \Carbon\Carbon::parse($lastPaymentDate)->format('d M Y') : '--' }}
                        </h5>
                        <p class="mb-1">Last Payment Date</p>
                        <small class="text-white">
                            Receipt: {{ $lastReceipt ?? '--' }}
                        </small>
                    </div>
                    <div class="icon"><i class="fas fa-receipt"></i></div>
                </div>
            </div>
        </div>

        {{-- Month wise records --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
            <h5 class="mb-2 mb-md-0">
                <i class="fas fa-calendar-alt mr-1"></i> Month Wise Fee Records
            </h5>

            <span class="badge badge-light p-2" style="border:1px solid #e8edf5;">
                Session: <strong>{{ $session }}</strong>
                <span class="text-muted"> (April → March)</span>
            </span>
        </div>

        {{-- ACCORDION --}}
        <div class="accordion" id="ledgerAccordion">

            @forelse($monthRecords as $key => $row)

                @php
                    $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $row['month'])->format('F Y');
                    $status = $row['status'];

                    $badgeClass = 'secondary';
                    if ($status === 'PAID') $badgeClass = 'success';
                    elseif ($status === 'PARTIAL') $badgeClass = 'warning';
                    elseif ($status === 'DUE') $badgeClass = 'danger';
                    elseif ($status === 'NEW') $badgeClass = 'info';

                    $invoice = $row['invoice'];
                @endphp

                <div class="card mb-2 shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
                         style="cursor:pointer;"
                         data-toggle="collapse"
                         data-target="#collapse{{ $key }}">

                        <div class="d-flex align-items-center">
                            <strong class="text-primary">
                                {{ $monthLabel }}
                            </strong>

                            <span class="ml-2 badge badge-{{ $badgeClass }}">
                                {{ $status }}
                            </span>

                            @if(!$invoice)
                                <span class="ml-2 badge badge-light" style="border:1px solid #eee;">
                                    No Invoice Generated
                                </span>
                            @endif
                        </div>

                        <div class="text-right">
                            <small class="text-muted">Total:</small>
                            <strong>₹{{ number_format($row['total'],2) }}</strong>

                            <small class="text-muted ml-2">Paid:</small>
                            <strong class="text-success">₹{{ number_format($row['paid'],2) }}</strong>

                            <small class="text-muted ml-2">Due:</small>
                            <strong class="text-danger">₹{{ number_format($row['due'],2) }}</strong>

                            <span class="ml-2 text-muted">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </div>
                    </div>

                    <div id="collapse{{ $key }}" class="collapse" data-parent="#ledgerAccordion">
                        <div class="card-body">

                            {{-- Invoice not generated --}}
                            @if(!$invoice)
                                <div class="alert alert-light border">
                                    <i class="fas fa-info-circle"></i>
                                    This month invoice is not generated yet.
                                    <br>
                                    <small class="text-muted">
                                        Invoice will be created when you collect fee for this month.
                                    </small>
                                </div>
                            @else

                                {{-- Fee Heads --}}
                                <h6 class="mb-2">
                                    <i class="fas fa-list mr-1"></i>
                                    Fee Breakup (Fee Heads)
                                </h6>

                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered table-sm mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Fee Head</th>
                                                <th width="140" class="text-right">Amount</th>
                                                <th width="140" class="text-center">Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($row['items'] as $it)
                                                <tr>
                                                    <td>
                                                        {{ $it->head->name ?? 'N/A' }}
                                                    </td>
                                                    <td class="text-right">
                                                        ₹{{ number_format($it->amount,2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light" style="border:1px solid #eee;">
                                                            {{ strtoupper($it->head->frequency ?? '-') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">
                                                        No fee heads saved for this month.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-right">Month Total</th>
                                                <th class="text-right">₹{{ number_format($row['total'],2) }}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                {{-- Installments --}}
                                <h6 class="mb-2">
                                    <i class="fas fa-receipt mr-1"></i>
                                    Installment Payments
                                </h6>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="120">Paid Date</th>
                                                <th>Receipt No</th>
                                                <th width="120">Mode</th>
                                                <th class="text-right" width="160">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($row['transactions'] as $tx)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($tx->payment->paid_date)->format('d M Y') }}</td>
                                                    <td>{{ $tx->payment->receipt_no }}</td>
                                                    <td>{{ strtoupper($tx->payment->payment_mode) }}</td>
                                                    <td class="text-right text-success">
                                                        <strong>₹{{ number_format($tx->amount,2) }}</strong>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        No installment paid in this month.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Month summary badges --}}
                                <div class="d-flex justify-content-between mt-2 flex-wrap" style="gap:10px;">
                                    <span class="badge badge-success p-2">
                                        Paid This Month: ₹{{ number_format($row['paid'],2) }}
                                    </span>

                                    <span class="badge badge-danger p-2">
                                        Due This Month: ₹{{ number_format($row['due'],2) }}
                                    </span>
                                </div>

                            @endif
                        </div>
                    </div>
                </div>

            @empty
                <div class="text-center text-muted py-3">
                    No fee records found.
                </div>
            @endforelse

        </div>

    </div>
</div>
@endsection
