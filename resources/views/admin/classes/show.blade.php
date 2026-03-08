@extends('layouts.admin')

@section('title', $class->name)
@section('page-icon', '🏫')
@section('page-heading', 'Class Information')

@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
.scroll-body { flex: 1; min-height: 0; overflow-y: auto; overflow-x: hidden; padding-bottom: 24px; }
.scroll-body::-webkit-scrollbar { width: 5px; }
.scroll-body::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }

/* Class hero banner */
.class-hero {
    background: linear-gradient(135deg, #06143a 0%, #003A8C 50%, #C8102E 100%);
    border-radius: 14px 14px 0 0;
    padding: 24px 28px;
    position: relative;
    overflow: hidden;
}
.class-hero::before {
    content: ''; position: absolute;
    right: -40px; top: -40px;
    width: 180px; height: 180px;
    background: rgba(255,255,255,.05); border-radius: 50%;
}
.class-hero::after {
    content: ''; position: absolute;
    right: 60px; bottom: -60px;
    width: 140px; height: 140px;
    background: rgba(200,16,46,.15); border-radius: 50%;
}
.class-hero-icon {
    width: 56px; height: 56px; border-radius: 14px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.22);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(0,0,0,.2);
}
.class-hero-name {
    font-family: 'Sora', sans-serif;
    font-size: 22px; font-weight: 800; color: #fff;
    letter-spacing: -.4px; margin-bottom: 4px;
}
.class-hero-sub { font-size: 13px; color: rgba(255,255,255,.65); font-weight: 500; }

/* Stat tiles inside class card */
.class-stats { display: grid; grid-template-columns: repeat(3,1fr); }
.cs-tile {
    padding: 18px 16px; text-align: center;
    border-right: 1px solid var(--border);
}
.cs-tile:last-child { border-right: none; }
.cs-val {
    font-family: 'Sora', sans-serif;
    font-size: 28px; font-weight: 800; color: var(--primary);
    line-height: 1; margin-bottom: 5px;
}
.cs-lbl {
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px; color: var(--muted);
}

/* Risk bars */
.risk-row { margin-bottom: 14px; }
.risk-row:last-child { margin-bottom: 0; }
.risk-bar-bg { height: 8px; background: #f1f5f9; border-radius: 99px; overflow: hidden; margin: 5px 0 3px; }
.risk-bar-fill { height: 100%; border-radius: 99px; transition: width .6s cubic-bezier(.4,0,.2,1); }

/* Roster rows */
.roster-row {
    padding: 12px 20px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 12px;
    transition: background .12s;
}
.roster-row:hover { background: #fafbff; }
.roster-row:last-of-type { border-bottom: none; }
</style>
@endpush

@section('content')

<div style="display:flex;flex-direction:column;height:100%;">
<div class="page-header">
    <div>
        <h1>{{ $class->name }}</h1>
        <div class="page-subtitle">Grade {{ $class->grade_level }} &nbsp;·&nbsp; {{ $class->school_year }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-primary">
            <i class="fas fa-pencil-alt"></i> Edit Class
        </a>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="scroll-body">
<div class="grid-2" style="gap:22px;align-items:start;">

    {{-- LEFT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Class Info Card --}}
        <div class="card" style="overflow:hidden;">
            <div class="class-hero">
                <div style="display:flex;align-items:center;gap:16px;position:relative;z-index:1;">
                    <div class="class-hero-icon">🏫</div>
                    <div>
                        <div class="class-hero-name">{{ $class->name }}</div>
                        <div class="class-hero-sub">
                            Grade {{ $class->grade_level }} &nbsp;·&nbsp; {{ $class->school_year }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="class-stats">
                <div class="cs-tile">
                    <div class="cs-val">{{ $class->students->count() }}</div>
                    <div class="cs-lbl">Students</div>
                </div>
                <div class="cs-tile">
                    <div class="cs-val">{{ $class->grade_level }}</div>
                    <div class="cs-lbl">Grade</div>
                </div>
                <div class="cs-tile" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;">
                    <span class="badge {{ $class->is_active ? 'badge-success' : 'badge-secondary' }}"
                          style="font-size:12px;padding:5px 14px;">
                        {{ $class->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <div class="cs-lbl">Status</div>
                </div>
            </div>
        </div>

        {{-- Assigned Teacher --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-chalkboard-teacher" style="color:var(--primary);"></i>
                    Assigned Teacher
                </div>
                <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-exchange-alt"></i> Change
                </a>
            </div>

            @if($class->teacher)
                <div style="padding:20px;display:flex;align-items:center;gap:16px;">
                    <img src="{{ $class->teacher->profilePhotoUrl() }}"
                         style="width:60px;height:60px;border-radius:50%;object-fit:cover;
                                border:3px solid var(--border);flex-shrink:0;">
                    <div style="flex:1;">
                        <div style="font-size:16px;font-weight:800;margin-bottom:3px;">
                            {{ $class->teacher->name }}
                        </div>
                        <div style="font-size:12.5px;color:var(--muted);margin-bottom:8px;">
                            {{ $class->teacher->email }}
                        </div>
                        <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> Approved
                        </span>
                    </div>
                    <a href="{{ route('admin.teachers.show', $class->teacher) }}"
                       class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
            @else
                <div class="empty-state" style="padding:32px;">
                    <div class="empty-state-icon">👨‍🏫</div>
                    <h3>No teacher assigned</h3>
                    <p>This class doesn't have a teacher yet.</p>
                    <a href="{{ route('admin.classes.edit', $class) }}"
                       class="btn btn-primary" style="margin-top:12px;">
                        <i class="fas fa-plus"></i> Assign Teacher
                    </a>
                </div>
            @endif
        </div>

        {{-- Reading Risk Summary --}}
        @php
            $total       = $class->students->count();
            $meeting     = $class->students->filter(fn($s) => $s->latestAssessment && str_contains($s->latestAssessment->risk_level ?? '', 'Meeting'))->count();
            $approaching = $class->students->filter(fn($s) => $s->latestAssessment && str_contains($s->latestAssessment->risk_level ?? '', 'Approaching'))->count();
            $below       = $class->students->filter(fn($s) => $s->latestAssessment && str_contains($s->latestAssessment->risk_level ?? '', 'Below'))->count();
            $unassessed  = $class->students->filter(fn($s) => !$s->latestAssessment)->count();
            $safeTotal   = max($total, 1);
        @endphp

        @if($total > 0)
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-chart-bar" style="color:var(--primary);"></i>
                    Reading Risk Summary
                </div>
            </div>
            <div style="padding:20px;">
                @foreach([
                    ['Meeting Standard', $meeting,     '#16a34a', 'fas fa-star'],
                    ['Approaching',      $approaching,  '#d97706', 'fas fa-chart-line'],
                    ['Below Standard',   $below,        '#C8102E', 'fas fa-exclamation-triangle'],
                    ['Not Yet Assessed', $unassessed,   '#94a3b8', 'fas fa-clock'],
                ] as [$label, $count, $color, $icon])
                <div class="risk-row">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:28px;height:28px;border-radius:8px;
                                        background:{{ $color }}18;
                                        display:flex;align-items:center;justify-content:center;">
                                <i class="{{ $icon }}" style="font-size:11px;color:{{ $color }};"></i>
                            </div>
                            <span style="font-size:13px;font-weight:600;color:var(--text);">{{ $label }}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span style="font-family:'Sora',sans-serif;font-size:16px;font-weight:800;color:{{ $color }};">
                                {{ $count }}
                            </span>
                            <span style="font-size:11px;color:var(--muted);font-weight:600;min-width:34px;text-align:right;">
                                {{ round($count/$safeTotal*100) }}%
                            </span>
                        </div>
                    </div>
                    <div class="risk-bar-bg">
                        <div class="risk-bar-fill"
                             style="width:{{ round($count/$safeTotal*100) }}%;background:{{ $color }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT COLUMN: Student Roster --}}
    <div class="card" style="overflow:hidden;">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-user-graduate" style="color:var(--primary);"></i>
                Student Roster
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="badge badge-primary">{{ $class->students->count() }} students</span>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add
                </a>
            </div>
        </div>

        @forelse($class->students->sortBy('last_name') as $student)
            @php $la = $student->latestAssessment; @endphp
            <div class="roster-row">
                <img src="{{ $student->profilePhotoUrl() }}"
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;
                            border:2px solid var(--border);flex-shrink:0;">
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:700;font-size:13px;color:var(--text);">
                        {{ $student->fullName() }}
                    </div>
                    <div style="font-size:11px;color:var(--muted);">
                        {{ $student->gender ?? 'No gender' }}
                        @if($student->lrn) &nbsp;·&nbsp; LRN: {{ $student->lrn }} @endif
                    </div>
                </div>

                <span class="badge badge-primary" style="font-size:10px;">
                    {{ $student->philIriLabel() }}
                </span>

                @if($la && $la->risk_level)
                    @if(str_contains($la->risk_level, 'Below'))
                        <span class="badge badge-danger" style="font-size:10px;">
                            <i class="fas fa-exclamation-triangle"></i> Below
                        </span>
                    @elseif(str_contains($la->risk_level, 'Approaching'))
                        <span class="badge badge-warning" style="font-size:10px;">Approaching</span>
                    @else
                        <span class="badge badge-success" style="font-size:10px;">
                            <i class="fas fa-star"></i> Meeting
                        </span>
                    @endif
                @else
                    <span class="badge badge-secondary" style="font-size:10px;">Unassessed</span>
                @endif

                <a href="{{ route('admin.students.show', $student) }}"
                   class="btn-icon" title="View Profile">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        @empty
            <div class="empty-state" style="padding:48px;">
                <div class="empty-state-icon">🎓</div>
                <h3>No students enrolled</h3>
                <p>No students have been assigned to {{ $class->name }} yet.</p>
                <a href="{{ route('admin.students.create') }}"
                   class="btn btn-primary" style="margin-top:14px;">
                    <i class="fas fa-plus"></i> Add Student
                </a>
            </div>
        @endforelse
    </div>

</div>
</div>
</div>

@endsection