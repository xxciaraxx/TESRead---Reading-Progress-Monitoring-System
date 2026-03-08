<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['section', 'teacher', 'latestAssessment'])
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

        if ($request->boolean('archived')) {
            $query = Student::with(['section', 'teacher'])
                ->archived()
                ->latest();
        }

        $students      = $query->latest()->paginate(20)->withQueryString();
        $sections      = SchoolClass::where('is_active', true)->get();
        $showArchived  = $request->boolean('archived');

        return view('admin.students.index', compact('students', 'sections', 'showArchived'));
    }

    public function create()
    {
        $sections      = SchoolClass::where('is_active', true)->get();
        $teachers      = User::where('role', 'teacher')->where('account_status', 'Approved')->get();

        return view('admin.students.create', compact('sections', 'teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'lrn'              => 'nullable|string|max:20|unique:students,lrn',
            'gender'           => 'nullable|in:Male,Female',
            'birthdate'        => 'nullable|date',
            'section_id'       => 'nullable|exists:sections,id',
            'teacher_id'       => 'nullable|exists:users,id',
            'profile_photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('student_photos', 'public');
        }

        $student = Student::create($data);
        ActivityLog::log('Added student', "Student: {$student->fullName()}");

        return redirect()->route('admin.students.index')
            ->with('success', "Student '{$student->fullName()}' added successfully.");
    }

    public function show(Student $student)
    {
        $student->load(['section', 'teacher', 'interventions']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $sections      = SchoolClass::where('is_active', true)->get();
        $teachers      = User::where('role', 'teacher')->where('account_status', 'Approved')->get();

        return view('admin.students.edit', compact('student', 'sections', 'teachers'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'lrn'              => "nullable|string|max:20|unique:students,lrn,{$student->id}",
            'gender'           => 'nullable|in:Male,Female',
            'birthdate'        => 'nullable|date',
            'section_id'       => 'nullable|exists:sections,id',
            'teacher_id'       => 'nullable|exists:users,id',
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

        return redirect()->route('admin.students.show', $student)
            ->with('success', "Student profile for '{$student->fullName()}' has been updated successfully.");
    }

    public function destroy(Student $student)
    {
        if ($student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);
        }
        $name = $student->fullName();
        $student->delete();
        ActivityLog::log('Deleted student', "Student: {$name}");

        return redirect()->route('admin.students.index')
            ->with('success', "Student '{$name}' deleted.");
    }

    public function archive(Student $student)
    {
        $student->update(['is_archived' => true]);
        ActivityLog::log('Archived student', "Student: {$student->fullName()}");
        return back()->with('success', "{$student->fullName()} archived.");
    }

    public function restore(Student $student)
    {
        $student->update(['is_archived' => false]);
        ActivityLog::log('Restored student', "Student: {$student->fullName()}");
        return back()->with('success', "{$student->fullName()} restored.");
    }
}
