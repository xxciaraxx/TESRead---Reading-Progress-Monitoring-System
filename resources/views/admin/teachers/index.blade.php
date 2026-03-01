@extends('layouts.admin')

@section('title', 'Teacher Accounts')
@section('page-icon', '👨‍🏫')
@section('page-heading', 'Teacher Accounts')

@section('content')

<div class="page-header">
    <div>
        <h1>Teacher Accounts</h1>
    </div>
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Teacher
    </a>
</div>

{{-- Filters --}}
<div class="card" style="padding:16px 20px;margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search name or email..."
                   value="{{ request('search') }}">
        </div>

        <select name="status" class="form-control" style="width:auto;border-radius:8px;padding:9px 38px 9px 14px;">
            <option value="">All Status</option>
            <option value="Pending"  {{ request('status') === 'Pending'  ? 'selected' : '' }}>Pending</option>
            <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
            <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>

        @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline btn-sm">
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
                    <th>Teacher</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Students</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
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
                    <td>
                        <span class="badge badge-{{ strtolower($teacher->account_status) }}">
                            @if($teacher->account_status === 'Approved') <i class="fas fa-check-circle"></i>
                            @elseif($teacher->account_status === 'Pending') <i class="fas fa-clock"></i>
                            @else <i class="fas fa-ban"></i>
                            @endif
                            {{ $teacher->account_status }}
                        </span>
                    </td>
                    <td class="text-muted text-small">
                        {{ $teacher->created_at->format('M d, Y') }}<br>
                        <span style="font-size:11px;">{{ $teacher->created_at->diffForHumans() }}</span>
                    </td>
                    <td>
                        <span class="badge badge-primary">{{ $teacher->students_count ?? $teacher->students()->count() }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;">
                            {{-- Quick approve/reject for pending --}}
                            @if($teacher->account_status === 'Pending')
                                <form method="POST" action="{{ route('admin.teachers.approve', $teacher) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-success btn-xs" title="Approve">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.teachers.reject', $teacher) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-danger btn-xs" title="Reject">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn-icon" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($teacher->name) }}? This cannot be undone.')">
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
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">👨‍🏫</div>
                                <h3>No teachers found</h3>
                                <p>Try adjusting your filters or add a new teacher account.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($teachers->hasPages())
        <div class="pagination">
            {{ $teachers->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
