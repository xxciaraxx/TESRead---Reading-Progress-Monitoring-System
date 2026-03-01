<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Student;
use App\Models\Intervention;
use App\Models\User;
use App\Services\ReadingRiskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');   // monthly | quarterly
        $year   = (int) $request->get('year', now()->year);

        /* ── 1. OVERVIEW STATS ── */
        $totalStudents       = Student::active()->count();
        $totalTeachers       = User::where('role', 'teacher')->where('account_status', 'Approved')->count();
        $totalAssessments    = Assessment::whereYear('assessed_on', $year)->count();
        $activeInterventions = Intervention::where('status', 'Active')->count();

        /* ── 2. RISK DISTRIBUTION (latest assessment per student) ── */
        $riskDistribution = $this->riskDistribution();

        /* ── 3. MONTHLY / QUARTERLY TREND: avg fluency & comprehension ── */
        $trendData = $period === 'quarterly'
            ? $this->quarterlyTrend($year)
            : $this->monthlyTrend($year);

        /* ── 4. RISK COUNT OVER TIME ── */
        $riskOverTime = $period === 'quarterly'
            ? $this->riskByQuarter($year)
            : $this->riskByMonth($year);

        /* ── 5. SESSIONS PER WEEK distribution ── */
        $sessionsDist = Assessment::whereYear('assessed_on', $year)
            ->select('reading_sessions_per_week', DB::raw('count(*) as total'))
            ->groupBy('reading_sessions_per_week')
            ->orderBy('reading_sessions_per_week')
            ->pluck('total', 'reading_sessions_per_week')
            ->toArray();

        /* ── 6. TOP BELOW-STANDARD STUDENTS ── */
        $belowStudents = Student::active()
            ->whereHas('latestAssessment', fn($q) => $q->where('risk_level', ReadingRiskService::BELOW))
            ->with(['latestAssessment', 'section', 'teacher'])
            ->take(10)
            ->get();

        /* ── 7. TEACHER COMPARISON ── */
        $teacherStats = User::where('role', 'teacher')
            ->where('account_status', 'Approved')
            ->withCount('students')
            ->with(['students.latestAssessment'])
            ->get()
            ->map(function ($teacher) {
                $assessments = Assessment::where('teacher_id', $teacher->id)
                    ->whereYear('assessed_on', now()->year)
                    ->get();
                return [
                    'name'        => $teacher->name,
                    'students'    => $teacher->students_count,
                    'assessments' => $assessments->count(),
                    'avg_fluency' => round($assessments->avg('fluency_score') ?? 0, 1),
                    'avg_comp'    => round($assessments->avg('comprehension_score') ?? 0, 1),
                    'below'       => $assessments->where('risk_level', ReadingRiskService::BELOW)->count(),
                ];
            });

        /* ── 8. AVAILABLE YEARS ── */
        $availableYears = Assessment::selectRaw('YEAR(assessed_on) as yr')
            ->distinct()->orderByDesc('yr')->pluck('yr')->toArray();
        if (empty($availableYears)) $availableYears = [now()->year];

        return view('admin.analytics.index', compact(
            'period', 'year', 'availableYears',
            'totalStudents', 'totalTeachers', 'totalAssessments', 'activeInterventions',
            'riskDistribution', 'trendData', 'riskOverTime',
            'sessionsDist', 'belowStudents', 'teacherStats'
        ));
    }

    /* ──────────────────────────────────────────────
     |  PRIVATE HELPERS
     ─────────────────────────────────────────────── */

    private function riskDistribution(): array
    {
        // Latest assessment per student
        $sub = Assessment::select('student_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('student_id');

        $counts = Assessment::joinSub($sub, 'latest', fn($j) =>
                $j->on('assessments.id', '=', 'latest.max_id'))
            ->select('risk_level', DB::raw('count(*) as total'))
            ->whereNotNull('risk_level')
            ->groupBy('risk_level')
            ->pluck('total', 'risk_level')
            ->toArray();

        return [
            'below'      => $counts[ReadingRiskService::BELOW]      ?? 0,
            'approaching'=> $counts[ReadingRiskService::APPROACHING] ?? 0,
            'meeting'    => $counts[ReadingRiskService::MEETING]     ?? 0,
        ];
    }

    private function monthlyTrend(int $year): array
    {
        $rows = Assessment::whereYear('assessed_on', $year)
            ->select(
                DB::raw('MONTH(assessed_on) as period'),
                DB::raw('ROUND(AVG(fluency_score),1) as avg_fluency'),
                DB::raw('ROUND(AVG(comprehension_score),1) as avg_comp'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $labels = $fluency = $comp = $totals = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[]  = $months[$m - 1];
            $fluency[]  = $rows->has($m) ? (float) $rows[$m]->avg_fluency : null;
            $comp[]     = $rows->has($m) ? (float) $rows[$m]->avg_comp    : null;
            $totals[]   = $rows->has($m) ? (int)   $rows[$m]->total       : 0;
        }

        return compact('labels', 'fluency', 'comp', 'totals');
    }

    private function quarterlyTrend(int $year): array
    {
        $rows = Assessment::whereYear('assessed_on', $year)
            ->select(
                DB::raw('QUARTER(assessed_on) as period'),
                DB::raw('ROUND(AVG(fluency_score),1) as avg_fluency'),
                DB::raw('ROUND(AVG(comprehension_score),1) as avg_comp'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        $labels = ['Q1 (Jan–Mar)', 'Q2 (Apr–Jun)', 'Q3 (Jul–Sep)', 'Q4 (Oct–Dec)'];
        $fluency = $comp = $totals = [];

        for ($q = 1; $q <= 4; $q++) {
            $fluency[]  = $rows->has($q) ? (float) $rows[$q]->avg_fluency : null;
            $comp[]     = $rows->has($q) ? (float) $rows[$q]->avg_comp    : null;
            $totals[]   = $rows->has($q) ? (int)   $rows[$q]->total       : 0;
        }

        return compact('labels', 'fluency', 'comp', 'totals');
    }

    private function riskByMonth(int $year): array
    {
        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $below = $approaching = $meeting = [];

        for ($m = 1; $m <= 12; $m++) {
            $rows = Assessment::whereYear('assessed_on', $year)
                ->whereMonth('assessed_on', $m)
                ->select('risk_level', DB::raw('count(*) as total'))
                ->groupBy('risk_level')
                ->pluck('total', 'risk_level');

            $below[]      = (int) ($rows[ReadingRiskService::BELOW]      ?? 0);
            $approaching[] = (int) ($rows[ReadingRiskService::APPROACHING] ?? 0);
            $meeting[]    = (int) ($rows[ReadingRiskService::MEETING]     ?? 0);
        }

        return ['labels' => $months, 'below' => $below, 'approaching' => $approaching, 'meeting' => $meeting];
    }

    private function riskByQuarter(int $year): array
    {
        $labels = ['Q1', 'Q2', 'Q3', 'Q4'];
        $below = $approaching = $meeting = [];

        for ($q = 1; $q <= 4; $q++) {
            $rows = Assessment::whereYear('assessed_on', $year)
                ->whereRaw('QUARTER(assessed_on) = ?', [$q])
                ->select('risk_level', DB::raw('count(*) as total'))
                ->groupBy('risk_level')
                ->pluck('total', 'risk_level');

            $below[]      = (int) ($rows[ReadingRiskService::BELOW]      ?? 0);
            $approaching[] = (int) ($rows[ReadingRiskService::APPROACHING] ?? 0);
            $meeting[]    = (int) ($rows[ReadingRiskService::MEETING]     ?? 0);
        }

        return ['labels' => $labels, 'below' => $below, 'approaching' => $approaching, 'meeting' => $meeting];
    }
}
