@extends('layouts.teacher')

@section('title', $student->fullName())
@section('page-icon', '🎓')
@section('page-heading', 'Student Profile')

@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
.scroll-body { flex: 1; min-height: 0; overflow-y: auto; overflow-x: hidden; padding-bottom: 24px; }
.scroll-body::-webkit-scrollbar { width: 5px; }
.scroll-body::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }
.profile-grid { display: grid; grid-template-columns: 320px 1fr; gap: 20px; align-items: start; }
.risk-bar { height: 8px; background: #f1f5f9; border-radius: 99px; overflow: hidden; margin-top: 6px; }
.risk-bar-fill { height: 100%; border-radius: 99px; }
@media(max-width:900px) { .profile-grid { grid-template-columns: 1fr; } }

/* ── Print-only elements hidden on screen ── */
.print-only { display: none; }

/* ══════════════════════════════════════
   STUDENT PROFILE PRINT STYLES
   ══════════════════════════════════════ */
@media print {
    @page { size: A4 portrait; margin: 14mm 14mm 16mm 14mm; }

    /* Hide all screen UI */
    html, body { overflow: visible !important; height: auto !important; background: #fff !important; }
    .sidebar, .top-header, .page-header, .scroll-body > .profile-grid { display: none !important; }
    .main-area { margin-left: 0 !important; padding: 0 !important; }
    .page-content { padding: 0 !important; overflow: visible !important; display: block !important; }

    /* Show print-only content */
    .print-only { display: block !important; }

    /* ── Formal DepEd header ── */
    .p-header {
        display: flex !important;
        align-items: center;
        gap: 12px;
        padding-bottom: 10px;
        border-bottom: 2.5px solid #003A8C;
        margin-bottom: 5px;
    }
    .p-header-logo { width: 60px; height: 60px; object-fit: contain; flex-shrink: 0; }
    .p-header-center { flex: 1; text-align: center; }
    .p-header-republic { font-size: 8pt; color: #222; }
    .p-header-dept  { font-size: 12pt; font-weight: 800; color: #003A8C; text-transform: uppercase; letter-spacing: .4px; line-height: 1.2; }
    .p-header-div   { font-size: 8.5pt; color: #111; font-weight: 600; margin-top: 1px; }
    .p-header-school { font-size: 8pt; color: #555; margin-top: 1px; }

    /* ── Document title ── */
    .p-doc-title {
        text-align: center;
        padding: 7px 0 8px;
        border-bottom: 2px solid #003A8C;
        margin-bottom: 10px;
    }
    .p-doc-title h2 { font-size: 12pt; font-weight: 800; color: #003A8C; text-transform: uppercase; letter-spacing: .8px; margin: 0 0 2px; }
    .p-doc-title p  { font-size: 7.5pt; color: #666; margin: 0; }

    /* ── Student hero section ── */
    .p-student-hero {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 10px 14px;
        background: #f0f4ff;
        border: 1px solid #d0d7e8;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .p-avatar { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid #c8d5f0; flex-shrink: 0; }
    .p-student-name { font-size: 14pt; font-weight: 800; color: #003A8C; margin: 0 0 3px; }
    .p-student-badges { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 4px; }
    .p-badge {
        font-size: 7.5pt; font-weight: 700; padding: 2px 8px; border-radius: 5px;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .p-badge-blue  { background: #dbeafe; color: #1d4ed8; }
    .p-badge-green { background: #dcfce7; color: #15803d; }

    /* ── Two-column info grid ── */
    .p-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
    .p-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 10px; }

    /* ── Info card ── */
    .p-card {
        border: 1px solid #d0d7e8;
        border-radius: 8px;
        overflow: hidden;
        break-inside: avoid;
        page-break-inside: avoid;
    }
    .p-card-head {
        padding: 6px 12px;
        background: #003A8C;
        color: #fff;
        font-size: 8pt;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .7px;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .p-card-body { padding: 10px 12px; }

    /* ── Info row ── */
    .p-info-row { display: flex; gap: 8px; margin-bottom: 7px; }
    .p-info-row:last-child { margin-bottom: 0; }
    .p-info-label { font-size: 7pt; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .5px; width: 90px; flex-shrink: 0; padding-top: 1px; }
    .p-info-value { font-size: 9pt; font-weight: 600; color: #0f172a; }

    /* ── Risk status banner ── */
    .p-risk-banner {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 12px; border-radius: 6px; margin-bottom: 8px;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .p-risk-banner.meeting    { background: rgba(13,148,72,.10); border: 1px solid rgba(13,148,72,.25); }
    .p-risk-banner.approaching { background: rgba(196,125,14,.10); border: 1px solid rgba(196,125,14,.25); }
    .p-risk-banner.below      { background: rgba(200,16,46,.10); border: 1px solid rgba(200,16,46,.25); }
    .p-risk-icon { font-size: 15pt; }
    .p-risk-label { font-size: 10pt; font-weight: 800; }
    .p-risk-sub   { font-size: 7.5pt; color: #555; margin-top: 1px; }

    /* ── Score bars ── */
    .p-score-row { margin-bottom: 9px; }
    .p-score-row:last-child { margin-bottom: 0; }
    .p-score-top { display: flex; justify-content: space-between; margin-bottom: 3px; }
    .p-score-label { font-size: 8pt; font-weight: 700; color: #334155; }
    .p-score-val   { font-size: 8.5pt; font-weight: 800; }
    .p-bar { height: 9px; background: #f1f5f9; border-radius: 99px; overflow: hidden; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .p-bar-fill { height: 100%; border-radius: 99px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

    /* ── Assessment history table ── */
    .p-table { width: 100%; border-collapse: collapse; font-size: 8pt; }
    .p-table thead th {
        background: #eef2ff; color: #1e3a6e;
        padding: 6px 10px; text-align: left;
        font-size: 7.5pt; font-weight: 800; text-transform: uppercase; letter-spacing: .5px;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .p-table thead th:not(:first-child) { text-align: center; }
    .p-table tbody td { padding: 7px 10px; border-bottom: 1px solid #f0f4fb; vertical-align: middle; }
    .p-table tbody td:not(:first-child) { text-align: center; }
    .p-table tbody tr:last-child td { border-bottom: none; }
    .p-chip { display: inline-block; font-size: 7pt; font-weight: 700; padding: 2px 7px; border-radius: 5px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .p-chip-g { background: rgba(13,148,72,.12);  color: #0d6634; }
    .p-chip-a { background: rgba(196,125,14,.12); color: #92570a; }
    .p-chip-r { background: rgba(200,16,46,.12);  color: #9b0f24; }

    /* ── Footer signature ── */
    .p-footer {
        margin-top: 14px;
        padding-top: 8px;
        border-top: 2px solid #003A8C;
        page-break-inside: avoid;
    }
    .p-footer-meta { text-align: right; font-size: 7pt; color: #777; margin-bottom: 10px; }
    .p-sig-row { display: flex; justify-content: space-between; gap: 20px; }
    .p-sig-col { flex: 1; }
    .p-sig-label { font-size: 7pt; color: #666; display: block; margin-bottom: 18px; }
    .p-sig-line  { border-top: 1px solid #444; padding-top: 3px; }
    .p-sig-name  { font-size: 9pt; font-weight: 800; color: #003A8C; display: block; }
    .p-sig-title { font-size: 7pt; color: #555; display: block; margin-top: 1px; }
    .p-disclaimer { text-align: center; font-size: 6.5pt; color: #999; margin-top: 8px; padding-top: 5px; border-top: 1px solid #e2e8f0; }

    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════
     PRINT-ONLY: FORMAL DEPED STUDENT PROFILE REPORT
     Hidden on screen, visible only when printing
     ══════════════════════════════════════════════ --}}
@php
    $latest        = $student->assessments->first();
    $risk          = $latest?->risk_level;
    $isBelow       = str_contains($risk ?? '', 'Below');
    $isApproaching = str_contains($risk ?? '', 'Approaching');
    $riskClass     = $isBelow ? 'below' : ($isApproaching ? 'approaching' : 'meeting');
    $riskColor     = $isBelow ? '#C8102E' : ($isApproaching ? '#c47d0e' : '#0d9448');
    $riskIcon      = $isBelow ? '⚠' : ($isApproaching ? '📈' : '⭐');
@endphp

<div class="print-only">

    {{-- DepEd formal header --}}
    <div class="p-header">
        <img src="{{ asset('images/TES-logo.jpg') }}" class="p-header-logo" alt="School Logo">
        <div class="p-header-center">
            <div class="p-header-republic">Republic of the Philippines</div>
            <div class="p-header-dept">Department of Education</div>
            <div class="p-header-div">Schools Division of Ilocos Sur</div>
            <div class="p-header-school">Tampugo Elementary School &bull; Tampugo, Tagudin, Ilocos Sur</div>
        </div>
        <img src="{{ asset('images/TES-logo.jpg') }}" class="p-header-logo" alt="School Logo" style="opacity:0;">
    </div>

    {{-- Document title --}}
    <div class="p-doc-title">
        <h2>Student Reading Progress Report</h2>
        <p>TESRead &mdash; Digital Reading Progress Monitoring System &bull; <span id="pPrintDate"></span></p>
    </div>

    {{-- Student hero --}}
    <div class="p-student-hero">
        <img src="{{ $student->profilePhotoUrl() }}" class="p-avatar" alt="{{ $student->fullName() }}">
        <div style="flex:1;">
            <div class="p-student-name">{{ $student->fullName() }}</div>
            <div style="font-size:8.5pt;color:#334155;margin-bottom:4px;">
                LRN: <strong>{{ $student->lrn ?? '—' }}</strong>
                &nbsp;&bull;&nbsp; Gender: <strong>{{ $student->gender ?? '—' }}</strong>
                &nbsp;&bull;&nbsp; Birthdate: <strong>{{ $student->birthdate?->format('F d, Y') ?? '—' }}</strong>
            </div>
            <div class="p-student-badges">
                @if($student->section)
                    <span class="p-badge p-badge-blue">
                        Grade {{ $student->section->grade_level }} – {{ $student->section->name }}
                    </span>
                @endif
                <span class="p-badge p-badge-green">{{ $student->philIriLabel() }}</span>
                @if($student->teacher)
                    <span class="p-badge" style="background:#fef3c7;color:#92400e;">
                        Teacher: {{ $student->teacher->name }}
                    </span>
                @endif
            </div>
        </div>
        <div style="text-align:right;font-size:7.5pt;color:#64748b;line-height:1.8;">
            <div>Enrolled: <strong>{{ $student->created_at->format('M d, Y') }}</strong></div>
            <div>Total Assessments: <strong>{{ $student->assessments->count() }}</strong></div>
        </div>
    </div>

    {{-- Reading Status + Student Info side by side --}}
    <div class="p-grid-2">

        {{-- Reading Status --}}
        <div class="p-card">
            <div class="p-card-head">📊 Current Reading Status</div>
            <div class="p-card-body">
                @if($latest)
                    <div class="p-risk-banner {{ $riskClass }}">
                        <span class="p-risk-icon">{{ $riskIcon }}</span>
                        <div>
                            <div class="p-risk-label" style="color:{{ $riskColor }};">{{ $risk }}</div>
                            <div class="p-risk-sub">Last assessed: {{ $latest->assessed_on?->format('F d, Y') }}</div>
                        </div>
                    </div>
                    <div class="p-score-row">
                        <div class="p-score-top">
                            <span class="p-score-label">Fluency Score</span>
                            <span class="p-score-val" style="color:{{ $latest->fluency_score >= 85 ? '#0d9448' : ($latest->fluency_score >= 70 ? '#c47d0e' : '#C8102E') }};">
                                {{ $latest->fluency_score }}% <small style="font-size:7pt;font-weight:400;color:#94a3b8;">(Target: 85%)</small>
                            </span>
                        </div>
                        <div class="p-bar">
                            <div class="p-bar-fill" style="width:{{ $latest->fluency_score }}%;background:{{ $latest->fluency_score >= 85 ? '#0d9448' : ($latest->fluency_score >= 70 ? '#c47d0e' : '#C8102E') }};"></div>
                        </div>
                    </div>
                    <div class="p-score-row">
                        <div class="p-score-top">
                            <span class="p-score-label">Comprehension Score</span>
                            <span class="p-score-val" style="color:{{ $latest->comprehension_score >= 80 ? '#0d9448' : ($latest->comprehension_score >= 65 ? '#c47d0e' : '#C8102E') }};">
                                {{ $latest->comprehension_score }}% <small style="font-size:7pt;font-weight:400;color:#94a3b8;">(Target: 80%)</small>
                            </span>
                        </div>
                        <div class="p-bar">
                            <div class="p-bar-fill" style="width:{{ $latest->comprehension_score }}%;background:{{ $latest->comprehension_score >= 80 ? '#0d9448' : ($latest->comprehension_score >= 65 ? '#c47d0e' : '#C8102E') }};"></div>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;
                                margin-top:9px;padding:6px 10px;background:#f8faff;border-radius:6px;">
                        <span style="font-size:8pt;font-weight:600;">Reading Sessions / Week</span>
                        <span class="p-chip {{ $latest->reading_sessions_per_week <= 1 ? 'p-chip-r' : 'p-chip-g' }}">
                            {{ $latest->reading_sessions_per_week }} / week
                        </span>
                    </div>
                @else
                    <p style="font-size:9pt;color:#94a3b8;text-align:center;padding:12px 0;">No assessments recorded yet.</p>
                @endif
            </div>
        </div>

        {{-- Active Interventions --}}
        <div class="p-card">
            <div class="p-card-head" style="background:{{ $student->interventions->where('status','Active')->count() ? '#C8102E' : '#003A8C' }};">
                🔔 Intervention Status
            </div>
            <div class="p-card-body">
                @php $activeIvs = $student->interventions->where('status','Active'); @endphp
                @if($activeIvs->count())
                    @foreach($activeIvs as $iv)
                        <div style="margin-bottom:8px;padding:8px 10px;background:#fff5f6;border-radius:6px;border:1px solid #fecdd3;">
                            <div style="font-size:8pt;font-weight:700;color:#C8102E;margin-bottom:3px;">
                                ⚠ Active Intervention
                            </div>
                            <div style="font-size:8pt;color:#374151;line-height:1.5;">
                                {{ Str::limit($iv->intervention_notes, 180) }}
                            </div>
                            <div style="font-size:7pt;color:#94a3b8;margin-top:4px;">
                                Started: {{ $iv->started_on?->format('M d, Y') }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="padding:10px 0;text-align:center;">
                        <div style="font-size:18pt;margin-bottom:4px;">✅</div>
                        <div style="font-size:8.5pt;font-weight:700;color:#0d9448;">No Active Interventions</div>
                        <div style="font-size:7.5pt;color:#94a3b8;margin-top:2px;">Student is progressing well</div>
                    </div>
                @endif

                <div style="margin-top:10px;padding-top:8px;border-top:1px solid #f1f5f9;">
                    <div style="font-size:7.5pt;font-weight:700;color:#64748b;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Intervention Summary</div>
                    <div style="display:flex;gap:8px;">
                        @foreach([['Active','#C8102E'],['Completed','#0d9448'],['Cancelled','#94a3b8']] as [$st,$clr])
                            <div style="flex:1;padding:5px 8px;border-radius:6px;background:#f8faff;text-align:center;">
                                <div style="font-size:11pt;font-weight:800;color:{{ $clr }};">
                                    {{ $student->interventions->where('status',$st)->count() }}
                                </div>
                                <div style="font-size:6.5pt;color:#94a3b8;font-weight:600;">{{ $st }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Assessment History table --}}
    <div class="p-card" style="margin-bottom:12px;">
        <div class="p-card-head">📋 Assessment History ({{ $student->assessments->count() }} records)</div>
        <div class="p-card-body" style="padding:0;">
            @if($student->assessments->count())
                <table class="p-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Fluency</th>
                            <th>Comprehension</th>
                            <th>Sessions/wk</th>
                            <th>Risk Level</th>
                            <th>Assessed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->assessments as $a)
                        <tr>
                            <td style="font-weight:600;">{{ $a->assessed_on?->format('M d, Y') ?? '—' }}</td>
                            <td>
                                <span class="p-chip {{ $a->fluency_score >= 85 ? 'p-chip-g' : ($a->fluency_score >= 70 ? 'p-chip-a' : 'p-chip-r') }}">
                                    {{ $a->fluency_score }}%
                                </span>
                            </td>
                            <td>
                                <span class="p-chip {{ $a->comprehension_score >= 80 ? 'p-chip-g' : ($a->comprehension_score >= 65 ? 'p-chip-a' : 'p-chip-r') }}">
                                    {{ $a->comprehension_score }}%
                                </span>
                            </td>
                            <td>
                                <span class="p-chip {{ $a->reading_sessions_per_week <= 1 ? 'p-chip-r' : 'p-chip-g' }}">
                                    {{ $a->reading_sessions_per_week }}/wk
                                </span>
                            </td>
                            <td>
                                @if(str_contains($a->risk_level ?? '','Below'))
                                    <span class="p-chip p-chip-r">Below Standard</span>
                                @elseif(str_contains($a->risk_level ?? '','Approaching'))
                                    <span class="p-chip p-chip-a">Approaching</span>
                                @else
                                    <span class="p-chip p-chip-g">Meeting</span>
                                @endif
                            </td>
                            <td style="font-size:7.5pt;color:#64748b;">{{ $a->teacher?->name ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="padding:16px;text-align:center;font-size:8.5pt;color:#94a3b8;">No assessments recorded.</p>
            @endif
        </div>
    </div>

    {{-- Footer with signatures --}}
    <div class="p-footer">
        <div class="p-footer-meta">
            Generated via TESRead System &bull; Tampugo Elementary School &bull; <span id="pPrintDateFooter"></span>
        </div>
        <div class="p-sig-row">
            <div class="p-sig-col">
                <span class="p-sig-label">Prepared by:</span>
                <div class="p-sig-line">
                    <span class="p-sig-name">{{ auth()->user()->name }}</span>
                    <span class="p-sig-title">Class Teacher</span>
                </div>
            </div>
            <div class="p-sig-col" style="text-align:right;">
                <span class="p-sig-label">Noted by:</span>
                <div class="p-sig-line">
                    <span class="p-sig-name">{{ config('school.principal_name', '________________________________') }}</span>
                    <span class="p-sig-title">School Head / Principal</span>
                </div>
            </div>
        </div>
        <div class="p-disclaimer">
            This report is system-generated from TESRead — Digital Reading Progress Monitoring System. For official use only.
            &bull; Department of Education &bull; Schools Division of Ilocos Sur &bull; Tampugo Elementary School
        </div>
    </div>

</div>{{-- /print-only --}}
<div style="display:flex;flex-direction:column;height:100%;">
<div class="page-header">
    <div><h1>Student Profile</h1></div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fas fa-print"></i> Print Profile
        </button>
        <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
            <i class="fas fa-clipboard-check"></i> New Assessment
        </a>
        <a href="{{ route('teacher.students.edit', $student) }}" class="btn btn-outline">
            <i class="fas fa-pencil-alt"></i> Edit
        </a>
        <a href="{{ route('teacher.students.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="scroll-body">
<div class="profile-grid">

    {{-- LEFT: Profile + Active Interventions --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Profile Card --}}
        <div class="card">
            <div style="padding:28px;text-align:center;border-bottom:1px solid var(--border);">
                <img src="{{ $student->profilePhotoUrl() }}"
                     style="width:88px;height:88px;border-radius:50%;object-fit:cover;
                            border:4px solid var(--border);margin:0 auto 14px;display:block;">
                <h2 style="font-size:18px;font-weight:800;margin-bottom:8px;">{{ $student->fullName() }}</h2>
                @if($student->section)
                    <div style="margin-bottom:6px;">
                        <span class="badge badge-info">
                            <i class="fas fa-door-open"></i>
                            Grade {{ $student->section->grade_level }} – {{ $student->section->name }}
                        </span>
                    </div>
                @endif
                <span class="badge badge-primary" style="font-size:12px;">
                    <i class="fas fa-book-open"></i> {{ $student->philIriLabel() }}
                </span>
            </div>
            <div style="padding:18px 22px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:3px;">LRN</div>
                        <div style="font-size:13px;font-weight:600;">{{ $student->lrn ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:3px;">Gender</div>
                        <div style="font-size:13px;font-weight:600;">{{ $student->gender ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:3px;">Birthdate</div>
                        <div style="font-size:13px;font-weight:600;">{{ $student->birthdate?->format('M d, Y') ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:3px;">Enrolled</div>
                        <div style="font-size:13px;font-weight:600;">{{ $student->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Active Interventions --}}
        @php $activeInterventions = $student->interventions->where('status', 'Active'); @endphp
        @if($activeInterventions->count() > 0)
        <div class="card" style="border-left:4px solid var(--danger);">
            <div class="card-header" style="background:rgba(200,16,46,0.04);">
                <div class="card-title" style="color:var(--danger);">
                    <i class="fas fa-bell"></i> Active Interventions
                </div>
                <span class="badge badge-danger">{{ $activeInterventions->count() }}</span>
            </div>
            @foreach($activeInterventions as $intervention)
                <div style="padding:14px 20px;border-bottom:1px solid var(--border);">
                    <div style="font-size:13px;color:var(--text);margin-bottom:6px;line-height:1.6;">
                        {{ Str::limit($intervention->intervention_notes, 120) }}
                    </div>
                    <div style="display:flex;gap:10px;align-items:center;">
                        <span style="font-size:11px;color:var(--muted);">Started: {{ $intervention->started_on?->format('M d, Y') }}</span>
                        <a href="{{ route('teacher.interventions.show', $intervention) }}" class="btn btn-outline btn-xs">View</a>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

    </div>

    {{-- RIGHT: Assessment History + Intervention History --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Assessment History --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-clipboard-list" style="color:var(--primary);"></i>
                    Assessment History ({{ $student->assessments->count() }})
                </div>
                <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New
                </a>
            </div>
            @forelse($student->assessments as $assessment)
                <div style="padding:14px 20px;border-bottom:1px solid var(--border);
                            display:flex;align-items:center;gap:14px;transition:background .12s;"
                     onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background=''">
                    <div style="text-align:center;min-width:46px;">
                        <div style="font-size:18px;font-weight:800;color:var(--primary);line-height:1;">
                            {{ $assessment->assessed_on?->format('d') }}
                        </div>
                        <div style="font-size:10px;color:var(--muted);text-transform:uppercase;">
                            {{ $assessment->assessed_on?->format('M Y') }}
                        </div>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;gap:8px;margin-bottom:5px;flex-wrap:wrap;">
                            <span style="font-size:12px;color:var(--muted);">
                                F: <strong style="color:{{ $assessment->fluency_score >= 85 ? 'var(--success)' : ($assessment->fluency_score >= 70 ? '#b8860b' : 'var(--danger)') }};">{{ $assessment->fluency_score }}%</strong>
                            </span>
                            <span style="font-size:12px;color:var(--muted);">
                                C: <strong style="color:{{ $assessment->comprehension_score >= 80 ? 'var(--success)' : ($assessment->comprehension_score >= 65 ? '#b8860b' : 'var(--danger)') }};">{{ $assessment->comprehension_score }}%</strong>
                            </span>
                            <span style="font-size:12px;color:var(--muted);">
                                <strong>{{ $assessment->reading_sessions_per_week }}/wk</strong>
                            </span>
                        </div>
                        @if($assessment->risk_level)
                            @if(str_contains($assessment->risk_level,'Below'))
                                <span class="badge badge-danger" style="font-size:10.5px;"><i class="fas fa-exclamation-triangle"></i> Below Standard</span>
                            @elseif(str_contains($assessment->risk_level,'Approaching'))
                                <span class="badge badge-warning" style="font-size:10.5px;"><i class="fas fa-chart-line"></i> Approaching</span>
                            @else
                                <span class="badge badge-success" style="font-size:10.5px;"><i class="fas fa-star"></i> Meeting</span>
                            @endif
                        @endif
                    </div>
                    <a href="{{ route('teacher.assessments.show', $assessment) }}" class="btn-icon" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            @empty
                <div class="empty-state" style="padding:36px;">
                    <div class="empty-state-icon">📋</div>
                    <h3>No assessments yet</h3>
                </div>
            @endforelse
        </div>

        {{-- Reading Status --}}
        @php $latest = $student->assessments->first(); @endphp
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-line" style="color:var(--primary);"></i> Reading Status</div>
                @if($latest)<span class="text-muted text-small">{{ $latest->assessed_on?->format('M d, Y') }}</span>@endif
            </div>
            @if($latest)
                @php
                    $risk          = $latest->risk_level;
                    $isBelow       = str_contains($risk ?? '', 'Below');
                    $isApproaching = str_contains($risk ?? '', 'Approaching');
                    $riskColor     = $isBelow ? 'var(--danger)' : ($isApproaching ? '#b8860b' : 'var(--success)');
                    $riskBg        = $isBelow ? 'rgba(200,16,46,0.06)' : ($isApproaching ? 'rgba(255,193,7,0.10)' : 'rgba(40,167,69,0.06)');
                    $riskIcon      = $isBelow ? 'fa-exclamation-triangle' : ($isApproaching ? 'fa-chart-line' : 'fa-star');
                @endphp
                <div style="padding:14px 20px;background:{{ $riskBg }};border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:{{ $riskColor }}20;
                                    display:flex;align-items:center;justify-content:center;">
                            <i class="fas {{ $riskIcon }}" style="color:{{ $riskColor }};font-size:15px;"></i>
                        </div>
                        <div>
                            <div style="font-size:10px;color:var(--muted);font-weight:600;">Risk Level</div>
                            <div style="font-weight:800;color:{{ $riskColor }};font-size:13px;">{{ $risk ?? 'Not evaluated' }}</div>
                        </div>
                    </div>
                </div>
                <div style="padding:16px 22px;">
                    <div style="margin-bottom:12px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span style="font-size:12.5px;font-weight:600;">Fluency</span>
                            <span style="font-weight:800;color:{{ $latest->fluency_score >= 85 ? 'var(--success)' : ($latest->fluency_score >= 70 ? '#b8860b' : 'var(--danger)') }};">{{ $latest->fluency_score }}%</span>
                        </div>
                        <div class="risk-bar"><div class="risk-bar-fill" style="width:{{ $latest->fluency_score }}%;background:{{ $latest->fluency_score >= 85 ? 'var(--success)' : ($latest->fluency_score >= 70 ? 'var(--warning)' : 'var(--danger)') }};"></div></div>
                    </div>
                    <div style="margin-bottom:12px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span style="font-size:12.5px;font-weight:600;">Comprehension</span>
                            <span style="font-weight:800;color:{{ $latest->comprehension_score >= 80 ? 'var(--success)' : ($latest->comprehension_score >= 65 ? '#b8860b' : 'var(--danger)') }};">{{ $latest->comprehension_score }}%</span>
                        </div>
                        <div class="risk-bar"><div class="risk-bar-fill" style="width:{{ $latest->comprehension_score }}%;background:{{ $latest->comprehension_score >= 80 ? 'var(--success)' : ($latest->comprehension_score >= 65 ? 'var(--warning)' : 'var(--danger)') }};"></div></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;
                                padding:10px 14px;background:#f8faff;border-radius:8px;">
                        <span style="font-size:12.5px;font-weight:600;">Sessions / Week</span>
                        <span class="badge {{ $latest->reading_sessions_per_week <= 1 ? 'badge-danger' : 'badge-success' }}">
                            {{ $latest->reading_sessions_per_week }} / week
                        </span>
                    </div>
                </div>
            @else
                <div class="empty-state" style="padding:32px;">
                    <div class="empty-state-icon">📊</div>
                    <h3>No assessments yet</h3>
                    <p>Record this student's first reading assessment.</p>
                    <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}"
                       class="btn btn-primary" style="margin-top:12px;">
                        <i class="fas fa-plus"></i> Assess Now
                    </a>
                </div>
            @endif
        </div>

        {{-- Intervention History --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-hands-helping" style="color:var(--primary);"></i>
                    Intervention History
                </div>
                <a href="{{ route('teacher.interventions.index') }}" class="btn btn-outline btn-sm">View All</a>
            </div>
            @forelse($student->interventions as $intervention)
                <div style="padding:14px 20px;border-bottom:1px solid var(--border);">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                        <span class="badge {{ $intervention->status === 'Active' ? 'badge-danger' : ($intervention->status === 'Completed' ? 'badge-success' : 'badge-secondary') }}">
                            {{ $intervention->status }}
                        </span>
                        <span style="font-size:11px;color:var(--muted);">{{ $intervention->started_on?->format('M d, Y') }}</span>
                    </div>
                    <p style="font-size:13px;color:var(--muted);margin:0;line-height:1.5;">
                        {{ Str::limit($intervention->intervention_notes, 100) }}
                    </p>
                </div>
            @empty
                <div class="empty-state" style="padding:28px;">
                    <div class="empty-state-icon">✅</div>
                    <h3>No interventions</h3>
                    <p>No interventions on record for this student.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
</div>
</div>
@push('scripts')
<script>
const _d = new Date();
const _f = _d.toLocaleDateString('en-PH',{year:'numeric',month:'long',day:'numeric'})
          + ', ' + _d.toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'});
const _e1 = document.getElementById('pPrintDate');
const _e2 = document.getElementById('pPrintDateFooter');
if(_e1) _e1.textContent = _f;
if(_e2) _e2.textContent = _f;
</script>
@endpush

@endsection