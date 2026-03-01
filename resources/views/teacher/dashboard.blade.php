@extends('layouts.teacher')

@section('title', 'My Dashboard')
@section('page-icon', '🏠')
@section('page-heading', 'My Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Hello, {{ Str::words(auth()->user()->name, 1, '') }}! 👋</h2>
        <p>Here's your classroom reading progress overview.</p>
        <div class="welcome-badge">
            <i class="fas fa-book-open"></i>
            {{ $totalStudents }} Student{{ $totalStudents !== 1 ? 's' : '' }} in your class
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="stat-grid" style="margin-bottom:28px;">
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

<div class="grid-2" style="gap:22px;">

    {{-- Students Needing Attention --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-exclamation-circle" style="color:var(--danger);"></i>
                Needs Attention
            </div>
            <a href="{{ route('teacher.students.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>

        @forelse($studentsNeedingAttention as $student)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
                <img src="{{ $student->profilePhotoUrl() }}" class="avatar avatar-sm">
                <div style="flex:1;">
                    <div class="font-semibold" style="font-size:13.5px;">{{ $student->fullName() }}</div>
                    <div style="font-size:12px;color:var(--muted);">
                        Fluency: {{ $student->latestAssessment?->fluency_score ?? '—' }} |
                        Comprehension: {{ $student->latestAssessment?->comprehension_score ?? '—' }}
                    </div>
                </div>
                <span class="badge badge-danger">Below</span>
                <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}"
                   class="btn btn-primary btn-xs">Assess</a>
            </div>
        @empty
            <div class="empty-state" style="padding:32px;">
                <div class="empty-state-icon">🎉</div>
                <h3>Great job!</h3>
                <p>No students are currently below standard.</p>
            </div>
        @endforelse
    </div>

    {{-- Recent Assessments --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-clipboard-check" style="color:var(--primary);"></i>
                Recent Assessments
            </div>
            <a href="{{ route('teacher.assessments.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New
            </a>
        </div>

        @forelse($recentAssessments as $assessment)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
                <img src="{{ $assessment->student?->profilePhotoUrl() }}" class="avatar avatar-sm">
                <div style="flex:1;">
                    <div class="font-semibold" style="font-size:13.5px;">{{ $assessment->student?->fullName() ?? '—' }}</div>
                    <div style="font-size:12px;color:var(--muted);">
                        {{ $assessment->assessed_on?->format('M d, Y') }}
                    </div>
                </div>
                @if($assessment->risk_level)
                    @if(str_contains($assessment->risk_level,'Below'))
                        <span class="badge badge-danger">Below</span>
                    @elseif(str_contains($assessment->risk_level,'Approaching'))
                        <span class="badge badge-warning">Approaching</span>
                    @else
                        <span class="badge badge-success">Meeting</span>
                    @endif
                @endif
            </div>
        @empty
            <div class="empty-state" style="padding:32px;">
                <div class="empty-state-icon">📊</div>
                <h3>No assessments yet</h3>
                <p>Start assessing your students' reading progress.</p>
            </div>
        @endforelse
    </div>

</div>

@endsection
