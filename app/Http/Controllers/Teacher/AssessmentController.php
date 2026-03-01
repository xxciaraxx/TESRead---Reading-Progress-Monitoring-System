<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\ActivityLog;
use App\Services\ReadingRiskService;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function __construct(private ReadingRiskService $riskService) {}

    public function index()
    {
        $assessments = Assessment::with(['student', 'readingLevel'])
            ->where('teacher_id', auth()->id())
            ->latest('assessed_on')
            ->paginate(20);

        return view('teacher.assessments.index', compact('assessments'));
    }

    public function create(Request $request)
    {
        $students = Student::active()
            ->where('teacher_id', auth()->id())
            ->orderBy('last_name')
            ->get();

        $selectedStudent = $request->filled('student_id')
            ? Student::find($request->student_id)
            : null;

        return view('teacher.assessments.create', compact('students', 'selectedStudent'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'                 => 'required|exists:students,id',
            'reading_level_id'           => 'nullable|exists:reading_levels,id',
            'fluency_score'              => 'required|numeric|min:0|max:100',
            'comprehension_score'        => 'required|numeric|min:0|max:100',
            'reading_sessions_per_week'  => 'required|integer|min:0|max:7',
            'notes'                      => 'nullable|string|max:1000',
            'assessed_on'                => 'required|date|before_or_equal:today',
        ]);

        $data['teacher_id'] = auth()->id();

        $assessment = Assessment::create($data);

        // Apply risk evaluation + auto-intervention
        $this->riskService->evaluateAndIntervene($assessment);

        ActivityLog::log(
            'Recorded assessment',
            "Student: {$assessment->student->fullName()} — Risk: {$assessment->risk_level}"
        );

        return redirect()->route('teacher.assessments.show', $assessment)
            ->with('success', 'Assessment saved. Risk level: ' . $assessment->fresh()->risk_level);
    }

    public function show(Assessment $assessment)
    {
        abort_if($assessment->teacher_id !== auth()->id(), 403);
        $assessment->load(['student', 'readingLevel', 'intervention']);
        return view('teacher.assessments.show', compact('assessment'));
    }
}
