@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-icon', '📋')
@section('page-heading', 'Activity Logs')

@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content {
    flex: 1;
    min-height: 0;
    overflow: hidden !important;
    display: flex;
    flex-direction: column;
    padding-bottom: 0 !important;
}
.logs-outer {
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
}
.logs-table-card {
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
    border-radius: 12px;
    background: #fff;
    border: 1px solid var(--border);
    overflow: hidden;
}
.logs-table-card .table-scroll {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    overflow-x: auto;
}
.logs-table-card .table-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
.logs-table-card .table-scroll::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }
.logs-table-card .data-table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #f8faff;
}
.logs-pagination {
    flex-shrink: 0;
    padding: 10px 20px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12.5px;
    color: var(--muted);
}
.logs-pagination a, .logs-pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 30px;
    padding: 0 8px;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none;
    color: var(--text);
    border: 1px solid var(--border);
    margin: 0 2px;
    transition: all .15s;
}
.logs-pagination a:hover { background: var(--primary); color: #fff; border-color: var(--primary); }
.logs-pagination span[aria-current] { background: var(--primary); color: #fff; border-color: var(--primary); }
.logs-pagination [disabled], .logs-pagination span.disabled { opacity: .4; pointer-events: none; }
</style>
@endpush

@section('content')
<div class="logs-outer">

    {{-- Header --}}
    <div class="page-header" style="flex-shrink:0;">
        <div><h1>Activity Logs</h1></div>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline">
            <i class="fas fa-sync-alt"></i> Refresh
        </a>
    </div>

    {{-- Search bar --}}
    <div class="card" style="padding:14px 20px;margin-bottom:16px;flex-shrink:0;">
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

    {{-- Table card --}}
    <div class="logs-table-card">
        <div class="table-scroll">
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
                                <div class="empty-state" style="padding:48px;">
                                    <div class="empty-state-icon">📋</div>
                                    <h3>No activity logs</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination pinned to bottom of card --}}
        @if($logs->hasPages())
        <div class="logs-pagination">
            <span>Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }}</span>
            <div style="display:flex;align-items:center;gap:4px;">
                {{-- Prev --}}
                @if($logs->onFirstPage())
                    <span class="disabled"><i class="fas fa-chevron-left" style="font-size:10px;"></i></span>
                @else
                    <a href="{{ $logs->withQueryString()->previousPageUrl() }}"><i class="fas fa-chevron-left" style="font-size:10px;"></i></a>
                @endif

                {{-- Pages --}}
                @foreach($logs->withQueryString()->getUrlRange(1, $logs->lastPage()) as $page => $url)
                    @if($page == $logs->currentPage())
                        <span aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($logs->hasMorePages())
                    <a href="{{ $logs->withQueryString()->nextPageUrl() }}"><i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
                @else
                    <span class="disabled"><i class="fas fa-chevron-right" style="font-size:10px;"></i></span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>
@endsection