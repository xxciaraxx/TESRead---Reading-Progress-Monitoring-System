@extends('layouts.admin')

@section('title', $student->fullName())
@section('page-icon', '🎓')
@section('page-heading', 'Student Profile')

@section('content')

<div class="page-header">
    <div><h1>Student Profile</h1></div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary">
            <i class="fas fa-pencil-alt"></i> Edit
        </a>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline">
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
                <h2 style="font-size:19px;font-weight:800;margin-bottom:6px;">{{ $student->fullName() }}</h2>

                @if($student->section)
                    <span class="badge badge-info" style="margin-bottom:6px;">
                        <i class="fas fa-door-open"></i>
                        Grade {{ $student->section->grade_level }} – {{ $student->section->name }}
                    </span>
                @endif
                @if($student->readingLevel)
                    <br>
                    <span class="badge badge-primary" style="margin-top:4px;">
                        <i class="fas fa-book-open"></i> {{ $student->readingLevel->name }}
                    </span>
                @endif
            </div>

            <div style="padding:18px 22px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">LRN</div>
                        <div style="font-size:13.5px;font-weight:600;">{{ $student->lrn ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Gender</div>
                        <div style="font-size:13.5px;font-weight:600;">{{ $student->gender ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Birthdate</div>
                        <div style="font-size:13.5px;font-weight:600;">{{ $student->birthdate?->format('M d, Y') ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Added</div>
                        <div style="font-size:13.5px;font-weight:600;">{{ $student->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>

            @if($student->teacher)
                <div style="padding:14px 20px;border-top:1px solid var(--border);
                            display:flex;align-items:center;gap:10px;background:#f8faff;">
                    <img src="{{ $student->teacher->profilePhotoUrl() }}"
                         style="width:30px;height:30px;border-radius:50%;object-fit:cover;">
                    <div style="flex:1;">
                        <div style="font-size:10px;color:var(--muted);font-weight:600;">Teacher</div>
                        <div style="font-size:13px;font-weight:700;">{{ $student->teacher->name }}</div>
                    </div>
                    <a href="{{ route('admin.teachers.show', $student->teacher) }}" class="btn-icon" title="View Teacher">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            @endif
        </div>

        {{-- Latest Assessment Status --}}
        @php $latest = $student->assessments->first(); @endphp
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-line" style="color:var(--primary);"></i> Reading Status</div>
                @if($latest) <span class="text-muted text-small">{{ $latest->assessed_on?->format('M d, Y') }}</span> @endif
            </div>

            @if($latest)
                @php
                    $risk = $latest->risk_level;
                    $isBelow      = str_contains($risk ?? '', 'Below');
                    $isApproaching = str_contains($risk ?? '', 'Approaching');
                    $riskColor = $isBelow ? 'var(--danger)' : ($isApproaching ? '#b8860b' : 'var(--success)');
                    $riskIcon  = $isBelow ? 'fa-exclamation-triangle' : ($isApproaching ? 'fa-chart-line' : 'fa-star');
                @endphp
                <div style="padding:14px 20px;background:{{ $isBelow ? 'rgba(200,16,46,0.05)' : ($isApproaching ? 'rgba(255,193,7,0.08)' : 'rgba(40,167,69,0.05)') }};
                            border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <i class="fas {{ $riskIcon }}" style="color:{{ $riskColor }};font-size:20px;"></i>
                        <div style="font-weight:800;color:{{ $riskColor }};font-size:13.5px;">{{ $risk ?? 'Not evaluated' }}</div>
                    </div>
                </div>
                <div style="padding:18px 22px;">
                    <div style="margin-bottom:12px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                            <span style="font-size:12.5px;font-weight:600;">Fluency</span>
                            <span style="font-weight:800;color:{{ $latest->fluency_score >= 85 ? 'var(--success)' : ($latest->fluency_score >= 70 ? '#b8860b' : 'var(--danger)') }};">
                                {{ $latest->fluency_score }}%
                            </span>
                        </div>
                        <div class="risk-bar"><div class="risk-bar-fill" style="width:{{ $latest->fluency_score }}%;background:{{ $latest->fluency_score >= 85 ? 'var(--success)' : ($latest->fluency_score >= 70 ? 'var(--warning)' : 'var(--danger)') }};"></div></div>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                            <span style="font-size:12.5px;font-weight:600;">Comprehension</span>
                            <span style="font-weight:800;color:{{ $latest->comprehension_score >= 80 ? 'var(--success)' : ($latest->comprehension_score >= 65 ? '#b8860b' : 'var(--danger)') }};">
                                {{ $latest->comprehension_score }}%
                            </span>
                        </div>
                        <div class="risk-bar"><div class="risk-bar-fill" style="width:{{ $latest->comprehension_score }}%;background:{{ $latest->comprehension_score >= 80 ? 'var(--success)' : ($latest->comprehension_score >= 65 ? 'var(--warning)' : 'var(--danger)') }};"></div></div>
                    </div>
                </div>
            @else
                <div class="empty-state" style="padding:28px;">
                    <div class="empty-state-icon" style="font-size:32px;">📊</div>
                    <h3>No assessments yet</h3>
                </div>
            @endif
        </div>

        {{-- Active Interventions --}}
        @if($student->interventions->where('status','Active')->count())
            <div class="card" style="border-left:4px solid var(--danger);">
                <div class="card-header" style="background:rgba(200,16,46,0.04);">
                    <div class="card-title" style="color:var(--danger);">
                        <i class="fas fa-bell"></i> Active Intervention
                    </div>
                </div>
                @foreach($student->interventions->where('status','Active') as $iv)
                    <div style="padding:14px 20px;">
                        <p style="font-size:13px;line-height:1.6;color:var(--muted);margin-bottom:8px;">
                            {{ Str::limit($iv->intervention_notes, 140) }}
                        </p>
                        <span style="font-size:11px;color:var(--muted);">
                            Started: {{ $iv->started_on?->format('M d, Y') }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Right column: Assessment History --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-clipboard-list" style="color:var(--primary);"></i>
                Assessment History ({{ $student->assessments->count() }})
            </div>
        </div>
        @forelse($student->assessments as $assessment)
            <div style="padding:14px 20px;border-bottom:1px solid var(--border);
                        display:flex;align-items:center;gap:14px;">
                <div style="text-align:center;min-width:44px;">
                    <div style="font-size:18px;font-weight:800;color:var(--primary);line-height:1;">
                        {{ $assessment->assessed_on?->format('d') }}
                    </div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;">
                        {{ $assessment->assessed_on?->format('M Y') }}
                    </div>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;gap:10px;margin-bottom:4px;font-size:12px;color:var(--muted);">
                        <span>Fluency: <strong style="color:{{ $assessment->fluency_score >= 85 ? 'var(--success)' : ($assessment->fluency_score >= 70 ? '#b8860b' : 'var(--danger)') }};">{{ $assessment->fluency_score }}%</strong></span>
                        <span>Comp: <strong style="color:{{ $assessment->comprehension_score >= 80 ? 'var(--success)' : ($assessment->comprehension_score >= 65 ? '#b8860b' : 'var(--danger)') }};">{{ $assessment->comprehension_score }}%</strong></span>
                    </div>
                    @if($assessment->risk_level)
                        @if(str_contains($assessment->risk_level,'Below'))
                            <span class="badge badge-danger" style="font-size:10.5px;"><i class="fas fa-exclamation-triangle"></i> Below Standard</span>
                        @elseif(str_contains($assessment->risk_level,'Approaching'))
                            <span class="badge badge-warning" style="font-size:10.5px;">Approaching</span>
                        @else
                            <span class="badge badge-success" style="font-size:10.5px;"><i class="fas fa-star"></i> Meeting</span>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state" style="padding:36px;">
                <div class="empty-state-icon" style="font-size:36px;">📋</div>
                <h3>No assessments on record</h3>
            </div>
        @endforelse
    </div>

</div>

@endsection
