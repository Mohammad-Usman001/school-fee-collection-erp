<?php

namespace App\Http\Controllers;

use App\Models\FeeHead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeHeadController extends Controller
{
    public function index(Request $request)
    {
        $query = FeeHead::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        $feeHeads = $query->latest()->paginate(10)->withQueryString();

        return view('fee_heads.index', compact('feeHeads'));
    }

    public function create()
    {
        return view('fee_heads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:fee_heads,name'],
            'frequency' => ['required', Rule::in(['monthly', 'one_time', 'quarterly'])],
            'is_active' => ['nullable'],
        ]);

        FeeHead::create([
            'name' => $request->name,
            'frequency' => $request->frequency,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('fee-heads.index')->with('success', 'Fee Head added successfully!');
    }

    public function edit(FeeHead $feeHead)
    {
        return view('fee_heads.edit', compact('feeHead'));
    }

    public function update(Request $request, FeeHead $feeHead)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('fee_heads', 'name')->ignore($feeHead->id)],
            'frequency' => ['required', Rule::in(['monthly', 'one_time', 'quarterly'])],
            'is_active' => ['nullable'],
        ]);

        $feeHead->update([
            'name' => $request->name,
            'frequency' => $request->frequency,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('fee-heads.index')->with('success', 'Fee Head updated successfully!');
    }

    public function destroy(FeeHead $feeHead)
    {
        $feeHead->delete();
        return redirect()->route('fee-heads.index')->with('success', 'Fee Head deleted successfully!');
    }
}
