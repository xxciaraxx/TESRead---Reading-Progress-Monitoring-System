<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with('teacher')
            ->latest()
            ->paginate(20);

        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')
            ->where('account_status', 'Approved')
            ->get();

        return view('admin.sections.create', compact('teachers'));
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

        $section = Section::create($data);
        ActivityLog::log('Created section', "Section: {$section->name}");

        return redirect()->route('admin.sections.index')
            ->with('success', "Section '{$section->name}' created.");
    }

    public function edit(Section $section)
    {
        $teachers = User::where('role', 'teacher')
            ->where('account_status', 'Approved')
            ->get();

        return view('admin.sections.edit', compact('section', 'teachers'));
    }

    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'grade_level' => 'required|integer|between:1,6',
            'school_year' => 'required|string|max:20',
            'teacher_id'  => 'nullable|exists:users,id',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $section->update($data);
        ActivityLog::log('Updated section', "Section: {$section->name}");

        return redirect()->route('admin.sections.index')
            ->with('success', "Section '{$section->name}' updated.");
    }

    public function destroy(Section $section)
    {
        $name = $section->name;
        $section->delete();
        ActivityLog::log('Deleted section', "Section: {$name}");

        return redirect()->route('admin.sections.index')
            ->with('success', "Section '{$name}' deleted.");
    }

    public function show(Section $section)
    {
        $section->load('teacher', 'students');
        return view('admin.sections.show', compact('section'));
    }
}
