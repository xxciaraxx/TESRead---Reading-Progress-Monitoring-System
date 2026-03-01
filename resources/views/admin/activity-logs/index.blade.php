@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-icon', '📋')
@section('page-heading', 'Activity Logs')

@section('content')

<div class="page-header">
    <div><h1>Activity Logs</h1></div>
    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline">
        <i class="fas fa-sync-alt"></i> Refresh
    </a>
</div>

<div class="card" style="padding:16px 20px;margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;align-items:center;">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search actions..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        @if(request('search'))
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline btn-sm">Clear</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Subject</th>
                    <th>Performed By</th>
                    <th>IP Address</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0;"></div>
                            <span class="font-semibold">{{ $log->action }}</span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $log->subject ?? '—' }}</td>
                    <td>
                        @if($log->user)
                            <div class="user-row">
                                <img src="{{ $log->user->profilePhotoUrl() }}" class="user-avatar-sm">
                                <span class="user-name">{{ $log->user->name }}</span>
                            </div>
                        @else
                            <span class="badge badge-secondary">System</span>
                        @endif
                    </td>
                    <td class="text-muted text-small">{{ $log->ip_address ?? '—' }}</td>
                    <td class="text-muted text-small">
                        {{ $log->created_at->format('M d, Y g:i A') }}<br>
                        <span style="font-size:11px;">{{ $log->created_at->diffForHumans() }}</span>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">📋</div>
                                <h3>No activity logs</h3>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="pagination">{{ $logs->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
