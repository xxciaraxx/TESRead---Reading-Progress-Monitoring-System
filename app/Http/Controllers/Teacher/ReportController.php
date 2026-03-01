<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\Intervention;
use App\Services\ReadingRiskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = auth()->id();
        $period    = $request->get('period', 'monthly');   // monthly | quarterly
        $year      = (int) $request->get('year', now()->year);

        /* ── 1. OVERVIEW STATS ── */
        $totalStudents       = Student::active()->where('teacher_id', $teacherId)->count();
        $activeInterventions = Intervention::where('teacher_id', $teacherId)->where('status', 'Active')->count();

        /* ── 2. RISK DISTRIBUTION (latest per student) ── */
        $riskDistribution = $this->riskDistribution($teacherId);

        /* ── 3. SCORE TREND ── */
        $trendData = $period === 'quarterly'
            ? $this->quarterlyTrend($teacherId, $year)
            : $this->monthlyTrend($teacherId, $year);

        /* ── 4. RISK COUNT OVER TIME ── */
        $riskOverTime = $period === 'quarterly'
            ? $this->riskByQuarter($teacherId, $year)
            : $this->riskByMonth($teacherId, $year);

        /* ── 5. SESSIONS DISTRIBUTION ── */
        $sessionsDist = Assessment::where('teacher_id', $teacherId)
            ->whereYear('assessed_on', $year)
            ->select('reading_sessions_per_week', DB::raw('count(*) as total'))
            ->groupBy('reading_sessions_per_week')
            ->orderBy('reading_sessions_per_week')
            ->pluck('total', 'reading_sessions_per_week')
            ->toArray();

        /* ── 6. PER-STUDENT PROGRESS (for individual sparklines) ── */
        $students = Student::active()
            ->where('teacher_id', $teacherId)
            ->with([
                'section', 'readingLevel',
                'assessments' => fn($q) => $q->whereYear('assessed_on', $year)
                    ->orderBy('assessed_on')->select('id','student_id','fluency_score','comprehension_score','risk_level','assessed_on'),
                'latestAssessment',
                'interventions',
            ])
            ->orderBy('last_name')
            ->get();

        /* ── 7. AVAILABLE YEARS ── */
        $availableYears = Assessment::where('teacher_id', $teacherId)
            ->selectRaw('YEAR(assessed_on) as yr')
            ->distinct()->orderByDesc('yr')->pluck('yr')->toArray();
        if (empty($availableYears)) $availableYears = [now()->year];

        return view('teacher.reports.index', compact(
            'period', 'year', 'availableYears',
            'totalStudents', 'activeInterventions',
            'riskDistribution', 'trendData', 'riskOverTime',
            'sessionsDist', 'students'
        ));
    }

    /* ──────────────────────────────────────────────
     |  PRIVATE HELPERS
     ─────────────────────────────────────────────── */

    private function riskDistribution(int $teacherId): array
    {
        $sub = Assessment::where('teacher_id', $teacherId)
            ->select('student_id', DB::raw('MAX(id) as max_id'))
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

    private function monthlyTrend(int $teacherId, int $year): array
    {
        $rows = Assessment::where('teacher_id', $teacherId)
            ->whereYear('assessed_on', $year)
            ->select(
                DB::raw('MONTH(assessed_on) as period'),
                DB::raw('ROUND(AVG(fluency_score),1) as avg_fluency'),
                DB::raw('ROUND(AVG(comprehension_score),1) as avg_comp'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('period')->orderBy('period')
            ->get()->keyBy('period');

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

    private function quarterlyTrend(int $teacherId, int $year): array
    {
        $rows = Assessment::where('teacher_id', $teacherId)
            ->whereYear('assessed_on', $year)
            ->select(
                DB::raw('QUARTER(assessed_on) as period'),
                DB::raw('ROUND(AVG(fluency_score),1) as avg_fluency'),
                DB::raw('ROUND(AVG(comprehension_score),1) as avg_comp'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('period')->orderBy('period')
            ->get()->keyBy('period');

        $labels  = ['Q1 (Jan–Mar)', 'Q2 (Apr–Jun)', 'Q3 (Jul–Sep)', 'Q4 (Oct–Dec)'];
        $fluency = $comp = $totals = [];

        for ($q = 1; $q <= 4; $q++) {
            $fluency[]  = $rows->has($q) ? (float) $rows[$q]->avg_fluency : null;
            $comp[]     = $rows->has($q) ? (float) $rows[$q]->avg_comp    : null;
            $totals[]   = $rows->has($q) ? (int)   $rows[$q]->total       : 0;
        }

        return compact('labels', 'fluency', 'comp', 'totals');
    }

    private function riskByMonth(int $teacherId, int $year): array
    {
        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $below = $approaching = $meeting = [];

        for ($m = 1; $m <= 12; $m++) {
            $rows = Assessment::where('teacher_id', $teacherId)
                ->whereYear('assessed_on', $year)->whereMonth('assessed_on', $m)
                ->select('risk_level', DB::raw('count(*) as total'))
                ->groupBy('risk_level')->pluck('total', 'risk_level');

            $below[]       = (int) ($rows[ReadingRiskService::BELOW]       ?? 0);
            $approaching[]  = (int) ($rows[ReadingRiskService::APPROACHING]  ?? 0);
            $meeting[]     = (int) ($rows[ReadingRiskService::MEETING]      ?? 0);
        }

        return ['labels' => $months, 'below' => $below, 'approaching' => $approaching, 'meeting' => $meeting];
    }

    private function riskByQuarter(int $teacherId, int $year): array
    {
        $labels = ['Q1', 'Q2', 'Q3', 'Q4'];
        $below = $approaching = $meeting = [];

        for ($q = 1; $q <= 4; $q++) {
            $rows = Assessment::where('teacher_id', $teacherId)
                ->whereYear('assessed_on', $year)
                ->whereRaw('QUARTER(assessed_on) = ?', [$q])
                ->select('risk_level', DB::raw('count(*) as total'))
                ->groupBy('risk_level')->pluck('total', 'risk_level');

            $below[]       = (int) ($rows[ReadingRiskService::BELOW]       ?? 0);
            $approaching[]  = (int) ($rows[ReadingRiskService::APPROACHING]  ?? 0);
            $meeting[]     = (int) ($rows[ReadingRiskService::MEETING]      ?? 0);
        }

        return ['labels' => $labels, 'below' => $below, 'approaching' => $approaching, 'meeting' => $meeting];
    }
}
