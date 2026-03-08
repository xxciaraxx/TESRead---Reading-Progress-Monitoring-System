<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\ActivityLog;
use App\Services\ReadingRiskService;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $year = now()->year;

        /* ── Teacher stats ─────────────────────────── */
        $totalTeachers    = User::where('role', 'teacher')->count();
        $pendingTeachers  = User::where('role', 'teacher')->where('account_status', 'Pending')->count();
        $approvedTeachers = User::where('role', 'teacher')->where('account_status', 'Approved')->count();
        $rejectedTeachers = User::where('role', 'teacher')->where('account_status', 'Rejected')->count();

        /* ── Student stats ─────────────────────────── */
        $totalStudents = Student::active()->count();

        // Latest assessment per student for accurate risk counts
        $latestSub  = Assessment::select('student_id', DB::raw('MAX(id) as max_id'))
                        ->groupBy('student_id');
        $riskCounts = Assessment::joinSub($latestSub, 'latest', fn($j) =>
                        $j->on('assessments.id', '=', 'latest.max_id'))
                        ->whereNotNull('risk_level')
                        ->select('risk_level', DB::raw('count(*) as total'))
                        ->groupBy('risk_level')
                        ->pluck('total', 'risk_level');

        $meeting    = (int) ($riskCounts[ReadingRiskService::MEETING]    ?? 0);
        $approaching = (int) ($riskCounts[ReadingRiskService::APPROACHING] ?? 0);
        $below       = (int) ($riskCounts[ReadingRiskService::BELOW]      ?? 0);

        /* ── Monthly monitoring (current year, all 12 months) ─── */
        $monthlyRaw = Assessment::whereYear('assessed_on', $year)
            ->select(
                DB::raw('MONTH(assessed_on) as month'),
                DB::raw('COUNT(*) as assessments'),
                DB::raw('ROUND(AVG(fluency_score), 1) as avg_fluency'),
                DB::raw('ROUND(AVG(comprehension_score), 1) as avg_comp'),
                DB::raw('SUM(CASE WHEN risk_level = ? THEN 1 ELSE 0 END) as cnt_meeting'),
                DB::raw('SUM(CASE WHEN risk_level = ? THEN 1 ELSE 0 END) as cnt_approaching'),
                DB::raw('SUM(CASE WHEN risk_level = ? THEN 1 ELSE 0 END) as cnt_below')
            )
            ->addBinding([
                ReadingRiskService::MEETING,
                ReadingRiskService::APPROACHING,
                ReadingRiskService::BELOW,
            ], 'select')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthNames = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',
                       7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
        $currentMonth = now()->month;

        // School year order: Jun → Mar (10 months, Apr/May = summer break)
        $schoolYearMonths = [6,7,8,9,10,11,12,1,2,3];
        $monthlyMonitoring = collect($schoolYearMonths)->map(function ($m) use ($monthlyRaw, $monthNames, $currentMonth, $totalStudents) {
            $row = $monthlyRaw->get($m);
            $assessed  = $row ? (int) $row->assessments : 0;
            $safeTotal = max($totalStudents, 1);
            $pct       = min(100, round($assessed / $safeTotal * 100));

            return [
                'month'       => $m,
                'name'        => $monthNames[$m],
                'assessments' => $assessed,
                'avg_fluency' => $row ? (float) $row->avg_fluency : null,
                'avg_comp'    => $row ? (float) $row->avg_comp    : null,
                'meeting'     => $row ? (int) $row->cnt_meeting    : 0,
                'approaching' => $row ? (int) $row->cnt_approaching : 0,
                'below'       => $row ? (int) $row->cnt_below      : 0,
                'pct'         => $pct,
                'is_current'  => $m === $currentMonth,
                'is_past'     => $m < $currentMonth,
                'is_future'   => $m > $currentMonth,
            ];
        });

        /* ── Targets (DepEd benchmarks) ─────────────── */
        $fluencyTarget = 85;
        $compTarget    = 80;

        /* ── Year-to-date summary ───────────────────── */
        $ytdAssessments = Assessment::whereYear('assessed_on', $year)->count();
        $ytdMonthsDone  = $monthlyMonitoring->where('is_past', true)->count()
                        + $monthlyMonitoring->where('is_current', true)->where('assessments', '>', 0)->count();
        $monthsWithData = $monthlyMonitoring->where('assessments', '>', 0)->count();

        /* ── Recent teacher registrations ──────────── */
        $recentTeachers = User::where('role', 'teacher')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTeachers', 'pendingTeachers', 'approvedTeachers', 'rejectedTeachers',
            'totalStudents', 'meeting', 'approaching', 'below',
            'monthlyMonitoring', 'ytdAssessments', 'monthsWithData',
            'fluencyTarget', 'compTarget', 'year',
            'recentTeachers'
        ));
    }
}
