@extends('layouts.teacher')
@section('title', 'Analytics & Reports')
@section('page-icon', '📊')
@section('page-heading', 'Analytics & Reports')

@push('styles')
<style>
:root {
    --an-blue:   #003A8C;
    --an-red:    #C8102E;
    --an-green:  #0d9448;
    --an-amber:  #c47d0e;
    --an-gap:    18px;
    --an-pad:    22px;
    --an-radius: 14px;
    --an-card-shadow: 0 1px 4px rgba(0,0,0,.06), 0 4px 16px rgba(0,40,140,.07);
    --an-card-shadow-hover: 0 4px 12px rgba(0,0,0,.08), 0 12px 32px rgba(0,40,140,.12);
}
.an-page { display:flex; flex-direction:column; gap:var(--an-gap); }

/* Toolbar */
.an-toolbar {
    display:flex; align-items:center; flex-wrap:wrap; gap:10px;
    background:#fff; border:1px solid var(--border); border-radius:var(--an-radius);
    padding:12px 18px; box-shadow:var(--an-card-shadow);
}
.an-toolbar-left  { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.an-toolbar-right { display:flex; align-items:center; gap:10px; margin-left:auto; }
.period-toggle { display:flex; background:#f1f5f9; border-radius:9px; padding:3px; gap:2px; }
.pt-btn {
    display:flex; align-items:center; gap:6px; padding:6px 16px;
    border:none; background:transparent; border-radius:7px;
    font-size:12.5px; font-weight:600; color:#64748b; cursor:pointer;
    transition:all .15s; font-family:inherit;
}
.pt-btn.active { background:#fff; color:var(--an-blue); box-shadow:0 1px 5px rgba(0,0,0,.1); }
.yr-select {
    border:1.5px solid var(--border); border-radius:9px; padding:7px 12px;
    font-size:13px; font-weight:600; color:#1e293b; background:#fff;
    cursor:pointer; outline:none; font-family:inherit;
}
.yr-select:focus { border-color:var(--an-blue); }
.toolbar-meta { font-size:12.5px; color:var(--muted); padding-left:12px; border-left:1.5px solid var(--border); }
.toolbar-meta strong { color:#1e293b; }
.toolbar-meta em { color:var(--an-blue); font-style:normal; font-weight:700; }

/* KPI grid */
.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:var(--an-gap); }
.kpi-card {
    background:#fff; border:1px solid var(--border); border-radius:var(--an-radius);
    box-shadow:var(--an-card-shadow); padding:var(--an-pad);
    display:flex; align-items:flex-start; gap:16px;
    position:relative; overflow:hidden; transition:transform .2s,box-shadow .2s;
}
.kpi-card:hover { transform:translateY(-3px); box-shadow:var(--an-card-shadow-hover); }
.kpi-card::after {
    content:''; position:absolute; bottom:0; left:0; right:0; height:3px;
    border-radius:0 0 var(--an-radius) var(--an-radius);
}
.kpi-card.kpi-blue::after  { background:linear-gradient(90deg,#003A8C,#1a52b3); }
.kpi-card.kpi-green::after { background:linear-gradient(90deg,#0d9448,#16a34a); }
.kpi-card.kpi-amber::after { background:linear-gradient(90deg,#c47d0e,#d97706); }
.kpi-card.kpi-red::after   { background:linear-gradient(90deg,#C8102E,#e03355); }
.kpi-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
.kpi-card.kpi-blue  .kpi-icon { background:rgba(0,58,140,.1);  color:var(--an-blue); }
.kpi-card.kpi-green .kpi-icon { background:rgba(13,148,72,.1); color:var(--an-green); }
.kpi-card.kpi-amber .kpi-icon { background:rgba(196,125,14,.1);color:var(--an-amber); }
.kpi-card.kpi-red   .kpi-icon { background:rgba(200,16,46,.1); color:var(--an-red); }
.kpi-value { font-size:32px; font-weight:800; line-height:1; color:#0f172a; letter-spacing:-1.5px; }
.kpi-label { font-size:11.5px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.6px; margin-top:5px; }
.kpi-note  { display:flex; align-items:center; gap:5px; font-size:11.5px; font-weight:600; margin-top:8px; padding-top:8px; border-top:1px solid #f1f5f9; }
.kpi-note.pos { color:var(--an-green); }
.kpi-note.neg { color:var(--an-red); }
.kpi-note.neu { color:var(--muted); }

/* Section label */
.sec-label { display:flex; align-items:center; gap:12px; margin-bottom:2px; }
.sec-label-line { flex:1; height:1px; background:var(--border); }
.sec-label-text { display:flex; align-items:center; gap:7px; font-size:10.5px; font-weight:800; text-transform:uppercase; letter-spacing:1.2px; color:#94a3b8; white-space:nowrap; padding:0 4px; }
.sec-label-text.red { color:#f87171; }
.sec-label-text i { font-size:10px; }

/* Chart card */
.an-card { background:#fff; border:1px solid var(--border); border-radius:var(--an-radius); box-shadow:var(--an-card-shadow); display:flex; flex-direction:column; overflow:hidden; }
.an-card-head { display:flex; align-items:center; justify-content:space-between; padding:14px var(--an-pad) 12px; border-bottom:1px solid #f1f5f9; flex-shrink:0; }
.an-card-title { display:flex; align-items:center; gap:8px; font-size:13.5px; font-weight:700; color:#0f172a; }
.an-card-title i { color:var(--an-blue); font-size:13px; }
.an-card-badge { font-size:10.5px; font-weight:700; color:#64748b; background:#f1f5f9; border-radius:99px; padding:3px 10px; }
.an-card-body { padding:var(--an-pad); flex:1; display:flex; flex-direction:column; }

/* Grid layouts */
.g-trend { display:grid; grid-template-columns:3fr 2fr; gap:var(--an-gap); align-items:stretch; }
.g-three { display:grid; grid-template-columns:repeat(2,1fr); gap:var(--an-gap); align-items:stretch; }

/* Chart */
.chart-wrap { position:relative; flex:1; }
.c-legend { display:flex; flex-wrap:wrap; gap:12px; margin-top:14px; }
.c-leg-item { display:flex; align-items:center; gap:6px; font-size:11.5px; color:#64748b; font-weight:500; }
.c-leg-line { width:18px; height:2.5px; border-radius:2px; flex-shrink:0; }
.c-leg-dash { width:18px; height:0; border-top:2px dashed; flex-shrink:0; opacity:.4; }

/* Donut */
.donut-layout { display:flex; align-items:center; gap:20px; flex:1; }
.donut-canvas-wrap { flex:0 0 140px; }
.donut-legend { flex:1; display:flex; flex-direction:column; gap:12px; }
.dl-row1 { display:flex; align-items:center; justify-content:space-between; margin-bottom:4px; }
.dl-label { display:flex; align-items:center; gap:8px; font-size:12.5px; font-weight:600; color:#1e293b; }
.dl-dot   { width:9px; height:9px; border-radius:3px; flex-shrink:0; }
.dl-count { font-size:15px; font-weight:800; color:#0f172a; }
.dl-bar   { height:5px; background:#f1f5f9; border-radius:99px; overflow:hidden; }
.dl-fill  { height:100%; border-radius:99px; }
.dl-pct   { font-size:10.5px; color:var(--muted); margin-top:2px; }

/* Chips / tags */
.chip   { display:inline-flex; align-items:center; font-size:12px; font-weight:700; padding:3px 9px; border-radius:6px; }
.chip-g { background:rgba(13,148,72,.12);  color:#0d9448; }
.chip-a { background:rgba(196,125,14,.12); color:#c47d0e; }
.chip-r { background:rgba(200,16,46,.12);  color:#C8102E; }
.chip-n { background:#f1f5f9; color:#64748b; }
.ctag   { background:#eff6ff; color:#1d4ed8; font-size:10.5px; font-weight:700; padding:2px 8px; border-radius:5px; }
.itag   { background:#fee2e2; color:var(--an-red); font-size:10.5px; font-weight:700; padding:2px 8px; border-radius:5px; }

/* Info note */
.info-note { display:flex; align-items:flex-start; gap:7px; background:#fefce8; border:1px solid #fef08a; border-radius:8px; padding:10px 12px; font-size:11.5px; color:#854d0e; margin-top:12px; line-height:1.5; }
.info-note i { flex-shrink:0; margin-top:1px; }

/* Chart interp — screen hidden, print shown */
.chart-interp { display:none; }

/* Student progress table */
.sp-table { width:100%; border-collapse:collapse; font-size:13px; }
.sp-table thead th {
    text-align:left; padding:10px 16px;
    font-size:10.5px; font-weight:800; text-transform:uppercase;
    letter-spacing:.7px; color:var(--muted);
    background:#fafbff; border-bottom:1px solid var(--border); white-space:nowrap;
}
.sp-table thead th:not(:first-child) { text-align:center; }
.sp-table tbody td { padding:11px 16px; border-bottom:1px solid #f4f6fb; vertical-align:middle; }
.sp-table tbody td:not(:first-child) { text-align:center; }
.sp-table tbody tr:last-child td { border-bottom:none; }
.sp-table tbody tr:hover td { background:#fafbff; }
.sp-table tbody tr.row-alert td { background:#fff8f8; }
.s-row  { display:flex; align-items:center; gap:10px; }
.s-av   { width:34px; height:34px; border-radius:50%; object-fit:cover; flex-shrink:0; border:2px solid var(--border); }
.s-name { font-weight:700; font-size:13px; color:#0f172a; }
.s-sub  { font-size:11px; color:var(--muted); }
.empty-cell { padding:48px 20px; text-align:center; color:var(--muted); }
.empty-cell i { display:block; font-size:28px; margin-bottom:10px; opacity:.3; }

/* Print-only elements hidden on screen */
.print-only  { display:none; }
.print-hdr2  { display:none; }
.print-footer { display:none; }

/* Responsive */
@media(max-width:1280px) { .g-trend{grid-template-columns:1fr 1fr;} }
@media(max-width:1100px) { .kpi-grid{grid-template-columns:repeat(2,1fr);} .g-three{grid-template-columns:repeat(2,1fr);} .g-three .an-card:last-child{grid-column:1/-1;} }
@media(max-width:900px)  { .g-trend{grid-template-columns:1fr;} }
@media(max-width:768px)  { :root{--an-gap:12px;--an-pad:16px;} .kpi-grid{grid-template-columns:repeat(2,1fr);gap:12px;} .kpi-value{font-size:26px;} .kpi-icon{width:42px;height:42px;font-size:17px;} .g-trend,.g-three{grid-template-columns:1fr;} .g-three .an-card:last-child{grid-column:auto;} .donut-layout{flex-direction:column;align-items:center;} .donut-canvas-wrap{flex:none;width:130px;} .donut-legend{width:100%;} .toolbar-meta{display:none;} }
@media(max-width:480px)  { .kpi-grid{grid-template-columns:1fr 1fr;gap:10px;} .kpi-value{font-size:22px;} }

/* ═══════════════════════════════════════════
   PRINT STYLES — Landscape A4 / 2 pages
   Page 1: Header + KPIs + Charts
   Page 2: Student Progress + At-Risk tables
   ═══════════════════════════════════════════ */
@media print {
    @page { size: A4 landscape; margin: 6mm 8mm 6mm 8mm; }

    /* ── Reset everything to Times New Roman, flow layout ── */
    * {
        font-family: 'Times New Roman', Times, serif !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        box-sizing: border-box !important;
    }

    /* ── Hide screen chrome ── */
    html, body { overflow:visible !important; height:auto !important; background:#fff !important; }
    .sidebar, .top-header, .an-toolbar, .page-header { display:none !important; }
    .main-area    { margin-left:0 !important; padding:0 !important; display:block !important; }
    .page-content { padding:0 !important; overflow:visible !important; display:block !important; }
    .an-page      { display:block !important; }

    /* ── Show print-only elements ── */
    .print-only, .print-hdr2, .print-footer, .chart-interp { display:block !important; }
    .print-header   { display:flex !important; }
    .print-doc-info { display:block !important; }

    /* ── Logo placeholder ── */
    .print-header-logo-slot {
        width:58px; height:58px; flex-shrink:0;
    }
    .print-header-logo-img {
        width:58px; height:58px; object-fit:contain; display:block;
    }
    .print-header-logo-fallback {
        display:none; width:58px; height:58px;
        border:2px dashed #999; border-radius:6px;
        align-items:center; justify-content:center;
        font-size:9pt; color:#999; font-weight:bold;
    }
    /* ── Formal DepEd header — centered, no logos ── */
    .print-header {
        flex-direction:row; align-items:center; justify-content:center;
        gap:14px; padding:5px 0 7px; border-bottom:2px solid #000;
        margin-bottom:4px;
    }
    .print-header-logos  { display:none !important; }
    .print-header-center { text-align:center; flex:1; }
    .print-header-republic { font-size:7pt; color:#000; }
    .print-header-dept     { font-size:10.5pt; font-weight:bold; color:#000; text-transform:uppercase; letter-spacing:.3px; }
    .print-header-division { font-size:8pt; font-weight:bold; color:#000; margin-top:1px; }
    .print-header-school   { font-size:7pt; color:#000; margin-top:1px; }

    .print-doc-info  { text-align:center; margin:4px 0 6px; padding:4px 0; border-bottom:1.5px solid #000; }
    .print-doc-title { font-size:11pt; font-weight:bold; color:#000; text-transform:uppercase; letter-spacing:.6px; }
    .print-doc-sub   { font-size:8pt; color:#000; margin-top:2px; }
    .print-doc-meta  { font-size:7pt; color:#000; margin-top:2px; }

    /* ── Section labels ── */
    .sec-label      { margin:4px 0 3px !important; }
    .sec-label-text { font-size:7.5pt !important; color:#000 !important; font-weight:bold !important; }
    .sec-label-line { background:#003A8C !important; opacity:.2; }

    /* ── KPI row ── */
    .kpi-grid {
        display:grid !important;
        grid-template-columns:repeat(4,1fr) !important;
        gap:5px !important;
        margin-bottom:5px !important;
        break-inside:avoid !important;
        page-break-inside:avoid !important;
    }
    .kpi-card      { box-shadow:none !important; border:1px solid #c8d0e0 !important; padding:6px 9px !important; gap:7px !important; break-inside:avoid !important; }
    .kpi-card::after { height:2px !important; }
    .kpi-value     { font-size:18pt !important; letter-spacing:-1px !important; }
    .kpi-label     { font-size:7pt !important; }
    .kpi-note      { font-size:6pt !important; margin-top:2px !important; padding-top:2px !important; }
    .kpi-icon      { width:30px !important; height:30px !important; font-size:13px !important; border-radius:7px !important; flex-shrink:0 !important; }

    /* ── Chart grids: flex rows, NO break inside ── */
    .g-trend {
        display:flex !important; flex-direction:row !important;
        gap:7px !important; margin-bottom:5px !important;
        break-inside:avoid !important; page-break-inside:avoid !important;
        width:100% !important;
    }
    .g-three {
        display:flex !important; flex-direction:row !important;
        gap:7px !important; margin-bottom:5px !important;
        break-inside:avoid !important; page-break-inside:avoid !important;
        width:100% !important;
    }
    .g-trend > .an-card:first-child { flex:3 !important; min-width:0 !important; }
    .g-trend > .an-card:last-child  { flex:2 !important; min-width:0 !important; }
    .g-three > .an-card             { flex:1 !important; min-width:0 !important; }

    /* ── Chart cards ── */
    .an-card {
        box-shadow:none !important; border:1px solid #c8d0e0 !important;
        overflow:visible !important; break-inside:avoid !important; page-break-inside:avoid !important;
    }
    .an-card-head  { padding:4px 9px !important; }
    .an-card-title { font-size:7.5pt !important; font-weight:bold !important; color:#000 !important; }
    .an-card-badge { font-size:6pt !important; padding:1px 5px !important; }
    .an-card-body  { padding:5px 9px 7px !important; display:flex !important; flex-direction:column !important; }

    /* ── Canvas heights (JS mirrors these) ── */
    .chart-wrap {
        position:relative !important; overflow:hidden !important;
        min-height:0 !important; flex-shrink:0 !important;
    }
    .g-trend .chart-wrap { height:155px !important; }
    .g-three .chart-wrap { height:160px !important; }
    canvas { display:block !important; width:100% !important; height:100% !important; }

    /* ── Donut layout ── */
    .donut-layout      { gap:8px !important; flex-wrap:nowrap !important; align-items:center !important; }
    .donut-canvas-wrap { flex:0 0 80px !important; width:80px !important; height:80px !important; }
    .dl-label { font-size:7pt !important; }
    .dl-count { font-size:9.5pt !important; font-weight:bold !important; }
    .dl-pct   { font-size:6pt !important; }
    .dl-bar   { height:3px !important; }

    /* ── Chart interpretation box ── */
    .chart-interp {
        margin-top:4px !important; padding:3px 7px !important;
        background:#f0f0f0 !important; border-left:2.5px solid #000 !important;
        font-size:6pt !important; color:#000 !important; line-height:1.5 !important;
    }
    .chart-interp strong { color:#000 !important; font-weight:bold !important; }
    .c-legend   { margin-top:3px !important; gap:4px !important; flex-wrap:wrap !important; }
    .c-leg-item { font-size:6pt !important; }
    .c-leg-line, .c-leg-dash { width:10px !important; }
    .info-note  { font-size:5.5pt !important; padding:2px 6px !important; margin-top:3px !important; }

    /* ── Page 2 & 3 repeat headers ── */

    /* page breaks removed — single flowing layout */

    /* ── Tables ── */
    .dt, .at, .sp-table { width:100% !important; border-collapse:collapse !important; }
    .dt thead th, .at thead th, .sp-table thead th {
        font-size:7pt !important; padding:4px 8px !important;
        background:#e0e0e0 !important; color:#000 !important; font-weight:bold !important;
    }
    .dt tbody td, .at tbody td, .sp-table tbody td {
        font-size:7pt !important; padding:4px 8px !important;
        border-bottom:1px solid #e8edf8 !important;
    }
    .dt-name, .s-name { font-size:7.5pt !important; font-weight:bold !important; }
    .dt-sub, .s-sub   { font-size:6pt !important; }
    .s-av   { width:20px !important; height:20px !important; }
    .chip, .ctag, .itag, .rtag { font-size:6.5pt !important; padding:1px 5px !important; }
    .alert-card  { border-left:3px solid #C8102E !important; }
    .alert-head  { padding:5px 10px !important; }
    .alert-title { font-size:8.5pt !important; font-weight:bold !important; }
    .alert-count { font-size:7.5pt !important; }

    /* ── Signature footer ── */
    .print-footer {
        margin-top:10px !important; padding-top:6px !important;
        border-top:2px solid #000 !important;
        break-inside:avoid !important; page-break-inside:avoid !important;
    }
    .print-sig-row   { display:flex !important; justify-content:space-between !important; gap:24px !important; margin-bottom:5px !important; }
    .print-sig-col   { flex:1 !important; }
    .print-sig-label { font-size:6.5pt; color:#000; margin-bottom:16px; display:block; }
    .print-sig-line  { border-top:1px solid #000 !important; padding-top:3px !important; font-size:8.5pt !important; font-weight:bold !important; color:#000 !important; }
    .print-sig-title-text { font-size:6.5pt; color:#000; margin-top:1px; display:block; }
    .print-disclaimer {
        text-align:center !important; margin-top:6px !important;
        font-size:6pt !important; color:#000 !important;
        border-top:1px solid #999 !important; padding-top:4px !important;
    }
}
</style>
@endpush

@section('content')
@php
    $tot = max($riskDistribution['meeting']+$riskDistribution['approaching']+$riskDistribution['below'],1);
    $mp  = round($riskDistribution['meeting']    /$tot*100);
    $ap  = round($riskDistribution['approaching']/$tot*100);
    $bp  = round($riskDistribution['below']      /$tot*100);
    $belowStudents = $students->filter(fn($s) => $s->latestAssessment &&
        str_contains($s->latestAssessment->risk_level ?? '', 'Below'));
@endphp

{{-- ══════════════════════════════════════════
     PRINT-ONLY PAGE 1 HEADER (hidden on screen)
     ══════════════════════════════════════════ --}}
<div class="print-header" style="display:none;">
    <div class="print-header-logo-slot">
        <img src="{{ asset('images/TES-logo.jpg') }}" class="print-header-logo-img" alt="School Logo"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
        <div class="print-header-logo-fallback">
            <span>LOGO</span>
        </div>
    </div>
    <div class="print-header-center">
        <div class="print-header-republic">Republic of the Philippines</div>
        <div class="print-header-dept">Department of Education</div>
        <div class="print-header-division">Schools Division of Ilocos Sur</div>
        <div class="print-header-school">Tampugo Elementary School &bull; Borono, Tagudin, Ilocos Sur</div>
    </div>
</div>

<div class="print-doc-info" style="display:none;">
    <div class="print-doc-title">Class Reading Progress Report</div>
    <div class="print-doc-sub">
        TESRead — Digital Reading Progress Monitoring System &bull;
        Teacher: <strong>{{ auth()->user()->name }}</strong>
    </div>
    <div class="print-doc-meta">
        School Year: <strong>{{ $year }}</strong> &bull;
        Period: <strong>{{ ucfirst($period) }}</strong> &bull;
        Date Printed: <strong id="printDate"></strong>
    </div>
</div>

<div class="an-page">

{{-- ════ TOOLBAR ════ --}}
<div class="an-toolbar">
    <div class="an-toolbar-left">
        <div class="period-toggle">
            <button type="button" onclick="changePeriod('monthly')"
                    class="pt-btn {{ $period==='monthly'?'active':'' }}">
                <i class="fas fa-calendar-alt"></i> Monthly
            </button>
            <button type="button" onclick="changePeriod('quarterly')"
                    class="pt-btn {{ $period==='quarterly'?'active':'' }}">
                <i class="fas fa-calendar-week"></i> Quarterly
            </button>
        </div>
        <form id="filterForm" method="GET" style="display:contents;">
            <input type="hidden" name="period" id="periodInput" value="{{ $period }}">
            <select name="year" class="yr-select" onchange="this.form.submit()">
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
        <div class="toolbar-meta">
            Showing <strong>{{ ucfirst($period) }}</strong> data for <em>SY {{ $year }}</em>
        </div>
    </div>
    <div class="an-toolbar-right">
        <button onclick="window.print()" class="btn btn-outline btn-sm">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>
</div>

{{-- ════ KPI GRID ════ --}}
<div class="kpi-grid">
    <div class="kpi-card kpi-blue">
        <div class="kpi-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $totalStudents }}</div>
            <div class="kpi-label">My Students</div>
            <div class="kpi-note neu"><i class="fas fa-circle" style="font-size:5px;"></i> Active enrollment</div>
        </div>
    </div>
    <div class="kpi-card kpi-green">
        <div class="kpi-icon"><i class="fas fa-star"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $riskDistribution['meeting'] }}</div>
            <div class="kpi-label">Meeting Standard</div>
            <div class="kpi-note pos"><i class="fas fa-check-circle"></i> {{ $mp }}% of class</div>
        </div>
    </div>
    <div class="kpi-card kpi-amber">
        <div class="kpi-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ array_sum($trendData['totals']) }}</div>
            <div class="kpi-label">Assessments &bull; {{ $year }}</div>
            <div class="kpi-note neu"><i class="fas fa-calendar"></i> This school year</div>
        </div>
    </div>
    <div class="kpi-card kpi-red">
        <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $activeInterventions }}</div>
            <div class="kpi-label">Active Interventions</div>
            <div class="kpi-note {{ $activeInterventions > 0 ? 'neg' : 'pos' }}">
                <i class="fas fa-{{ $activeInterventions > 0 ? 'bell' : 'check-circle' }}"></i>
                {{ $activeInterventions > 0 ? 'Needs attention' : 'All clear' }}
            </div>
        </div>
    </div>
</div>

{{-- ════ READING PERFORMANCE ════ --}}
<div class="sec-label">
    <div class="sec-label-line"></div>
    <span class="sec-label-text"><i class="fas fa-chart-area"></i> Reading Performance</span>
    <div class="sec-label-line"></div>
</div>

<div class="g-trend">
    <div class="an-card">
        <div class="an-card-head">
            <div class="an-card-title"><i class="fas fa-chart-area"></i> Average Score Trend</div>
            <span class="an-card-badge">{{ ucfirst($period) }} &middot; {{ $year }}</span>
        </div>
        <div class="an-card-body">
            <div class="chart-wrap">
                <canvas id="cTrend"></canvas>
            </div>
            <div class="c-legend">
                <div class="c-leg-item"><div class="c-leg-line" style="background:#003A8C;"></div>Avg Fluency</div>
                <div class="c-leg-item"><div class="c-leg-line" style="background:#C8102E;"></div>Avg Comprehension</div>
                <div class="c-leg-item"><div class="c-leg-dash" style="border-color:#003A8C;"></div>85% Target</div>
                <div class="c-leg-item"><div class="c-leg-dash" style="border-color:#C8102E;"></div>80% Target</div>
            </div>
            <div class="chart-interp">
                <strong>Interpretation:</strong> Shows average fluency and comprehension scores over time for your class.
                Scores at or above the target thresholds (85% fluency, 80% comprehension) indicate pupils are meeting the expected reading standard.
                A downward trend signals a need for class-wide reading intervention.
            </div>
        </div>
    </div>

    <div class="an-card">
        <div class="an-card-head">
            <div class="an-card-title"><i class="fas fa-chart-pie"></i> Risk Distribution</div>
            <span class="an-card-badge">Latest per student</span>
        </div>
        <div class="an-card-body">
            <div class="donut-layout">
                <div class="donut-canvas-wrap"><canvas id="cDonut"></canvas></div>
                <div class="donut-legend">
                    @foreach([
                        ['Meeting Standard',  $riskDistribution['meeting'],    '#0d9448', $mp],
                        ['Approaching',       $riskDistribution['approaching'], '#c47d0e', $ap],
                        ['Below Standard',    $riskDistribution['below'],      '#C8102E', $bp],
                    ] as [$lbl,$cnt,$clr,$pct])
                    <div class="dl-item">
                        <div class="dl-row1">
                            <div class="dl-label"><div class="dl-dot" style="background:{{ $clr }};"></div>{{ $lbl }}</div>
                            <span class="dl-count">{{ $cnt }}</span>
                        </div>
                        <div class="dl-bar"><div class="dl-fill" style="width:{{ $pct }}%;background:{{ $clr }};"></div></div>
                        <div class="dl-pct">{{ $pct }}% of assessed</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="chart-interp">
                <strong>Interpretation:</strong> Distribution of pupils by reading risk level based on their latest assessment.
                A higher proportion in <strong style="color:#0d9448;">Meeting Standard</strong> reflects effective literacy instruction.
                Students in <strong style="color:#C8102E;">Below Standard</strong> require immediate targeted reading intervention.
            </div>
        </div>
    </div>
</div>

<div class="sec-label">
    <div class="sec-label-line"></div>
    <span class="sec-label-text"><i class="fas fa-chart-bar"></i> Assessment Breakdown</span>
    <div class="sec-label-line"></div>
</div>

<div class="g-three">
    <div class="an-card">
        <div class="an-card-head">
            <div class="an-card-title"><i class="fas fa-layer-group"></i> Risk Count Over Time</div>
            <span class="an-card-badge">{{ ucfirst($period) }}</span>
        </div>
        <div class="an-card-body">
            <div class="chart-wrap"><canvas id="cRiskBar"></canvas></div>
            <div class="chart-interp">
                <strong>Interpretation:</strong> Tracks pupils per risk category each period.
                An increasing <strong style="color:#0d9448;">Meeting</strong> count reflects literacy growth;
                a rising <strong style="color:#C8102E;">Below Standard</strong> bar signals the need for intervention planning.
            </div>
        </div>
    </div>
    <div class="an-card">
        <div class="an-card-head">
            <div class="an-card-title"><i class="fas fa-clipboard-list"></i> Assessment Volume</div>
            <span class="an-card-badge">{{ ucfirst($period) }}</span>
        </div>
        <div class="an-card-body">
            <div class="chart-wrap"><canvas id="cVolume"></canvas></div>
            <div class="chart-interp">
                <strong>Interpretation:</strong> Total reading assessments conducted per period.
                Consistent volume indicates active monitoring.
                Low-volume periods may reflect missed opportunities or calendar interruptions requiring make-up assessments.
            </div>
        </div>
    </div>
</div>
{{-- ════ STUDENT PROGRESS ════ --}}
<div class="sec-label" style="break-before:page; page-break-before:always;">
    <div class="sec-label-line"></div>
    <span class="sec-label-text"><i class="fas fa-chalkboard-teacher"></i> Student Progress Summary</span>
    <div class="sec-label-line"></div>
</div>

<div class="an-card">
    <div class="an-card-head">
        <div class="an-card-title"><i class="fas fa-table"></i> All Students — {{ $year }} Progress</div>
        <span class="an-card-badge">{{ $students->count() }} students</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="sp-table">
            <thead>
                <tr>
                    <th style="width:22%;">Student</th>
                    <th>Class</th>
                    <th>Reading Level</th>
                    <th>Fluency</th>
                    <th>Comprehension</th>
                    <th>Assessments</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                @php
                    $la      = $student->latestAssessment;
                    $isBelow = $la && str_contains($la->risk_level ?? '', 'Below');
                    $isAppr  = $la && str_contains($la->risk_level ?? '', 'Approaching');
                    $hasIntv = $student->interventions->where('status','Active')->count() > 0;
                @endphp
                <tr class="{{ $isBelow ? 'row-alert' : '' }}">
                    <td>
                        <div class="s-row">
                            <img src="{{ $student->profilePhotoUrl() }}" class="s-av"
                                 style="{{ $isBelow ? 'border-color:#fecdd3;' : '' }}">
                            <div>
                                <div class="s-name">{{ $student->fullName() }}</div>
                                <div class="s-sub">{{ $student->lrn ?? 'No LRN' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($student->section)
                            <span class="ctag">Gr.{{ $student->section->grade_level }} – {{ $student->section->name }}</span>
                        @else <span style="color:var(--muted);">—</span>
                        @endif
                    </td>
                    <td style="font-size:12.5px;color:#374151;">{{ $student->philIriLabel() ?? '—' }}</td>
                    <td>
                        @if($la && $la->fluency_score !== null)
                            <span class="chip {{ $la->fluency_score>=85?'chip-g':($la->fluency_score>=70?'chip-a':'chip-r') }}">{{ $la->fluency_score }}%</span>
                        @else <span class="chip chip-n">—</span>
                        @endif
                    </td>
                    <td>
                        @if($la && $la->comprehension_score !== null)
                            <span class="chip {{ $la->comprehension_score>=80?'chip-g':($la->comprehension_score>=65?'chip-a':'chip-r') }}">{{ $la->comprehension_score }}%</span>
                        @else <span class="chip chip-n">—</span>
                        @endif
                    </td>
                    <td><strong style="font-size:15px;">{{ $student->assessments->count() }}</strong></td>
                    <td>
                        @if(!$la)
                            <span class="chip chip-n">Not assessed</span>
                        @elseif($isBelow)
                            <span class="chip chip-r"><i class="fas fa-exclamation-triangle" style="font-size:10px;margin-right:3px;"></i>Below</span>
                        @elseif($isAppr)
                            <span class="chip chip-a">Approaching</span>
                        @else
                            <span class="chip chip-g"><i class="fas fa-check" style="font-size:10px;margin-right:3px;"></i>Meeting</span>
                        @endif
                        @if($hasIntv) <span class="itag" style="margin-left:4px;">Intervention</span> @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-cell">
                        <i class="fas fa-user-graduate"></i>
                        No students assigned yet.
                        <br><a href="{{ route('teacher.students.create') }}" style="color:var(--an-blue);font-weight:600;margin-top:8px;display:inline-block;">Add your first student →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ════ BELOW STANDARD ALERT ════ --}}
@if($belowStudents->count())
<div class="sec-label">
    <div class="sec-label-line" style="background:#fecdd3;"></div>
    <span class="sec-label-text red"><i class="fas fa-exclamation-triangle"></i> Requires Intervention</span>
    <div class="sec-label-line" style="background:#fecdd3;"></div>
</div>
<div class="an-card" style="border-left:3px solid var(--an-red);">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px var(--an-pad) 12px;background:linear-gradient(to right,#fff5f6,#fff);border-bottom:1px solid #fde8ea;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:700;color:var(--an-red);">
            <i class="fas fa-user-slash"></i> Students Below Expected Standard
        </div>
        <span style="background:var(--an-red);color:#fff;font-size:11px;font-weight:800;padding:3px 11px;border-radius:99px;">
            {{ $belowStudents->count() }} students
        </span>
    </div>
    <div style="padding:16px var(--an-pad);">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px;">
            @foreach($belowStudents as $s)
            @php $la = $s->latestAssessment; @endphp
            <div style="border:1px solid #fde8ea;border-radius:10px;padding:14px;background:#fff8f8;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <img src="{{ $s->profilePhotoUrl() }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #fecdd3;flex-shrink:0;">
                    <div>
                        <div style="font-weight:700;font-size:13px;color:#0f172a;">{{ $s->fullName() }}</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $s->lrn ?? 'No LRN' }}</div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:12px;">
                    <div style="background:#fff;border:1px solid #fde8ea;border-radius:7px;padding:7px 10px;">
                        <div style="color:var(--muted);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Fluency</div>
                        <div style="font-weight:800;font-size:16px;color:var(--an-red);margin-top:2px;">{{ $la?->fluency_score ?? '—' }}{{ $la ? '%' : '' }}</div>
                    </div>
                    <div style="background:#fff;border:1px solid #fde8ea;border-radius:7px;padding:7px 10px;">
                        <div style="color:var(--muted);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Comprehension</div>
                        <div style="font-weight:800;font-size:16px;color:var(--an-red);margin-top:2px;">{{ $la?->comprehension_score ?? '—' }}{{ $la ? '%' : '' }}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:10px;">
                    <span style="font-size:11px;color:var(--muted);">{{ $la?->assessed_on?->format('M d, Y') ?? 'Not assessed' }}</span>
                    <a href="{{ route('teacher.students.show', $s) }}" class="btn btn-outline btn-sm" style="font-size:11px;padding:4px 10px;">View Profile</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ══ PRINT FOOTER ══ --}}
<div class="print-footer" style="display:none;">
    <div style="text-align:right;font-size:7pt;color:#666;margin-bottom:10px;">
        Generated via TESRead System &bull; Tampugo Elementary School &bull; <span id="printDateFooter"></span>
    </div>
    <div class="print-sig-row">
        <div class="print-sig-col">
            <span class="print-sig-label">Prepared by:</span>
            <div class="print-sig-line">{{ auth()->user()->name }}</div>
            <span class="print-sig-title-text">Class Teacher</span>
        </div>
        <div class="print-sig-col" style="text-align:right;">
            <span class="print-sig-label">Noted by:</span>
            <div class="print-sig-line">{{ config('school.principal_name', '________________________________') }}</div>
            <span class="print-sig-title-text">School Head / Principal</span>
        </div>
    </div>
    <div class="print-disclaimer">
        This report is system-generated from TESRead — Digital Reading Progress Monitoring System. For official use only. &bull;
        Department of Education &bull; Schools Division of Ilocos Sur &bull; Tampugo Elementary School
    </div>
</div>

</div>{{-- /an-page --}}
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
function changePeriod(p) {
    document.getElementById('periodInput').value = p;
    document.getElementById('filterForm').submit();
}

const B='#003A8C', R='#C8102E', G='#0d9448', A='#c47d0e';
const GC='#aaaaaa';

Chart.defaults.font.family = "'Times New Roman', Times, serif";
Chart.defaults.font.size   = 11.5;
Chart.defaults.color       = '#333333';

const TIP = { backgroundColor:'#0f172a', titleColor:'#f8fafc', bodyColor:'#94a3b8', padding:12, cornerRadius:10, boxPadding:5, borderColor:'#1e293b', borderWidth:1 };
const SY  = { grid:{ color:GC, drawBorder:false }, border:{ dash:[4,4], display:false }, ticks:{ padding:6 } };
const SX  = { grid:{ display:false }, border:{ display:false } };

const tL  = @json($trendData['labels']);
const tF  = @json($trendData['fluency']);
const tC  = @json($trendData['comp']);
const tT  = @json($trendData['totals']);
const rL  = @json($riskOverTime['labels']);
const rBl = @json($riskOverTime['below']);
const rAp = @json($riskOverTime['approaching']);
const rMt = @json($riskOverTime['meeting']);
const dM  = {{ $riskDistribution['meeting'] }};
const dA  = {{ $riskDistribution['approaching'] }};
const dB  = {{ $riskDistribution['below'] }};

/* 1 — Score Trend */
new Chart('cTrend', { type:'line', data:{ labels:tL, datasets:[
    { label:'Avg Fluency',      data:tF, borderColor:B, backgroundColor:B+'14', borderWidth:2.5, pointBackgroundColor:B, pointRadius:4, pointHoverRadius:6, tension:.4, fill:true, spanGaps:true },
    { label:'Avg Comprehension',data:tC, borderColor:R, backgroundColor:R+'0e', borderWidth:2.5, pointBackgroundColor:R, pointRadius:4, pointHoverRadius:6, tension:.4, fill:true, spanGaps:true },
    { label:'Fluency 85%',  data:Array(tL.length).fill(85), borderColor:B+'44', borderDash:[6,4], borderWidth:1.5, pointRadius:0, fill:false },
    { label:'Comp 80%',     data:Array(tL.length).fill(80), borderColor:R+'44', borderDash:[6,4], borderWidth:1.5, pointRadius:0, fill:false },
]}, options:{ responsive:true, maintainAspectRatio:false,
    interaction:{ mode:'index', intersect:false },
    plugins:{ legend:{ display:false }, tooltip:{ ...TIP, callbacks:{ label:c=>'  '+c.dataset.label+': '+(c.parsed.y!=null?c.parsed.y+'%':'No data') }}},
    scales:{ y:{ ...SY, min:0, max:100, ticks:{ callback:v=>v+'%', stepSize:20 }}, x:SX }}});

/* 2 — Risk Donut */
new Chart('cDonut', { type:'doughnut', data:{ labels:['Meeting','Approaching','Below'],
    datasets:[{ data:[dM,dA,dB], backgroundColor:[G,A,R], borderWidth:4, borderColor:'#fff', hoverOffset:8 }]},
    options:{ responsive:true, maintainAspectRatio:true, cutout:'72%',
        plugins:{ legend:{ display:false }, tooltip:{ ...TIP, callbacks:{ label:c=>'  '+c.label+': '+c.raw+' students' }}}}});

/* 3 — Risk Over Time */
new Chart('cRiskBar', { type:'bar', data:{ labels:rL, datasets:[
    { label:'Meeting',    data:rMt, backgroundColor:G+'cc', borderRadius:5, borderSkipped:false },
    { label:'Approaching',data:rAp, backgroundColor:A+'cc', borderRadius:5, borderSkipped:false },
    { label:'Below',      data:rBl, backgroundColor:R+'cc', borderRadius:5, borderSkipped:false },
]}, options:{ responsive:true, maintainAspectRatio:false,
    interaction:{ mode:'index', intersect:false },
    plugins:{ legend:{ position:'bottom', labels:{ boxWidth:10, padding:12, font:{ size:11 }}}, tooltip:TIP },
    scales:{ y:{ ...SY, beginAtZero:true, ticks:{ stepSize:1 }}, x:SX }}});


/* 5 — Assessment Volume */
new Chart('cVolume', { type:'bar', data:{ labels:tL, datasets:[
    { label:'Assessments', data:tT, backgroundColor:tL.map((_,i)=>i%2===0?B+'dd':B+'77'), borderRadius:5, borderSkipped:false },
]}, options:{ responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ display:false }, tooltip:TIP },
    scales:{ y:{ ...SY, beginAtZero:true, ticks:{ stepSize:1 }}, x:SX }}});

/* ── Print date ── */
const _now = new Date();
const _fmt = _now.toLocaleDateString('en-PH',{ year:'numeric', month:'long', day:'numeric' })
           + ', ' + _now.toLocaleTimeString('en-PH',{ hour:'2-digit', minute:'2-digit' });
const _e1 = document.getElementById('printDate');
const _e2 = document.getElementById('printDateFooter');
if (_e1) _e1.textContent = _fmt;
if (_e2) _e2.textContent = _fmt;

/* ── Chart resize before/after print ── */
function resizeChartsForPrint() {
    document.querySelectorAll('.g-trend canvas, .g-three canvas').forEach(canvas => {
        const chart = Chart.getChart(canvas);
        if (!chart) return;
        const wrap = canvas.closest('.chart-wrap');
        const inGThree = !!canvas.closest('.g-three');
        const h = inGThree ? 160 : 155;
        const w = wrap ? wrap.offsetWidth : 300;
        chart.resize(w || 300, h);
        chart.update('none');
    });
}
function restoreChartsAfterPrint() {
    document.querySelectorAll('canvas').forEach(canvas => {
        const chart = Chart.getChart(canvas);
        if (chart) { chart.resize(); chart.update('none'); }
    });
}
window.addEventListener('beforeprint', resizeChartsForPrint);
window.addEventListener('afterprint',  restoreChartsAfterPrint);
</script>
@endpush