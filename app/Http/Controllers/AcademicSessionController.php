<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\Request;

class AcademicSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicSession::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sessions = $query->latest()->paginate(10)->withQueryString();

        return view('sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:50','unique:academic_sessions,name'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date'],
            'is_active' => ['nullable'],
        ]);

        // if active = true then disable others
        $isActive = $request->has('is_active');

        if ($isActive) {
            AcademicSession::query()->update(['is_active' => false]);
        }

        AcademicSession::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $isActive,
        ]);

        return redirect()->route('sessions.index')->with('success','Session created successfully!');
    }

    public function edit(AcademicSession $session)
    {
        return view('sessions.edit', compact('session'));
    }

    public function update(Request $request, AcademicSession $session)
    {
        $request->validate([
            'name' => ['required','string','max:50','unique:academic_sessions,name,' . $session->id],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date'],
            'is_active' => ['nullable'],
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
            AcademicSession::query()->where('id', '!=', $session->id)->update(['is_active' => false]);
        }

        $session->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $isActive,
        ]);

        return redirect()->route('sessions.index')->with('success','Session updated successfully!');
    }

    public function destroy(AcademicSession $session)
    {
        $session->delete();
        return redirect()->route('sessions.index')->with('success','Session deleted successfully!');
    }
}
