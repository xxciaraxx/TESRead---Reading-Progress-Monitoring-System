@extends('layouts.teacher')
@section('title', $student->fullName().' — Assessment History')
@section('page-icon', '📋')
@section('page-heading', 'Assessment History')

@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden !important; display: flex; flex-direction: column; padding-bottom: 0 !important; }
.hist-layout { display: flex; flex-direction: column; height: 100%; gap: 14px; overflow: hidden; }

/* Student hero */
.stu-hero {
    background: #fff; border: 1px solid var(--border); border-radius: 14px;
    padding: 16px 20px; display: flex; align-items: center; gap: 18px;
    flex-shrink: 0; box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.stu-hero-avatar { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 3px solid #e8edf8; flex-shrink: 0; }
.stu-hero-name   { font-size: 17px; font-weight: 800; color: #0f172a; }
.stu-hero-meta   { font-size: 12px; color: var(--muted); margin-top: 3px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.stu-hero-meta span { display: flex; align-items: center; gap: 4px; }
.stu-hero-stats  { display: flex; gap: 12px; margin-left: auto; flex-shrink: 0; }
.hs-stat { text-align: center; padding: 8px 14px; background: #f8faff; border-radius: 10px; border: 1px solid #e8edf8; }
.hs-stat-val { font-size: 18px; font-weight: 800; color: #0f172a; line-height: 1; }
.hs-stat-lbl { font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-top: 3px; }

/* Table */
.hist-table-card { flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }
.hist-table-card .table-wrap { flex: 1; overflow-y: auto; overflow-x: auto; }
.hist-table-card .table-wrap::-webkit-scrollbar { width: 5px; }
.hist-table-card .table-wrap::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }

/* Score bars */
.score-cell { display: flex; align-items: center; gap: 8px; }
.score-bar  { flex: 1; height: 5px; background: #f1f5f9; border-radius: 99px; overflow: hidden; max-width: 60px; }
.score-fill { height: 100%; border-radius: 99px; }
</style>
@endpush

@section('content')
@php
    $count      = $assessments->count();
    $avgFluency = $count ? round($assessments->avg('fluency_score'), 1) : null;
    $avgComp    = $count ? round($assessments->avg('comprehension_score'), 1) : null;
    $latest     = $assessments->first();
    $trend      = null;
    if ($count >= 2) {
        $first = $assessments->last();
        $trend = ($latest->fluency_score - $first->fluency_score);
    }
@endphp

<div class="hist-layout">

{{-- Page header --}}
<div class="page-header" style="flex-shrink:0;">
    <div style="display:flex;align-items:center;gap:10px;">
        <a href="{{ route('teacher.assessments.index') }}" class="btn-icon" title="Back" style="font-size:16px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1>{{ $student->fullName() }}</h1>
            <div class="page-subtitle">Full assessment history</div>
        </div>
    </div>
    <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Assessment
    </a>
</div>

{{-- Student Hero Card --}}
<div class="stu-hero">
    <img src="{{ $student->profilePhotoUrl() }}" class="stu-hero-avatar" alt="">
    <div>
        <div class="stu-hero-name">{{ $student->fullName() }}</div>
        <div class="stu-hero-meta">
            @if($student->lrn)
                <span><i class="fas fa-id-card"></i> {{ $student->lrn }}</span>
            @endif
            @if($student->section)
                <span><i class="fas fa-chalkboard"></i> Gr.{{ $student->section->grade_level }} – {{ $student->section->name }}</span>
            @endif
            @if($student->gender)
                <span><i class="fas fa-user"></i> {{ $student->gender }}</span>
            @endif
            @if($student->philIriLabel())
                <span><i class="fas fa-book-open"></i> {{ $student->philIriLabel() }}</span>
            @endif
        </div>
    </div>

    <div class="stu-hero-stats">
        <div class="hs-stat">
            <div class="hs-stat-val">{{ $count }}</div>
            <div class="hs-stat-lbl">Assessments</div>
        </div>
        <div class="hs-stat">
            <div class="hs-stat-val" style="color:{{ $avgFluency >= 85 ? '#0d9448' : ($avgFluency >= 70 ? '#c47d0e' : '#C8102E') }};">
                {{ $avgFluency ? $avgFluency.'%' : '—' }}
            </div>
            <div class="hs-stat-lbl">Avg Fluency</div>
        </div>
        <div class="hs-stat">
            <div class="hs-stat-val" style="color:{{ $avgComp >= 80 ? '#0d9448' : ($avgComp >= 65 ? '#c47d0e' : '#C8102E') }};">
                {{ $avgComp ? $avgComp.'%' : '—' }}
            </div>
            <div class="hs-stat-lbl">Avg Comp.</div>
        </div>
        @if($latest?->risk_level)
        <div class="hs-stat">
            <div class="hs-stat-val" style="font-size:13px;
                color:{{ str_contains($latest->risk_level,'Below') ? '#C8102E' : (str_contains($latest->risk_level,'Approaching') ? '#c47d0e' : '#0d9448') }};">
                @if(str_contains($latest->risk_level,'Below')) Below
                @elseif(str_contains($latest->risk_level,'Approaching')) Approaching
                @else Meeting @endif
            </div>
            <div class="hs-stat-lbl">Current Risk</div>
        </div>
        @endif
        @if($trend !== null)
        <div class="hs-stat">
            <div class="hs-stat-val" style="color:{{ $trend >= 0 ? '#0d9448' : '#C8102E' }};">
                {{ $trend >= 0 ? '+' : '' }}{{ round($trend, 1) }}%
            </div>
            <div class="hs-stat-lbl">Fluency Trend</div>
        </div>
        @endif
    </div>
</div>

{{-- Assessment History Table --}}
<div class="card hist-table-card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date Assessed</th>
                    <th>Fluency Score</th>
                    <th>Comprehension</th>
                    <th>Risk Level</th>
                    <th>Notes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $i => $assessment)
                <tr>
                    <td class="text-muted text-small">{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $assessment->assessed_on?->format('M d, Y') ?? '—' }}</td>
                    <td>
                        @php $f = $assessment->fluency_score; @endphp
                        <div class="score-cell">
                            <span class="{{ $f >= 85 ? 'risk-meeting' : ($f >= 70 ? 'risk-approaching' : 'risk-below') }}">
                                {{ $f }}%
                            </span>
                            <div class="score-bar">
                                <div class="score-fill" style="width:{{ $f }}%;background:{{ $f >= 85 ? '#0d9448' : ($f >= 70 ? '#c47d0e' : '#C8102E') }};"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $cv = $assessment->comprehension_score; @endphp
                        <div class="score-cell">
                            <span class="{{ $cv >= 80 ? 'risk-meeting' : ($cv >= 65 ? 'risk-approaching' : 'risk-below') }}">
                                {{ $cv }}%
                            </span>
                            <div class="score-bar">
                                <div class="score-fill" style="width:{{ $cv }}%;background:{{ $cv >= 80 ? '#0d9448' : ($cv >= 65 ? '#c47d0e' : '#C8102E') }};"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($assessment->risk_level)
                            @if(str_contains($assessment->risk_level,'Below'))
                                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Below</span>
                            @elseif(str_contains($assessment->risk_level,'Approaching'))
                                <span class="badge badge-warning"><i class="fas fa-chart-line"></i> Approaching</span>
                            @else
                                <span class="badge badge-success"><i class="fas fa-star"></i> Meeting</span>
                            @endif
                        @else
                            <span class="badge badge-secondary">Pending</span>
                        @endif
                    </td>
                    <td class="text-muted text-small" style="max-width:200px;">
                        {{ $assessment->notes ? \Str::limit($assessment->notes, 60) : '—' }}
                    </td>
                    <td>
                        <a href="{{ route('teacher.assessments.show', $assessment) }}" class="btn-icon" title="View Full">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">📋</div>
                            <h3>No assessments recorded yet</h3>
                            <p>Start tracking {{ $student->first_name }}'s reading progress.</p>
                            <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}"
                               class="btn btn-primary" style="margin-top:12px;">
                                <i class="fas fa-plus"></i> Record First Assessment
                            </a>
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