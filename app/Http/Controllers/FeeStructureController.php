<?php

namespace App\Http\Controllers;

use App\Models\FeeHead;
use App\Models\FeeStructure;
use App\Models\AcademicSession;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeStructureController extends Controller
{
    public function index(Request $request)
    {
        $query = FeeStructure::with(['head'])->latest();

        if ($request->filled('session')) {
            $query->where('session', $request->session);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('frequency')) {
            $query->whereHas('head', function ($q) use ($request) {
                $q->where('frequency', $request->frequency);
            });
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('head', function ($q) use ($s) {
                $q->where('name', 'like', "%$s%");
            });
        }

        $feeStructures = $query->paginate(10)->withQueryString();

        $sessions = AcademicSession::orderBy('name', 'desc')->get();
        $classes = SchoolClass::orderBy('sort_order')->get();

        return view('fee_structures.index', compact('feeStructures', 'sessions', 'classes'));
    }

    public function create()
    {
        $sessions = AcademicSession::orderBy('name', 'desc')->get();
        $classes  = SchoolClass::orderBy('sort_order')->get();
        $activeSession = active_session_name();

        $feeHeads = FeeHead::orderBy('name')->get();

        return view('fee_structures.create', compact('sessions', 'classes', 'activeSession', 'feeHeads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'session' => ['required', 'string'],
            'class'   => ['required', 'string'],
            'items'   => ['required', 'array', 'min:1'],
            'items.*.fee_head_id' => ['required', 'exists:fee_heads,id'],
            'items.*.amount'      => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($request) {

            foreach ($request->items as $row) {
                FeeStructure::updateOrCreate(
                    [
                        'session'     => $request->session,
                        'class'       => $request->class,
                        'fee_head_id' => $row['fee_head_id'],
                    ],
                    [
                        'amount' => $row['amount'],
                    ]
                );
            }
        });

        return redirect()->route('fee-structures.index')->with('success', 'Fee Structure saved successfully!');
    }

    public function edit(FeeStructure $feeStructure)
    {
        // For edit UI we open session+class wise group
        $sessions = AcademicSession::orderBy('name', 'desc')->get();
        $classes  = SchoolClass::orderBy('sort_order')->get();
        $feeHeads = FeeHead::orderBy('name')->get();

        $activeSession = active_session_name();

        // load all structure of same session/class
        $structures = FeeStructure::with('head')
            ->where('session', $feeStructure->session)
            ->where('class', $feeStructure->class)
            ->get();

        return view('fee_structures.edit', compact('feeStructure', 'sessions', 'classes', 'feeHeads', 'activeSession', 'structures'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $request->validate([
            'session' => ['required', 'string'],
            'class'   => ['required', 'string'],
            'items'   => ['required', 'array', 'min:1'],
            'items.*.fee_head_id' => ['required', 'exists:fee_heads,id'],
            'items.*.amount'      => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($request) {

            // delete old session/class structure and recreate
            FeeStructure::where('session', $request->session)
                ->where('class', $request->class)
                ->delete();

            foreach ($request->items as $row) {
                FeeStructure::create([
                    'session'     => $request->session,
                    'class'       => $request->class,
                    'fee_head_id' => $row['fee_head_id'],
                    'amount'      => $row['amount'],
                ]);
            }
        });

        return redirect()->route('fee-structures.index')->with('success', 'Fee Structure updated successfully!');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();
        return redirect()->route('fee-structures.index')->with('success', 'Fee Structure deleted!');
    }

    // -------- recycle bin ----------
    public function recycleBin()
    {
        $feeStructures = FeeStructure::onlyTrashed()->with('head')->latest('deleted_at')->paginate(10);
        return view('fee_structures.recycle-bin', compact('feeStructures'));
    }

    public function restore($id)
    {
        $item = FeeStructure::onlyTrashed()->findOrFail($id);
        $item->restore();
        return redirect()->route('fee-structures.recycleBin')->with('success', 'Restored successfully!');
    }

    public function forceDelete($id)
    {
        $item = FeeStructure::onlyTrashed()->findOrFail($id);
        $item->forceDelete();
        return redirect()->route('fee-structures.recycleBin')->with('success', 'Deleted permanently!');
    }
}
