@extends('layouts.admin')

@section('title', 'Analytics & Reports')
@section('page-icon', '📊')
@section('page-heading', 'Analytics & Reports')

@section('content')

{{-- ══ FILTER BAR ══ --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius);
            padding:16px 22px;margin-bottom:24px;
            display:flex;align-items:center;gap:16px;flex-wrap:wrap;
            box-shadow:var(--shadow-sm);">
    <form method="GET" id="filterForm"
          style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;width:100%;">

        <div style="font-size:12px;font-weight:700;text-transform:uppercase;
                    letter-spacing:0.8px;color:var(--muted);">
            <i class="fas fa-filter"></i> Filter
        </div>

        {{-- Period Toggle --}}
        <div style="display:flex;background:#f0f3fa;border-radius:8px;padding:3px;gap:2px;">
            <button type="submit" name="period" value="monthly"
                    class="{{ $period === 'monthly' ? 'period-btn active' : 'period-btn' }}">
                Monthly
            </button>
            <button type="submit" name="period" value="quarterly"
                    class="{{ $period === 'quarterly' ? 'period-btn active' : 'period-btn' }}">
                Quarterly
            </button>
        </div>

        {{-- Year Selector --}}
        <select name="year" onchange="this.form.submit()"
                style="border:1px solid var(--border);border-radius:8px;
                       padding:7px 32px 7px 12px;font-size:13px;font-weight:600;
                       background:#fff;cursor:pointer;color:var(--text);">
            @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>

        <div style="margin-left:auto;font-size:13px;color:var(--muted);">
            Showing <strong style="color:var(--text);">{{ ucfirst($period) }}</strong>
            data for <strong style="color:var(--primary);">{{ $year }}</strong>
        </div>

        <button onclick="window.print()" type="button" class="btn btn-outline btn-sm">
            <i class="fas fa-print"></i> Print
        </button>
    </form>
</div>

{{-- ══ OVERVIEW STAT CARDS ══ --}}
<div class="stat-grid" style="margin-bottom:28px;">
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-number">{{ $totalStudents }}</div>
        <div class="stat-label">Total Students</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-number">{{ $totalTeachers }}</div>
        <div class="stat-label">Active Teachers</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-icon yellow"><i class="fas fa-clipboard-check"></i></div>
        <div class="stat-number">{{ $totalAssessments }}</div>
        <div class="stat-label">Assessments ({{ $year }})</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red"><i class="fas fa-bell"></i></div>
        <div class="stat-number">{{ $activeInterventions }}</div>
        <div class="stat-label">Active Interventions</div>
    </div>
</div>

{{-- ══ ROW 1: Score Trend + Risk Distribution ══ --}}
<div class="grid-2" style="gap:22px;margin-bottom:22px;">

    {{-- Chart 1: Average Score Trend (Line Chart) --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-chart-line" style="color:var(--primary);"></i>
                Average Score Trend
            </div>
            <span class="badge badge-secondary" style="font-size:11px;">{{ ucfirst($period) }} · {{ $year }}</span>
        </div>
        <div style="padding:20px;">
            <canvas id="scoreTrendChart" height="260"></canvas>
        </div>
        <div style="padding:0 20px 16px;display:flex;gap:20px;">
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted);">
                <div style="width:24px;height:3px;background:#003A8C;border-radius:2px;"></div>
                Fluency
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted);">
                <div style="width:24px;height:3px;background:#C8102E;border-radius:2px;"></div>
                Comprehension
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted);">
                <div style="width:12px;height:3px;background:#ccc;border-radius:2px;border-top:2px dashed #999;"></div>
                Benchmarks
            </div>
        </div>
    </div>

    {{-- Chart 2: Risk Distribution Doughnut --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-chart-pie" style="color:var(--primary);"></i>
                Risk Level Distribution
            </div>
            <span class="text-muted text-small">Current (latest per student)</span>
        </div>
        <div style="padding:20px;display:flex;align-items:center;gap:20px;">
            <div style="flex:0 0 200px;">
                <canvas id="riskDoughnutChart" height="200"></canvas>
            </div>
            <div style="flex:1;">
                @php
                    $total = max($riskDistribution['below'] + $riskDistribution['approaching'] + $riskDistribution['meeting'], 1);
                @endphp
                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:12px;height:12px;border-radius:3px;background:#28A745;"></div>
                            <span style="font-size:13px;font-weight:600;">Meeting</span>
                        </div>
                        <span style="font-weight:800;font-size:15px;">{{ $riskDistribution['meeting'] }}</span>
                    </div>
                    <div class="risk-bar" style="height:8px;">
                        <div class="risk-bar-fill" style="width:{{ round($riskDistribution['meeting']/$total*100) }}%;background:#28A745;"></div>
                    </div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ round($riskDistribution['meeting']/$total*100) }}% of students</div>
                </div>
                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:12px;height:12px;border-radius:3px;background:#FFC107;"></div>
                            <span style="font-size:13px;font-weight:600;">Approaching</span>
                        </div>
                        <span style="font-weight:800;font-size:15px;">{{ $riskDistribution['approaching'] }}</span>
                    </div>
                    <div class="risk-bar" style="height:8px;">
                        <div class="risk-bar-fill" style="width:{{ round($riskDistribution['approaching']/$total*100) }}%;background:#FFC107;"></div>
                    </div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ round($riskDistribution['approaching']/$total*100) }}% of students</div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:12px;height:12px;border-radius:3px;background:#C8102E;"></div>
                            <span style="font-size:13px;font-weight:600;">Below</span>
                        </div>
                        <span style="font-weight:800;font-size:15px;">{{ $riskDistribution['below'] }}</span>
                    </div>
                    <div class="risk-bar" style="height:8px;">
                        <div class="risk-bar-fill" style="width:{{ round($riskDistribution['below']/$total*100) }}%;background:#C8102E;"></div>
                    </div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ round($riskDistribution['below']/$total*100) }}% of students</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ ROW 2: Risk Over Time + Sessions Distribution ══ --}}
<div class="grid-2" style="gap:22px;margin-bottom:22px;">

    {{-- Chart 3: Risk Level Over Time (Grouped Bar) --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-chart-bar" style="color:var(--primary);"></i>
                Risk Level Count — {{ ucfirst($period) }}
            </div>
            <span class="badge badge-secondary" style="font-size:11px;">{{ $year }}</span>
        </div>
        <div style="padding:20px;">
            <canvas id="riskOverTimeChart" height="240"></canvas>
        </div>
    </div>

    {{-- Chart 4: Sessions/Week Distribution (Horizontal Bar) --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-calendar-week" style="color:var(--primary);"></i>
                Reading Sessions per Week
            </div>
            <span class="text-muted text-small">{{ $year }}</span>
        </div>
        <div style="padding:20px;">
            <canvas id="sessionsChart" height="240"></canvas>
        </div>
        <div style="padding:0 20px 16px;font-size:12px;color:var(--muted);">
            <i class="fas fa-info-circle"></i>
            Students with ≤1 session/week are flagged as <strong style="color:var(--danger);">Below Standard</strong>.
        </div>
    </div>
</div>

{{-- ══ ROW 3: Assessment Volume Bar + Teacher Comparison ══ --}}
<div class="grid-2" style="gap:22px;margin-bottom:22px;">

    {{-- Chart 5: Assessment Volume (Bar) --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-clipboard-list" style="color:var(--primary);"></i>
                Assessment Volume
            </div>
            <span class="text-muted text-small">{{ ucfirst($period) }} · {{ $year }}</span>
        </div>
        <div style="padding:20px;">
            <canvas id="volumeChart" height="240"></canvas>
        </div>
    </div>

    {{-- Table: Teacher Comparison --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-chalkboard-teacher" style="color:var(--primary);"></i>
                Teacher Performance Summary
            </div>
            <span class="text-muted text-small">{{ $year }}</span>
        </div>
        <div class="table-wrap">
            <table class="data-table" style="font-size:12.5px;">
                <thead>
                    <tr>
                        <th>Teacher</th>
                        <th style="text-align:center;">Students</th>
                        <th style="text-align:center;">Avg Fluency</th>
                        <th style="text-align:center;">Avg Comp.</th>
                        <th style="text-align:center;">Below</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teacherStats as $ts)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $ts['name'] }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $ts['assessments'] }} assessments</div>
                        </td>
                        <td style="text-align:center;font-weight:700;">{{ $ts['students'] }}</td>
                        <td style="text-align:center;">
                            <span style="font-weight:700;color:{{ $ts['avg_fluency'] >= 85 ? 'var(--success)' : ($ts['avg_fluency'] >= 70 ? '#b8860b' : 'var(--danger)') }};">
                                {{ $ts['avg_fluency'] > 0 ? $ts['avg_fluency'].'%' : '—' }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <span style="font-weight:700;color:{{ $ts['avg_comp'] >= 80 ? 'var(--success)' : ($ts['avg_comp'] >= 65 ? '#b8860b' : 'var(--danger)') }};">
                                {{ $ts['avg_comp'] > 0 ? $ts['avg_comp'].'%' : '—' }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            @if($ts['below'] > 0)
                                <span class="badge badge-danger">{{ $ts['below'] }}</span>
                            @else
                                <span class="badge badge-success">0</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5">
                        <div class="empty-state" style="padding:28px;">
                            <div class="empty-state-icon">👨‍🏫</div>
                            <h3>No teacher data yet</h3>
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══ ROW 4: Below-Standard Students Alert Table ══ --}}
@if($belowStudents->count() > 0)
<div class="card" style="border-left:4px solid var(--danger);margin-bottom:22px;">
    <div class="card-header" style="background:rgba(200,16,46,0.04);">
        <div class="card-title" style="color:var(--danger);">
            <i class="fas fa-exclamation-triangle"></i>
            Students Requiring Immediate Attention
        </div>
        <span class="badge badge-danger">{{ $belowStudents->count() }} students</span>
    </div>
    <div class="table-wrap">
        <table class="data-table" style="font-size:13px;">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Teacher</th>
                    <th>Section</th>
                    <th>Fluency</th>
                    <th>Comprehension</th>
                    <th>Sessions/wk</th>
                    <th>Assessed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($belowStudents as $student)
                @php $la = $student->latestAssessment; @endphp
                <tr>
                    <td>
                        <div class="user-row">
                            <img src="{{ $student->profilePhotoUrl() }}" class="user-avatar-sm">
                            <div class="user-name">{{ $student->fullName() }}</div>
                        </div>
                    </td>
                    <td class="text-muted text-small">{{ $student->teacher?->name ?? '—' }}</td>
                    <td>
                        @if($student->section)
                            <span class="badge badge-info" style="font-size:10.5px;">
                                Gr.{{ $student->section->grade_level }} {{ $student->section->name }}
                            </span>
                        @else — @endif
                    </td>
                    <td><span style="font-weight:700;color:var(--danger);">{{ $la?->fluency_score ?? '—' }}{{ $la ? '%' : '' }}</span></td>
                    <td><span style="font-weight:700;color:var(--danger);">{{ $la?->comprehension_score ?? '—' }}{{ $la ? '%' : '' }}</span></td>
                    <td>
                        <span class="badge badge-danger" style="font-size:10.5px;">
                            {{ $la?->reading_sessions_per_week ?? '—' }}/wk
                        </span>
                    </td>
                    <td class="text-muted text-small">{{ $la?->assessed_on?->format('M d, Y') ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

{{-- ══ CHART.JS SCRIPTS ══ --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// ── Color palette ──
const BLUE    = '#003A8C';
const RED     = '#C8102E';
const GREEN   = '#28A745';
const YELLOW  = '#FFC107';
const MUTED   = '#94a3b8';

// ── PHP data passed to JS ──
const trendLabels   = @json($trendData['labels']);
const trendFluency  = @json($trendData['fluency']);
const trendComp     = @json($trendData['comp']);
const trendTotals   = @json($trendData['totals']);

const riskLabels     = @json($riskOverTime['labels']);
const riskBelow      = @json($riskOverTime['below']);
const riskApproach   = @json($riskOverTime['approaching']);
const riskMeeting    = @json($riskOverTime['meeting']);

const sessKeys = Object.keys(@json($sessionsDist)).map(Number);
const sessVals = Object.values(@json($sessionsDist)).map(Number);

const distBelow  = {{ $riskDistribution['below'] }};
const distAppr   = {{ $riskDistribution['approaching'] }};
const distMeet   = {{ $riskDistribution['meeting'] }};

// ── Chart defaults ──
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.font.size   = 12;
Chart.defaults.color       = '#64748b';

// ── 1. SCORE TREND (Line) ──────────────────────────────
new Chart(document.getElementById('scoreTrendChart'), {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [
            {
                label: 'Avg Fluency',
                data: trendFluency,
                borderColor: BLUE,
                backgroundColor: BLUE + '18',
                borderWidth: 2.5,
                pointBackgroundColor: BLUE,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4,
                fill: true,
                spanGaps: true,
            },
            {
                label: 'Avg Comprehension',
                data: trendComp,
                borderColor: RED,
                backgroundColor: RED + '12',
                borderWidth: 2.5,
                pointBackgroundColor: RED,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4,
                fill: true,
                spanGaps: true,
            },
            // Benchmark lines
            {
                label: 'Fluency Benchmark (85)',
                data: Array(trendLabels.length).fill(85),
                borderColor: BLUE + '40',
                borderDash: [6, 4],
                borderWidth: 1.5,
                pointRadius: 0,
                fill: false,
            },
            {
                label: 'Comp. Benchmark (80)',
                data: Array(trendLabels.length).fill(80),
                borderColor: RED + '40',
                borderDash: [6, 4],
                borderWidth: 1.5,
                pointRadius: 0,
                fill: false,
            },
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.dataset.label + ': ' + (ctx.parsed.y !== null ? ctx.parsed.y + '%' : 'No data')
                }
            }
        },
        scales: {
            y: {
                min: 0, max: 100,
                grid: { color: '#f0f3fa' },
                ticks: { callback: v => v + '%' },
            },
            x: { grid: { display: false } }
        }
    }
});

// ── 2. RISK DOUGHNUT ──────────────────────────────────
new Chart(document.getElementById('riskDoughnutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Meeting', 'Approaching', 'Below'],
        datasets: [{
            data: [distMeet, distAppr, distBelow],
            backgroundColor: [GREEN, YELLOW, RED],
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.label + ': ' + ctx.raw + ' students'
                }
            }
        }
    }
});

// ── 3. RISK OVER TIME (Grouped Bar) ──────────────────
new Chart(document.getElementById('riskOverTimeChart'), {
    type: 'bar',
    data: {
        labels: riskLabels,
        datasets: [
            {
                label: 'Meeting',
                data: riskMeeting,
                backgroundColor: GREEN + 'cc',
                borderRadius: 4,
                borderSkipped: false,
            },
            {
                label: 'Approaching',
                data: riskApproach,
                backgroundColor: YELLOW + 'cc',
                borderRadius: 4,
                borderSkipped: false,
            },
            {
                label: 'Below',
                data: riskBelow,
                backgroundColor: RED + 'cc',
                borderRadius: 4,
                borderSkipped: false,
            },
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, padding: 16 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f0f3fa' },
                ticks: { stepSize: 1 }
            },
            x: { grid: { display: false } }
        }
    }
});

// ── 4. SESSIONS DISTRIBUTION (Horizontal Bar) ────────
new Chart(document.getElementById('sessionsChart'), {
    type: 'bar',
    data: {
        labels: sessKeys.map(k => k + (k === 1 ? ' session' : ' sessions') + '/wk'),
        datasets: [{
            label: 'Students',
            data: sessVals,
            backgroundColor: sessKeys.map(k => k <= 1 ? RED + 'cc' : BLUE + 'cc'),
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.raw + ' assessments'
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: { color: '#f0f3fa' },
                ticks: { stepSize: 1 }
            },
            y: { grid: { display: false } }
        }
    }
});

// ── 5. ASSESSMENT VOLUME (Bar) ────────────────────────
new Chart(document.getElementById('volumeChart'), {
    type: 'bar',
    data: {
        labels: trendLabels,
        datasets: [{
            label: 'Assessments Recorded',
            data: trendTotals,
            backgroundColor: BLUE + 'cc',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f0f3fa' },
                ticks: { stepSize: 1 }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush

@push('styles')
<style>
.period-btn {
    padding: 6px 16px;
    border: none;
    background: transparent;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
    cursor: pointer;
    transition: all 0.18s;
}
.period-btn.active {
    background: #fff;
    color: var(--primary);
    box-shadow: 0 1px 4px rgba(0,0,0,0.12);
}
.period-btn:hover:not(.active) { color: var(--text); }

@media print {
    .sidebar, .top-header, form, .btn { display: none !important; }
    .main-area { margin-left: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; break-inside: avoid; }
}
</style>
@endpush
