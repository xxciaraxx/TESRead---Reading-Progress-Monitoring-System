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
    /*
     * DepEd Quarter month mapping (school year Jun → Mar)
     * Q1: Jun, Jul, Aug       (6,7,8)
     * Q2: Sep, Oct            (9,10)
     * Q3: Nov, Dec, Jan       (11,12,1)  — spans two calendar years
     * Q4: Feb, Mar            (2,3)
     */
    private const QUARTERS = [
        1 => ['label' => 'Q1  (Jun – Aug)', 'short' => 'Q1', 'months' => [6, 7, 8]],
        2 => ['label' => 'Q2  (Sep – Oct)', 'short' => 'Q2', 'months' => [9, 10]],
        3 => ['label' => 'Q3  (Nov – Jan)', 'short' => 'Q3', 'months' => [11, 12, 1]],
        4 => ['label' => 'Q4  (Feb – Mar)', 'short' => 'Q4', 'months' => [2, 3]],
    ];

    public function index(Request $request)
    {
        $teacherId = auth()->id();
        $period    = $request->get('period', 'monthly');
        $year      = (int) $request->get('year', now()->year);

        $totalStudents       = Student::active()->where('teacher_id', $teacherId)->count();
        $activeInterventions = Intervention::where('teacher_id', $teacherId)->where('status', 'Active')->count();

        $riskDistribution = $this->riskDistribution($teacherId);

        $trendData = $period === 'quarterly'
            ? $this->quarterlyTrend($teacherId, $year)
            : $this->monthlyTrend($teacherId, $year);

        $riskOverTime = $period === 'quarterly'
            ? $this->riskByQuarter($teacherId, $year)
            : $this->riskByMonth($teacherId, $year);

        $students = Student::active()
            ->where('teacher_id', $teacherId)
            ->with([
                'section',
                'assessments' => fn($q) => $q->whereYear('assessed_on', $year)
                    ->orderBy('assessed_on')
                    ->select('id','student_id','fluency_score','comprehension_score','risk_level','assessed_on'),
                'latestAssessment',
                'interventions',
            ])
            ->orderBy('last_name')
            ->get();

        $availableYears = Assessment::where('teacher_id', $teacherId)
            ->selectRaw('YEAR(assessed_on) as yr')
            ->distinct()->orderByDesc('yr')->pluck('yr')->toArray();
        if (empty($availableYears)) $availableYears = [now()->year];

        return view('teacher.reports.index', compact(
            'period', 'year', 'availableYears',
            'totalStudents', 'activeInterventions',
            'riskDistribution', 'trendData', 'riskOverTime', 'students'
        ));
    }

    /* ── Helpers ──────────────────────────────────── */

    private function riskDistribution(int $teacherId): array
    {
        $sub = Assessment::where('teacher_id', $teacherId)
            ->select('student_id', DB::raw('MAX(id) as max_id'))->groupBy('student_id');

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

        // School year order: Jun → Mar
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

    private function quarterlyTrend(int $teacherId, int $year): array
    {
        $labels = $fluency = $comp = $totals = [];

        foreach (self::QUARTERS as $q => $def) {
            $labels[] = $def['label'];
            $agg      = $this->aggregateQuarter($teacherId, $year, $q);
            $fluency[] = $agg['avg_fluency'];
            $comp[]    = $agg['avg_comp'];
            $totals[]  = $agg['total'];
        }

        return compact('labels', 'fluency', 'comp', 'totals');
    }

    private function riskByMonth(int $teacherId, int $year): array
    {
        $monthOrder = [6,7,8,9,10,11,12,1,2,3];
        $monthNames = [1=>'Jan',2=>'Feb',3=>'Mar',6=>'Jun',7=>'Jul',8=>'Aug',
                       9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
        $labels = $below = $approaching = $meeting = [];

        foreach ($monthOrder as $m) {
            $rows = Assessment::where('teacher_id', $teacherId)
                ->whereYear('assessed_on', $year)->whereMonth('assessed_on', $m)
                ->select('risk_level', DB::raw('count(*) as total'))
                ->groupBy('risk_level')->pluck('total', 'risk_level');

            $labels[]      = $monthNames[$m];
            $below[]       = (int) ($rows[ReadingRiskService::BELOW]       ?? 0);
            $approaching[] = (int) ($rows[ReadingRiskService::APPROACHING]  ?? 0);
            $meeting[]     = (int) ($rows[ReadingRiskService::MEETING]      ?? 0);
        }

        return compact('labels', 'below', 'approaching', 'meeting');
    }

    private function riskByQuarter(int $teacherId, int $year): array
    {
        $labels = $below = $approaching = $meeting = [];

        foreach (self::QUARTERS as $q => $def) {
            $labels[]      = $def['short'];
            $risk          = $this->riskCountQuarter($teacherId, $year, $q);
            $below[]       = $risk['below'];
            $approaching[] = $risk['approaching'];
            $meeting[]     = $risk['meeting'];
        }

        return compact('labels', 'below', 'approaching', 'meeting');
    }

    private function aggregateQuarter(int $teacherId, int $year, int $quarter): array
    {
        $months   = self::QUARTERS[$quarter]['months'];
        $sameYear = array_values(array_filter($months, fn($m) => $m >= 6));
        $nextYear = array_values(array_filter($months, fn($m) => $m < 6));

        $q = Assessment::where('teacher_id', $teacherId)
            ->where(function ($sub) use ($year, $sameYear, $nextYear) {
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

        $result = $q->selectRaw('ROUND(AVG(fluency_score),1) as avg_fluency,
                                  ROUND(AVG(comprehension_score),1) as avg_comp,
                                  COUNT(*) as total')->first();

        return [
            'avg_fluency' => $result?->total ? (float) $result->avg_fluency : null,
            'avg_comp'    => $result?->total ? (float) $result->avg_comp    : null,
            'total'       => (int) ($result?->total ?? 0),
        ];
    }

    private function riskCountQuarter(int $teacherId, int $year, int $quarter): array
    {
        $months   = self::QUARTERS[$quarter]['months'];
        $sameYear = array_values(array_filter($months, fn($m) => $m >= 6));
        $nextYear = array_values(array_filter($months, fn($m) => $m < 6));

        $q = Assessment::where('teacher_id', $teacherId)
            ->where(function ($sub) use ($year, $sameYear, $nextYear) {
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
