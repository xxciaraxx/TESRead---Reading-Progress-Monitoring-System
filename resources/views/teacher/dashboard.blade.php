@extends('layouts.teacher')
@section('title', 'My Dashboard')
@section('page-icon', '🏠')
@section('page-heading', 'My Dashboard')

@push('styles')
<style>
.dash-page { display:flex; flex-direction:column; gap:22px; }

/* Welcome Hero */
.welcome-hero {
    background: linear-gradient(135deg, #06143a 0%, #003A8C 45%, #C8102E 100%);
    border-radius: 16px;
    padding: 28px 32px;
    display: flex; align-items: center; justify-content: space-between; gap: 24px;
    position: relative; overflow: hidden;
}
.welcome-hero::before { content:''; position:absolute; right:-60px; top:-60px; width:240px; height:240px; background:rgba(255,255,255,.04); border-radius:50%; }
.welcome-hero::after  { content:''; position:absolute; right:80px; bottom:-80px; width:180px; height:180px; background:rgba(200,16,46,.12); border-radius:50%; }
.wh-eyebrow { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; color:rgba(255,255,255,.50); margin-bottom:8px; }
.wh-name { font-family:'Sora',sans-serif; font-size:26px; font-weight:800; color:#fff; margin-bottom:6px; letter-spacing:-.5px; }
.wh-name span { color:#fca5a5; }
.wh-sub  { font-size:13px; color:rgba(255,255,255,.62); font-weight:500; margin-bottom:18px; }
.wh-badges { display:flex; gap:10px; flex-wrap:wrap; }
.wh-badge {
    display:flex; align-items:center; gap:7px;
    background:rgba(255,255,255,.11); border:1px solid rgba(255,255,255,.16);
    border-radius:99px; padding:6px 14px;
    font-size:11.5px; font-weight:700; color:rgba(255,255,255,.90);
}
.wh-badge i { font-size:10px; }
.wh-right { position:relative; z-index:1; text-align:right; flex-shrink:0; }
.wh-sy-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:rgba(255,255,255,.40); margin-bottom:6px; }
.wh-sy { font-family:'Sora',sans-serif; font-size:32px; font-weight:800; color:#fff; letter-spacing:-1.5px; line-height:1; }
.wh-sy small { display:block; font-size:12px; font-weight:500; color:rgba(255,255,255,.45); letter-spacing:0; margin-top:4px; font-family:'Plus Jakarta Sans',sans-serif; }

/* Section heading */
.sec-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
.sec-label { font-family:'Sora',sans-serif; font-size:13px; font-weight:700; color:var(--text); display:flex; align-items:center; gap:9px; }
.sec-label-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.sec-label-dot.blue  { background:var(--primary); }
.sec-label-dot.red   { background:var(--red); }
.sec-label-dot.green { background:var(--success); }

/* KPI Cards */
.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
.kpi {
    background:#fff; border:1px solid var(--border); border-radius:14px;
    box-shadow:var(--shadow); padding:20px;
    display:flex; flex-direction:column;
    position:relative; overflow:hidden;
    transition:transform .2s, box-shadow .2s;
}
.kpi:hover { transform:translateY(-3px); box-shadow:var(--shadow-lg); }
.kpi-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px; }
.kpi-icon-wrap { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; }
.kpi-trend { font-size:11px; font-weight:700; padding:3px 9px; border-radius:99px; }
.kpi-number { font-family:'Sora',sans-serif; font-size:34px; font-weight:800; line-height:1; letter-spacing:-2px; margin-bottom:5px; }
.kpi-label  { font-size:12px; font-weight:600; color:var(--muted); }
.kpi-sub    { font-size:11px; font-weight:600; margin-top:6px; padding-top:6px; border-top:1px solid var(--border); }
.kpi.blue .kpi-icon-wrap { background:rgba(0,58,140,.1); color:#003A8C; }
.kpi.blue .kpi-number    { color:#003A8C; }
.kpi.blue .kpi-trend     { background:rgba(0,58,140,.08); color:#003A8C; }
.kpi.green .kpi-icon-wrap { background:rgba(13,148,72,.1); color:var(--success); }
.kpi.green .kpi-number    { color:var(--success); }
.kpi.green .kpi-sub       { color:var(--success); }
.kpi.amber .kpi-icon-wrap { background:rgba(217,119,6,.1); color:var(--warning); }
.kpi.amber .kpi-number    { color:var(--warning); }
.kpi.amber .kpi-sub       { color:var(--warning); }
.kpi.red  .kpi-icon-wrap  { background:rgba(200,16,46,.1); color:var(--red); }
.kpi.red  .kpi-number     { color:var(--red); }
.kpi.red  .kpi-sub        { color:var(--red); }
.kpi::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; }
.kpi.blue::after  { background:linear-gradient(90deg,#003A8C,#1a52b3); }
.kpi.green::after { background:linear-gradient(90deg,var(--success),#34d399); }
.kpi.amber::after { background:linear-gradient(90deg,var(--warning),#fbbf24); }
.kpi.red::after   { background:linear-gradient(90deg,#C8102E,#ef4444); }

/* Bottom two-col */
.dash-bottom { display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start; }
.d-card { background:#fff; border:1px solid var(--border); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
.d-head { display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-bottom:1px solid var(--border); background:linear-gradient(90deg,#fafbff,#f7f9ff); }
.d-title { display:flex; align-items:center; gap:8px; font-size:13.5px; font-weight:700; color:var(--text); font-family:'Sora',sans-serif; }

/* Quarterly items */
.qi { border:1px solid var(--border); border-radius:11px; overflow:hidden; transition:box-shadow .15s; margin-bottom:10px; }
.qi:last-of-type { margin-bottom:0; }
.qi:hover { box-shadow:0 3px 12px rgba(0,40,140,.09); }
.qi.current-q { border-color:#93c5fd; background:#eff6ff; }
.qi.future-q  { opacity:.6; }
.qi-top { display:flex; align-items:center; justify-content:space-between; padding:10px 14px 7px; }
.qi-label { display:flex; align-items:center; gap:8px; font-size:12.5px; font-weight:700; color:var(--text); }
.qi-now-tag { font-size:8.5px; font-weight:800; background:#003A8C; color:#fff; border-radius:4px; padding:1px 6px; text-transform:uppercase; letter-spacing:.3px; }
.qi-count { font-size:13px; font-weight:800; color:var(--text); }
.qi-count span { font-size:10.5px; font-weight:600; color:var(--muted); }
.qi-bar-wrap { margin:0 14px; background:#f1f5f9; border-radius:99px; height:7px; overflow:hidden; }
.qi-bar-fill { height:100%; border-radius:99px; transition:width .6s cubic-bezier(.4,0,.2,1); }
.qi-bottom { display:flex; align-items:center; justify-content:space-between; padding:7px 14px 11px; flex-wrap:wrap; gap:5px; }
.qi-scores { display:flex; gap:7px; }
.qi-chip { font-size:11px; font-weight:700; padding:2px 9px; border-radius:6px; }
.qi-chip.good { background:rgba(13,148,72,.12); color:var(--success); }
.qi-chip.warn { background:rgba(217,119,6,.12);  color:var(--warning); }
.qi-chip.bad  { background:rgba(200,16,46,.12);   color:var(--red); }
.qi-rpills { display:flex; gap:5px; }
.qi-rp { font-size:10px; font-weight:700; padding:1px 6px; border-radius:4px; display:flex; align-items:center; gap:3px; }
.qi-rp.g { background:rgba(13,148,72,.12); color:var(--success); }
.qi-rp.a { background:rgba(217,119,6,.12);  color:var(--warning); }
.qi-rp.r { background:rgba(200,16,46,.12);  color:var(--red); }
.qi-upcoming { padding:7px 14px 11px; font-size:11.5px; color:#cbd5e1; text-align:center; }

/* Attention rows */
.attn-item { display:flex; align-items:center; gap:12px; padding:12px 20px; border-bottom:1px solid #f4f6fb; transition:background .12s; }
.attn-item:hover { background:#fafbff; }
.attn-item:last-of-type { border-bottom:none; }
.attn-name { font-weight:700; font-size:13px; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.attn-sub  { font-size:11px; color:var(--muted); margin-top:1px; }
.attn-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; color:var(--muted); padding:40px 24px; }

/* Quick links */
.qa-item { display:flex; align-items:center; gap:14px; padding:14px 20px; border-bottom:1px solid #f4f6fb; text-decoration:none; transition:background .15s; }
.qa-item:last-child { border-bottom:none; }
.qa-item:hover { background:#f7f9ff; }
.qa-ico { width:40px; height:40px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.qa-ico.blue  { background:rgba(0,58,140,.1);  color:#003A8C; }
.qa-ico.green { background:rgba(13,148,72,.1);  color:var(--success); }
.qa-ico.amber { background:rgba(217,119,6,.12); color:var(--warning); }
.qa-ico.red   { background:rgba(200,16,46,.1);  color:var(--red); }
.qa-name { font-weight:700; font-size:13.5px; color:var(--text); }
.qa-desc { font-size:11.5px; color:var(--muted); margin-top:1px; }

/* Entry animation */
.dash-page > * { animation: fadeUp .35s ease both; }
.dash-page > *:nth-child(1){ animation-delay:.04s }
.dash-page > *:nth-child(2){ animation-delay:.08s }
.dash-page > *:nth-child(3){ animation-delay:.12s }
.dash-page > *:nth-child(4){ animation-delay:.16s }
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }

@media(max-width:1200px){ .kpi-grid{grid-template-columns:repeat(2,1fr)} }
@media(max-width:900px){ .dash-bottom{grid-template-columns:1fr} .wh-right{display:none} }
@media(max-width:600px){ .kpi-grid{grid-template-columns:1fr 1fr} }
</style>
@endpush

@section('content')
@php
    $riskTotal = max($meeting + $approaching + $below, 1);
    $fluencyTarget = 85;
    $compTarget    = 80;
    $phTime = now()->setTimezone('Asia/Manila');
    $hour   = (int) $phTime->format('H');
    $greeting = $hour < 12 ? 'Morning' : ($hour < 18 ? 'Afternoon' : 'Evening');
@endphp

<div class="dash-page">

{{-- Welcome Hero --}}
<div class="welcome-hero">
    <div style="position:relative;z-index:1;">
        <div class="wh-eyebrow"><i class="fas fa-chalkboard-teacher" style="margin-right:5px;"></i> Teacher · Tampugo Elementary School</div>
        <div class="wh-name">Good {{ $greeting }}, <span>{{ Str::words(auth()->user()->name, 1, '') }}!</span></div>
        <div class="wh-sub">
            Here's your class reading progress overview for S.Y. {{ $year }}–{{ $year + 1 }}.
            @if(!$isCurrentYear)
                <span style="margin-left:6px;background:rgba(255,193,7,.25);color:#856404;font-size:10px;font-weight:700;padding:1px 8px;border-radius:99px;">
                    Past Year
                </span>
            @endif
        </div>
        <div class="wh-badges">
            <div class="wh-badge"><i class="fas fa-user-graduate"></i> {{ $totalStudents }} Student{{ $totalStudents !== 1 ? 's' : '' }}</div>
            <div class="wh-badge"><i class="fas fa-star"></i> {{ $meeting }} Meeting Standard</div>
            @if($below > 0)
            <div class="wh-badge" style="background:rgba(200,16,46,.22);border-color:rgba(200,16,46,.35);color:#fca5a5;">
                <i class="fas fa-exclamation-triangle"></i> {{ $below }} Need Attention
            </div>
            @endif
        </div>
    </div>
    <div class="wh-right">
        <div class="wh-sy-label">School Year</div>
        <div class="wh-sy">{{ $year }}<small>– {{ $year + 1 }}</small></div>
        @if(!$isCurrentYear)
            <div style="font-size:9px;font-weight:700;color:rgba(255,193,7,.9);margin-top:2px;letter-spacing:.5px;">PAST YEAR</div>
        @endif
        <div style="font-size:11px;color:rgba(255,255,255,.40);margin-top:8px;font-weight:500;">
            {{ $phTime->format('l, F j') }}
        </div>
    </div>
</div>

{{-- KPI Cards --}}
<div>
    <div class="sec-row">
        <div class="sec-label"><span class="sec-label-dot blue"></span> Class Overview</div>
        <a href="{{ route('teacher.students.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-users"></i> View All Students</a>
    </div>
    <div class="kpi-grid">
        <div class="kpi blue">
            <div class="kpi-top">
                <div class="kpi-icon-wrap"><i class="fas fa-user-graduate"></i></div>
                <span class="kpi-trend"><i class="fas fa-users"></i> My Class</span>
            </div>
            <div class="kpi-number">{{ $totalStudents }}</div>
            <div class="kpi-label">Total Students</div>
        </div>
        <div class="kpi green">
            <div class="kpi-top">
                <div class="kpi-icon-wrap"><i class="fas fa-star"></i></div>
                <span class="kpi-trend" style="background:rgba(13,148,72,.08);color:var(--success);">{{ $riskTotal > 0 ? round($meeting/$riskTotal*100) : 0 }}%</span>
            </div>
            <div class="kpi-number">{{ $meeting }}</div>
            <div class="kpi-label">Meeting Standard</div>
            <div class="kpi-sub"><i class="fas fa-check-circle"></i> Reading proficiency achieved</div>
        </div>
        <div class="kpi amber">
            <div class="kpi-top">
                <div class="kpi-icon-wrap"><i class="fas fa-chart-line"></i></div>
                <span class="kpi-trend" style="background:rgba(217,119,6,.08);color:var(--warning);">{{ $riskTotal > 0 ? round($approaching/$riskTotal*100) : 0 }}%</span>
            </div>
            <div class="kpi-number">{{ $approaching }}</div>
            <div class="kpi-label">Approaching Standard</div>
            <div class="kpi-sub"><i class="fas fa-chart-line"></i> Improvement in progress</div>
        </div>
        <div class="kpi red">
            <div class="kpi-top">
                <div class="kpi-icon-wrap"><i class="fas fa-exclamation-triangle"></i></div>
                <span class="kpi-trend" style="background:rgba(200,16,46,.08);color:var(--red);">{{ $riskTotal > 0 ? round($below/$riskTotal*100) : 0 }}%</span>
            </div>
            <div class="kpi-number">{{ $below }}</div>
            <div class="kpi-label">Below Standard</div>
            @if($below > 0)
            <div class="kpi-sub"><i class="fas fa-exclamation-circle"></i> Needs intervention</div>
            @endif
        </div>
    </div>
</div>

{{-- Bottom two-col --}}
<div class="dash-bottom">

    {{-- Quarterly Progress --}}
    <div class="d-card">
        <div class="d-head">
            <div class="d-title"><i class="fas fa-calendar-alt" style="color:var(--primary);"></i> Quarterly Progress</div>
            <div style="display:flex;align-items:center;gap:8px;">
                @if(!$isCurrentYear)
                    <span style="font-size:10px;font-weight:700;background:#fff3cd;color:#856404;border:1px solid #ffc107;border-radius:99px;padding:2px 10px;">
                        <i class="fas fa-history" style="font-size:9px;"></i> Viewing Past Year
                    </span>
                @endif
                <form method="GET" action="{{ route('teacher.dashboard') }}" style="margin:0;">
                    <select name="sy" onchange="this.form.submit()"
                        style="font-size:11px;font-weight:700;background:rgba(0,58,140,.08);color:var(--primary);border:1.5px solid rgba(0,58,140,.18);border-radius:99px;padding:3px 12px;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;padding-right:24px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23003A8C'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;">
                        @foreach($availableYears as $sy)
                            <option value="{{ $sy }}" {{ $sy == $year ? 'selected' : '' }}>
                                S.Y. {{ $sy }}–{{ $sy + 1 }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        <div style="padding:16px 20px;display:flex;flex-direction:column;gap:10px;">
            @foreach($quarterlyData as $q)
            @php
                $has = $q['assessments'] > 0;
                $bc  = $q['pct'] >= 80 ? '#0d9448' : ($q['pct'] >= 50 ? '#d97706' : ($q['pct'] > 0 ? '#C8102E' : '#e2e8f0'));
                $fc  = !$has ? '' : ($q['avg_fluency'] >= $fluencyTarget ? 'good' : ($q['avg_fluency'] >= 70 ? 'warn' : 'bad'));
                $cc  = !$has ? '' : ($q['avg_comp']    >= $compTarget    ? 'good' : ($q['avg_comp']    >= 65 ? 'warn' : 'bad'));
            @endphp
            <div class="qi {{ $q['is_current'] ? 'current-q' : ($q['is_future'] ? 'future-q' : '') }}">
                <div class="qi-top">
                    <div class="qi-label">
                        {{ $q['label'] }}
                        @if($q['is_current'])<span class="qi-now-tag">Current</span>@endif
                    </div>
                    <div class="qi-count">{{ $q['assessments'] }} <span>assessed</span></div>
                </div>
                <div class="qi-bar-wrap">
                    <div class="qi-bar-fill" style="width:{{ $q['pct'] }}%;background:{{ $bc }};"></div>
                </div>
                @if($q['is_future'] && !$has)
                    <div class="qi-upcoming"><i class="fas fa-lock" style="margin-right:5px;"></i>Upcoming quarter</div>
                @else
                    <div class="qi-bottom">
                        <div class="qi-scores">
                            @if($has)
                                <span class="qi-chip {{ $fc }}"><i class="fas fa-book" style="font-size:9px;margin-right:3px;"></i>{{ $q['avg_fluency'] }}% flu.</span>
                                <span class="qi-chip {{ $cc }}"><i class="fas fa-brain" style="font-size:9px;margin-right:3px;"></i>{{ $q['avg_comp'] }}% comp.</span>
                            @else
                                <span style="font-size:11px;color:#cbd5e1;">No data yet</span>
                            @endif
                        </div>
                        @if($has)
                        <div class="qi-rpills">
                            @if($q['meeting']>0)   <span class="qi-rp g"><i class="fas fa-check" style="font-size:8px;"></i>{{ $q['meeting'] }}</span>@endif
                            @if($q['approaching']>0)<span class="qi-rp a"><i class="fas fa-minus" style="font-size:8px;"></i>{{ $q['approaching'] }}</span>@endif
                            @if($q['below']>0)     <span class="qi-rp r"><i class="fas fa-exclamation" style="font-size:8px;"></i>{{ $q['below'] }}</span>@endif
                        </div>
                        @endif
                    </div>
                @endif
            </div>
            @endforeach

            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0 0;border-top:1px solid #f1f5f9;margin-top:4px;">
                <span style="font-size:12px;color:var(--muted);font-weight:600;">Year-to-date total</span>
                <span style="font-family:'Sora',sans-serif;font-size:14px;font-weight:800;color:#003A8C;">{{ $ytdAssessments }} assessments</span>
            </div>
        </div>
    </div>

    {{-- Right column: Needs Attention + Quick Links --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Needs Attention --}}
        <div class="d-card">
            <div class="d-head">
                <div class="d-title">
                    <i class="fas fa-exclamation-circle" style="color:var(--red);"></i> Needs Attention
                    @if($studentsNeedingAttention->count() > 0)
                    <span style="background:#fee2e2;color:var(--red);font-size:10px;font-weight:800;padding:2px 8px;border-radius:99px;">{{ $studentsNeedingAttention->count() }}</span>
                    @endif
                </div>
                <a href="{{ route('teacher.students.index') }}" class="btn btn-outline btn-sm">All Students</a>
            </div>
            @forelse($studentsNeedingAttention as $student)
            <div class="attn-item">
                <img src="{{ $student->profilePhotoUrl() }}"
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid #fecdd3;">
                <div style="flex:1;min-width:0;">
                    <div class="attn-name">{{ $student->fullName() }}</div>
                    <div class="attn-sub">
                        Fluency: <strong style="color:var(--red);">{{ $student->latestAssessment?->fluency_score ?? '—' }}%</strong>
                        &nbsp;·&nbsp;
                        Comp: <strong style="color:var(--red);">{{ $student->latestAssessment?->comprehension_score ?? '—' }}%</strong>
                    </div>
                </div>
                <span class="badge badge-danger" style="flex-shrink:0;font-size:10px;">Below</span>
                <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}"
                   class="btn btn-primary btn-xs" style="flex-shrink:0;">Assess</a>
            </div>
            @empty
            <div class="attn-empty">
                <i class="fas fa-check-circle" style="color:var(--success);font-size:36px;"></i>
                <strong style="color:var(--success);font-size:13px;">All students on track!</strong>
                <span style="font-size:12px;text-align:center;color:var(--muted);">No students are currently below standard.</span>
            </div>
            @endforelse
            <div style="padding:12px 20px;border-top:1px solid var(--border-light);">
                <a href="{{ route('teacher.assessments.create') }}" class="btn btn-primary" style="width:100%;justify-content:center;">
                    <i class="fas fa-plus"></i> Record New Assessment
                </a>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="d-card">
            <div class="d-head">
                <div class="d-title"><i class="fas fa-bolt" style="color:var(--warning);"></i> Quick Actions</div>
            </div>
            <a href="{{ route('teacher.students.create') }}" class="qa-item">
                <div class="qa-ico blue"><i class="fas fa-user-plus"></i></div>
                <div><div class="qa-name">Add New Student</div><div class="qa-desc">Register a student to your class</div></div>
                <i class="fas fa-chevron-right" style="color:var(--muted-light);font-size:11px;margin-left:auto;"></i>
            </a>
            <a href="{{ route('teacher.assessments.create') }}" class="qa-item">
                <div class="qa-ico green"><i class="fas fa-clipboard-check"></i></div>
                <div><div class="qa-name">Record Assessment</div><div class="qa-desc">Log a new reading session</div></div>
                <i class="fas fa-chevron-right" style="color:var(--muted-light);font-size:11px;margin-left:auto;"></i>
            </a>
            <a href="{{ route('teacher.interventions.index') }}" class="qa-item">
                <div class="qa-ico amber"><i class="fas fa-hands-helping"></i></div>
                <div><div class="qa-name">View Interventions</div><div class="qa-desc">Manage active reading support plans</div></div>
                <i class="fas fa-chevron-right" style="color:var(--muted-light);font-size:11px;margin-left:auto;"></i>
            </a>
            <a href="{{ route('teacher.reports.index') }}" class="qa-item">
                <div class="qa-ico red"><i class="fas fa-chart-line"></i></div>
                <div><div class="qa-name">Analytics & Reports</div><div class="qa-desc">View class progress charts</div></div>
                <i class="fas fa-chevron-right" style="color:var(--muted-light);font-size:11px;margin-left:auto;"></i>
            </a>
        </div>

    </div>

</div>

</div>
@endsection