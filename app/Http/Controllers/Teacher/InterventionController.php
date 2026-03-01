<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    /**
     * List all interventions for the logged-in teacher's students.
     */
    public function index(Request $request)
    {
        $query = Intervention::with(['student', 'assessment'])
            ->where('teacher_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name',  'like', '%' . $request->search . '%');
            });
        }

        $interventions = $query->latest()->paginate(20)->withQueryString();

        $activeCount    = Intervention::where('teacher_id', auth()->id())->where('status', 'Active')->count();
        $completedCount = Intervention::where('teacher_id', auth()->id())->where('status', 'Completed')->count();

        return view('teacher.interventions.index', compact(
            'interventions', 'activeCount', 'completedCount'
        ));
    }

    /**
     * Show a single intervention detail + note history.
     */
    public function show(Intervention $intervention)
    {
        abort_if($intervention->teacher_id !== auth()->id(), 403);
        $intervention->load(['student', 'assessment.readingLevel']);
        return view('teacher.interventions.show', compact('intervention'));
    }

    /**
     * Update intervention notes and/or status.
     */
    public function update(Request $request, Intervention $intervention)
    {
        abort_if($intervention->teacher_id !== auth()->id(), 403);

        $data = $request->validate([
            'intervention_notes' => 'nullable|string|max:2000',
            'status'             => 'required|in:Active,Completed,Cancelled',
            'ended_on'           => 'nullable|date',
        ]);

        // Auto-set ended_on when marking complete
        if ($data['status'] === 'Completed' && empty($data['ended_on'])) {
            $data['ended_on'] = now()->toDateString();
        }

        $intervention->update($data);

        ActivityLog::log(
            "Updated intervention status to {$data['status']}",
            "Student: {$intervention->student->fullName()}"
        );

        return back()->with('success', 'Intervention updated successfully.');
    }

    /**
     * Quick-complete from list view.
     */
    public function complete(Intervention $intervention)
    {
        abort_if($intervention->teacher_id !== auth()->id(), 403);

        $intervention->update([
            'status'   => 'Completed',
            'ended_on' => now()->toDateString(),
        ]);

        ActivityLog::log(
            'Marked intervention as completed',
            "Student: {$intervention->student->fullName()}"
        );

        return back()->with('success', "{$intervention->student->fullName()}'s intervention marked as completed.");
    }
}
