@extends('layouts.teacher')

@section('title', $student->fullName())
@section('page-icon', '🎓')
@section('page-heading', 'Student Profile')

@section('content')

<div class="page-header">
    <div>
        <h1>Student Profile</h1>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}"
           class="btn btn-primary">
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

<div class="grid-2" style="gap:22px;align-items:start;">

    {{-- Left column --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Profile Card --}}
        <div class="card">
            <div style="padding:28px;text-align:center;border-bottom:1px solid var(--border);">
                <img src="{{ $student->profilePhotoUrl() }}"
                     style="width:90px;height:90px;border-radius:50%;object-fit:cover;
                            border:4px solid var(--border);margin:0 auto 16px;display:block;">

                <h2 style="font-size:19px;font-weight:800;margin-bottom:4px;">
                    {{ $student->fullName() }}
                </h2>

                @if($student->section)
                    <div style="margin-bottom:8px;">
                        <span class="badge badge-info">
                            <i class="fas fa-door-open"></i>
                            Grade {{ $student->section->grade_level }} – {{ $student->section->name }}
                        </span>
                    </div>
                @endif

                @if($student->readingLevel)
                    <span class="badge badge-primary" style="font-size:12px;">
                        <i class="fas fa-book-open"></i>
                        {{ $student->readingLevel->name }}
                    </span>
                @endif
            </div>

            <div style="padding:18px 22px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">LRN</div>
                        <div style="font-size:13.5px;font-weight:600;">{{ $student->lrn ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Gender</div>
                        <div style="font-size:13.5px;font-weight:600;">{{ $student->gender ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Birthdate</div>
                        <div style="font-size:13.5px;font-weight:600;">
                            {{ $student->birthdate?->format('M d, Y') ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Enrolled</div>
                        <div style="font-size:13.5px;font-weight:600;">
                            {{ $student->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Current Reading Status --}}
        @php
            $latest = $student->assessments->first();
        @endphp
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-chart-line" style="color:var(--primary);"></i>
                    Current Reading Status
                </div>
                @if($latest)
                    <span class="text-muted text-small">{{ $latest->assessed_on?->format('M d, Y') }}</span>
                @endif
            </div>

            @if($latest)
                @php
                    $risk = $latest->risk_level;
                    $isBelow      = str_contains($risk ?? '', 'Below');
                    $isApproaching = str_contains($risk ?? '', 'Approaching');
                    $riskColor = $isBelow ? 'var(--danger)' : ($isApproaching ? '#b8860b' : 'var(--success)');
                    $riskBg    = $isBelow ? 'rgba(200,16,46,0.06)' : ($isApproaching ? 'rgba(255,193,7,0.10)' : 'rgba(40,167,69,0.06)');
                    $riskIcon  = $isBelow ? 'fa-exclamation-triangle' : ($isApproaching ? 'fa-chart-line' : 'fa-star');
                @endphp
                <div style="padding:16px 20px;background:{{ $riskBg }};border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:42px;height:42px;border-radius:50%;
                                    background:{{ $riskColor }}20;
                                    display:flex;align-items:center;justify-content:center;">
                            <i class="fas {{ $riskIcon }}" style="color:{{ $riskColor }};font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--muted);font-weight:600;">Risk Level</div>
                            <div style="font-weight:800;color:{{ $riskColor }};font-size:13.5px;">
                                {{ $risk ?? 'Not evaluated' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div style="padding:18px 22px;">
                    <div style="margin-bottom:14px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                            <span style="font-size:12.5px;font-weight:600;">Fluency Score</span>
                            <span style="font-weight:800;font-size:14px;
                                color:{{ $latest->fluency_score >= 85 ? 'var(--success)' :
                                         ($latest->fluency_score >= 70 ? '#b8860b' : 'var(--danger)') }};">
                                {{ $latest->fluency_score }}%
                            </span>
                        </div>
                        <div class="risk-bar">
                            <div class="risk-bar-fill" style="width:{{ $latest->fluency_score }}%;
                                background:{{ $latest->fluency_score >= 85 ? 'var(--success)' :
                                             ($latest->fluency_score >= 70 ? 'var(--warning)' : 'var(--danger)') }};"></div>
                        </div>
                    </div>

                    <div style="margin-bottom:14px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                            <span style="font-size:12.5px;font-weight:600;">Comprehension Score</span>
                            <span style="font-weight:800;font-size:14px;
                                color:{{ $latest->comprehension_score >= 80 ? 'var(--success)' :
                                         ($latest->comprehension_score >= 65 ? '#b8860b' : 'var(--danger)') }};">
                                {{ $latest->comprehension_score }}%
                            </span>
                        </div>
                        <div class="risk-bar">
                            <div class="risk-bar-fill" style="width:{{ $latest->comprehension_score }}%;
                                background:{{ $latest->comprehension_score >= 80 ? 'var(--success)' :
                                             ($latest->comprehension_score >= 65 ? 'var(--warning)' : 'var(--danger)') }};"></div>
                        </div>
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

        {{-- Active Interventions --}}
        @php
            $activeInterventions = $student->interventions->where('status', 'Active');
        @endphp
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
                        <span style="font-size:11px;color:var(--muted);">
                            Started: {{ $intervention->started_on?->format('M d, Y') }}
                        </span>
                        <a href="{{ route('teacher.interventions.show', $intervention) }}"
                           class="btn btn-outline btn-xs">View</a>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

    </div>

    {{-- Right column --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Assessment History --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-clipboard-list" style="color:var(--primary);"></i>
                    Assessment History
                </div>
                <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New
                </a>
            </div>

            @forelse($student->assessments as $assessment)
                <div style="padding:14px 20px;border-bottom:1px solid var(--border);
                            display:flex;align-items:center;gap:14px;">
                    {{-- Date --}}
                    <div style="text-align:center;min-width:46px;">
                        <div style="font-size:18px;font-weight:800;color:var(--primary);line-height:1;">
                            {{ $assessment->assessed_on?->format('d') }}
                        </div>
                        <div style="font-size:10px;color:var(--muted);text-transform:uppercase;">
                            {{ $assessment->assessed_on?->format('M Y') }}
                        </div>
                    </div>

                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;gap:8px;margin-bottom:4px;flex-wrap:wrap;">
                            <span style="font-size:12px;color:var(--muted);">
                                F: <strong style="color:{{ $assessment->fluency_score >= 85 ? 'var(--success)' : ($assessment->fluency_score >= 70 ? '#b8860b' : 'var(--danger)') }};">
                                    {{ $assessment->fluency_score }}%
                                </strong>
                            </span>
                            <span style="font-size:12px;color:var(--muted);">
                                C: <strong style="color:{{ $assessment->comprehension_score >= 80 ? 'var(--success)' : ($assessment->comprehension_score >= 65 ? '#b8860b' : 'var(--danger)') }};">
                                    {{ $assessment->comprehension_score }}%
                                </strong>
                            </span>
                            <span style="font-size:12px;color:var(--muted);">
                                Sessions: <strong>{{ $assessment->reading_sessions_per_week }}/wk</strong>
                            </span>
                        </div>
                        @if($assessment->risk_level)
                            @if(str_contains($assessment->risk_level, 'Below'))
                                <span class="badge badge-danger" style="font-size:10.5px;">
                                    <i class="fas fa-exclamation-triangle"></i> Below Standard
                                </span>
                            @elseif(str_contains($assessment->risk_level, 'Approaching'))
                                <span class="badge badge-warning" style="font-size:10.5px;">
                                    <i class="fas fa-chart-line"></i> Approaching
                                </span>
                            @else
                                <span class="badge badge-success" style="font-size:10.5px;">
                                    <i class="fas fa-star"></i> Meeting Standard
                                </span>
                            @endif
                        @endif
                    </div>

                    <a href="{{ route('teacher.assessments.show', $assessment) }}"
                       class="btn-icon" title="View assessment">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            @empty
                <div class="empty-state" style="padding:32px;">
                    <div class="empty-state-icon">📋</div>
                    <h3>No assessments yet</h3>
                </div>
            @endforelse
        </div>

        {{-- Intervention History --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-hands-helping" style="color:var(--primary);"></i>
                    Intervention History
                </div>
                <a href="{{ route('teacher.interventions.index') }}" class="btn btn-outline btn-sm">
                    View All
                </a>
            </div>

            @forelse($student->interventions as $intervention)
                <div style="padding:14px 20px;border-bottom:1px solid var(--border);">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                        <span class="badge {{ $intervention->status === 'Active' ? 'badge-danger' :
                                              ($intervention->status === 'Completed' ? 'badge-success' : 'badge-secondary') }}">
                            {{ $intervention->status }}
                        </span>
                        <span style="font-size:11px;color:var(--muted);">
                            {{ $intervention->started_on?->format('M d, Y') }}
                        </span>
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

@endsection
