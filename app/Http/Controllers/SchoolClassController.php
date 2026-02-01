<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $classes = $query->orderBy('sort_order')->paginate(10)->withQueryString();

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:100','unique:classes,name'],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        SchoolClass::create([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('classes.index')->with('success','Class created successfully!');
    }

    public function edit(SchoolClass $class)
    {
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name' => ['required','string','max:100','unique:classes,name,' . $class->id],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        $class->update([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('classes.index')->with('success','Class updated successfully!');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();
        return redirect()->route('classes.index')->with('success','Class moved to recycle bin!');
    }
}
