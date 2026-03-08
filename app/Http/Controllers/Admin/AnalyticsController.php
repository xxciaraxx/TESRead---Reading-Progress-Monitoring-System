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
    /*
     * DepEd Quarter month mapping (school year Jun → Mar)
     * Q1: Jun, Jul, Aug       (6,7,8)
     * Q2: Sep, Oct            (9,10)     — "Aug to Oct" boundary
     * Q3: Nov, Dec, Jan       (11,12,1)  — spans two calendar years
     * Q4: Feb, Mar            (2,3)      — "Jan to Mar" boundary
     */
    private const QUARTERS = [
        1 => ['label' => 'Q1  (Jun – Aug)', 'short' => 'Q1', 'months' => [6, 7, 8]],
        2 => ['label' => 'Q2  (Sep – Oct)', 'short' => 'Q2', 'months' => [9, 10]],
        3 => ['label' => 'Q3  (Nov – Jan)', 'short' => 'Q3', 'months' => [11, 12, 1]],
        4 => ['label' => 'Q4  (Feb – Mar)', 'short' => 'Q4', 'months' => [2, 3]],
    ];

    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $year   = (int) $request->get('year', now()->year);

        $totalStudents       = Student::active()->count();
        $totalTeachers       = User::where('role', 'teacher')->where('account_status', 'Approved')->count();
        $totalAssessments    = Assessment::whereYear('assessed_on', $year)->count();
        $activeInterventions = Intervention::where('status', 'Active')->count();

        $riskDistribution = $this->riskDistribution();

        $trendData = $period === 'quarterly'
            ? $this->quarterlyTrend($year)
            : $this->monthlyTrend($year);

        $riskOverTime = $period === 'quarterly'
            ? $this->riskByQuarter($year)
            : $this->riskByMonth($year);

        $sessionsDist = Assessment::whereYear('assessed_on', $year)
            ->select('reading_sessions_per_week', DB::raw('count(*) as total'))
            ->groupBy('reading_sessions_per_week')
            ->orderBy('reading_sessions_per_week')
            ->pluck('total', 'reading_sessions_per_week')
            ->toArray();

        $belowStudents = Student::active()
            ->whereHas('latestAssessment', fn($q) => $q->where('risk_level', ReadingRiskService::BELOW))
            ->with(['latestAssessment', 'section', 'teacher'])
            ->take(10)->get();

        $teacherStats = User::where('role', 'teacher')
            ->where('account_status', 'Approved')
            ->withCount('students')
            ->with(['students.latestAssessment'])
            ->get()
            ->map(function ($teacher) {
                $assessments = Assessment::where('teacher_id', $teacher->id)
                    ->whereYear('assessed_on', now()->year)->get();
                return [
                    'name'        => $teacher->name,
                    'students'    => $teacher->students_count,
                    'assessments' => $assessments->count(),
                    'avg_fluency' => round($assessments->avg('fluency_score') ?? 0, 1),
                    'avg_comp'    => round($assessments->avg('comprehension_score') ?? 0, 1),
                    'below'       => $assessments->where('risk_level', ReadingRiskService::BELOW)->count(),
                ];
            });

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

    /* ── Helpers ──────────────────────────────────── */

    private function riskDistribution(): array
    {
        $sub = Assessment::select('student_id', DB::raw('MAX(id) as max_id'))->groupBy('student_id');
        $counts = Assessment::joinSub($sub, 'latest', fn($j) =>
                $j->on('assessments.id', '=', 'latest.max_id'))
            ->select('risk_level', DB::raw('count(*) as total'))
            ->whereNotNull('risk_level')->groupBy('risk_level')
            ->pluck('total', 'risk_level')->toArray();

        return [
            'below'       => $counts[ReadingRiskService::BELOW]       ?? 0,
            'approaching' => $counts[ReadingRiskService::APPROACHING]  ?? 0,
            'meeting'     => $counts[ReadingRiskService::MEETING]      ?? 0,
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
            ->groupBy('period')->orderBy('period')
            ->get()->keyBy('period');

        // School year order: Jun→Mar
        $monthOrder = [6,7,8,9,10,11,12,1,2,3];
        $monthNames = [1=>'Jan',2=>'Feb',3=>'Mar',6=>'Jun',7=>'Jul',8=>'Aug',
                       9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
        $labels = $fluency = $comp = $totals = [];

        foreach ($monthOrder as $m) {
            $labels[]  = $monthNames[$m];
            $fluency[] = $rows->has($m) ? (float) $rows[$m]->avg_fluency : null;
            $comp[]    = $rows->has($m) ? (float) $rows[$m]->avg_comp    : null;
            $totals[]  = $rows->has($m) ? (int)   $rows[$m]->total       : 0;
        }

        return compact('labels', 'fluency', 'comp', 'totals');
    }

    private function quarterlyTrend(int $year): array
    {
        $labels = $fluency = $comp = $totals = [];

        foreach (self::QUARTERS as $q => $def) {
            $labels[] = $def['label'];
            $agg = $this->aggregateQuarter(null, $year, $q);
            $fluency[] = $agg['avg_fluency'];
            $comp[]    = $agg['avg_comp'];
            $totals[]  = $agg['total'];
        }

        return compact('labels', 'fluency', 'comp', 'totals');
    }

    private function riskByMonth(int $year): array
    {
        $monthOrder = [6,7,8,9,10,11,12,1,2,3];
        $monthNames = [1=>'Jan',2=>'Feb',3=>'Mar',6=>'Jun',7=>'Jul',8=>'Aug',
                       9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
        $labels = $below = $approaching = $meeting = [];

        foreach ($monthOrder as $m) {
            $rows = Assessment::whereYear('assessed_on', $year)
                ->whereMonth('assessed_on', $m)
                ->select('risk_level', DB::raw('count(*) as total'))
                ->groupBy('risk_level')->pluck('total', 'risk_level');

            $labels[]      = $monthNames[$m];
            $below[]       = (int) ($rows[ReadingRiskService::BELOW]       ?? 0);
            $approaching[] = (int) ($rows[ReadingRiskService::APPROACHING]  ?? 0);
            $meeting[]     = (int) ($rows[ReadingRiskService::MEETING]      ?? 0);
        }

        return compact('labels', 'below', 'approaching', 'meeting');
    }

    private function riskByQuarter(int $year): array
    {
        $labels = $below = $approaching = $meeting = [];

        foreach (self::QUARTERS as $q => $def) {
            $labels[]      = $def['short'];
            $risk          = $this->riskCountQuarter(null, $year, $q);
            $below[]       = $risk['below'];
            $approaching[] = $risk['approaching'];
            $meeting[]     = $risk['meeting'];
        }

        return compact('labels', 'below', 'approaching', 'meeting');
    }

    /**
     * Aggregate avg scores & count for a quarter.
     * Q3 spans two calendar years: Nov–Dec in $year, Jan in $year+1.
     */
    private function aggregateQuarter(?int $teacherId, int $year, int $quarter): array
    {
        $months = self::QUARTERS[$quarter]['months'];

        // Split months into which calendar year they belong to
        $sameYear  = array_filter($months, fn($m) => $m >= 6);  // Jun–Dec
        $nextYear  = array_filter($months, fn($m) => $m < 6);   // Jan–May

        $q = Assessment::query();
        if ($teacherId) $q->where('teacher_id', $teacherId);

        $q->where(function ($sub) use ($year, $sameYear, $nextYear) {
            if ($sameYear) {
                $sub->orWhere(fn($s) =>
                    $s->whereYear('assessed_on', $year)
                      ->whereIn(DB::raw('MONTH(assessed_on)'), array_values($sameYear))
                );
            }
            if ($nextYear) {
                $sub->orWhere(fn($s) =>
                    $s->whereYear('assessed_on', $year + 1)
                      ->whereIn(DB::raw('MONTH(assessed_on)'), array_values($nextYear))
                );
            }
        });

        $result = $q->selectRaw('ROUND(AVG(fluency_score),1) as avg_fluency,
                                  ROUND(AVG(comprehension_score),1) as avg_comp,
                                  COUNT(*) as total')
                    ->first();

        return [
            'avg_fluency' => $result?->total ? (float) $result->avg_fluency : null,
            'avg_comp'    => $result?->total ? (float) $result->avg_comp    : null,
            'total'       => (int) ($result?->total ?? 0),
        ];
    }

    private function riskCountQuarter(?int $teacherId, int $year, int $quarter): array
    {
        $months   = self::QUARTERS[$quarter]['months'];
        $sameYear = array_values(array_filter($months, fn($m) => $m >= 6));
        $nextYear = array_values(array_filter($months, fn($m) => $m < 6));

        $q = Assessment::query();
        if ($teacherId) $q->where('teacher_id', $teacherId);

        $q->where(function ($sub) use ($year, $sameYear, $nextYear) {
            if ($sameYear) {
                $sub->orWhere(fn($s) =>
                    $s->whereYear('assessed_on', $year)
                      ->whereIn(DB::raw('MONTH(assessed_on)'), $sameYear)
                );
            }
            if ($nextYear) {
                $sub->orWhere(fn($s) =>
                    $s->whereYear('assessed_on', $year + 1)
                      ->whereIn(DB::raw('MONTH(assessed_on)'), $nextYear)
                );
            }
        });

        $rows = $q->select('risk_level', DB::raw('count(*) as total'))
                  ->groupBy('risk_level')->pluck('total', 'risk_level');

        return [
            'below'       => (int) ($rows[ReadingRiskService::BELOW]       ?? 0),
            'approaching' => (int) ($rows[ReadingRiskService::APPROACHING]  ?? 0),
            'meeting'     => (int) ($rows[ReadingRiskService::MEETING]      ?? 0),
        ];
    }
}
