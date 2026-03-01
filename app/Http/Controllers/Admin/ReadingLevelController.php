<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReadingLevel;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ReadingLevelController extends Controller
{
    public function index()
    {
        $levels = ReadingLevel::withCount('students')
            ->orderBy('grade_level')
            ->paginate(20);

        return view('admin.reading-levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.reading-levels.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'grade_level' => 'required|integer|between:1,6',
            'color_code'  => 'nullable|string|max:20',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $level = ReadingLevel::create($data);
        ActivityLog::log('Created reading level', "Level: {$level->name}");

        return redirect()->route('admin.reading-levels.index')
            ->with('success', "Reading level '{$level->name}' created.");
    }

    public function edit(ReadingLevel $readingLevel)
    {
        return view('admin.reading-levels.edit', compact('readingLevel'));
    }

    public function update(Request $request, ReadingLevel $readingLevel)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'grade_level' => 'required|integer|between:1,6',
            'color_code'  => 'nullable|string|max:20',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $readingLevel->update($data);
        ActivityLog::log('Updated reading level', "Level: {$readingLevel->name}");

        return redirect()->route('admin.reading-levels.index')
            ->with('success', "Reading level '{$readingLevel->name}' updated.");
    }

    public function destroy(ReadingLevel $readingLevel)
    {
        $name = $readingLevel->name;
        $readingLevel->delete();
        ActivityLog::log('Deleted reading level', "Level: {$name}");

        return redirect()->route('admin.reading-levels.index')
            ->with('success', "Reading level '{$name}' deleted.");
    }

    public function show(ReadingLevel $readingLevel)
    {
        $readingLevel->load('students');
        return view('admin.reading-levels.show', compact('readingLevel'));
    }
}
