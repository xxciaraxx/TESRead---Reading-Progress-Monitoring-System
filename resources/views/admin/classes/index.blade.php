@extends('layouts.admin')

@section('title', 'Classes')
@section('page-icon', '🏫')
@section('page-heading', 'Classes')

@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
.adm-cls-layout { display: flex; flex-direction: column; height: 100%; }
.adm-cls-table-card { flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }
.adm-cls-table-card .table-wrap { flex: 1; overflow-y: auto; overflow-x: auto; }
.adm-cls-table-card .table-wrap::-webkit-scrollbar { width: 5px; height: 5px; }
.adm-cls-table-card .table-wrap::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }
.adm-cls-table-card .data-table thead th { position: sticky; top: 0; z-index: 2; }
</style>
@endpush

@section('content')

<div class="adm-cls-layout">
<div class="page-header">
    <div>
        <h1>Manage Classes</h1>
        <div class="page-subtitle">All classes at Tampugo Elementary School</div>
    </div>
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Class
    </a>
</div>

{{-- Summary Cards --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="fas fa-chalkboard"></i></div>
        <div class="stat-number">{{ is_object($classes) ? $classes->count() : 0 }}</div>
        <div class="stat-label">Total Classes</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-number">{{ is_object($classes) ? $classes->getCollection()->where('is_active', true)->count() : 0 }}</div>
        <div class="stat-label">Active</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-icon yellow"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-number">{{ is_object($classes) ? $classes->getCollection()->whereNotNull('teacher_id')->count() : 0 }}</div>
        <div class="stat-label">With Teacher</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red"><i class="fas fa-exclamation-circle"></i></div>
        <div class="stat-number">{{ is_object($classes) ? $classes->getCollection()->whereNull('teacher_id')->count() : 0 }}</div>
        <div class="stat-label">Unassigned</div>
    </div>
</div>

<div class="card adm-cls-table-card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Class Name</th>
                    <th>Grade</th>
                    <th>School Year</th>
                    <th>Assigned Teacher</th>
                    <th style="text-align:center;">Students</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $class)
                <tr>
                    <td>
                        <div style="font-weight:700;font-size:14px;">{{ $class->name }}</div>
                        <div style="font-size:11px;color:var(--muted);">
                            Created {{ $class->created_at->format('M d, Y') }}
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-primary" style="font-size:12px;">
                            Grade {{ $class->grade_level }}
                        </span>
                    </td>
                    <td style="font-weight:600;font-size:13px;">{{ $class->school_year }}</td>
                    <td>
                        @if($class->teacher)
                            <div class="user-row">
                                <img src="{{ $class->teacher->profilePhotoUrl() }}"
                                     class="user-avatar-sm" style="width:28px;height:28px;">
                                <div>
                                    <div style="font-size:13px;font-weight:600;">{{ $class->teacher->name }}</div>
                                    <div style="font-size:11px;color:var(--muted);">{{ $class->teacher->email }}</div>
                                </div>
                            </div>
                        @else
                            <span style="font-size:13px;color:var(--muted);font-style:italic;">
                                <i class="fas fa-exclamation-circle" style="color:var(--warning);"></i>
                                Unassigned
                            </span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span style="font-weight:800;font-size:18px;color:var(--primary);">
                            {{ $class->students_count ?? $class->students->count() }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $class->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $class->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                            <a href="{{ route('admin.classes.show', $class) }}"
                               class="btn-icon" title="View Class">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.classes.edit', $class) }}"
                               class="btn-icon" title="Edit Class">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.classes.destroy', $class) }}"
                                  onsubmit="return confirm('Delete class \'{{ addslashes($class->name) }}\'?\nStudents in this class will be unlinked.')">
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
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">🏫</div>
                                <h3>No classes yet</h3>
                                <p>Create your first class and assign a teacher to get started.</p>
                                <a href="{{ route('admin.classes.create') }}"
                                   class="btn btn-primary" style="margin-top:12px;">
                                    <i class="fas fa-plus"></i> Add First Class
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($classes->hasPages())
        <div class="pagination">{{ $classes->links() }}</div>
    @endif
</div>

</div>
@endsection