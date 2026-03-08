@extends('layouts.admin')
@section('title', 'Analytics & Reports')
@section('page-icon', '📊')
@section('page-heading', 'Analytics & Reports')

@push('styles')
<style>
/* ════════════════════════════════════════════════════
   ANALYTICS — Design System
   Tone: Refined data-intelligence dashboard
   ════════════════════════════════════════════════════ */
:root {
    --an-blue:   #003A8C;
    --an-red:    #C8102E;
    --an-green:  #0d9448;
    --an-amber:  #c47d0e;
    --an-purple: #6d28d9;
    --an-gap:    18px;
    --an-pad:    22px;
    --an-radius: 14px;
    --an-card-shadow: 0 1px 4px rgba(0,0,0,.06), 0 4px 16px rgba(0,40,140,.07);
    --an-card-shadow-hover: 0 4px 12px rgba(0,0,0,.08), 0 12px 32px rgba(0,40,140,.12);
}

/* ── Page wrapper ───────────────────────────────────── */
.an-page { display:flex; flex-direction:column; gap:var(--an-gap); }

/* ── Toolbar ────────────────────────────────────────── */
.an-toolbar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--an-radius);
    padding: 12px 18px;
    box-shadow: var(--an-card-shadow);
}
.an-toolbar-left  { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.an-toolbar-right { display:flex; align-items:center; gap:10px; margin-left:auto; }
.period-toggle { display:flex; background:#f1f5f9; border-radius:9px; padding:3px; gap:2px; }
.pt-btn {
    display: flex; align-items: center; gap: 6px;
    padding: 6px 16px; border: none; background: transparent;
    border-radius: 7px; font-size: 12.5px; font-weight: 600;
    color: #64748b; cursor: pointer; transition: all .15s;
    font-family: inherit;
}
.pt-btn.active {
    background: #fff; color: var(--an-blue);
    box-shadow: 0 1px 5px rgba(0,0,0,.1);
}
.pt-btn:hover:not(.active) { color: #1e293b; }
.yr-select {
    border: 1.5px solid var(--border); border-radius: 9px;
    padding: 7px 12px; font-size: 13px; font-weight: 600;
    color: #1e293b; background: #fff; cursor: pointer;
    outline: none; font-family: inherit; transition: border-color .15s;
}
.yr-select:focus { border-color: var(--an-blue); }
.toolbar-meta {
    font-size: 12.5px; color: var(--muted);
    padding-left: 4px; border-left: 1.5px solid var(--border);
    padding-left: 12px;
}
.toolbar-meta strong { color: #1e293b; }
.toolbar-meta em { color: var(--an-blue); font-style: normal; font-weight: 700; }

/* ── KPI grid ───────────────────────────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--an-gap);
}
.kpi-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--an-radius);
    box-shadow: var(--an-card-shadow);
    padding: var(--an-pad);
    display: flex;
    align-items: flex-start;
    gap: 16px;
    position: relative;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    text-decoration: none;
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: var(--an-card-shadow-hover); }
.kpi-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 0 0 var(--an-radius) var(--an-radius);
}
.kpi-card.kpi-blue::after   { background: linear-gradient(90deg,#003A8C,#1a52b3); }
.kpi-card.kpi-green::after  { background: linear-gradient(90deg,#0d9448,#16a34a); }
.kpi-card.kpi-amber::after  { background: linear-gradient(90deg,#c47d0e,#d97706); }
.kpi-card.kpi-red::after    { background: linear-gradient(90deg,#C8102E,#e03355); }
.kpi-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.kpi-card.kpi-blue  .kpi-icon { background: rgba(0,58,140,.1);  color: var(--an-blue); }
.kpi-card.kpi-green .kpi-icon { background: rgba(13,148,72,.1); color: var(--an-green); }
.kpi-card.kpi-amber .kpi-icon { background: rgba(196,125,14,.1);color: var(--an-amber); }
.kpi-card.kpi-red   .kpi-icon { background: rgba(200,16,46,.1); color: var(--an-red); }
.kpi-body { flex: 1; min-width: 0; }
.kpi-value {
    font-size: 32px; font-weight: 800; line-height: 1;
    color: #0f172a; letter-spacing: -1.5px;
}
.kpi-label {
    font-size: 11.5px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .6px;
    margin-top: 5px;
}
.kpi-note {
    display: flex; align-items: center; gap: 5px;
    font-size: 11.5px; font-weight: 600;
    margin-top: 8px; padding-top: 8px;
    border-top: 1px solid #f1f5f9;
}
.kpi-note.pos { color: var(--an-green); }
.kpi-note.neg { color: var(--an-red); }
.kpi-note.neu { color: var(--muted); }

/* ── Section label ──────────────────────────────────── */
.sec-label {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 2px;
}
.sec-label-line { flex: 1; height: 1px; background: var(--border); }
.sec-label-text {
    display: flex; align-items: center; gap: 7px;
    font-size: 10.5px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 1.2px;
    color: #94a3b8; white-space: nowrap;
    padding: 0 4px;
}
.sec-label-text.red { color: #f87171; }
.sec-label-text i { font-size: 10px; }

/* ── Chart card ─────────────────────────────────────── */
.an-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--an-radius);
    box-shadow: var(--an-card-shadow);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.an-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px var(--an-pad) 12px;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}
.an-card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 13.5px; font-weight: 700; color: #0f172a;
}
.an-card-title i { color: var(--an-blue); font-size: 13px; }
.an-card-badge {
    font-size: 10.5px; font-weight: 700; color: #64748b;
    background: #f1f5f9; border-radius: 99px;
    padding: 3px 10px;
}
.an-card-body { padding: var(--an-pad); flex: 1; display: flex; flex-direction: column; }

/* ── Grid layouts ───────────────────────────────────── */
.g-trend { display:grid; grid-template-columns:3fr 2fr; gap:var(--an-gap); align-items:stretch; }
.g-three  { display:grid; grid-template-columns:repeat(3,1fr); gap:var(--an-gap); align-items:stretch; }
.g-two    { display:grid; grid-template-columns:1fr 1fr; gap:var(--an-gap); align-items:stretch; }

/* ── Chart canvas wrapper ───────────────────────────── */
.chart-wrap { position:relative; flex:1; }

/* ── Custom legend ──────────────────────────────────── */
.c-legend { display:flex; flex-wrap:wrap; gap:12px; margin-top:14px; }
.c-leg-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 11.5px; color: #64748b; font-weight: 500;
}
.c-leg-line { width:18px; height:2.5px; border-radius:2px; flex-shrink:0; }
.c-leg-dash { width:18px; height:0; border-top:2px dashed; flex-shrink:0; opacity:.4; }
.c-leg-box  { width:10px; height:10px; border-radius:3px; flex-shrink:0; }

/* ── Donut layout ───────────────────────────────────── */
.donut-layout { display:flex; align-items:center; gap:20px; flex:1; }
.donut-canvas-wrap { flex:0 0 140px; }
.donut-legend { flex:1; display:flex; flex-direction:column; gap:12px; }
.dl-item { }
.dl-row1 { display:flex; align-items:center; justify-content:space-between; margin-bottom:4px; }
.dl-label { display:flex; align-items:center; gap:8px; font-size:12.5px; font-weight:600; color:#1e293b; }
.dl-dot   { width:9px; height:9px; border-radius:3px; flex-shrink:0; }
.dl-count { font-size:15px; font-weight:800; color:#0f172a; }
.dl-bar   { height:5px; background:#f1f5f9; border-radius:99px; overflow:hidden; }
.dl-fill  { height:100%; border-radius:99px; transition:width .6s ease; }
.dl-pct   { font-size:10.5px; color:var(--muted); margin-top:2px; }

/* ── Score chip ─────────────────────────────────────── */
.chip { display:inline-flex; align-items:center; font-size:12px; font-weight:700; padding:3px 9px; border-radius:6px; }
.chip-g { background:rgba(13,148,72,.12);  color:#0d9448; }
.chip-a { background:rgba(196,125,14,.12); color:#c47d0e; }
.chip-r { background:rgba(200,16,46,.12);  color:#C8102E; }
.chip-n { background:#f1f5f9; color:#64748b; }

/* ── Data table ─────────────────────────────────────── */
.dt { width:100%; border-collapse:collapse; font-size:13px; }
.dt thead th {
    text-align:left; padding:10px 16px;
    font-size:10.5px; font-weight:800; text-transform:uppercase;
    letter-spacing:.7px; color:var(--muted);
    background:#fafbff; border-bottom:1px solid var(--border);
    white-space:nowrap;
}
.dt thead th:not(:first-child) { text-align:center; }
.dt tbody td {
    padding:12px 16px;
    border-bottom:1px solid #f4f6fb;
    vertical-align:middle;
}
.dt tbody td:not(:first-child) { text-align:center; }
.dt tbody tr:last-child td { border-bottom:none; }
.dt tbody tr:hover td { background:#fafbff; }
.dt-name { font-weight:700; color:#0f172a; font-size:13.5px; }
.dt-sub   { font-size:11px; color:var(--muted); margin-top:1px; }

/* ── Below-standard table ───────────────────────────── */
.alert-card { border-left: 3px solid var(--an-red); }
.alert-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px var(--an-pad) 12px;
    background: linear-gradient(to right,#fff5f6,#fff);
    border-bottom: 1px solid #fde8ea;
}
.alert-title { display:flex; align-items:center; gap:8px; font-size:13.5px; font-weight:700; color:var(--an-red); }
.alert-count { background:var(--an-red); color:#fff; font-size:11px; font-weight:800; padding:3px 11px; border-radius:99px; }
.at { width:100%; border-collapse:collapse; font-size:13px; }
.at thead th {
    text-align:left; padding:10px 16px;
    font-size:10.5px; font-weight:800; text-transform:uppercase;
    letter-spacing:.7px; color:#b91c1c;
    background:#fff8f8; border-bottom:1px solid #fde8ea;
}
.at tbody td { padding:11px 16px; border-bottom:1px solid #fff0f0; vertical-align:middle; }
.at tbody tr:last-child td { border-bottom:none; }
.at tbody tr:hover td { background:#fffafa; }
.s-row { display:flex; align-items:center; gap:10px; }
.s-av { width:34px; height:34px; border-radius:50%; object-fit:cover; flex-shrink:0; border:2px solid #fecdd3; }
.s-name { font-weight:700; font-size:13px; color:#0f172a; }
.s-sub  { font-size:11px; color:var(--muted); }
.ctag { background:#eff6ff; color:#1d4ed8; font-size:10.5px; font-weight:700; padding:2px 8px; border-radius:5px; }
.rtag { background:#fee2e2; color:var(--an-red); font-size:11px; font-weight:800; padding:2px 8px; border-radius:5px; }

/* ── Sessions info note ─────────────────────────────── */
.info-note {
    display:flex; align-items:flex-start; gap:7px;
    background:#fefce8; border:1px solid #fef08a;
    border-radius:8px; padding:10px 12px;
    font-size:11.5px; color:#854d0e;
    margin-top:12px; line-height:1.5;
}
.info-note i { flex-shrink:0; margin-top:1px; }

/* ── Empty state ─────────────────────────────────────── */
.empty-cell { padding:40px 20px; text-align:center; color:var(--muted); }
.empty-cell i { display:block; font-size:24px; margin-bottom:8px; opacity:.3; }

/* ══════════════════════════════════════════════════════
   RESPONSIVE BREAKPOINTS
   ══════════════════════════════════════════════════════ */
@media (max-width:1280px) {
    .g-trend { grid-template-columns:1fr 1fr; }
}
@media (max-width:1100px) {
    .kpi-grid { grid-template-columns:repeat(2,1fr); }
    .g-three  { grid-template-columns:repeat(2,1fr); }
    .g-three .an-card:last-child { grid-column: 1 / -1; }
}
@media (max-width:900px) {
    .g-trend { grid-template-columns:1fr; }
    .g-two   { grid-template-columns:1fr; }
}
@media (max-width:768px) {
    :root { --an-gap:12px; --an-pad:16px; }
    .kpi-grid { grid-template-columns:repeat(2,1fr); gap:12px; }
    .kpi-value { font-size:26px; }
    .kpi-icon  { width:42px; height:42px; font-size:17px; }
    .g-trend,.g-three,.g-two { grid-template-columns:1fr; }
    .g-three .an-card:last-child { grid-column:auto; }
    .donut-layout { flex-direction:column; align-items:center; }
    .donut-canvas-wrap { flex:none; width:130px; }
    .donut-legend { width:100%; }
    .toolbar-meta { display:none; }
    .an-toolbar-right { flex-wrap:wrap; }
}
@media (max-width:480px) {
    .kpi-grid { grid-template-columns:1fr 1fr; gap:10px; }
    .kpi-value { font-size:22px; letter-spacing:-1px; }
    .kpi-label { font-size:10px; }
}
/* ══════════════════════════════════════════════════════
   FORMAL DEPED PRINT REPORT
   ══════════════════════════════════════════════════════ */

/* Chart interpretation box — hidden on screen */
.chart-interp { display: none; }

@media print {
    @page { size: A4 landscape; margin: 10mm 12mm 12mm 12mm; }

    /* ── Hide all screen UI ── */
    html, body { overflow: visible !important; height: auto !important; background: #fff !important; }
    .sidebar, .top-header, .an-toolbar, .page-header { display: none !important; }
    .main-area  { margin-left: 0 !important; padding: 0 !important; display: block !important; }
    .page-content { padding: 0 !important; overflow: visible !important; display: block !important; }
    .an-page { gap: 8px !important; display: block !important; }

    /* ── Show print-only elements ── */
    .print-header   { display: flex !important; }
    .print-doc-info { display: block !important; }
    .print-footer   { display: block !important; }
    .chart-interp   { display: block !important; }

    /* ══════════════════════════════
       PAGE 1 — Header + KPIs + Charts
       ══════════════════════════════ */

    /* ── Formal DepEd header ── */
    .print-header {
        align-items: center;
        gap: 10px;
        padding: 6px 0 8px;
        border-bottom: 2.5px solid #003A8C;
        margin-bottom: 3px;
    }
    .print-header-logos  { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .print-header-logo   { width: 54px; height: 54px; object-fit: contain; }
    .print-header-center { flex: 1; text-align: center; }
    .print-header-republic { font-size: 7.5pt; color: #333; }
    .print-header-dept {
        font-size: 11pt; font-weight: 800; color: #003A8C;
        text-transform: uppercase; letter-spacing: .4px; line-height: 1.2;
    }
    .print-header-division { font-size: 8.5pt; font-weight: 600; color: #111; margin-top: 1px; }
    .print-header-school   { font-size: 7.5pt; color: #444; margin-top: 1px; }

    /* ── Document title ── */
    .print-doc-info {
        text-align: center;
        margin: 5px 0 8px;
        padding: 5px 0;
        border-bottom: 2px solid #003A8C;
    }
    .print-doc-title { font-size: 11pt; font-weight: 800; color: #003A8C; text-transform: uppercase; letter-spacing: .8px; }
    .print-doc-sub   { font-size: 8pt; color: #555; margin-top: 2px; }
    .print-doc-meta  { font-size: 7pt; color: #777; margin-top: 2px; }

    /* ── Section labels ── */
    .sec-label { margin: 5px 0 3px !important; }
    .sec-label-text { font-size: 7.5pt !important; color: #003A8C !important; font-weight: 800 !important; }
    .sec-label-line { background: #003A8C !important; opacity: .2; }

    /* ── KPI row — all 4 in one line ── */
    .kpi-grid {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 7px !important;
        margin-bottom: 6px !important;
    }
    .kpi-card {
        box-shadow: none !important;
        border: 1px solid #d0d7e8 !important;
        padding: 8px 10px !important;
        gap: 8px !important;
        break-inside: avoid !important;
    }
    .kpi-card::after { height: 2px !important; }
    .kpi-value { font-size: 20pt !important; letter-spacing: -1px !important; }
    .kpi-label { font-size: 7.5pt !important; }
    .kpi-note  { font-size: 6.5pt !important; margin-top: 3px !important; padding-top: 3px !important; }
    .kpi-icon  { width: 34px !important; height: 34px !important; font-size: 14px !important; border-radius: 8px !important; flex-shrink: 0 !important; }

    /* ── Chart grids — landscape gives us ~257mm usable width ── */
    .g-trend {
        display: grid !important;
        grid-template-columns: 3fr 2fr !important;
        gap: 8px !important;
        margin-bottom: 8px !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }
    .g-three {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 8px !important;
        margin-bottom: 8px !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }
    .g-two {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 8px !important;
        margin-bottom: 6px !important;
    }

    /* ── Chart cards ── */
    .an-card {
        box-shadow: none !important;
        border: 1px solid #d0d7e8 !important;
        overflow: visible !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }
    .an-card-head  { padding: 5px 10px !important; }
    .an-card-title { font-size: 8pt !important; }
    .an-card-badge { font-size: 6.5pt !important; padding: 2px 6px !important; }
    .an-card-body  { padding: 7px 10px 8px !important; }

    /* ── Canvas: JS will resize before print; CSS locks the container ── */
    .chart-wrap {
        position: relative !important;
        height: 118px !important;
        min-height: 0 !important;
        overflow: hidden !important;
    }
    .g-trend .an-card:first-child .chart-wrap { height: 125px !important; }
    /* g-three charts — full page 2, use more height */
    .g-three .an-card .chart-wrap { height: 155px !important; }
    canvas {
        width: 100% !important;
        height: 100% !important;
        max-height: none !important;
    }

    /* ── Donut layout ── */
    .donut-layout      { gap: 10px !important; flex-wrap: nowrap !important; }
    .donut-canvas-wrap { flex: 0 0 95px !important; }
    .dl-label  { font-size: 7.5pt !important; }
    .dl-count  { font-size: 10pt !important; }
    .dl-pct    { font-size: 6.5pt !important; }
    .dl-bar    { height: 4px !important; }
    .dl-dot    { width: 8px !important; height: 8px !important; }

    /* ── Interpretation box ── */
    .chart-interp {
        margin-top: 5px !important;
        padding: 4px 8px !important;
        background: #f8faff !important;
        border-left: 3px solid #003A8C !important;
        border-radius: 0 4px 4px 0 !important;
        font-size: 6.5pt !important;
        color: #334155 !important;
        line-height: 1.45 !important;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .chart-interp strong { color: #003A8C !important; }

    /* ── Legend ── */
    .c-legend   { margin-top: 4px !important; gap: 5px !important; flex-wrap: wrap !important; }
    .c-leg-item { font-size: 6.5pt !important; }
    .c-leg-line { width: 12px !important; }
    .c-leg-dash { width: 12px !important; }
    .info-note  { font-size: 6pt !important; padding: 3px 7px !important; margin-top: 4px !important; }

    /* ══════════════════════════════
       PAGE 2 — Assessment Breakdown (break-after on .g-trend)
       PAGE 3 — Tables
       ══════════════════════════════ */
    .print-hdr2 { display: block !important; }
    .print-page2-break {
        display: block !important;
        page-break-before: always !important;
        break-before: page !important;
    }

    /* ── Teacher table ── */
    .dt, .at { width: 100% !important; border-collapse: collapse !important; }
    .dt thead th, .at thead th {
        font-size: 7.5pt !important;
        padding: 5px 9px !important;
        background: #eef2ff !important;
        color: #1e3a6e !important;
        font-weight: 800 !important;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .dt tbody td, .at tbody td { font-size: 8pt !important; padding: 6px 9px !important; }
    .dt-name { font-size: 8.5pt !important; font-weight: 700 !important; }
    .dt-sub  { font-size: 6.5pt !important; }
    .chip    { font-size: 7pt !important; padding: 2px 6px !important; }
    .s-name  { font-size: 8pt !important; font-weight: 700 !important; }
    .s-sub   { font-size: 6.5pt !important; }
    .s-av    { width: 26px !important; height: 26px !important; }
    .ctag, .rtag { font-size: 7pt !important; padding: 2px 6px !important; }

    /* ── Alert card ── */
    .alert-card   { border-left: 3px solid #C8102E !important; break-inside: avoid !important; }
    .alert-head   { padding: 6px 12px !important; }
    .alert-title  { font-size: 9pt !important; }
    .alert-count  { font-size: 8pt !important; }

    /* ── Print footer signature ── */
    .print-footer {
        margin-top: 12px !important;
        padding-top: 7px !important;
        border-top: 2px solid #003A8C !important;
        page-break-inside: avoid !important;
    }
    .print-sig-row {
        display: flex !important;
        justify-content: space-between !important;
        gap: 30px !important;
        margin-bottom: 6px !important;
    }
    .print-sig-col { flex: 1 !important; }
    .print-sig-label { font-size: 7pt !important; color: #666; margin-bottom: 18px; display: block; }
    .print-sig-line  {
        border-top: 1px solid #333 !important;
        padding-top: 3px !important;
        font-size: 9pt !important;
        font-weight: 800 !important;
        color: #003A8C !important;
    }
    .print-sig-title-text { font-size: 7pt !important; color: #555; margin-top: 1px; display: block; }
    .print-disclaimer {
        text-align: center !important;
        margin-top: 8px !important;
        font-size: 6.5pt !important;
        color: #999 !important;
        border-top: 1px solid #e2e8f0 !important;
        padding-top: 5px !important;
    }

    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
}</style>
@endpush

@section('content')
@php
    $tot = max($riskDistribution['meeting']+$riskDistribution['approaching']+$riskDistribution['below'],1);
    $mp  = round($riskDistribution['meeting']    /$tot*100);
    $ap  = round($riskDistribution['approaching']/$tot*100);
    $bp  = round($riskDistribution['below']      /$tot*100);
    $meetingRate = $totalStudents > 0 ? round($riskDistribution['meeting']/$tot*100) : 0;
@endphp

{{-- ══ FORMAL DEPED PRINT HEADER (hidden on screen, shown on print) ══ --}}
<div class="print-header" style="display:none;">
    <div class="print-header-logos">
        <svg class="print-header-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
            <circle cx="100" cy="100" r="95" fill="#003A8C"/>
            <circle cx="100" cy="100" r="78" fill="#fff"/>
            <circle cx="100" cy="100" r="58" fill="#003A8C"/>
            <circle cx="100" cy="100" r="36" fill="#fff"/>
            <circle cx="100" cy="100" r="20" fill="#C8102E"/>
            <line x1="100" y1="5" x2="100" y2="195" stroke="#fff" stroke-width="3" opacity="0.2"/>
            <line x1="5" y1="100" x2="195" y2="100" stroke="#fff" stroke-width="3" opacity="0.2"/>
        </svg>
        <img src="{{ asset('images/logo.jpg') }}" class="print-header-logo" alt="School Logo">
    </div>
    <div class="print-header-center">
        <div class="print-header-republic">Republic of the Philippines</div>
        <div class="print-header-dept">Department of Education</div>
        <div class="print-header-division">Schools Division of Ilocos Sur</div>
        <div class="print-header-school">Tampugo Elementary School &bull; Candon City, Ilocos Sur</div>
    </div>
</div>

<div class="print-doc-info" style="display:none;">
    <div class="print-doc-title">Reading Progress Monitoring Report</div>
    <div class="print-doc-sub">TESRead — Digital Reading Progress Monitoring System</div>
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
            <div class="kpi-label">Total Students</div>
            <div class="kpi-note neu">
                <i class="fas fa-circle" style="font-size:5px;"></i> Active enrollment
            </div>
        </div>
    </div>
    <div class="kpi-card kpi-green">
        <div class="kpi-icon"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $totalTeachers }}</div>
            <div class="kpi-label">Active Teachers</div>
            <div class="kpi-note pos">
                <i class="fas fa-check-circle"></i> All approved
            </div>
        </div>
    </div>
    <div class="kpi-card kpi-amber">
        <div class="kpi-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $totalAssessments }}</div>
            <div class="kpi-label">Assessments &bull; {{ $year }}</div>
            <div class="kpi-note neu">
                <i class="fas fa-calendar"></i> This school year
            </div>
        </div>
    </div>
    <div class="kpi-card kpi-red">
        <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $activeInterventions }}</div>
            <div class="kpi-label">Active Interventions</div>
            <div class="kpi-note {{ $activeInterventions > 0 ? 'neg' : 'pos' }}">
                <i class="fas fa-{{ $activeInterventions > 0 ? 'bell' : 'check-circle' }}"></i>
                {{ $activeInterventions > 0 ? 'Requires attention' : 'All clear' }}
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
    {{-- Score Trend --}}
    <div class="an-card">
        <div class="an-card-head">
            <div class="an-card-title">
                <i class="fas fa-chart-area"></i> Average Score Trend
            </div>
            <span class="an-card-badge">{{ ucfirst($period) }} &middot; {{ $year }}</span>
        </div>
        <div class="an-card-body">
            <div class="chart-wrap" style="min-height:220px;">
                <canvas id="cTrend"></canvas>
            </div>
            <div class="c-legend">
                <div class="c-leg-item"><div class="c-leg-line" style="background:#003A8C;"></div>Avg Fluency</div>
                <div class="c-leg-item"><div class="c-leg-line" style="background:#C8102E;"></div>Avg Comprehension</div>
                <div class="c-leg-item"><div class="c-leg-dash" style="border-color:#003A8C;"></div>85% Target</div>
                <div class="c-leg-item"><div class="c-leg-dash" style="border-color:#C8102E;"></div>80% Target</div>
            </div>
            <div class="chart-interp">
                <strong>Interpretation:</strong> This line graph shows the average fluency and comprehension scores of all students over time.
                Lines at or above the target thresholds (85% for fluency, 80% for comprehension) indicate that pupils are meeting the expected reading standard.
                A downward trend signals a need for school-wide reading intervention.
            </div>
        </div>
    </div>

    {{-- Risk Donut --}}
    <div class="an-card">
        <div class="an-card-head">
            <div class="an-card-title">
                <i class="fas fa-chart-pie"></i> Risk Distribution
            </div>
            <span class="an-card-badge">Latest per student</span>
        </div>
        <div class="an-card-body">
            <div class="donut-layout">
                <div class="donut-canvas-wrap">
                    <canvas id="cDonut"></canvas>
                </div>
                <div class="donut-legend">
                    @foreach([
                        ['Meeting Standard',   $riskDistribution['meeting'],    '#0d9448', $mp],
                        ['Approaching',        $riskDistribution['approaching'], '#c47d0e', $ap],
                        ['Below Standard',     $riskDistribution['below'],      '#C8102E', $bp],
                    ] as [$lbl, $cnt, $clr, $pct])
                    <div class="dl-item">
                        <div class="dl-row1">
                            <div class="dl-label">
                                <div class="dl-dot" style="background:{{ $clr }};"></div>
                                {{ $lbl }}
                            </div>
                            <span class="dl-count">{{ $cnt }}</span>
                        </div>
                        <div class="dl-bar">
                            <div class="dl-fill" style="width:{{ $pct }}%;background:{{ $clr }};"></div>
                        </div>
                        <div class="dl-pct">{{ $pct }}% of assessed</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="chart-interp">
                <strong>Interpretation:</strong> This chart shows the distribution of pupils by reading risk level based on their latest assessment.
                A higher proportion in <strong style="color:#0d9448;">Meeting Standard</strong> reflects effective literacy instruction.
                Students in <strong style="color:#C8102E;">Below Standard</strong> require immediate targeted reading intervention.
            </div>
        </div>
    </div>
</div>

{{-- ════ ASSESSMENT BREAKDOWN — page break lands here ════ --}}
{{-- Repeat header for page 2 (hidden on screen) --}}
<div class="print-hdr2" style="display:none;margin-bottom:6px;">
    <div style="display:flex;align-items:center;gap:10px;padding:5px 0 7px;border-bottom:2px solid #003A8C;">
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
            <svg style="width:44px;height:44px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                <circle cx="100" cy="100" r="95" fill="#003A8C"/>
                <circle cx="100" cy="100" r="78" fill="#fff"/>
                <circle cx="100" cy="100" r="58" fill="#003A8C"/>
                <circle cx="100" cy="100" r="36" fill="#fff"/>
                <circle cx="100" cy="100" r="20" fill="#C8102E"/>
            </svg>
            <img src="{{ asset('images/logo.jpg') }}" style="width:44px;height:44px;object-fit:contain;" alt="">
        </div>
        <div style="flex:1;text-align:center;">
            <div style="font-size:8pt;color:#333;">Republic of the Philippines — Department of Education</div>
            <div style="font-size:9pt;font-weight:800;color:#003A8C;">Tampugo Elementary School &bull; Schools Division of Ilocos Sur</div>
            <div style="font-size:7.5pt;color:#555;font-style:italic;">Reading Progress Monitoring Report — Continued: Assessment Breakdown</div>
        </div>
    </div>
</div>

<div class="sec-label">
    <div class="sec-label-line"></div>
    <span class="sec-label-text"><i class="fas fa-chart-bar"></i> Assessment Breakdown</span>
    <div class="sec-label-line"></div>
</div>

<div class="g-three">
    {{-- Risk Count Over Time --}}
    <div class="an-card">
        <div class="an-card-head">
            <div class="an-card-title"><i class="fas fa-layer-group"></i> Risk Count Over Time</div>
            <span class="an-card-badge">{{ ucfirst($period) }}</span>
        </div>
        <div class="an-card-body">
            <div class="chart-wrap" style="min-height:210px;">
                <canvas id="cRiskBar"></canvas>
            </div>
            <div class="chart-interp">
                <strong>Interpretation:</strong> Tracks the number of pupils in each risk category per period.
                An increasing <strong style="color:#0d9448;">Meeting</strong> count over time reflects positive literacy growth.
                A growing <strong style="color:#C8102E;">Below Standard</strong> bar is an early warning for intervention planning.
            </div>
        </div>
    </div>

    {{-- Sessions/Week --}}
    <div class="an-card">
        <div class="an-card-head">
            <div class="an-card-title"><i class="fas fa-book-reader"></i> Sessions / Week</div>
            <span class="an-card-badge">{{ $year }}</span>
        </div>
        <div class="an-card-body">
            <div class="chart-wrap" style="min-height:210px;">
                <canvas id="cSessions"></canvas>
            </div>
            <div class="info-note">
                <i class="fas fa-info-circle"></i>
                Students with &le;1 session/week are flagged as <strong>Below Standard</strong>.
            </div>
            <div class="chart-interp">
                <strong>Interpretation:</strong> Shows the frequency of reading sessions per week among pupils.
                Pupils with only 1 or fewer sessions per week are at greater risk of reading difficulties.
                Higher session frequency generally correlates with improved fluency and comprehension scores.
            </div>
        </div>
    </div>

    {{-- Assessment Volume --}}
    <div class="an-card">
        <div class="an-card-head">
            <div class="an-card-title"><i class="fas fa-clipboard-list"></i> Assessment Volume</div>
            <span class="an-card-badge">{{ ucfirst($period) }}</span>
        </div>
        <div class="an-card-body">
            <div class="chart-wrap" style="min-height:210px;">
                <canvas id="cVolume"></canvas>
            </div>
            <div class="chart-interp">
                <strong>Interpretation:</strong> Displays the total number of reading assessments conducted each period.
                Consistent assessment volume indicates active monitoring. Low volume periods may reflect missed assessment opportunities
                or school calendar interruptions requiring make-up assessments.
            </div>
        </div>
    </div>
</div>

{{-- ════ TEACHER OVERVIEW ════ --}}
{{-- Page break is handled automatically by break-after:page on .g-three above --}}
<div class="print-header" style="display:none;">
    <div class="print-header-logos">
        <svg class="print-header-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
            <circle cx="100" cy="100" r="95" fill="#003A8C"/>
            <circle cx="100" cy="100" r="78" fill="#fff"/>
            <circle cx="100" cy="100" r="58" fill="#003A8C"/>
            <circle cx="100" cy="100" r="36" fill="#fff"/>
            <circle cx="100" cy="100" r="20" fill="#C8102E"/>
        </svg>
        <img src="{{ asset('images/logo.jpg') }}" class="print-header-logo" alt="School Logo">
    </div>
    <div class="print-header-center">
        <div class="print-header-republic">Republic of the Philippines</div>
        <div class="print-header-dept">Department of Education</div>
        <div class="print-header-division">Schools Division of Ilocos Sur</div>
        <div class="print-header-school">Tampugo Elementary School &bull; Candon City, Ilocos Sur &mdash; <em>Continued: Teacher &amp; Student Data</em></div>
    </div>
</div>
<div class="sec-label">
    <div class="sec-label-line"></div>
    <span class="sec-label-text"><i class="fas fa-chalkboard-teacher"></i> Teacher Overview</span>
    <div class="sec-label-line"></div>
</div>

<div class="an-card">
    <div class="an-card-head">
        <div class="an-card-title">
            <i class="fas fa-table-cells"></i> Teacher Performance Summary
        </div>
        <span class="an-card-badge">{{ $year }} &bull; {{ count((array)$teacherStats) }} teachers</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="dt">
            <thead>
                <tr>
                    <th style="width:28%;">Teacher</th>
                    <th>Students</th>
                    <th>Assessments</th>
                    <th>Avg Fluency</th>
                    <th>Avg Comp.</th>
                    <th>Below Std.</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teacherStats as $ts)
                <tr>
                    <td>
                        <div class="dt-name">{{ $ts['name'] }}</div>
                        <div class="dt-sub">{{ $ts['assessments'] }} assessments recorded</div>
                    </td>
                    <td><strong style="font-size:15px;">{{ $ts['students'] }}</strong></td>
                    <td><strong style="font-size:15px;">{{ $ts['assessments'] }}</strong></td>
                    <td>
                        @if($ts['avg_fluency'] > 0)
                            <span class="chip {{ $ts['avg_fluency']>=85?'chip-g':($ts['avg_fluency']>=70?'chip-a':'chip-r') }}">
                                {{ $ts['avg_fluency'] }}%
                            </span>
                        @else <span class="chip chip-n">—</span>
                        @endif
                    </td>
                    <td>
                        @if($ts['avg_comp'] > 0)
                            <span class="chip {{ $ts['avg_comp']>=80?'chip-g':($ts['avg_comp']>=65?'chip-a':'chip-r') }}">
                                {{ $ts['avg_comp'] }}%
                            </span>
                        @else <span class="chip chip-n">—</span>
                        @endif
                    </td>
                    <td>
                        @if($ts['below'] > 0)
                            <span class="chip chip-r">
                                <i class="fas fa-exclamation-triangle" style="font-size:10px;margin-right:3px;"></i>
                                {{ $ts['below'] }}
                            </span>
                        @else
                            <span class="chip chip-g"><i class="fas fa-check" style="font-size:10px;margin-right:3px;"></i> None</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-cell">
                        <i class="fas fa-chalkboard-teacher"></i>
                        No teacher data available for {{ $year }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ════ REQUIRES ATTENTION ════ --}}
@if($belowStudents->count())
<div class="sec-label">
    <div class="sec-label-line" style="background:#fecdd3;"></div>
    <span class="sec-label-text red">
        <i class="fas fa-exclamation-triangle"></i> Requires Attention
    </span>
    <div class="sec-label-line" style="background:#fecdd3;"></div>
</div>

<div class="an-card alert-card">
    <div class="alert-head">
        <div class="alert-title">
            <i class="fas fa-user-slash"></i>
            Students Below Expected Standard
        </div>
        <span class="alert-count">{{ $belowStudents->count() }} students</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="at">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Teacher</th>
                    <th>Class</th>
                    <th>Fluency</th>
                    <th>Comprehension</th>
                    <th>Sessions/wk</th>
                    <th>Last Assessed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($belowStudents as $s)
                @php $la = $s->latestAssessment; @endphp
                <tr>
                    <td>
                        <div class="s-row">
                            <img src="{{ $s->profilePhotoUrl() }}" class="s-av">
                            <div>
                                <div class="s-name">{{ $s->fullName() }}</div>
                                <div class="s-sub">{{ $s->lrn ?? 'No LRN' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#374151;">{{ $s->teacher?->name ?? '—' }}</td>
                    <td>
                        @if($s->section)
                            <span class="ctag">Gr.{{ $s->section->grade_level }} – {{ $s->section->name }}</span>
                        @else <span style="color:var(--muted);">—</span>
                        @endif
                    </td>
                    <td><span class="chip chip-r">{{ $la?->fluency_score ?? '—' }}{{ $la ? '%' : '' }}</span></td>
                    <td><span class="chip chip-r">{{ $la?->comprehension_score ?? '—' }}{{ $la ? '%' : '' }}</span></td>
                    <td><span class="rtag">{{ $la?->reading_sessions_per_week ?? '—' }}/wk</span></td>
                    <td style="font-size:12px;color:var(--muted);">
                        {{ $la?->assessed_on?->format('M d, Y') ?? '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
{{-- ══ PRINT FOOTER (hidden on screen) ══ --}}
<div class="print-footer" style="display:none;">
    <div style="text-align:right;font-size:7pt;color:#666;margin-bottom:10px;">
        Generated via TESRead System &bull; Tampugo Elementary School &bull; <span id="printDateFooter"></span>
    </div>
    <div class="print-sig-row">
        <div class="print-sig-col">
            <span class="print-sig-label">Prepared by:</span>
            <div class="print-sig-line">{{ auth()->user()->name }}</div>
            <span class="print-sig-title-text">{{ auth()->user()->role === 'admin' ? 'School Administrator' : 'Teacher-In-Charge' }}</span>
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
/* ── Print helpers ───────────────────────── */
function changePeriod(p) {
    document.getElementById('periodInput').value = p;
    document.getElementById('filterForm').submit();
}

/* ── Chart globals ───────────────────────── */
const B = '#003A8C', R = '#C8102E', G = '#0d9448', A = '#c47d0e';
const GRID_COLOR = '#f1f5f9';

Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";
Chart.defaults.font.size   = 11.5;
Chart.defaults.color       = '#94a3b8';

const TOOLTIP = {
    backgroundColor : '#0f172a',
    titleColor      : '#f8fafc',
    bodyColor       : '#94a3b8',
    padding         : 12,
    cornerRadius    : 10,
    boxPadding      : 5,
    borderColor     : '#1e293b',
    borderWidth     : 1,
    displayColors   : true,
};

const SCALE_Y = {
    grid  : { color: GRID_COLOR, drawBorder: false },
    border: { dash: [4,4], display: false },
    ticks : { padding: 6 },
};
const SCALE_X = { grid: { display: false }, border: { display: false } };

/* ── Data from controller ────────────────── */
const tL  = @json($trendData['labels']);
const tF  = @json($trendData['fluency']);
const tC  = @json($trendData['comp']);
const tT  = @json($trendData['totals']);
const rL  = @json($riskOverTime['labels']);
const rBl = @json($riskOverTime['below']);
const rAp = @json($riskOverTime['approaching']);
const rMt = @json($riskOverTime['meeting']);
const sDist = @json($sessionsDist);
const sK  = Object.keys(sDist).map(Number);
const sV  = Object.values(sDist).map(Number);
const dM  = {{ $riskDistribution['meeting'] }};
const dA  = {{ $riskDistribution['approaching'] }};
const dB  = {{ $riskDistribution['below'] }};

/* 1 ── Score Trend (line) */
new Chart('cTrend', {
    type: 'line',
    data: { labels: tL, datasets: [
        {
            label: 'Avg Fluency',
            data: tF, borderColor: B, backgroundColor: B + '14',
            borderWidth: 2.5, pointBackgroundColor: B, pointRadius: 4,
            pointHoverRadius: 6, tension: .4, fill: true, spanGaps: true
        },
        {
            label: 'Avg Comprehension',
            data: tC, borderColor: R, backgroundColor: R + '0e',
            borderWidth: 2.5, pointBackgroundColor: R, pointRadius: 4,
            pointHoverRadius: 6, tension: .4, fill: true, spanGaps: true
        },
        {
            label: 'Fluency 85%',
            data: Array(tL.length).fill(85),
            borderColor: B + '44', borderDash: [6, 4], borderWidth: 1.5,
            pointRadius: 0, fill: false
        },
        {
            label: 'Comp 80%',
            data: Array(tL.length).fill(80),
            borderColor: R + '44', borderDash: [6, 4], borderWidth: 1.5,
            pointRadius: 0, fill: false
        },
    ]},
    options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: { ...TOOLTIP, callbacks: {
                label: c => '  ' + c.dataset.label + ': ' +
                    (c.parsed.y != null ? c.parsed.y + '%' : 'No data')
            }}
        },
        scales: {
            y: { ...SCALE_Y, min: 0, max: 100, ticks: { callback: v => v + '%', stepSize: 20 } },
            x: SCALE_X,
        }
    }
});

/* 2 ── Risk Donut */
new Chart('cDonut', {
    type: 'doughnut',
    data: {
        labels: ['Meeting', 'Approaching', 'Below'],
        datasets: [{ data: [dM, dA, dB], backgroundColor: [G, A, R],
            borderWidth: 4, borderColor: '#fff', hoverOffset: 8 }]
    },
    options: {
        responsive: true, maintainAspectRatio: true, cutout: '72%',
        plugins: {
            legend: { display: false },
            tooltip: { ...TOOLTIP, callbacks: {
                label: c => '  ' + c.label + ': ' + c.raw + ' students'
            }}
        }
    }
});

/* 3 ── Risk Grouped Bar */
new Chart('cRiskBar', {
    type: 'bar',
    data: { labels: rL, datasets: [
        { label: 'Meeting',     data: rMt, backgroundColor: G + 'cc', borderRadius: 5, borderSkipped: false },
        { label: 'Approaching', data: rAp, backgroundColor: A + 'cc', borderRadius: 5, borderSkipped: false },
        { label: 'Below',       data: rBl, backgroundColor: R + 'cc', borderRadius: 5, borderSkipped: false },
    ]},
    options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } },
            tooltip: TOOLTIP
        },
        scales: {
            y: { ...SCALE_Y, beginAtZero: true, ticks: { stepSize: 1 } },
            x: SCALE_X
        }
    }
});

/* 4 ── Sessions Horizontal Bar */
new Chart('cSessions', {
    type: 'bar',
    data: {
        labels: sK.map(k => k + (k === 1 ? ' session' : ' sessions') + '/wk'),
        datasets: [{
            label: 'Count', data: sV,
            backgroundColor: sK.map(k => k <= 1 ? R + 'cc' : B + 'cc'),
            borderRadius: 5, borderSkipped: false
        }]
    },
    options: {
        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { ...TOOLTIP, callbacks: { label: c => '  ' + c.raw + ' assessments' } }
        },
        scales: {
            x: { ...SCALE_Y, beginAtZero: true, ticks: { stepSize: 1 } },
            y: SCALE_X
        }
    }
});

/* 5 ── Assessment Volume */
new Chart('cVolume', {
    type: 'bar',
    data: {
        labels: tL,
        datasets: [{
            label: 'Assessments', data: tT,
            backgroundColor: tL.map((_, i) => i % 2 === 0 ? B + 'dd' : B + '77'),
            borderRadius: 5, borderSkipped: false
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: TOOLTIP },
        scales: {
            y: { ...SCALE_Y, beginAtZero: true, ticks: { stepSize: 1 } },
            x: SCALE_X
        }
    }
});
/* ── Print date injection ────────────────── */
const now = new Date();
const formatted = now.toLocaleDateString('en-PH', { year:'numeric', month:'long', day:'numeric' });
const withTime  = formatted + ', ' + now.toLocaleTimeString('en-PH', { hour:'2-digit', minute:'2-digit' });
const el1 = document.getElementById('printDate');
const el2 = document.getElementById('printDateFooter');
if (el1) el1.textContent = withTime;
if (el2) el2.textContent = withTime;

/* ── Chart resize for print ──────────────────────────────────────────────
   Chart.js canvases don't respond to CSS @media print height changes.
   We intercept beforeprint, resize every chart to its wrapper's print
   pixel size, trigger a re-render, then restore on afterprint.
   ────────────────────────────────────────────────────────────────────── */
const _allCharts = Chart.instances ? Object.values(Chart.instances) : [];
const _origSizes = new Map();

function resizeChartsForPrint() {
    document.querySelectorAll('.chart-wrap').forEach(wrap => {
        // Read the CSS-locked print height (set by @media print)
        const canvas = wrap.querySelector('canvas');
        if (!canvas) return;
        const chart = Chart.getChart(canvas);
        if (!chart) return;
        _origSizes.set(chart.id, { w: canvas.width, h: canvas.height });
        // Landscape A4 ~257mm wide; chart wraps fill available cols
        // Force canvas to the wrapper's rendered dimensions
        chart.resize(canvas.parentElement.offsetWidth, 148);
        chart.update('none');
    });
    // Score trend gets slightly taller
    const trendCanvas = document.getElementById('cTrend');
    if (trendCanvas) {
        const c = Chart.getChart(trendCanvas);
        if (c) { c.resize(trendCanvas.parentElement.offsetWidth, 155); c.update('none'); }
    }
}

function restoreChartsAfterPrint() {
    document.querySelectorAll('.chart-wrap canvas').forEach(canvas => {
        const chart = Chart.getChart(canvas);
        if (!chart) return;
        chart.resize();   // let Chart.js auto-size back
        chart.update('none');
    });
}

window.addEventListener('beforeprint', resizeChartsForPrint);
window.addEventListener('afterprint',  restoreChartsAfterPrint);
</script>
@endpush