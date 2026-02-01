<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AcademicSession;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::orderBy('sort_order')->get();
        $sections = Section::orderBy('sort_order')->get();
        $sessions = AcademicSession::orderBy('name', 'desc')->get();

        return view('promotions.index', compact('classes', 'sections', 'sessions'));
    }

    public function promote(Request $request)
    {
        $request->validate([
            'from_class' => ['required'],
            'to_class'   => ['required', 'different:from_class'],
            'from_section' => ['nullable'],
            'to_section'   => ['nullable'],
            'to_session' => ['required'],
        ]);

        $query = Student::query()
            ->where('class', $request->from_class);

        if ($request->filled('from_section')) {
            $query->where('section', $request->from_section);
        }

        $students = $query->get();

        if ($students->count() == 0) {
            return redirect()->back()->with('error', 'No students found for promotion!');
        }

        foreach ($students as $st) {
            $st->update([
                'class' => $request->to_class,
                'section' => $request->to_section ?? $st->section,
                'session' => $request->to_session,
            ]);
        }

        return redirect()->route('promotions.index')
            ->with('success', $students->count() . " students promoted successfully!");
    }
    public function preview(Request $request)
    {
        $request->validate([
            'from_class' => ['required'],
            'to_class'   => ['required', 'different:from_class'],
            'from_section' => ['nullable'],
            'to_section'   => ['nullable'],
            'to_session' => ['required'],
        ]);

        $studentsQuery = Student::where('class', $request->from_class);

        if ($request->filled('from_section')) {
            $studentsQuery->where('section', $request->from_section);
        }

        $students = $studentsQuery->orderBy('name')->get();

        if ($students->count() == 0) {
            return redirect()->back()->with('error', 'No students found for promotion!');
        }

        // load masters for dropdown
        $classes = SchoolClass::orderBy('sort_order')->get();
        $sections = Section::orderBy('sort_order')->get();
        $sessions = AcademicSession::orderBy('name', 'desc')->get();

        // pass promotion data also
        $promotionData = $request->all();

        return view('promotions.preview', compact(
            'students',
            'classes',
            'sections',
            'sessions',
            'promotionData'
        ));
    }


    public function confirm(Request $request)
    {
        $request->validate([
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['integer'],
            'to_class'   => ['required'],
            'to_session' => ['required'],
            'to_section' => ['nullable'],
        ]);

        $count = Student::whereIn('id', $request->student_ids)->update([
            'class' => $request->to_class,
            'section' => $request->to_section,
            'session' => $request->to_session,
        ]);

        return redirect()->route('promotions.index')->with('success', "$count students promoted successfully!");
    }
}
