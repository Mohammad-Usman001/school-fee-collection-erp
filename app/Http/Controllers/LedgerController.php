<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\FeePaymentItem;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $sessions = AcademicSession::orderBy('name', 'desc')->get();
        $session  = $request->session ?? active_session_name();

        $students = Student::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where('name', 'like', "%$search%")
                    ->orWhere('unique_id', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            })
            ->when($session, fn($q) => $q->where('session', $session))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('ledger.index', compact('students', 'sessions', 'session'));
    }

    // ✅ helper: session months April -> March
    private function sessionMonths(string $session): array
    {
        // session format: 2025-26
        [$startYear, $endYearShort] = explode('-', $session);
        $startYear = (int)$startYear;
        $endYear   = (int)('20' . $endYearShort);

        $months = [];
        $start = Carbon::create($startYear, 4, 1); // April
        $end   = Carbon::create($endYear, 3, 1);   // March

        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }

        return $months;
    }

    public function show(Request $request, Student $student)
    {
        $sessions = AcademicSession::orderBy('name', 'desc')->get();
        $session  = $request->session ?? ($student->session ?? active_session_name());

        // ✅ Get all invoices of this session
        $invoices = FeeInvoice::with('items.head')
            ->where('student_id', $student->id)
            ->where('session', $session)
            ->get()
            ->keyBy('month'); // month => invoice

        // ✅ Get all payments of this session
        $payments = FeePayment::where('student_id', $student->id)
            ->where('session', $session)
            ->latest('paid_date')
            ->get();

        // ✅ Last payment info
        $lastPayment = $payments->first();
        $lastPaymentDate = $lastPayment?->paid_date;
        $lastReceipt = $lastPayment?->receipt_no;

        // ✅ Month wise paid from FeePaymentItem
        $paymentItems = FeePaymentItem::with('payment')
            ->whereHas('payment', function ($q) use ($student, $session) {
                $q->where('student_id', $student->id)->where('session', $session);
            })
            ->get();

        // month => sumPaid
        $paidByMonth = $paymentItems
            ->groupBy(fn($it) => $it->invoice?->month)
            ->map(fn($rows) => $rows->sum('amount'))
            ->toArray();

        // ✅ Generate months list April -> March
        $months = $this->sessionMonths($session);

        $monthRecords = collect($months)->map(function ($ym) use ($invoices, $paidByMonth, $paymentItems) {

            $invoice = $invoices[$ym] ?? null;

            $total = $invoice?->total_amount ?? 0;
            $paid  = $paidByMonth[$ym] ?? 0;
            $due   = max($total - $paid, 0);

            $status = "NEW";
            if ($total > 0 && $paid <= 0) $status = "DUE";
            if ($paid > 0 && $due > 0)   $status = "PARTIAL";
            if ($total > 0 && $due <= 0) $status = "PAID";

            // month transactions (installments list)
            $tx = $paymentItems->filter(function ($it) use ($ym) {
                return $it->invoice && $it->invoice->month === $ym;
            });

            return [
                'month' => $ym,
                'invoice' => $invoice,
                'items' => $invoice?->items ?? collect(),
                'transactions' => $tx,
                'total' => $total,
                'paid' => $paid,
                'due' => $due,
                'status' => $status,
            ];
        });

        // ✅ Totals
        $totalFees = $monthRecords->sum('total');
        $totalPaid = $monthRecords->sum('paid');
        $totalDue  = max($totalFees - $totalPaid, 0);

        return view('ledger.show', compact(
            'student',
            'sessions',
            'session',
            'totalFees',
            'totalPaid',
            'totalDue',
            'lastPaymentDate',
            'lastReceipt',
            'monthRecords'
        ));
    }


    /**
     * PDF Export
     */
    // public function pdf(Request $request, Student $student)
    // {
    //     $session = $request->session ?? ($student->session ?? active_session_name());

    //     $invoices = FeeInvoice::with('items')
    //         ->where('student_id', $student->id)
    //         ->where('session', $session)
    //         ->orderBy('month', 'asc')
    //         ->get();

    //     $payments = FeePayment::where('student_id', $student->id)
    //         ->where('session', $session)
    //         ->orderBy('paid_date', 'asc')
    //         ->get();

    //     $totalFees = $invoices->sum('total_amount');
    //     $totalPaid = $payments->sum('paid_amount');
    //     $totalDue  = max($totalFees - $totalPaid, 0);

    //     $pdf = Pdf::loadView('ledger.pdf', compact(
    //         'student',
    //         'session',
    //         'invoices',
    //         'payments',
    //         'totalFees',
    //         'totalPaid',
    //         'totalDue'
    //     ))->setPaper('A4', 'portrait');

    //     return $pdf->download("ledger_{$student->unique_id}_{$session}.pdf");
    // }
     // ✅ PDF DOWNLOAD
    public function pdf(Request $request, Student $student)
    {
        $session = $request->session ?? active_session_name();

        $invoices = FeeInvoice::with('items.head')
            ->where('student_id', $student->id)
            ->where('session', $session)
            ->get()
            ->keyBy('month');

        $paymentItems = FeePaymentItem::with('payment')
            ->whereHas('payment', function ($q) use ($student, $session) {
                $q->where('student_id', $student->id)
                    ->where('session', $session);
            })
            ->get();

        $paymentsByInvoice = $paymentItems->groupBy('fee_invoice_id');

        // ✅ April - March list
        [$startYear, $endYear] = explode('-', $session);
        $startYear = (int) $startYear;
        $endYear = $startYear + 1;

        $months = [];
        for ($m = 4; $m <= 12; $m++) $months[] = $startYear . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
        for ($m = 1; $m <= 3; $m++)  $months[] = $endYear . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);

        $monthRecords = collect($months)->map(function ($month) use ($invoices, $paymentsByInvoice) {

            $invoice = $invoices[$month] ?? null;

            $paid = 0;
            $due = 0;
            $total = 0;
            $status = 'NEW';

            if ($invoice) {
                $total = (float) $invoice->total_amount;

                $paid = isset($paymentsByInvoice[$invoice->id])
                    ? $paymentsByInvoice[$invoice->id]->sum('amount')
                    : 0;

                $due = max($total - $paid, 0);

                if ($paid <= 0) $status = 'DUE';
                elseif ($due <= 0) $status = 'PAID';
                else $status = 'PARTIAL';
            }

            return [
                'month' => $month,
                'invoice' => $invoice,
                'total' => $total,
                'paid' => $paid,
                'due' => $due,
                'status' => $status,
            ];
        });

        $totalFees = $monthRecords->sum('total');
        $totalPaid = $monthRecords->sum('paid');
        $totalDue  = max($totalFees - $totalPaid, 0);

        $pdf = Pdf::loadView('ledger.pdf', compact(
            'student',
            'session',
            'monthRecords',
            'totalFees',
            'totalPaid',
            'totalDue'
        ))->setPaper('A4', 'portrait');

        return $pdf->download("ledger_{$student->unique_id}_{$session}.pdf");
    }
}
