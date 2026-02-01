<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AcademicSession;

class StudentController extends Controller
{
    /**
     * Students List + Search/Filter
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // ✅ Search by name / unique_id / phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('unique_id', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        // ✅ Filter by class
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        // ✅ Filter by section
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $students = $query->latest()->paginate(10)->withQueryString();

        // for filters dropdown
        $classes = Student::select('class')->distinct()->orderBy('class')->pluck('class');
        $sections = Student::select('section')->whereNotNull('section')->distinct()->orderBy('section')->pluck('section');

        return view('students.index', compact('students', 'classes', 'sections'));
    }


    public function create()
    {
        $classes = SchoolClass::orderBy('sort_order')->get();
        $sections = Section::orderBy('sort_order')->get();
        $sessions = AcademicSession::orderBy('name', 'desc')->get();
        $activeSession = active_session_name();

        return view('students.create', compact('classes', 'sections', 'sessions', 'activeSession'));
    }



    public function edit(Student $student)
    {
        $classes = SchoolClass::orderBy('sort_order')->get();
        $sections = Section::orderBy('sort_order')->get();
        $sessions = AcademicSession::orderBy('name', 'desc')->get();
        $activeSession = active_session_name();
        return view('students.edit', compact('student', 'classes', 'sections', 'sessions', 'activeSession'));
    }



    /**
     * Store Student
     */
    public function store(Request $request)
    {
        $request->validate([
            'session' => ['required', 'string', 'max:50'],
            'name'        => ['required', 'string', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'class'       => ['required', 'string', 'max:50'],
            'section'     => ['nullable', 'string', 'max:10'],
            
            'phone'       => ['nullable', 'string', 'max:20'],
            'address'     => ['nullable', 'string', 'max:500'],
        ]);

        // ✅ Unique ID auto generate
        $unique_id = $this->generateUniqueId();

        Student::create([
            'unique_id'   => $unique_id,
            'session' => $request->session,
            'name'        => $request->name,
            'father_name' => $request->father_name,
            'class'       => $request->class,
            'section'     => $request->section,
            
            'phone'       => $request->phone,
            'address'     => $request->address,
        ]);

        return redirect()->route('students.index')->with('success', 'Student added successfully!');
    }

    /**
     * Show single student
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }



    /**
     * Update Student
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'unique_id'   => ['required', 'string', 'max:50', Rule::unique('students', 'unique_id')->ignore($student->id)],
            'session'     => ['required', 'string', 'max:50'],
            'name'        => ['required', 'string', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'class'       => ['required', 'string', 'max:50'],
            'section'     => ['nullable', 'string', 'max:10'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'address'     => ['nullable', 'string', 'max:500'],
        ]);

        $student->update($request->only([
            'unique_id',
            'session',
            'name',
            'father_name',
            'class',
            'section',
            'phone',
            'address'
        ]));

        return redirect()->route('students.index')->with('success', 'Student updated successfully!');
    }

    /**
     * Soft Delete
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student moved to recycle bin!');
    }

    /**
     * Recycle Bin list
     */
    public function recycleBin(Request $request)
    {
        $query = Student::onlyTrashed();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('unique_id', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        $students = $query->latest('deleted_at')->paginate(10)->withQueryString();

        return view('students.recycle-bin', compact('students'));
    }

    /**
     * Restore Student
     */
    public function restore($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->restore();

        return redirect()->route('students.recycleBin')->with('success', 'Student restored successfully!');
    }

    /**
     * Permanent delete
     */
    public function forceDelete($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->forceDelete();

        return redirect()->route('students.recycleBin')->with('success', 'Student permanently deleted!');
    }

    /**
     * Unique ID generator
     * Example: STU-2026-00001
     */
    private function generateUniqueId(): string
    {
        $prefix = 'STU-' . date('Y') . '-';

        $last = Student::withTrashed()
            ->where('unique_id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($last) {
            $lastNumber = (int) Str::afterLast($last->unique_id, '-');
            $nextNumber = $lastNumber + 1;
        }

        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
