@extends('layouts.app')

@section('title', 'Fee Receipt')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="card receipt-print">

        {{-- PRINT BUTTON --}}
        <div class="card-header no-print d-flex justify-content-between">
            <h3 class="card-title">
                <i class="fas fa-receipt"></i> Fee Receipt
            </h3>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fas fa-print"></i> Print
            </button>
        </div>

        <div class="card-body p-4">

            {{-- SCHOOL HEADER --}}
            <div class="row align-items-center mb-4">
                <div class="col-2 text-center">
                    @if ($setting->school_logo)
                        <img src="{{ asset($setting->school_logo) }}" style="max-height:90px;">
                    @endif
                </div>

                <div class="col-8 text-center">
                    <h2 class="mb-1 font-weight-bold">{{ $setting->school_name }}</h2>
                    <div>{{ $setting->school_address }}</div>
                    <div>
                        Phone: {{ $setting->school_phone }} |
                        Email: {{ $setting->school_email }}
                    </div>
                    <strong>Academic Session: {{ $session->name ?? $payment->session }}</strong>
                </div>

                <div class="col-2 text-right">
                    <span class="badge badge-success p-2">PAID</span>
                </div>
            </div>

            <hr>

            {{-- RECEIPT INFO --}}
            <div class="row mb-4">
                <div class="col-6">
                    <p><strong>Receipt No:</strong> {{ $payment->receipt_no }}</p>
                    <p><strong>Date:</strong> {{ Carbon::parse($payment->paid_date)->format('d M Y') }}</p>
                    <p><strong>Payment Mode:</strong> {{ strtoupper($payment->payment_mode) }}</p>
                </div>

                <div class="col-6 text-right">
                    <p><strong>Student Name:</strong> {{ $payment->student->name }}</p>
                    <p><strong>Class:</strong> {{ $payment->student->class }}</p>
                    <p><strong>Student ID:</strong> {{ $payment->student->unique_id }}</p>
                </div>
            </div>

            {{-- GROUP ITEMS BY MONTH --}}
            @php
                // Collect invoices from payment items
                $invoices = $payment->items->map(fn($pi) => $pi->invoice)->unique('id');
            @endphp


            {{-- FEE BREAKUP --}}
            {{-- @foreach ($grouped as $month => $items)
                <h5 class="mt-4 mb-2">
                    Month: {{ Carbon::parse($month)->format('F Y') }}
                </h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fee Head</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $monthTotal = 0; @endphp
                        @foreach ($items as $it)
                            <tr>
                                <td>{{ $it->fee_head_name ?? 'Fee' }}</td>
                                <td class="text-right">
                                    {{ $setting->currency_symbol }}
                                    {{ number_format($it->amount, 2) }}
                                </td>
                            </tr>
                            @php $monthTotal += $it->amount; @endphp
                        @endforeach
                        <tr class="bg-light font-weight-bold">
                            <td class="text-right">Month Total</td>
                            <td class="text-right">
                                {{ $setting->currency_symbol }}
                                {{ number_format($monthTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endforeach --}}
            @foreach ($invoices as $invoice)
                <h5 class="mt-4 mb-2">
                    Month: {{ \Carbon\Carbon::parse($invoice->month)->format('F Y') }}
                </h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fee Head</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $monthTotal = 0; @endphp

                        @foreach ($invoice->items as $invItem)
                            <tr>
                                <td>{{ $invItem->head->name }}</td>
                                <td class="text-right">
                                    {{ $setting->currency_symbol }}
                                    {{ number_format($invItem->amount, 2) }}
                                </td>
                            </tr>
                            @php $monthTotal += $invItem->amount; @endphp
                        @endforeach

                        <tr class="bg-light font-weight-bold">
                            <td class="text-right">Month Total</td>
                            <td class="text-right">
                                {{ $setting->currency_symbol }}
                                {{ number_format($monthTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endforeach

            {{-- GRAND TOTAL --}}
            <div class="row mt-4">
                <div class="col-7">
                    <strong>Amount in Words:</strong><br>
                    {{ ucfirst(\App\Helpers\NumberHelper::toWords($payment->paid_amount)) }} only
                </div>
                <div class="col-5 text-right">
                    <h4>
                        Total Paid:
                        {{ $setting->currency_symbol }}
                        {{ number_format($payment->paid_amount, 2) }}
                    </h4>
                </div>
            </div>

            <hr>

            {{-- FOOTER --}}
            <div class="row mt-5">
                <div class="col-6 text-center">
                    <div style="height:60px;"></div>
                    <strong>Authorized Sign</strong>
                </div>
                <div class="col-6 text-center">
                    <div style="height:60px;"></div>
                    <strong>School Stamp</strong>
                </div>
            </div>

            <div class="text-center mt-3">
                <small>{{ $setting->receipt_footer }}</small>
            </div>

        </div>
    </div>

    {{-- PRINT STYLE --}}
    <style>
        @media print {
            body {
                background: #fff !important;
            }

            .no-print {
                display: none !important;
            }

            .receipt-print {
                border: none !important;
                box-shadow: none !important;
            }

            .table th,
            .table td {
                padding: 8px !important;
                font-size: 13px;
            }
        }
    </style>
@endsection
