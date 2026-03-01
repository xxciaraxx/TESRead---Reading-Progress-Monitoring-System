@extends('layouts.teacher')

@section('title', 'Interventions')
@section('page-icon', '🤝')
@section('page-heading', 'Interventions')

@section('content')

<div class="page-header">
    <div>
        <h1>Interventions</h1>
        <div class="page-subtitle">Reading support plans for your students</div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
    <div class="stat-card red">
        <div class="stat-icon red"><i class="fas fa-bell"></i></div>
        <div class="stat-number">{{ $activeCount }}</div>
        <div class="stat-label">Active Interventions</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-number">{{ $completedCount }}</div>
        <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="fas fa-list-ul"></i></div>
        <div class="stat-number">{{ $activeCount + $completedCount }}</div>
        <div class="stat-label">Total Records</div>
    </div>
</div>

{{-- Active Interventions Alert --}}
@if($activeCount > 0)
    <div class="alert alert-danger" style="margin-bottom:20px;">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>{{ $activeCount }} student{{ $activeCount > 1 ? 's' : '' }}</strong>
        currently {{ $activeCount > 1 ? 'have' : 'has' }} an active reading intervention.
        Review and update their progress regularly.
    </div>
@endif

{{-- Filters --}}
<div class="card" style="padding:16px 20px;margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search student name..."
                   value="{{ request('search') }}">
        </div>

        <select name="status" class="form-control"
                style="width:auto;border-radius:8px;padding:9px 38px 9px 14px;">
            <option value="">All Status</option>
            <option value="Active"    {{ request('status') === 'Active'    ? 'selected' : '' }}>Active</option>
            <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>

        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('teacher.interventions.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif
    </form>
</div>

{{-- Interventions Table --}}
<div class="card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Started</th>
                    <th>Last Assessment</th>
                    <th>Notes Preview</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($interventions as $intervention)
                <tr>
                    <td>
                        <div class="user-row">
                            <img src="{{ $intervention->student?->profilePhotoUrl() }}"
                                 alt="{{ $intervention->student?->fullName() }}"
                                 class="user-avatar-sm">
                            <div>
                                <div class="user-name">{{ $intervention->student?->fullName() ?? '—' }}</div>
                                @if($intervention->student?->section)
                                    <div class="user-email">
                                        Grade {{ $intervention->student->section->grade_level }}
                                        – {{ $intervention->student->section->name }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($intervention->status === 'Active')
                            <span class="badge badge-danger">
                                <i class="fas fa-circle" style="font-size:7px;"></i> Active
                            </span>
                        @elseif($intervention->status === 'Completed')
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                        @else
                            <span class="badge badge-secondary">
                                <i class="fas fa-ban"></i> Cancelled
                            </span>
                        @endif
                    </td>
                    <td class="text-muted text-small">
                        {{ $intervention->started_on?->format('M d, Y') }}<br>
                        <span style="font-size:11px;">{{ $intervention->started_on?->diffForHumans() }}</span>
                    </td>
                    <td class="text-muted text-small">
                        @if($intervention->assessment)
                            @php $risk = $intervention->assessment->risk_level; @endphp
                            @if(str_contains($risk ?? '', 'Below'))
                                <span class="badge badge-danger" style="font-size:10px;">Below</span>
                            @elseif(str_contains($risk ?? '', 'Approaching'))
                                <span class="badge badge-warning" style="font-size:10px;">Approaching</span>
                            @else
                                <span class="badge badge-success" style="font-size:10px;">Meeting</span>
                            @endif
                            <div style="font-size:11px;color:var(--muted);margin-top:2px;">
                                {{ $intervention->assessment->assessed_on?->format('M d, Y') }}
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td style="max-width:200px;">
                        <span style="font-size:12.5px;color:var(--muted);line-height:1.5;">
                            {{ $intervention->intervention_notes
                                ? Str::limit($intervention->intervention_notes, 70)
                                : '—' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                            <a href="{{ route('teacher.interventions.show', $intervention) }}"
                               class="btn-icon" title="View & Edit">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if($intervention->status === 'Active')
                                <form method="POST"
                                      action="{{ route('teacher.interventions.complete', $intervention) }}"
                                      onsubmit="return confirm('Mark this intervention as completed?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-icon success" title="Mark Complete">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">🤝</div>
                                <h3>No interventions found</h3>
                                <p>Interventions are automatically created when a student is assessed
                                   as <strong>Below Expected Literacy Standard</strong>.</p>
                                <a href="{{ route('teacher.assessments.create') }}"
                                   class="btn btn-primary" style="margin-top:12px;">
                                    <i class="fas fa-clipboard-check"></i> Record Assessment
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($interventions->hasPages())
        <div class="pagination">{{ $interventions->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
