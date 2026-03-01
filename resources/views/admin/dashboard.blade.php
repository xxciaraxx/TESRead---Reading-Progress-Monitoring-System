@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-icon', '🏠')
@section('page-heading', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Hi, {{ Str::words(auth()->user()->name, 2, '') }}! 👋</h2>
        <p>Welcome back to TESRead. Here's what's happening at Tampugo Elementary School.</p>
        <div class="welcome-badge">
            <i class="fas fa-calendar-check"></i>
            S.Y. {{ date('Y') . '-' . (date('Y') + 1) }}
        </div>
    </div>
</div>

{{-- ── Teacher Stats ── --}}
<div style="margin-bottom:8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">
    Teacher Accounts
</div>
<div class="stat-grid" style="margin-bottom:28px;">
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-number">{{ $totalTeachers }}</div>
        <div class="stat-label">Total Teachers</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
        <div class="stat-number">{{ $pendingTeachers }}</div>
        <div class="stat-label">Pending Approval</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
        <div class="stat-number">{{ $approvedTeachers }}</div>
        <div class="stat-label">Approved Teachers</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red"><i class="fas fa-user-times"></i></div>
        <div class="stat-number">{{ $rejectedTeachers }}</div>
        <div class="stat-label">Rejected Teachers</div>
    </div>
</div>

{{-- ── Student Reading Stats ── --}}
<div style="margin-bottom:8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">
    Student Reading Overview
</div>
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:28px;">
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

{{-- ── Two Column: Pending Approvals + Activity Log ── --}}
<div class="grid-2" style="gap:22px;">

    {{-- Pending Teacher Registrations --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-user-clock" style="color:var(--warning);"></i>
                Pending Approvals
            </div>
            <a href="{{ route('admin.teachers.index', ['status' => 'Pending']) }}" class="btn btn-outline btn-sm">
                View All
            </a>
        </div>

        @if($recentTeachers->where('account_status','Pending')->count() > 0)
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTeachers->where('account_status','Pending') as $teacher)
                        <tr>
                            <td>
                                <div class="user-row">
                                    <img src="{{ $teacher->profilePhotoUrl() }}"
                                         alt="{{ $teacher->name }}"
                                         class="user-avatar-sm">
                                    <div>
                                        <div class="user-name">{{ $teacher->name }}</div>
                                        <div class="user-email">{{ $teacher->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted text-small">
                                {{ $teacher->created_at->diffForHumans() }}
                            </td>
                            <td>
                                <div class="d-flex gap-8">
                                    <form method="POST" action="{{ route('admin.teachers.approve', $teacher) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn-icon success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.teachers.reject', $teacher) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn-icon danger" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state" style="padding:36px;">
                <div class="empty-state-icon">✅</div>
                <h3>All caught up!</h3>
                <p>No pending teacher registrations.</p>
            </div>
        @endif
    </div>

    {{-- Recent Activity --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-history" style="color:var(--primary);"></i>
                Recent Activity
            </div>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline btn-sm">
                View All
            </a>
        </div>
        <div style="overflow-y:auto;max-height:340px;">
            @forelse($recentLogs as $log)
                <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
                    <div style="width:34px;height:34px;background:rgba(0,58,140,0.08);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;">
                        <i class="fas fa-bolt" style="color:var(--primary);"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:13px;color:var(--text);">{{ $log->action }}</div>
                        @if($log->subject)
                            <div style="font-size:12px;color:var(--muted);">{{ $log->subject }}</div>
                        @endif
                        <div style="font-size:11px;color:#b0b9d0;margin-top:2px;">
                            {{ $log->user?->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding:36px;">
                    <div class="empty-state-icon">📋</div>
                    <h3>No activity yet</h3>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
