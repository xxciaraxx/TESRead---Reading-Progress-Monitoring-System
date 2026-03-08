@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-icon', '🏠')
@section('page-heading', 'Dashboard')

@push('styles')
<style>
/* ════════════════════════════════════════
   DASHBOARD — Monthly Monitoring
   ════════════════════════════════════════ */

/* ── Welcome banner ─────────────────────── */
.dash-page { display:flex; flex-direction:column; gap:24px; }

/* ── Section labels ─────────────────────── */
.dash-label {
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.1px;
    color: var(--muted);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.dash-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

/* ── Monthly monitoring card ─────────────── */
.mm-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.mm-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 16px 22px;
    border-bottom: 1px solid var(--border);
    background: #fafbff;
}
.mm-head-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.mm-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.mm-title i { color: var(--primary); }
.mm-year-badge {
    font-size: 11px;
    font-weight: 700;
    background: rgba(0,58,140,.08);
    color: var(--primary);
    border-radius: 99px;
    padding: 3px 10px;
}
.mm-summary-pills {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.mm-pill {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 99px;
    white-space: nowrap;
}
.mm-pill.blue  { background:rgba(0,58,140,.08);  color:var(--primary); }
.mm-pill.green { background:rgba(40,167,69,.10); color:#1a7a38; }
.mm-pill.red   { background:rgba(200,16,46,.10); color:var(--danger); }

/* ── Target benchmarks bar ───────────────── */
.mm-targets {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 10px 22px;
    background: #f8faff;
    border-bottom: 1px solid var(--border);
    font-size: 12px;
    flex-wrap: wrap;
}
.mm-target-item {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #374151;
    font-weight: 600;
}
.mm-target-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ── Month grid ─────────────────────────── */
.mm-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0;
}
.mm-month {
    padding: 16px 14px;
    border-right: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
    position: relative;
    transition: background .15s;
    cursor: default;
}
.mm-month:hover { background: #fafbff; }
.mm-month:nth-child(6n) { border-right: none; }
.mm-month:nth-child(n+7) { border-bottom: none; }

/* Current month highlight */
.mm-month.current {
    background: #eff6ff;
}
.mm-month.current::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), #1a52b3);
    border-radius: 0;
}

/* Future month */
.mm-month.future {
    background: #fafafa;
    opacity: .7;
}

.mm-month-name {
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: var(--muted);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.mm-month-name .curr-tag {
    font-size: 8.5px;
    font-weight: 800;
    background: var(--primary);
    color: #fff;
    border-radius: 4px;
    padding: 1px 5px;
    letter-spacing: .4px;
}

/* Assessment count */
.mm-count {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 6px;
}
.mm-count.zero { color: #cbd5e1; }
.mm-count-label {
    font-size: 10px;
    color: var(--muted);
    margin-bottom: 10px;
}

/* Progress bar */
.mm-bar-wrap {
    background: #f1f5f9;
    border-radius: 99px;
    height: 7px;
    overflow: hidden;
    margin-bottom: 8px;
}
.mm-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width .6s cubic-bezier(.4,0,.2,1);
}

/* Risk mini-dots */
.mm-risk-row {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 8px;
    flex-wrap: wrap;
}
.mm-risk-chip {
    display: flex;
    align-items: center;
    gap: 3px;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 4px;
}
.mm-risk-chip.g { background:rgba(40,167,69,.12);  color:#1a7a38; }
.mm-risk-chip.a { background:rgba(255,193,7,.15);  color:#7a5a00; }
.mm-risk-chip.r { background:rgba(200,16,46,.12);  color:var(--danger); }

/* Score row */
.mm-scores {
    display: flex;
    gap: 6px;
    margin-top: 6px;
}
.mm-score {
    flex: 1;
    background: #f8faff;
    border-radius: 6px;
    padding: 4px 6px;
    text-align: center;
}
.mm-score-val {
    font-size: 12px;
    font-weight: 800;
    line-height: 1;
}
.mm-score-lbl {
    font-size: 8.5px;
    color: var(--muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-top: 1px;
}

/* Future placeholder */
.mm-future-txt {
    font-size: 11px;
    color: #cbd5e1;
    text-align: center;
    padding: 12px 0;
}

/* ── YTD summary strip ──────────────────── */
.ytd-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border-top: 1px solid var(--border);
}
.ytd-cell {
    padding: 14px 18px;
    border-right: 1px solid var(--border);
    text-align: center;
}
.ytd-cell:last-child { border-right: none; }
.ytd-val {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}
.ytd-lbl {
    font-size: 10.5px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .6px;
    margin-top: 4px;
}

/* ── Bottom grid ────────────────────────── */
.dash-bottom {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
    align-items: start;
}

/* ── Responsive ─────────────────────────── */
@media (max-width: 1200px) {
    .mm-grid { grid-template-columns: repeat(4, 1fr); }
    .mm-month:nth-child(6n)  { border-right: 1px solid #f1f5f9; }
    .mm-month:nth-child(4n)  { border-right: none; }
    .mm-month:nth-child(n+9) { border-bottom: none; }
    .mm-month:nth-child(5),
    .mm-month:nth-child(6),
    .mm-month:nth-child(7),
    .mm-month:nth-child(8)   { border-bottom: 1px solid #f1f5f9; }
    .ytd-strip { grid-template-columns: repeat(2, 1fr); }
    .ytd-cell:nth-child(2)   { border-right: none; }
    .ytd-cell:nth-child(3)   { border-top: 1px solid var(--border); }
    .ytd-cell:nth-child(4)   { border-top: 1px solid var(--border); border-right: none; }
}
@media (max-width: 900px) {
    .dash-bottom { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .mm-grid { grid-template-columns: repeat(3, 1fr); }
    .mm-month:nth-child(4n) { border-right: 1px solid #f1f5f9; }
    .mm-month:nth-child(3n) { border-right: none; }
    .mm-scores { display: none; }
    .mm-head { padding: 12px 16px; }
    .mm-month { padding: 12px 10px; }
    .mm-count { font-size: 18px; }
    .ytd-strip { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .mm-grid { grid-template-columns: repeat(2, 1fr); }
    .mm-month:nth-child(3n) { border-right: 1px solid #f1f5f9; }
    .mm-month:nth-child(2n) { border-right: none; }
    .mm-risk-row { display: none; }
}
</style>
@endpush

@section('content')
@php
    $riskTotal   = max($meeting + $approaching + $below, 1);
    $meetPct     = round($meeting    / $riskTotal * 100);
    $apprPct     = round($approaching / $riskTotal * 100);
    $belowPct    = round($below       / $riskTotal * 100);
    $ytdPct      = $totalStudents > 0
                    ? min(100, round($ytdAssessments / max($totalStudents * $monthsWithData, 1) * 100))
                    : 0;
@endphp

<div class="dash-page">

{{-- ══ Welcome Banner ══ --}}
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Welcome back, {{ Str::words(auth()->user()->name, 2, '') }}! 👋</h2>
        <p>Here's the reading progress overview for Tampugo Elementary School.</p>
        <div class="welcome-badge">
            <i class="fas fa-calendar-check"></i>
            S.Y. {{ $year }}&ndash;{{ $year + 1 }}
        </div>
    </div>
</div>

{{-- ══ Teacher Accounts ══ --}}
<div>
    <div class="dash-label"><i class="fas fa-chalkboard-teacher"></i> Teacher Accounts</div>
    <div class="stat-grid">
        <div class="stat-card blue">
            <div class="stat-icon blue"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-number">{{ $totalTeachers }}</div>
            <div class="stat-label">Total Teachers</div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
            <div class="stat-number">{{ $pendingTeachers }}</div>
            <div class="stat-label">Pending Approval</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
            <div class="stat-number">{{ $approvedTeachers }}</div>
            <div class="stat-label">Approved</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon red"><i class="fas fa-user-times"></i></div>
            <div class="stat-number">{{ $rejectedTeachers }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>
</div>

{{-- ══ Student Reading Overview ══ --}}
<div>
    <div class="dash-label"><i class="fas fa-user-graduate"></i> Student Reading Overview</div>
    <div class="stat-grid" style="grid-template-columns:repeat(4,1fr);">
        <div class="stat-card blue">
            <div class="stat-icon blue"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-number">{{ $totalStudents }}</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon green"><i class="fas fa-star"></i></div>
            <div class="stat-number">{{ $meeting }}</div>
            <div class="stat-label">Meeting Standard</div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-icon yellow"><i class="fas fa-chart-line"></i></div>
            <div class="stat-number">{{ $approaching }}</div>
            <div class="stat-label">Approaching Standard</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-number">{{ $below }}</div>
            <div class="stat-label">Below Standard</div>
        </div>
    </div>
</div>

{{-- ══ Monthly Progress Monitoring ══ --}}
<div>
    <div class="dash-label"><i class="fas fa-chart-bar"></i> Monthly Progress Monitoring</div>

    <div class="mm-card">

        {{-- Header --}}
        <div class="mm-head">
            <div class="mm-head-left">
                <div class="mm-title">
                    <i class="fas fa-calendar-alt"></i>
                    Reading Assessment Progress
                </div>
                <span class="mm-year-badge">S.Y. {{ $year }}&ndash;{{ $year + 1 }}</span>
            </div>
            <div class="mm-summary-pills">
                <div class="mm-pill blue">
                    <i class="fas fa-clipboard-check"></i>
                    {{ $ytdAssessments }} assessments this year
                </div>
                <div class="mm-pill green">
                    <i class="fas fa-calendar-check"></i>
                    {{ $monthsWithData }} of 12 months recorded
                </div>
                @if($below > 0)
                    <div class="mm-pill red">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $below }} need intervention
                    </div>
                @endif
            </div>
        </div>

        {{-- DepEd Benchmark targets --}}
        <div class="mm-targets">
            <span style="font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-right:4px;">
                DepEd Targets:
            </span>
            <div class="mm-target-item">
                <div class="mm-target-dot" style="background:#003A8C;"></div>
                Fluency ≥ {{ $fluencyTarget }}%
            </div>
            <div class="mm-target-item">
                <div class="mm-target-dot" style="background:#C8102E;"></div>
                Comprehension ≥ {{ $compTarget }}%
            </div>
            <div style="margin-left:auto;font-size:11.5px;color:var(--muted);">
                Bar shows % of students assessed that month
            </div>
        </div>

        {{-- 12-month grid --}}
        <div class="mm-grid">
            @foreach($monthlyMonitoring as $m)
            @php
                $hasData = $m['assessments'] > 0;
                $barColor = $m['pct'] >= 80
                    ? '#28A745'
                    : ($m['pct'] >= 50 ? '#FFC107' : ($m['pct'] > 0 ? '#C8102E' : '#e2e8f0'));
                $fColor = !$hasData ? '#cbd5e1'
                    : ($m['avg_fluency'] >= $fluencyTarget ? '#28A745'
                    : ($m['avg_fluency'] >= 70 ? '#c47d0e' : '#C8102E'));
                $cColor = !$hasData ? '#cbd5e1'
                    : ($m['avg_comp'] >= $compTarget ? '#28A745'
                    : ($m['avg_comp'] >= 65 ? '#c47d0e' : '#C8102E'));
            @endphp
            <div class="mm-month {{ $m['is_current'] ? 'current' : ($m['is_future'] ? 'future' : '') }}">

                {{-- Month name + current tag --}}
                <div class="mm-month-name">
                    {{ $m['name'] }}
                    @if($m['is_current'])
                        <span class="curr-tag">NOW</span>
                    @endif
                </div>

                @if($m['is_future'] && !$hasData)
                    {{-- Future with no data --}}
                    <div class="mm-future-txt">
                        <i class="fas fa-lock" style="font-size:13px;display:block;margin-bottom:4px;"></i>
                        Upcoming
                    </div>
                @else
                    {{-- Assessment count --}}
                    <div class="mm-count {{ !$hasData ? 'zero' : '' }}">
                        {{ $m['assessments'] }}
                    </div>
                    <div class="mm-count-label">
                        {{ $hasData ? 'assessments' : 'none recorded' }}
                    </div>

                    {{-- Coverage progress bar --}}
                    <div class="mm-bar-wrap">
                        <div class="mm-bar-fill"
                             style="width:{{ $m['pct'] }}%; background:{{ $barColor }};"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:9.5px;color:var(--muted);font-weight:600;margin-bottom:6px;">
                        <span>Coverage</span>
                        <span>{{ $m['pct'] }}%</span>
                    </div>

                    @if($hasData)
                        {{-- Avg scores --}}
                        <div class="mm-scores">
                            <div class="mm-score">
                                <div class="mm-score-val" style="color:{{ $fColor }};">
                                    {{ $m['avg_fluency'] !== null ? $m['avg_fluency'].'%' : '—' }}
                                </div>
                                <div class="mm-score-lbl">Fluency</div>
                            </div>
                            <div class="mm-score">
                                <div class="mm-score-val" style="color:{{ $cColor }};">
                                    {{ $m['avg_comp'] !== null ? $m['avg_comp'].'%' : '—' }}
                                </div>
                                <div class="mm-score-lbl">Comp.</div>
                            </div>
                        </div>

                        {{-- Risk mini breakdown --}}
                        <div class="mm-risk-row">
                            @if($m['meeting'] > 0)
                                <span class="mm-risk-chip g">
                                    <i class="fas fa-check" style="font-size:8px;"></i>{{ $m['meeting'] }}
                                </span>
                            @endif
                            @if($m['approaching'] > 0)
                                <span class="mm-risk-chip a">
                                    <i class="fas fa-minus" style="font-size:8px;"></i>{{ $m['approaching'] }}
                                </span>
                            @endif
                            @if($m['below'] > 0)
                                <span class="mm-risk-chip r">
                                    <i class="fas fa-exclamation" style="font-size:8px;"></i>{{ $m['below'] }}
                                </span>
                            @endif
                            @if($m['meeting'] === 0 && $m['approaching'] === 0 && $m['below'] === 0)
                                <span style="font-size:10px;color:#cbd5e1;">No risk data</span>
                            @endif
                        </div>
                    @else
                        <div style="font-size:10.5px;color:#cbd5e1;text-align:center;padding:8px 0;">
                            No data recorded
                        </div>
                    @endif
                @endif
            </div>
            @endforeach
        </div>

        {{-- YTD summary strip --}}
        <div class="ytd-strip">
            <div class="ytd-cell">
                <div class="ytd-val" style="color:var(--primary);">{{ $ytdAssessments }}</div>
                <div class="ytd-lbl">Total Assessments</div>
            </div>
            <div class="ytd-cell">
                <div class="ytd-val" style="color:#1a7a38;">{{ $meetPct }}%</div>
                <div class="ytd-lbl">Meeting Standard</div>
            </div>
            <div class="ytd-cell">
                <div class="ytd-val" style="color:#7a5a00;">{{ $apprPct }}%</div>
                <div class="ytd-lbl">Approaching</div>
            </div>
            <div class="ytd-cell">
                <div class="ytd-val" style="color:var(--danger);">{{ $belowPct }}%</div>
                <div class="ytd-lbl">Below Standard</div>
            </div>
        </div>

    </div>{{-- /mm-card --}}
</div>

{{-- ══ Bottom Row: Pending Approvals ══ --}}
<div class="dash-bottom">

    {{-- Pending Teacher Registrations --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-user-clock" style="color:var(--warning);"></i>
                Pending Approvals
            </div>
            <a href="{{ route('admin.teachers.index', ['status' => 'Pending']) }}"
               class="btn btn-outline btn-sm">View All</a>
        </div>

        @if($recentTeachers->where('account_status','Pending')->count() > 0)
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTeachers->where('account_status','Pending') as $teacher)
                        <tr>
                            <td>
                                <div class="user-row">
                                    <img src="{{ $teacher->profilePhotoUrl() }}"
                                         alt="{{ $teacher->name }}" class="user-avatar-sm">
                                    <div>
                                        <div class="user-name">{{ $teacher->name }}</div>
                                        <div class="user-email">{{ $teacher->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted text-small">
                                {{ $teacher->created_at->diffForHumans() }}
                            </td>
                            <td>
                                <div class="d-flex gap-8">
                                    <form method="POST"
                                          action="{{ route('admin.teachers.approve', $teacher) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn-icon success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST"
                                          action="{{ route('admin.teachers.reject', $teacher) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn-icon danger" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state" style="padding:36px;">
                <div class="empty-state-icon">✅</div>
                <h3>All caught up!</h3>
                <p>No pending teacher registrations at this time.</p>
            </div>
        @endif
    </div>

    {{-- Quick Links / School Summary --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-tachometer-alt" style="color:var(--primary);"></i>
                Quick Access
            </div>
        </div>
        <div style="padding:8px 0;">
            @foreach([
                ['admin.students.index',      'fas fa-user-graduate',     'Students',           'Manage and monitor student records',              'blue'],
                ['admin.teachers.index',      'fas fa-chalkboard-teacher','Teachers',           'View and approve teacher accounts',               'green'],
                ['admin.classes.index',       'fas fa-chalkboard',        'Classes',            'Organize grade-level class sections',             'yellow'],
                ['admin.analytics.index',     'fas fa-chart-area',        'Analytics & Reports','View charts, trends, and performance summaries',  'red'],
            ] as [$route, $icon, $label, $desc, $color])
            <a href="{{ route($route) }}"
               style="display:flex;align-items:center;gap:14px;padding:13px 20px;
                      border-bottom:1px solid #f4f6fb;text-decoration:none;
                      transition:background .15s;"
               onmouseover="this.style.background='#f8faff'"
               onmouseout="this.style.background=''">
                <div class="stat-icon {{ $color }}"
                     style="width:38px;height:38px;border-radius:10px;font-size:15px;flex-shrink:0;">
                    <i class="{{ $icon }}"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:700;font-size:13.5px;color:#0f172a;">{{ $label }}</div>
                    <div style="font-size:11.5px;color:var(--muted);">{{ $desc }}</div>
                </div>
                <i class="fas fa-chevron-right" style="color:var(--muted);font-size:11px;flex-shrink:0;"></i>
            </a>
            @endforeach
            <div style="height:1px;"></div>
        </div>
    </div>

</div>

</div>{{-- /dash-page --}}
@endsection
