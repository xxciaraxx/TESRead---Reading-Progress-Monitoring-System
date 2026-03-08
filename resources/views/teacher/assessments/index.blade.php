@extends('layouts.teacher')
@section('title', 'Assessments')
@section('page-icon', '📊')
@section('page-heading', 'Assessments')

@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden !important; display: flex; flex-direction: column; padding-bottom: 0 !important; }
.asmts-layout { display: flex; flex-direction: column; height: 100%; gap: 14px; overflow: hidden; }

/* Overview cards */
.ov-grid { display: grid; grid-template-columns: repeat(6,1fr); gap: 12px; flex-shrink: 0; }
.ov-card {
    background: #fff; border: 1px solid var(--border); border-radius: 12px;
    padding: 14px 16px; display: flex; align-items: flex-start; gap: 12px;
    position: relative; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.ov-card::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; border-radius:0 0 12px 12px; }
.ov-card.ov-blue::after   { background: linear-gradient(90deg,#003A8C,#1a52b3); }
.ov-card.ov-green::after  { background: linear-gradient(90deg,#0d9448,#16a34a); }
.ov-card.ov-amber::after  { background: linear-gradient(90deg,#c47d0e,#d97706); }
.ov-card.ov-red::after    { background: linear-gradient(90deg,#C8102E,#e03355); }
.ov-card.ov-purple::after { background: linear-gradient(90deg,#6d28d9,#7c3aed); }
.ov-icon { width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.ov-blue   .ov-icon { background:rgba(0,58,140,.1);  color:#003A8C; }
.ov-green  .ov-icon { background:rgba(13,148,72,.1); color:#0d9448; }
.ov-amber  .ov-icon { background:rgba(196,125,14,.1);color:#c47d0e; }
.ov-red    .ov-icon { background:rgba(200,16,46,.1); color:#C8102E; }
.ov-purple .ov-icon { background:rgba(109,40,217,.1);color:#6d28d9; }
.ov-value { font-size:22px; font-weight:800; line-height:1; color:#0f172a; letter-spacing:-0.5px; }
.ov-label { font-size:10.5px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-top:4px; }
.ov-sub   { font-size:10.5px; font-weight:600; color:var(--muted); margin-top:5px; padding-top:5px; border-top:1px solid #f1f5f9; display:flex; align-items:center; gap:4px; }
.ov-sub.good { color:#0d9448; }
.ov-sub.warn { color:#c47d0e; }
.ov-sub.bad  { color:#C8102E; }

/* Table card */
.assessments-table-card { flex:1; min-height:0; display:flex; flex-direction:column; overflow:hidden; }
.assessments-table-card .table-wrap { flex:1; overflow-y:auto; overflow-x:auto; }
.assessments-table-card .table-wrap::-webkit-scrollbar { width:5px; }
.assessments-table-card .table-wrap::-webkit-scrollbar-thumb { background:#d1d9f0; border-radius:99px; }

/* Clickable student row */
.student-row-link { cursor: pointer; transition: background .15s; }
.student-row-link:hover td { background: #f5f7ff !important; }
.count-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px; border-radius: 50%;
    background: #eef2ff; color: #3730a3;
    font-size: 10.5px; font-weight: 800;
}
</style>
@endpush

@section('content')
@php
    $tot = max($meeting + $approaching + $below, 1);
    $mp  = round($meeting     / $tot * 100);
    $app = round($approaching / $tot * 100);
    $bp  = round($below       / $tot * 100);
@endphp

<div class="asmts-layout">

<div class="page-header" style="flex-shrink:0;">
    <div>
        <h1>Reading Assessments</h1>
        <div class="page-subtitle">Click a student to view their full assessment history</div>
    </div>
    <a href="{{ route('teacher.assessments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Assessment
    </a>
</div>

{{-- Overview Cards --}}
<div class="ov-grid">
    <div class="ov-card ov-blue">
        <div class="ov-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="ov-body">
            <div class="ov-value">{{ $total }}</div>
            <div class="ov-label">Total Records</div>
            <div class="ov-sub">
                <i class="fas fa-calendar" style="font-size:9px;"></i>
                @if($latest) Last: {{ $latest->assessed_on?->format('M d, Y') }}
                @else No records yet @endif
            </div>
        </div>
    </div>
    <div class="ov-card ov-blue">
        <div class="ov-icon"><i class="fas fa-tachometer-alt"></i></div>
        <div class="ov-body">
            <div class="ov-value">{{ $avgFluency > 0 ? $avgFluency.'%' : '—' }}</div>
            <div class="ov-label">Avg Fluency</div>
            <div class="ov-sub {{ $avgFluency >= 85 ? 'good' : ($avgFluency >= 70 ? 'warn' : ($avgFluency > 0 ? 'bad' : '')) }}">
                <i class="fas fa-circle" style="font-size:7px;"></i> Target: 85%
            </div>
        </div>
    </div>
    <div class="ov-card ov-purple">
        <div class="ov-icon"><i class="fas fa-brain"></i></div>
        <div class="ov-body">
            <div class="ov-value">{{ $avgComp > 0 ? $avgComp.'%' : '—' }}</div>
            <div class="ov-label">Avg Comprehension</div>
            <div class="ov-sub {{ $avgComp >= 80 ? 'good' : ($avgComp >= 65 ? 'warn' : ($avgComp > 0 ? 'bad' : '')) }}">
                <i class="fas fa-circle" style="font-size:7px;"></i> Target: 80%
            </div>
        </div>
    </div>
    <div class="ov-card ov-green">
        <div class="ov-icon"><i class="fas fa-star"></i></div>
        <div class="ov-body">
            <div class="ov-value">{{ $meeting }}</div>
            <div class="ov-label">Meeting Standard</div>
            <div class="ov-sub good"><i class="fas fa-check-circle" style="font-size:9px;"></i> {{ $mp }}% of assessed</div>
        </div>
    </div>
    <div class="ov-card ov-amber">
        <div class="ov-icon"><i class="fas fa-chart-line"></i></div>
        <div class="ov-body">
            <div class="ov-value">{{ $approaching }}</div>
            <div class="ov-label">Approaching</div>
            <div class="ov-sub warn"><i class="fas fa-exclamation-circle" style="font-size:9px;"></i> {{ $app }}% of assessed</div>
        </div>
    </div>
    <div class="ov-card ov-red">
        <div class="ov-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="ov-body">
            <div class="ov-value">{{ $below }}</div>
            <div class="ov-label">Below Standard</div>
            <div class="ov-sub {{ $below > 0 ? 'bad' : 'good' }}">
                <i class="fas fa-{{ $below > 0 ? 'bell' : 'check-circle' }}" style="font-size:9px;"></i>
                {{ $below > 0 ? $bp.'% need intervention' : 'All clear' }}
            </div>
        </div>
    </div>
</div>

{{-- Students Table — one row per student --}}
<div class="card assessments-table-card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Assessments</th>
                    <th>Latest Fluency</th>
                    <th>Latest Comprehension</th>
                    <th>Current Risk</th>
                    <th>Last Assessed</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                @php
                    $la    = $student->latestAssessment;
                    $count = $student->assessments->count();
                @endphp
                <tr class="student-row-link"
                    onclick="window.location='{{ route('teacher.assessments.student-history', $student) }}'">
                    <td>
                        <div class="user-row">
                            <img src="{{ $student->profilePhotoUrl() }}" class="user-avatar-sm" alt="">
                            <div>
                                <div class="user-name">{{ $student->fullName() }}</div>
                                <div class="text-muted text-small">{{ $student->lrn ?? 'No LRN' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted text-small">
                        @if($student->section)
                            Gr.{{ $student->section->grade_level }} – {{ $student->section->name }}
                        @else —
                        @endif
                    </td>
                    <td>
                        <span class="count-badge">{{ $count }}</span>
                    </td>
                    <td>
                        @if($la)
                            @php $f = $la->fluency_score; @endphp
                            <span class="{{ $f >= 85 ? 'risk-meeting' : ($f >= 70 ? 'risk-approaching' : 'risk-below') }}">
                                {{ $f }}%
                            </span>
                        @else <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($la)
                            @php $cv = $la->comprehension_score; @endphp
                            <span class="{{ $cv >= 80 ? 'risk-meeting' : ($cv >= 65 ? 'risk-approaching' : 'risk-below') }}">
                                {{ $cv }}%
                            </span>
                        @else <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($la?->risk_level)
                            @if(str_contains($la->risk_level,'Below'))
                                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Below</span>
                            @elseif(str_contains($la->risk_level,'Approaching'))
                                <span class="badge badge-warning"><i class="fas fa-chart-line"></i> Approaching</span>
                            @else
                                <span class="badge badge-success"><i class="fas fa-star"></i> Meeting</span>
                            @endif
                        @elseif($count === 0)
                            <span class="badge badge-secondary">Not assessed</span>
                        @else
                            <span class="badge badge-secondary">Pending</span>
                        @endif
                    </td>
                    <td class="text-muted text-small">
                        {{ $la?->assessed_on?->format('M d, Y') ?? '—' }}
                    </td>
                    <td onclick="event.stopPropagation()">
                        <a href="{{ route('teacher.assessments.student-history', $student) }}"
                           class="btn-icon" title="View History">
                            <i class="fas fa-history"></i>
                        </a>
                        <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}"
                           class="btn-icon" title="New Assessment" style="color:#003A8C;">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon">📊</div>
                            <h3>No students assigned yet</h3>
                            <p>Add students to your class to start recording assessments.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection