<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Reports Dashboard
     */
    public function index()
    {
        $session = active_session_name();

        $totalStudents = Student::where('session', $session)->count();

        // ✅ Total Received (from payments)
        $totalReceived = FeePayment::where('session', $session)->sum('paid_amount');

        // ✅ Total Due (from invoices)
        $totalDue = FeeInvoice::where('session', $session)->sum('due_amount');

        // ✅ This Month Received
        $thisMonth = now()->format('Y-m');
        $monthReceived = FeePayment::where('session', $session)
            ->whereRaw("strftime('%Y-%m', paid_date) = ?", [$thisMonth])
            ->sum('paid_amount');

        return view('reports.index', compact(
            'totalStudents',
            'totalReceived',
            'totalDue',
            'thisMonth',
            'monthReceived'
        ));
    }
    public function monthlyReport(Request $request)
    {
        $sessions = AcademicSession::orderBy('name', 'desc')->get();
        $session = $request->session ?? active_session_name();
        $month   = $request->month ?? now()->format('Y-m');

        $invoices = FeeInvoice::with('student')
            ->where('session', $session)
            ->where('month', $month)
            ->whereHas('student')
            ->orderBy('student_id')
            ->get();

        $summary = [
            'total' => $invoices->sum('total_amount'),
            'paid'  => $invoices->sum('paid_amount'),
            'due'   => $invoices->sum('due_amount'),
        ];

        return view('reports.monthly', compact(
            'invoices',
            'summary',
            'month',
            'sessions',
            'session'
        ));
    }
    public function classWiseReport(Request $request)
    {
        $session = $request->session ?? active_session_name();
        $month   = $request->month ?? now()->format('Y-m');

        $rows = FeeInvoice::join('students', 'fee_invoices.student_id', '=', 'students.id')
            ->where('fee_invoices.session', $session)
            ->where('fee_invoices.month', $month)
            ->selectRaw("
                students.class as class,
                SUM(fee_invoices.total_amount) as total,
                SUM(fee_invoices.paid_amount) as paid,
                SUM(fee_invoices.due_amount) as due
            ")
            ->groupBy('students.class')
            ->orderBy('students.class')
            ->get();

        return view('reports.class-wise', compact('rows', 'month', 'session'));
    }
    public function dueReport(Request $request)
    {
        $session = $request->session ?? active_session_name();
        $class   = $request->class ?? null;

        $query = FeeInvoice::with('student')
            ->where('session', $session)
            ->whereHas('student')
            ->where('due_amount', '>', 0);

        if ($class) {
            $query->whereHas('student', fn ($q) => $q->where('class', $class));
        }

        $invoices = $query->orderBy('month')->paginate(15)->withQueryString();

        $classes = Student::select('class')->distinct()->orderBy('class')->pluck('class');

        return view('reports.dues', compact('invoices', 'classes', 'class', 'session'));
    }
    public function monthlyPdf(Request $request)
    {
        $session = $request->session ?? active_session_name();
        $month   = $request->month ?? now()->format('Y-m');

        $invoices = FeeInvoice::with('student')
            ->where('session', $session)
            ->where('month', $month)
            ->get();

        $summary = [
            'total' => $invoices->sum('total_amount'),
            'paid'  => $invoices->sum('paid_amount'),
            'due'   => $invoices->sum('due_amount'),
        ];

        $pdf = Pdf::loadView('reports.pdf.monthly-pdf', compact(
            'invoices',
            'summary',
            'month',
            'session'
        ))->setPaper('A4');

        return $pdf->download("monthly_report_{$month}.pdf");
    }
}
