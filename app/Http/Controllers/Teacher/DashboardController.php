<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assessment;
use App\Services\ReadingRiskService;

class DashboardController extends Controller
{
    public function index()
    {
        $teacherId = auth()->id();

        $totalStudents = Student::active()->where('teacher_id', $teacherId)->count();

        $meeting     = Assessment::where('teacher_id', $teacherId)
            ->where('risk_level', ReadingRiskService::MEETING)->count();
        $approaching = Assessment::where('teacher_id', $teacherId)
            ->where('risk_level', ReadingRiskService::APPROACHING)->count();
        $below       = Assessment::where('teacher_id', $teacherId)
            ->where('risk_level', ReadingRiskService::BELOW)->count();

        $recentAssessments = Assessment::with(['student'])
            ->where('teacher_id', $teacherId)
            ->latest('assessed_on')
            ->take(8)
            ->get();

        $studentsNeedingAttention = Student::active()
            ->where('teacher_id', $teacherId)
            ->whereHas('latestAssessment', fn($q) =>
                $q->where('risk_level', ReadingRiskService::BELOW)
            )
            ->with('latestAssessment')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact(
            'totalStudents', 'meeting', 'approaching', 'below',
            'recentAssessments', 'studentsNeedingAttention'
        ));
    }
}
