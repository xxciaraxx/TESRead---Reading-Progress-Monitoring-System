<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assessment;
use App\Services\ReadingRiskService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private const QUARTERS = [
        1 => ['label' => 'Q1 — Jun to Aug', 'short' => 'Q1', 'months' => [6, 7, 8]],
        2 => ['label' => 'Q2 — Sep to Oct', 'short' => 'Q2', 'months' => [9, 10]],
        3 => ['label' => 'Q3 — Nov to Jan', 'short' => 'Q3', 'months' => [11, 12, 1]],
        4 => ['label' => 'Q4 — Feb to Mar', 'short' => 'Q4', 'months' => [2, 3]],
    ];

    public function index()
    {
        $teacherId = auth()->id();
        $year      = now()->year;

        /* ── KPI counts ─────────────────────────── */
        $totalStudents = Student::active()->where('teacher_id', $teacherId)->count();

        $latestSub  = Assessment::where('teacher_id', $teacherId)
                        ->select('student_id', DB::raw('MAX(id) as max_id'))
                        ->groupBy('student_id');
        $riskCounts = Assessment::joinSub($latestSub, 'latest', fn($j) =>
                        $j->on('assessments.id', '=', 'latest.max_id'))
                        ->select('risk_level', DB::raw('count(*) as total'))
                        ->whereNotNull('risk_level')
                        ->groupBy('risk_level')
                        ->pluck('total', 'risk_level');

        $meeting     = (int) ($riskCounts[ReadingRiskService::MEETING]    ?? 0);
        $approaching = (int) ($riskCounts[ReadingRiskService::APPROACHING] ?? 0);
        $below       = (int) ($riskCounts[ReadingRiskService::BELOW]       ?? 0);

        /* ── Quarterly monitoring ───────────────── */
        $currentQuarter = $this->currentQuarter();

        $quarterlyData = collect(self::QUARTERS)->map(function ($def, $q) use ($teacherId, $year, $totalStudents, $currentQuarter) {
            $months   = $def['months'];
            $sameYear = array_values(array_filter($months, fn($m) => $m >= 6));
            $nextYear = array_values(array_filter($months, fn($m) => $m < 6));

            $query = Assessment::where('teacher_id', $teacherId)
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

            $agg = $query->selectRaw(
                'COUNT(*) as assessments,
                 ROUND(AVG(fluency_score),1) as avg_fluency,
                 ROUND(AVG(comprehension_score),1) as avg_comp,
                 SUM(CASE WHEN risk_level = ? THEN 1 ELSE 0 END) as cnt_meeting,
                 SUM(CASE WHEN risk_level = ? THEN 1 ELSE 0 END) as cnt_approaching,
                 SUM(CASE WHEN risk_level = ? THEN 1 ELSE 0 END) as cnt_below',
                [ReadingRiskService::MEETING, ReadingRiskService::APPROACHING, ReadingRiskService::BELOW]
            )->first();

            $assessed  = (int) ($agg->assessments ?? 0);
            $safeTotal = max($totalStudents, 1);
            $pct       = min(100, round($assessed / $safeTotal * 100));

            return [
                'quarter'     => $q,
                'label'       => $def['label'],
                'short'       => $def['short'],
                'assessments' => $assessed,
                'avg_fluency' => $assessed ? (float) $agg->avg_fluency : null,
                'avg_comp'    => $assessed ? (float) $agg->avg_comp    : null,
                'meeting'     => (int) ($agg->cnt_meeting    ?? 0),
                'approaching' => (int) ($agg->cnt_approaching ?? 0),
                'below'       => (int) ($agg->cnt_below      ?? 0),
                'pct'         => $pct,
                'is_current'  => $q === $currentQuarter,
                'is_past'     => $q < $currentQuarter,
                'is_future'   => $q > $currentQuarter,
            ];
        });

        $ytdAssessments = Assessment::where('teacher_id', $teacherId)
            ->where(function ($q) use ($year) {
                // School year Jun Y → Mar Y+1
                $q->where(fn($s) => $s->whereYear('assessed_on', $year)->whereIn(DB::raw('MONTH(assessed_on)'), [6,7,8,9,10,11,12]))
                  ->orWhere(fn($s) => $s->whereYear('assessed_on', $year + 1)->whereIn(DB::raw('MONTH(assessed_on)'), [1,2,3]));
            })->count();

        /* ── Students needing attention ─────────── */
        $studentsNeedingAttention = Student::active()
            ->where('teacher_id', $teacherId)
            ->whereHas('latestAssessment', fn($q) =>
                $q->where('risk_level', ReadingRiskService::BELOW)
            )
            ->with('latestAssessment')
            ->take(5)->get();

        return view('teacher.dashboard', compact(
            'totalStudents', 'meeting', 'approaching', 'below',
            'quarterlyData', 'ytdAssessments', 'year',
            'studentsNeedingAttention'
        ));
    }

    private function currentQuarter(): int
    {
        $month = now()->month;
        return match(true) {
            in_array($month, [6, 7, 8])       => 1,
            in_array($month, [9, 10])          => 2,
            in_array($month, [11, 12, 1])      => 3,
            in_array($month, [2, 3])           => 4,
            default                            => 1,
        };
    }
}
