@extends('layouts.admin')

@section('title', 'Reading Levels')
@section('page-icon', '📚')
@section('page-heading', 'Reading Levels')

@section('content')

<div class="page-header">
    <div><h1>Reading Levels</h1></div>
    <a href="{{ route('admin.reading-levels.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Level
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Level Name</th>
                    <th>Grade Level</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($levels as $level)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:12px;height:12px;border-radius:50%;background:{{ $level->color_code }};flex-shrink:0;"></div>
                            <div class="font-semibold">{{ $level->name }}</div>
                        </div>
                        @if($level->description)
                            <div class="text-muted text-small" style="padding-left:22px;">{{ Str::limit($level->description, 60) }}</div>
                        @endif
                    </td>
                    <td><span class="badge badge-info">Grade {{ $level->grade_level }}</span></td>
                    <td><span class="badge badge-primary">{{ $level->students_count }}</span></td>
                    <td>
                        <span class="badge {{ $level->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $level->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                            <a href="{{ route('admin.reading-levels.edit', $level) }}" class="btn-icon">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.reading-levels.destroy', $level) }}"
                                  onsubmit="return confirm('Delete this reading level?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">📚</div>
                                <h3>No reading levels</h3>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($levels->hasPages())
        <div class="pagination">{{ $levels->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
