<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\SchoolClass;
use App\Models\ActivityLog;
use App\Services\ReadingRiskService;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function __construct(private ReadingRiskService $riskService) {}

    public function index()
    {
        $tid = auth()->id();

        // Keep both datasets available to support either table variant in the Blade.
        $students = Student::with(['latestAssessment', 'assessments', 'section'])
            ->where('teacher_id', $tid)
            ->active()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $assessments = Assessment::with('student')
            ->where('teacher_id', $tid)
            ->latest('assessed_on')
            ->get();

        $total       = Assessment::where('teacher_id', $tid)->count();
        $avgFluency  = round(Assessment::where('teacher_id', $tid)->avg('fluency_score') ?? 0, 1);
        $avgComp     = round(Assessment::where('teacher_id', $tid)->avg('comprehension_score') ?? 0, 1);
        $below       = Assessment::where('teacher_id', $tid)->where('risk_level','like','%Below%')->count();
        $meeting     = Assessment::where('teacher_id', $tid)->where('risk_level','like','%Meeting%')->count();
        $approaching = Assessment::where('teacher_id', $tid)->where('risk_level','like','%Approaching%')->count();
        $latest      = Assessment::where('teacher_id', $tid)->latest('assessed_on')->first();

        return view('teacher.assessments.index', compact(
            'students','assessments','total','avgFluency','avgComp',
            'below','meeting','approaching','latest'
        ));
    }

    public function studentHistory(Student $student)
    {
        abort_if($student->teacher_id !== auth()->id(), 403);
        $student->load(['section']);
        $assessments = Assessment::where('student_id', $student->id)
            ->where('teacher_id', auth()->id())
            ->latest('assessed_on')
            ->get();
        return view('teacher.assessments.student-history', compact('student', 'assessments'));
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
            'fluency_score'              => 'required|numeric|min:0|max:100',
            'comprehension_score'        => 'required|numeric|min:0|max:100',
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
        $assessment->load(['student', 'intervention']);
        return view('teacher.assessments.show', compact('assessment'));
    }
}
