@extends('layouts.admin')

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
</style>
@endpush

@section('content')
<div style="display:flex;flex-direction:column;height:100%;">
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

<div class="scroll-body">
<div class="profile-grid">

    {{-- LEFT: Profile --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Profile Card --}}
        <div class="card">
            <div style="padding:28px;text-align:center;border-bottom:1px solid var(--border);">
                <img src="{{ $student->profilePhotoUrl() }}"
                     style="width:88px;height:88px;border-radius:50%;object-fit:cover;
                            border:4px solid var(--border);margin:0 auto 14px;display:block;">
                <h2 style="font-size:18px;font-weight:800;margin-bottom:8px;">{{ $student->fullName() }}</h2>
                @if($student->section)
                    <span class="badge badge-info" style="margin-bottom:6px;">
                        <i class="fas fa-door-open"></i>
                        Grade {{ $student->section->grade_level }} – {{ $student->section->name }}
                    </span><br>
                @endif
                <span class="badge badge-primary" style="margin-top:4px;">
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
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:3px;">Added</div>
                        <div style="font-size:13px;font-weight:600;">{{ $student->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
            @if($student->teacher)
                <div style="padding:12px 20px;border-top:1px solid var(--border);
                            display:flex;align-items:center;gap:10px;background:#f8faff;">
                    <img src="{{ $student->teacher->profilePhotoUrl() }}"
                         style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
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


        {{-- Active Intervention --}}
        @if($student->interventions->where('status','Active')->count())
        <div class="card" style="border-left:4px solid var(--danger);">
            <div class="card-header" style="background:rgba(200,16,46,0.04);">
                <div class="card-title" style="color:var(--danger);">
                    <i class="fas fa-bell"></i> Active Intervention
                </div>
            </div>
            @foreach($student->interventions->where('status','Active') as $iv)
                <div style="padding:14px 20px;">
                    <p style="font-size:13px;line-height:1.6;color:var(--muted);margin-bottom:6px;">
                        {{ Str::limit($iv->intervention_notes, 140) }}
                    </p>
                    <span style="font-size:11px;color:var(--muted);">Started: {{ $iv->started_on?->format('M d, Y') }}</span>
                </div>
            @endforeach
        </div>
        @endif

    </div>

    {{-- RIGHT: Assessment History --}}
    <div class="card" style="overflow:hidden;">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-clipboard-list" style="color:var(--primary);"></i>
                Assessment History ({{ $student->assessments->count() }})
            </div>
        </div>
        @forelse($student->assessments as $assessment)
            <div style="padding:14px 20px;border-bottom:1px solid var(--border);
                        display:flex;align-items:center;gap:14px;transition:background .12s;"
                 onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background=''">
                <div style="text-align:center;min-width:44px;">
                    <div style="font-size:18px;font-weight:800;color:var(--primary);line-height:1;">
                        {{ $assessment->assessed_on?->format('d') }}
                    </div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;">
                        {{ $assessment->assessed_on?->format('M Y') }}
                    </div>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;gap:10px;margin-bottom:5px;font-size:12px;color:var(--muted);">
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
                <a href="{{ route('admin.students.show', $assessment->id) }}" class="btn-icon" title="View">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        @empty
            <div class="empty-state" style="padding:40px;">
                <div class="empty-state-icon">📋</div>
                <h3>No assessments on record</h3>
            </div>
        @endforelse
        {{-- Reading Status --}}
        @php $latest = $student->assessments->first(); @endphp
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-line" style="color:var(--primary);"></i> Reading Status</div>
                @if($latest)<span class="text-muted text-small">{{ $latest->assessed_on?->format('M d, Y') }}</span>@endif
            </div>
            @if($latest)
                @php
                    $risk = $latest->risk_level;
                    $isBelow      = str_contains($risk ?? '', 'Below');
                    $isApproaching = str_contains($risk ?? '', 'Approaching');
                    $riskColor = $isBelow ? 'var(--danger)' : ($isApproaching ? '#b8860b' : 'var(--success)');
                    $riskBg    = $isBelow ? 'rgba(200,16,46,0.05)' : ($isApproaching ? 'rgba(255,193,7,0.08)' : 'rgba(40,167,69,0.05)');
                    $riskIcon  = $isBelow ? 'fa-exclamation-triangle' : ($isApproaching ? 'fa-chart-line' : 'fa-star');
                @endphp
                <div style="padding:14px 20px;background:{{ $riskBg }};border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:{{ $riskColor }}20;
                                    display:flex;align-items:center;justify-content:center;">
                            <i class="fas {{ $riskIcon }}" style="color:{{ $riskColor }};font-size:15px;"></i>
                        </div>
                        <div style="font-weight:800;color:{{ $riskColor }};font-size:13px;">{{ $risk ?? 'Not evaluated' }}</div>
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
                    <div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span style="font-size:12.5px;font-weight:600;">Comprehension</span>
                            <span style="font-weight:800;color:{{ $latest->comprehension_score >= 80 ? 'var(--success)' : ($latest->comprehension_score >= 65 ? '#b8860b' : 'var(--danger)') }};">{{ $latest->comprehension_score }}%</span>
                        </div>
                        <div class="risk-bar"><div class="risk-bar-fill" style="width:{{ $latest->comprehension_score }}%;background:{{ $latest->comprehension_score >= 80 ? 'var(--success)' : ($latest->comprehension_score >= 65 ? 'var(--warning)' : 'var(--danger)') }};"></div></div>
                    </div>
                </div>
            @else
                <div class="empty-state" style="padding:28px;">
                    <div class="empty-state-icon">📊</div>
                    <h3>No assessments yet</h3>
                </div>
            @endif
        </div>

    </div>

</div>
</div>
</div>
@endsection