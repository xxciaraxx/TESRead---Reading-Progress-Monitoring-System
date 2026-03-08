<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with(['teacher', 'students'])
            ->latest()
            ->paginate(20);

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')
            ->where('account_status', 'Approved')
            ->orderBy('name')
            ->get();

        return view('admin.classes.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'grade_level' => 'required|integer|between:1,6',
            'school_year' => 'required|string|max:20',
            'teacher_id'  => 'nullable|exists:users,id',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $class = SchoolClass::create($data);
        ActivityLog::log('Created class', "Class: {$class->name} (Grade {$class->grade_level})");

        return redirect()->route('admin.classes.index')
            ->with('success', "Class '{$class->name}' created successfully.");
    }

    public function show(SchoolClass $class)
    {
        $class->load(['teacher', 'students.latestAssessment']);
        return view('admin.classes.show', compact('class'));
    }

    public function edit(SchoolClass $class)
    {
        $teachers = User::where('role', 'teacher')
            ->where('account_status', 'Approved')
            ->orderBy('name')
            ->get();

        return view('admin.classes.edit', compact('class', 'teachers'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'grade_level' => 'required|integer|between:1,6',
            'school_year' => 'required|string|max:20',
            'teacher_id'  => 'nullable|exists:users,id',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $class->update($data);
        ActivityLog::log('Updated class', "Class: {$class->name} (Grade {$class->grade_level})");

        return redirect()->route('admin.classes.index')
            ->with('success', "Class '{$class->name}' updated successfully.");
    }

    public function destroy(SchoolClass $class)
    {
        $name = $class->name;
        $class->delete();
        ActivityLog::log('Deleted class', "Class: {$name}");

        return redirect()->route('admin.classes.index')
            ->with('success', "Class '{$name}' deleted.");
    }
}
