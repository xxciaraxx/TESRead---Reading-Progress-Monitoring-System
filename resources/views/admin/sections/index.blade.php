@extends('layouts.admin')

@section('title', 'Assign Classes')
@section('page-icon', '🏫')
@section('page-heading', 'Assign Classes')

@section('content')

<div class="page-header">
    <div><h1>Assign Classes</h1></div>
    <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Section
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Section Name</th>
                    <th>Grade Level</th>
                    <th>School Year</th>
                    <th>Assigned Teacher</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $section)
                <tr>
                    <td class="font-semibold">{{ $section->name }}</td>
                    <td><span class="badge badge-info">Grade {{ $section->grade_level }}</span></td>
                    <td class="text-muted text-small">{{ $section->school_year }}</td>
                    <td>
                        @if($section->teacher)
                            <div class="user-row">
                                <img src="{{ $section->teacher->profilePhotoUrl() }}" class="user-avatar-sm">
                                <span class="user-name">{{ $section->teacher->name }}</span>
                            </div>
                        @else
                            <span class="text-muted">Unassigned</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-primary">{{ $section->students()->count() }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $section->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                            <a href="{{ route('admin.sections.edit', $section) }}" class="btn-icon">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.sections.destroy', $section) }}"
                                  onsubmit="return confirm('Delete section {{ addslashes($section->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon danger">
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
                                <h3>No sections yet</h3>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sections->hasPages())
        <div class="pagination">{{ $sections->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
