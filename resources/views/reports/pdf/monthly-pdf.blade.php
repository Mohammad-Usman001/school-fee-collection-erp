<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Fee Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
        }

        .school-title {
            font-size: 18px;
            font-weight: bold;
        }

        .sub-title {
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #999;
            padding: 6px;
            font-size: 11px;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .summary {
            margin-top: 10px;
            border: 1px solid #000;
        }

        .summary td {
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .paid { color: green; font-weight: bold; }
        .due { color: red; font-weight: bold; }

    </style>
</head>
<body>

{{-- ================= HEADER ================= --}}
<div class="header">
    <div class="school-title">School Fees Management System</div>
    <div class="sub-title">
        Monthly Fee Collection Report <br>
        <strong>Session:</strong> {{ $session ?? 'N/A' }} |
        <strong>Month:</strong>
        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
    </div>
</div>

{{-- ================= SUMMARY ================= --}}
<table class="summary">
    <tr>
        <td>Total Amount</td>
        <td class="right">₹{{ number_format($summary->total ?? 0, 2) }}</td>
        <td>Total Paid</td>
        <td class="right">₹{{ number_format($summary->paid ?? 0, 2) }}</td>
        <td>Total Due</td>
        <td class="right">₹{{ number_format($summary->due ?? 0, 2) }}</td>
    </tr>
</table>

{{-- ================= DATA TABLE ================= --}}
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Student ID</th>
            <th>Class</th>
            <th class="right">Total</th>
            <th class="right">Paid</th>
            <th class="right">Due</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($invoices as $i => $inv)
        <tr>
            <td class="center">{{ $i+1 }}</td>
            <td>{{ $inv->student->name }}</td>
            <td>{{ $inv->student->unique_id }}</td>
            <td class="center">{{ $inv->student->class }}</td>

            <td class="right">₹{{ number_format($inv->total_amount,2) }}</td>
            <td class="right paid">₹{{ number_format($inv->paid_amount,2) }}</td>
            <td class="right due">₹{{ number_format($inv->due_amount,2) }}</td>

            <td class="center">
                @if($inv->due_amount <= 0)
                    PAID
                @elseif($inv->paid_amount > 0)
                    PARTIAL
                @else
                    DUE
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="center">No records found</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- ================= FOOTER ================= --}}
<div class="footer">
    Generated on {{ now()->format('d M Y, h:i A') }} |
    School Fees Management System
</div>

</body>
</html>
