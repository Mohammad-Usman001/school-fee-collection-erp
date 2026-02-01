<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sections = $query->orderBy('sort_order')->paginate(10)->withQueryString();

        return view('sections.index', compact('sections'));
    }

    public function create()
    {
        return view('sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:50','unique:sections,name'],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        Section::create([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('sections.index')->with('success','Section created successfully!');
    }

    public function edit(Section $section)
    {
        return view('sections.edit', compact('section'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => ['required','string','max:50','unique:sections,name,' . $section->id],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        $section->update([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('sections.index')->with('success','Section updated successfully!');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('sections.index')->with('success','Section moved to recycle bin!');
    }
}
