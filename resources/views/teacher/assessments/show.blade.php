@extends('layouts.teacher')

@section('title', 'Assessment Result')
@section('page-icon', '📊')
@section('page-heading', 'Assessment Result')


@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
.scroll-body { flex: 1; min-height: 0; overflow-y: auto; overflow-x: hidden; padding-bottom: 20px; }
.scroll-body::-webkit-scrollbar { width: 5px; }
.scroll-body::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }
</style>
@endpush
@section('content')

<div style="display:flex;flex-direction:column;height:100%;">
<div class="page-header">
    <div><h1>Assessment Result</h1></div>
    <div class="d-flex gap-12">
        <a href="{{ route('teacher.assessments.create', ['student_id' => $assessment->student_id]) }}"
           class="btn btn-outline">
            <i class="fas fa-redo"></i> Re-assess
        </a>
        <a href="{{ route('teacher.assessments.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="scroll-body">
<div style="max-width:760px;">

    {{-- Risk Level Hero --}}
    @php
        $risk = $assessment->risk_level;
        $isBelow      = str_contains($risk, 'Below');
        $isApproaching = str_contains($risk, 'Approaching');
        $isMeeting    = str_contains($risk, 'Meeting');
        $riskColor    = $isBelow ? 'var(--danger)' : ($isApproaching ? '#b8860b' : 'var(--success)');
        $riskBg       = $isBelow ? 'rgba(200,16,46,0.06)' : ($isApproaching ? 'rgba(255,193,7,0.10)' : 'rgba(40,167,69,0.06)');
        $riskIcon     = $isBelow ? 'fa-exclamation-triangle' : ($isApproaching ? 'fa-chart-line' : 'fa-star');
    @endphp

    <div class="card" style="margin-bottom:20px;border-left:5px solid {{ $riskColor }};">
        <div style="padding:24px 28px;display:flex;align-items:center;gap:20px;background:{{ $riskBg }};border-radius:0 14px 14px 0;">
            <div style="width:60px;height:60px;border-radius:50%;background:{{ $riskColor }}20;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas {{ $riskIcon }}" style="font-size:26px;color:{{ $riskColor }};"></i>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:4px;">Risk Level</div>
                <div style="font-size:18px;font-weight:800;color:{{ $riskColor }};">
                    {{ $assessment->risk_level ?? 'Not evaluated' }}
                </div>
            </div>

            @if($assessment->intervention)
                <div class="badge badge-danger" style="margin-left:auto;">
                    <i class="fas fa-bell"></i> Intervention Active
                </div>
            @endif
        </div>
    </div>

    <div class="grid-2" style="gap:20px;">

        {{-- Student Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-user-graduate"></i> Student</div>
            </div>
            <div style="padding:20px;display:flex;align-items:center;gap:16px;">
                <img src="{{ $assessment->student?->profilePhotoUrl() }}"
                     class="avatar" style="width:60px;height:60px;border:3px solid var(--border);">
                <div>
                    <div class="font-bold" style="font-size:16px;">{{ $assessment->student?->fullName() }}</div>
                    <div class="text-muted text-small">
                        {{ $assessment->student?->section?->name ?? 'No class' }}
                    </div>
                    <span class="badge badge-primary" style="margin-top:6px;">
                            {{ $assessment->philIriLabel() }}
                        </span>
                </div>
            </div>
        </div>

        {{-- Scores --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-bar"></i> Scores</div>
                <span class="text-muted text-small">{{ $assessment->assessed_on?->format('F j, Y') }}</span>
            </div>
            <div style="padding:20px;">
                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span class="font-semibold" style="font-size:13px;">Fluency Score</span>
                        <span class="font-bold" style="font-size:16px;color:{{ $assessment->fluency_score >= 85 ? 'var(--success)' : ($assessment->fluency_score >= 70 ? '#b8860b' : 'var(--danger)') }};">
                            {{ $assessment->fluency_score }}%
                        </span>
                    </div>
                    <div class="risk-bar">
                        <div class="risk-bar-fill" style="width:{{ min($assessment->fluency_score, 100) }}%;background:{{ $assessment->fluency_score >= 85 ? 'var(--success)' : ($assessment->fluency_score >= 70 ? 'var(--warning)' : 'var(--danger)') }};"></div>
                    </div>
                </div>

                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span class="font-semibold" style="font-size:13px;">Comprehension Score</span>
                        <span class="font-bold" style="font-size:16px;color:{{ $assessment->comprehension_score >= 80 ? 'var(--success)' : ($assessment->comprehension_score >= 65 ? '#b8860b' : 'var(--danger)') }};">
                            {{ $assessment->comprehension_score }}%
                        </span>
                    </div>
                    <div class="risk-bar">
                        <div class="risk-bar-fill" style="width:{{ min($assessment->comprehension_score, 100) }}%;background:{{ $assessment->comprehension_score >= 80 ? 'var(--success)' : ($assessment->comprehension_score >= 65 ? 'var(--warning)' : 'var(--danger)') }};"></div>
                    </div>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="font-semibold" style="font-size:13px;">Sessions per Week</span>
                    <span class="badge {{ $assessment->reading_sessions_per_week <= 1 ? 'badge-danger' : 'badge-success' }}">
                        {{ $assessment->reading_sessions_per_week }}/week
                    </span>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($assessment->notes)
        <div class="card" style="grid-column:span 2;">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-sticky-note"></i> Observations</div>
            </div>
            <div class="card-body">
                <p style="font-size:13.5px;line-height:1.7;color:var(--text);">{{ $assessment->notes }}</p>
            </div>
        </div>
        @endif

        {{-- Intervention Alert --}}
        @if($assessment->intervention)
        <div class="card" style="grid-column:span 2;border-left:4px solid var(--danger);">
            <div class="card-header" style="background:rgba(200,16,46,0.04);">
                <div class="card-title" style="color:var(--danger);">
                    <i class="fas fa-bell"></i> Intervention Created
                </div>
                <span class="badge badge-{{ strtolower($assessment->intervention->status) === 'active' ? 'danger' : 'secondary' }}">
                    {{ $assessment->intervention->status }}
                </span>
            </div>
            <div class="card-body">
                <p style="font-size:13.5px;color:var(--text);">{{ $assessment->intervention->intervention_notes }}</p>
                <p class="text-muted text-small" style="margin-top:8px;">
                    Started: {{ $assessment->intervention->started_on?->format('F j, Y') }}
                </p>
            </div>
        </div>
        @endif

    </div>
</div>

</div>
</div>
@endsection