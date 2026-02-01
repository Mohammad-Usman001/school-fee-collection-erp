<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\FeeInvoice;
use App\Models\FeeInvoiceItem;
use App\Models\FeeStructure;
use App\Models\FeePayment;
use App\Models\FeePaymentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Setting;

class FeeCollectionController extends Controller
{
    /* =====================================================
     * INDEX : Payments List
     * ===================================================== */
    public function index(Request $request)
    {
        $query = FeePayment::with('student')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('receipt_no', 'like', "%$search%")
                ->orWhereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('unique_id', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
        }

        $payments = $query->paginate(10)->withQueryString();
        return view('fees.index', compact('payments'));
    }

    /* =====================================================
     * CREATE PAGE
     * ===================================================== */
    public function create()
    {
        $activeSession = active_session_name();
        $sessions = \App\Models\AcademicSession::orderBy('name', 'desc')->get();

        return view('fees.create', compact('activeSession', 'sessions'));
    }

    /* =====================================================
     * AJAX : Student Search
     * ===================================================== */
    public function studentSearch(Request $request)
    {
        $request->validate([
            'q' => 'required|min:2',
            'session' => 'required'
        ]);

        return Student::where('session', $request->session)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('unique_id', 'like', "%{$request->q}%")
                    ->orWhere('phone', 'like', "%{$request->q}%");
            })
            ->limit(20)
            ->get(['id', 'unique_id', 'name', 'class', 'section', 'phone']);
    }

    /* =====================================================
     * AJAX : LOAD MULTI-MONTH INVOICES
     * ===================================================== */
    public function loadInvoice(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'session'    => 'required',
            'months'     => 'required|array|min:1',
            'months.*'   => 'date_format:Y-m',
        ]);

        $student = Student::findOrFail($request->student_id);
        $invoices = [];

        foreach ($request->months as $month) {

            // 1️⃣ Existing invoice?
            $invoice = FeeInvoice::with('items.head')
                ->where('student_id', $student->id)
                ->where('session', $request->session)
                ->where('month', $month)
                ->first();

            if ($invoice) {
                $invoices[] = $invoice;
                continue;
            }

            // 2️⃣ Load fee structure
            $structures = FeeStructure::with('head')
                ->where('session', $request->session)
                ->where('class', $student->class)
                ->get();

            if ($structures->isEmpty()) {
                continue;
            }

            // 3️⃣ ONE_TIME already used check
            $usedOneTimeHeads = FeeInvoiceItem::whereHas('invoice', function ($q) use ($student, $request) {
                $q->where('student_id', $student->id)
                    ->where('session', $request->session);
            })
                ->pluck('fee_head_id')
                ->unique()
                ->toArray();

            // 4️⃣ Create invoice
            $invoice = DB::transaction(function () use (
                $student,
                $request,
                $month,
                $structures,
                $usedOneTimeHeads
            ) {
                $inv = FeeInvoice::create([
                    'student_id'   => $student->id,
                    'session'      => $request->session,
                    'month'        => $month,
                    'total_amount' => 0,
                    'paid_amount'  => 0,
                    'due_amount'   => 0,
                    'status'       => 'unpaid',
                ]);

                $total = 0;

                // foreach ($structures as $fs) {

                //     if (
                //         $fs->head->frequency === 'one_time' &&
                //         in_array($fs->fee_head_id, $usedOneTimeHeads)
                //     ) {
                //         continue;
                //     }

                //     FeeInvoiceItem::create([
                //         'fee_invoice_id' => $inv->id,
                //         'fee_head_id'    => $fs->fee_head_id,
                //         'amount'         => $fs->amount,
                //     ]);

                //     $total += $fs->amount;
                // }
                foreach ($structures as $fs) {

                    $frequency = $fs->head->frequency; // one_time | monthly | quarterly

                    // ❌ ONE TIME already used
                    if (
                        $frequency === 'one_time' &&
                        in_array($fs->fee_head_id, $usedOneTimeHeads)
                    ) {
                        continue;
                    }

                    // ❌ QUARTERLY but not quarterly month
                    if ($frequency === 'quarterly' && !isQuarterlyMonth($month)) {
                        continue;
                    }

                    // ✅ MONTHLY → always allowed

                    FeeInvoiceItem::create([
                        'fee_invoice_id' => $inv->id,
                        'fee_head_id'    => $fs->fee_head_id,
                        'amount'         => $fs->amount,
                    ]);

                    $total += $fs->amount;
                }

                $inv->update([
                    'total_amount' => $total,
                    'due_amount'   => $total,
                ]);

                return FeeInvoice::with('items.head')->find($inv->id);
            });

            $invoices[] = $invoice;
        }

        // return response()->json([
        //     'invoices' => $invoices
        // ]);
        return response()->json([
            'invoices' => $invoices,
            'summary' => [
                'total' => collect($invoices)->sum('total_amount'),
                'paid'  => collect($invoices)->sum('paid_amount'),
                'due'   => collect($invoices)->sum('due_amount'),
                'status' => collect($invoices)->every(fn($i) => $i->due_amount <= 0)
                    ? 'PAID'
                    : 'DUE'
            ]
        ]);
    }

    /* =====================================================
     * STORE PAYMENT (FIFO MULTI-MONTH)
     * ===================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'session'       => 'required',
            'student_id'    => 'required|exists:students,id',
            'paid_date'     => 'required|date',
            'payment_mode'  => 'required|in:cash,upi,bank',
            'paid_amount'   => 'required|numeric|min:1',
            'invoice_ids'   => 'required|array|min:1',
            'invoice_ids.*' => 'exists:fee_invoices,id',
        ]);

        $receiptNo = $this->generateReceiptNo();
        $totalDue = FeeInvoice::whereIn('id', $request->invoice_ids)
            ->sum('due_amount');

        if ($request->paid_amount > $totalDue) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'paid_amount' => 'Entered amount exceeds the pending due. Please enter an amount up to ₹' . number_format($totalDue, 2)
                ]);
        }


        DB::transaction(function () use ($request, $receiptNo) {

            $payment = FeePayment::create([
                'student_id'   => $request->student_id,
                'session'      => $request->session,
                'receipt_no'   => $receiptNo,
                'paid_amount'  => $request->paid_amount,
                'payment_mode' => $request->payment_mode,
                'paid_date'    => $request->paid_date,
                'note'         => $request->note ?? null,
            ]);

            $remaining = (float) $request->paid_amount;

            $invoices = FeeInvoice::whereIn('id', $request->invoice_ids)
                ->orderBy('month', 'asc') // FIFO
                ->get();

            foreach ($invoices as $inv) {

                if ($remaining <= 0) break;
                if ($inv->due_amount <= 0) continue;

                $pay = min($remaining, $inv->due_amount);

                FeePaymentItem::create([
                    'fee_payment_id' => $payment->id,
                    'fee_invoice_id' => $inv->id,
                    'amount'         => $pay,
                ]);

                $newPaid = $inv->paid_amount + $pay;
                $newDue  = max($inv->total_amount - $newPaid, 0);

                $inv->update([
                    'paid_amount' => $newPaid,
                    'due_amount'  => $newDue,
                    'status'      => $newDue <= 0 ? 'paid' : 'partial',
                ]);

                $remaining -= $pay;
            }
        });

        return redirect()
            ->route('fees.index')
            ->with('success', 'Payment saved successfully!');
    }

    /* =====================================================
     * AJAX : MONTH PAYMENT HISTORY
     * ===================================================== */
    public function monthPayments(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:fee_invoices,id'
        ]);

        $items = FeePaymentItem::with('payment')
            ->where('fee_invoice_id', $request->invoice_id)
            ->latest()
            ->get()
            ->map(fn($it) => [
                'date'    => $it->payment->paid_date,
                'receipt' => $it->payment->receipt_no,
                'mode'    => strtoupper($it->payment->payment_mode),
                'amount'  => $it->amount,
            ]);

        return response()->json([
            'items' => $items,
            'sum'   => $items->sum('amount')
        ]);
    }

    /* =====================================================
     * RECEIPT
     * ===================================================== */
    public function receipt(FeePayment $payment)
    {
        $payment->load('student', 'items.invoice');
        $setting = Setting::first();
        // ✅ Active academic session
        $session = \App\Models\AcademicSession::where('is_active', 1)->first();
        return view('fees.receipt', compact('payment', 'setting', 'session'));
    }

    /* =====================================================
     * RECEIPT NUMBER GENERATOR
     * ===================================================== */
    private function generateReceiptNo(): string
    {
        $prefix = 'REC-' . date('Y') . '-';

        $last = FeePayment::where('receipt_no', 'like', $prefix . '%')
            ->latest('id')
            ->first();

        $next = $last
            ? ((int) Str::afterLast($last->receipt_no, '-') + 1)
            : 1;

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /* =====================================================
     * DELETE PAYMENT (ROLLBACK)
     * ===================================================== */
    public function delete($id)
    {
        $payment = FeePayment::with('items')->findOrFail($id);

        DB::transaction(function () use ($payment) {
            foreach ($payment->items as $item) {
                $inv = FeeInvoice::find($item->fee_invoice_id);
                if (!$inv) continue;

                $newPaid = $inv->paid_amount - $item->amount;
                $newDue  = max($inv->total_amount - $newPaid, 0);

                $inv->update([
                    'paid_amount' => max($newPaid, 0),
                    'due_amount'  => $newDue,
                    'status'      => $newDue <= 0 ? 'paid' : 'partial',
                ]);
            }

            $payment->items()->delete();
            $payment->delete();
        });

        return redirect()
            ->route('fees.index')
            ->with('success', 'Payment deleted successfully!');
    }

    /* =====================================================
     * LOAD ALL PENDING INVOICES
     * ===================================================== */
    public function loadPendingInvoices(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'session'    => 'required',
        ]);

        return FeeInvoice::where('student_id', $request->student_id)
            ->where('session', $request->session)
            ->orderBy('month', 'asc')
            ->get();
    }
}
