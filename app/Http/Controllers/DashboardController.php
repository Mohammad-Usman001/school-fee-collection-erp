<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Student;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sessions = AcademicSession::orderBy('name', 'desc')->get();
        $session  = $request->session ?? active_session_name();
        $sessionProgress = session_progress($session);

        // ================= KPIs =================
        $totalStudents = Student::where('session', $session)->count();

        $totalInvoiced = FeeInvoice::where('session', $session)
            ->sum('total_amount');

        $totalCollected = FeeInvoice::where('session', $session)
            ->sum('paid_amount');

        $totalDue = FeeInvoice::where('session', $session)
            ->sum('due_amount');

        // ================= TODAY / MONTH =================
        $today = now()->toDateString();

        $todayCollection = FeePayment::where('session', $session)
            ->whereDate('paid_date', $today)
            ->sum('paid_amount');

        $thisMonth = now()->format('Y-m');

        $monthCollection = FeePayment::where('session', $session)
            ->whereRaw("strftime('%Y-%m', paid_date) = ?", [$thisMonth])
            ->sum('paid_amount');

        // ================= RECENT PAYMENTS =================
        $recentPayments = FeePayment::with('student')
            ->where('session', $session)
            ->latest()
            ->take(8)
            ->get();

        // ================= MONTHLY CHART =================
        $monthlyChart = FeePayment::selectRaw("
                strftime('%Y-%m', paid_date) as month,
                SUM(paid_amount) as total
            ")
            ->where('session', $session)
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->reverse()
            ->values();

        // ================= TOP DUES =================
        $topDues = FeeInvoice::with('student')
            ->where('session', $session)
            ->where('due_amount', '>', 0)
            ->whereHas('student') // 🔥 no student = skip
            ->take(5)
            ->get();
        $classSectionStats = Student::where('session', $session)
            ->select(
                'class',
                DB::raw("COALESCE(section, 'NO_SECTION') as section"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('class', 'section')
            ->orderBy('class')
            ->orderBy('section')
            ->get();


        return view('dashboard', compact(
            'sessions',
            'session',
            'sessionProgress',
            'totalStudents',
            'totalInvoiced',
            'totalCollected',
            'totalDue',
            'todayCollection',
            'monthCollection',
            'recentPayments',
            'monthlyChart',
            'topDues',
            'classSectionStats'
        ));
    }
}
