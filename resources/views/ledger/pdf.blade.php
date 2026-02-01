<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ledger PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align:center; margin-bottom: 10px; }
        .meta { margin-top:5px; font-size: 12px; }
        table { width:100%; border-collapse: collapse; margin-top:10px; }
        th, td { border:1px solid #ddd; padding:6px; }
        th { background:#f2f2f2; }
        .right { text-align:right; }
        .center { text-align:center; }
        .badge { padding:2px 6px; border-radius: 4px; font-size: 11px; }
        .paid { background:#dcfce7; }
        .partial { background:#fef9c3; }
        .due { background:#fee2e2; }
        .new { background:#e0f2fe; }
    </style>
</head>
<body>

<div class="header">
    <h2>Student Ledger Report</h2>
    <div class="meta">
        <strong>{{ $student->name }}</strong> ({{ $student->unique_id }}) <br>
        Class: {{ $student->class }} {{ $student->section ? '- '.$student->section : '' }} <br>
        Session: <strong>{{ $session }}</strong>
    </div>
</div>

<table>
    <tr>
        <th>Total Fees</th>
        <th>Total Paid</th>
        <th>Total Due</th>
    </tr>
    <tr>
        <td class="right">₹{{ number_format($totalFees,2) }}</td>
        <td class="right">₹{{ number_format($totalPaid,2) }}</td>
        <td class="right">₹{{ number_format($totalDue,2) }}</td>
    </tr>
</table>

<h3 style="margin-top:14px;">Month Wise Fee Records (April to March)</h3>
<table>
    <thead>
        <tr>
            <th width="120">Month</th>
            <th class="right">Total</th>
            <th class="right">Paid</th>
            <th class="right">Due</th>
            <th width="90" class="center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($monthRecords as $row)
            @php
                $m = \Carbon\Carbon::createFromFormat('Y-m', $row['month'])->format('M Y');
                $cls = $row['status']=='PAID' ? 'paid' : ($row['status']=='PARTIAL' ? 'partial' : ($row['status']=='DUE' ? 'due' : 'new'));
            @endphp
            <tr>
                <td>{{ $m }}</td>
                <td class="right">₹{{ number_format($row['total'],2) }}</td>
                <td class="right">₹{{ number_format($row['paid'],2) }}</td>
                <td class="right">₹{{ number_format($row['due'],2) }}</td>
                <td class="center">
                    <span class="badge {{ $cls }}">{{ $row['status'] }}</span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:20px; font-size: 11px; text-align:center;">
    Generated on {{ now()->format('d M Y, h:i A') }}
</p>

</body>
</html>
