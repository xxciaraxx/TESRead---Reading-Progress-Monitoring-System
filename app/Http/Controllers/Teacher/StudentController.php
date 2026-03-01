<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Section;
use App\Models\ReadingLevel;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['section', 'readingLevel', 'latestAssessment'])
            ->where('teacher_id', auth()->id())
            ->active();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name',  'like', '%' . $request->search . '%')
                  ->orWhere('lrn',        'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $students = $query->orderBy('last_name')->paginate(20)->withQueryString();
        $sections = Section::where('is_active', true)->get();

        return view('teacher.students.index', compact('students', 'sections'));
    }

    public function create()
    {
        $sections      = Section::where('is_active', true)->get();
        $readingLevels = ReadingLevel::where('is_active', true)->orderBy('grade_level')->get();

        return view('teacher.students.create', compact('sections', 'readingLevels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'lrn'              => 'nullable|string|max:20|unique:students,lrn',
            'gender'           => 'nullable|in:Male,Female',
            'birthdate'        => 'nullable|date|before:today',
            'section_id'       => 'nullable|exists:sections,id',
            'reading_level_id' => 'nullable|exists:reading_levels,id',
            'profile_photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('student_photos', 'public');
        }

        $data['teacher_id'] = auth()->id();
        $student = Student::create($data);
        ActivityLog::log('Added student', "Student: {$student->fullName()}");

        return redirect()->route('teacher.students.show', $student)
            ->with('success', "Student '{$student->fullName()}' added successfully.");
    }

    public function show(Student $student)
    {
        abort_if($student->teacher_id !== auth()->id(), 403);

        $student->load([
            'section', 'readingLevel',
            'assessments' => fn($q) => $q->latest('assessed_on')->take(10),
            'assessments.readingLevel',
            'interventions' => fn($q) => $q->latest(),
        ]);

        return view('teacher.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        abort_if($student->teacher_id !== auth()->id(), 403);

        $sections      = Section::where('is_active', true)->get();
        $readingLevels = ReadingLevel::where('is_active', true)->orderBy('grade_level')->get();

        return view('teacher.students.edit', compact('student', 'sections', 'readingLevels'));
    }

    public function update(Request $request, Student $student)
    {
        abort_if($student->teacher_id !== auth()->id(), 403);

        $data = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'lrn'              => "nullable|string|max:20|unique:students,lrn,{$student->id}",
            'gender'           => 'nullable|in:Male,Female',
            'birthdate'        => 'nullable|date|before:today',
            'section_id'       => 'nullable|exists:sections,id',
            'reading_level_id' => 'nullable|exists:reading_levels,id',
            'profile_photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('student_photos', 'public');
        }

        $student->update($data);
        ActivityLog::log('Updated student', "Student: {$student->fullName()}");

        return redirect()->route('teacher.students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        abort_if($student->teacher_id !== auth()->id(), 403);

        if ($student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);
        }

        $name = $student->fullName();
        $student->delete();
        ActivityLog::log('Deleted student', "Student: {$name}");

        return redirect()->route('teacher.students.index')
            ->with('success', "Student '{$name}' deleted.");
    }
}
