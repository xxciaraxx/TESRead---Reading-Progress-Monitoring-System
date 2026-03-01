@extends('layouts.teacher')

@section('title', 'My Students')
@section('page-icon', '🎓')
@section('page-heading', 'My Students')

@section('content')

<div class="page-header">
    <div>
        <h1>My Students</h1>
        <div class="page-subtitle">{{ auth()->user()->name }}'s class roster</div>
    </div>
    <a href="{{ route('teacher.students.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Student
    </a>
</div>

{{-- Quick Stats --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-number">{{ $students->total() }}</div>
        <div class="stat-label">Total Students</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><i class="fas fa-star"></i></div>
        <div class="stat-number">
            {{ $students->getCollection()->filter(fn($s) =>
                $s->latestAssessment && str_contains($s->latestAssessment->risk_level ?? '', 'Meeting')
            )->count() }}
        </div>
        <div class="stat-label">Meeting Standard</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-icon yellow"><i class="fas fa-chart-line"></i></div>
        <div class="stat-number">
            {{ $students->getCollection()->filter(fn($s) =>
                $s->latestAssessment && str_contains($s->latestAssessment->risk_level ?? '', 'Approaching')
            )->count() }}
        </div>
        <div class="stat-label">Approaching</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-number">
            {{ $students->getCollection()->filter(fn($s) =>
                $s->latestAssessment && str_contains($s->latestAssessment->risk_level ?? '', 'Below')
            )->count() }}
        </div>
        <div class="stat-label">Below Standard</div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="padding:16px 20px;margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Name or LRN..."
                   value="{{ request('search') }}">
        </div>

        <select name="section_id" class="form-control"
                style="width:auto;border-radius:8px;padding:9px 38px 9px 14px;">
            <option value="">All Sections</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}"
                    {{ request('section_id') == $section->id ? 'selected' : '' }}>
                    Grade {{ $section->grade_level }} – {{ $section->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>

        @if(request()->hasAny(['search', 'section_id']))
            <a href="{{ route('teacher.students.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Section</th>
                    <th>Reading Level</th>
                    <th>Latest Risk</th>
                    <th>Last Assessed</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>
                        <div class="user-row">
                            <img src="{{ $student->profilePhotoUrl() }}"
                                 alt="{{ $student->fullName() }}"
                                 class="user-avatar-sm">
                            <div>
                                <div class="user-name">{{ $student->fullName() }}</div>
                                <div class="user-email">
                                    {{ $student->gender ?? 'No gender' }}
                                    @if($student->lrn) · LRN: {{ $student->lrn }} @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($student->section)
                            <span class="badge badge-info">
                                Grade {{ $student->section->grade_level }} – {{ $student->section->name }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($student->readingLevel)
                            <span class="badge badge-primary">{{ $student->readingLevel->name }}</span>
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </td>
                    <td>
                        @if($student->latestAssessment && $student->latestAssessment->risk_level)
                            @php $r = $student->latestAssessment->risk_level; @endphp
                            @if(str_contains($r, 'Below'))
                                <span class="badge badge-danger">
                                    <i class="fas fa-exclamation-triangle"></i> Below
                                </span>
                            @elseif(str_contains($r, 'Approaching'))
                                <span class="badge badge-warning">
                                    <i class="fas fa-chart-line"></i> Approaching
                                </span>
                            @else
                                <span class="badge badge-success">
                                    <i class="fas fa-star"></i> Meeting
                                </span>
                            @endif
                        @else
                            <span class="badge badge-secondary">Not assessed</span>
                        @endif
                    </td>
                    <td class="text-muted text-small">
                        @if($student->latestAssessment)
                            {{ $student->latestAssessment->assessed_on?->format('M d, Y') }}
                            <br>
                            <span style="font-size:11px;">
                                {{ $student->latestAssessment->assessed_on?->diffForHumans() }}
                            </span>
                        @else
                            <span class="text-muted">Never</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;">
                            <a href="{{ route('teacher.students.show', $student) }}"
                               class="btn-icon" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('teacher.assessments.create', ['student_id' => $student->id]) }}"
                               class="btn btn-primary btn-xs" title="Assess">
                                <i class="fas fa-clipboard-check"></i> Assess
                            </a>

                            <a href="{{ route('teacher.students.edit', $student) }}"
                               class="btn-icon" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            <form method="POST" action="{{ route('teacher.students.destroy', $student) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($student->fullName()) }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">🎓</div>
                                <h3>No students found</h3>
                                <p>
                                    @if(request()->hasAny(['search', 'section_id']))
                                        Try adjusting your search filters.
                                    @else
                                        You haven't added any students yet. Get started!
                                    @endif
                                </p>
                                <a href="{{ route('teacher.students.create') }}"
                                   class="btn btn-primary" style="margin-top:12px;">
                                    <i class="fas fa-plus"></i> Add First Student
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
        <div class="pagination">{{ $students->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
