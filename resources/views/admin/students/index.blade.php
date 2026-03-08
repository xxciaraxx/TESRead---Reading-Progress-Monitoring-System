@extends('layouts.admin')

@section('title', 'Students')
@section('page-icon', '🎓')
@section('page-heading', 'Students')

@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
.adm-stu-layout { display: flex; flex-direction: column; height: 100%; }
.adm-stu-table-card { flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }
.adm-stu-table-card .table-wrap { flex: 1; overflow-y: auto; overflow-x: auto; }
.adm-stu-table-card .table-wrap::-webkit-scrollbar { width: 5px; height: 5px; }
.adm-stu-table-card .table-wrap::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }
.adm-stu-table-card .data-table thead th { position: sticky; top: 0; z-index: 2; }
</style>
@endpush

@section('content')

<div class="adm-stu-layout">
<div class="page-header">
    <div>
        <h1>{{ $showArchived ? 'Archived Students' : 'Students' }}</h1>
    </div>
    <div class="d-flex gap-12">
        <a href="{{ route('admin.students.index', ['archived' => !$showArchived]) }}" class="btn btn-outline">
            <i class="fas fa-{{ $showArchived ? 'list' : 'archive' }}"></i>
            {{ $showArchived ? 'Active Students' : 'Archived' }}
        </a>
        @if(!$showArchived)
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Student
            </a>
        @endif
    </div>
</div>

{{-- Filters --}}
<div class="card" style="padding:16px 20px;margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <input type="hidden" name="archived" value="{{ $showArchived ? '1' : '0' }}">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Name or LRN..."
                   value="{{ request('search') }}">
        </div>
        <select name="section_id" class="form-control" style="width:auto;border-radius:8px;padding:9px 38px 9px 14px;">
            <option value="">All Classes</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                    Grade {{ $section->grade_level }} – {{ $section->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['search','section_id']))
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline btn-sm">Clear</a>
        @endif
    </form>
</div>

<div class="card adm-stu-table-card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>LRN</th>
                    <th>Grade / Class</th>
                    <th>Reading Level</th>
                    <th>Risk Level</th>
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
                                <div class="user-email">{{ $student->gender }} · {{ $student->birthdate?->format('M d, Y') ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted text-small">{{ $student->lrn ?? '—' }}</td>
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
                        <span class="badge badge-primary">{{ $student->philIriLabel() }}</span>
                    </td>
                    <td>
                        @if($student->latestAssessment)
                            @php $risk = $student->latestAssessment->risk_level; @endphp
                            @if(str_contains($risk, 'Below'))
                                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Below</span>
                            @elseif(str_contains($risk, 'Approaching'))
                                <span class="badge badge-warning"><i class="fas fa-chart-line"></i> Approaching</span>
                            @else
                                <span class="badge badge-success"><i class="fas fa-star"></i> Meeting</span>
                            @endif
                        @else
                            <span class="badge badge-secondary">Not assessed</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                            <a href="{{ route('admin.students.show', $student) }}" class="btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$showArchived)
                                <a href="{{ route('admin.students.edit', $student) }}" class="btn-icon" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.students.archive', $student) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-icon" title="Archive" style="color:#b8860b;">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.students.restore', $student) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-icon success" title="Restore">
                                        <i class="fas fa-undo"></i>
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
                                <div class="empty-state-icon">🎓</div>
                                <h3>No students found</h3>
                                <p>Try adjusting your search filters.</p>
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

</div>
@endsection