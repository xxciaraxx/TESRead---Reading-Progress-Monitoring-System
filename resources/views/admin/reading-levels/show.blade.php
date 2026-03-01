@extends('layouts.admin')

@section('title', $readingLevel->name)
@section('page-icon', '📚')
@section('page-heading', 'Reading Level Detail')

@section('content')

<div class="page-header">
    <div>
        <h1>Reading Level</h1>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.reading-levels.edit', $readingLevel) }}" class="btn btn-primary">
            <i class="fas fa-pencil-alt"></i> Edit
        </a>
        <a href="{{ route('admin.reading-levels.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="grid-2" style="gap:22px;align-items:start;">

    {{-- Level Info --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            {{-- Colored header strip --}}
            <div style="background:{{ $readingLevel->color_code ?? 'var(--gradient)' }};
                        padding:28px 28px;border-radius:var(--radius) var(--radius) 0 0;
                        display:flex;align-items:center;gap:18px;">
                <div style="width:64px;height:64px;border-radius:50%;
                            background:rgba(255,255,255,0.25);
                            display:flex;align-items:center;justify-content:center;
                            font-size:28px;flex-shrink:0;">
                    📖
                </div>
                <div>
                    <div style="font-size:22px;font-weight:800;color:#fff;margin-bottom:4px;">
                        {{ $readingLevel->name }}
                    </div>
                    <div style="color:rgba(255,255,255,0.80);font-size:14px;">
                        Grade {{ $readingLevel->grade_level }} Level
                    </div>
                </div>
            </div>

            <div style="padding:20px 24px;">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Grade</div>
                        <div style="font-size:18px;font-weight:800;color:var(--primary);">
                            Grade {{ $readingLevel->grade_level }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Students</div>
                        <div style="font-size:28px;font-weight:800;color:var(--primary);">
                            {{ $readingLevel->students->count() }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Status</div>
                        <span class="badge {{ $readingLevel->is_active ? 'badge-success' : 'badge-secondary' }}"
                              style="font-size:13px;padding:5px 14px;margin-top:4px;display:inline-flex;">
                            {{ $readingLevel->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                @if($readingLevel->color_code)
                    <div style="margin-bottom:14px;padding-top:14px;border-top:1px solid var(--border);">
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.8px;color:var(--muted);margin-bottom:8px;">Color</div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:28px;height:28px;border-radius:6px;
                                        background:{{ $readingLevel->color_code }};
                                        border:1px solid rgba(0,0,0,0.1);"></div>
                            <code style="font-size:13px;background:#f0f3fa;
                                         padding:3px 10px;border-radius:6px;">
                                {{ $readingLevel->color_code }}
                            </code>
                        </div>
                    </div>
                @endif

                @if($readingLevel->description)
                    <div style="padding-top:14px;border-top:1px solid var(--border);">
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.8px;color:var(--muted);margin-bottom:8px;">Description</div>
                        <p style="font-size:13.5px;color:var(--text);line-height:1.7;margin:0;">
                            {{ $readingLevel->description }}
                        </p>
                    </div>
                @endif
            </div>

            <div style="padding:14px 20px;border-top:1px solid var(--border);
                        display:flex;gap:10px;background:#f8faff;">
                <a href="{{ route('admin.reading-levels.edit', $readingLevel) }}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-pencil-alt"></i> Edit Level
                </a>
                <form method="POST" action="{{ route('admin.reading-levels.destroy', $readingLevel) }}"
                      onsubmit="return confirm('Delete \'{{ addslashes($readingLevel->name) }}\'?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Students at this level --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-user-graduate" style="color:var(--primary);"></i>
                Enrolled Students
            </div>
            <span class="badge badge-primary">{{ $readingLevel->students->count() }}</span>
        </div>

        @forelse($readingLevel->students as $student)
            <div style="padding:12px 20px;border-bottom:1px solid var(--border);
                        display:flex;align-items:center;gap:12px;">
                <img src="{{ $student->profilePhotoUrl() }}"
                     class="user-avatar-sm" alt="{{ $student->fullName() }}">
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:13.5px;">{{ $student->fullName() }}</div>
                    <div style="font-size:12px;color:var(--muted);">
                        {{ $student->lrn ?? 'No LRN' }}
                        @if($student->section)
                            &nbsp;·&nbsp; Grade {{ $student->section->grade_level }}
                            – {{ $student->section->name }}
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.students.show', $student) }}"
                   class="btn-icon" title="View Student">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        @empty
            <div class="empty-state" style="padding:40px;">
                <div class="empty-state-icon" style="font-size:40px;">🎓</div>
                <h3>No students at this level</h3>
                <p>No students have been assigned the <strong>{{ $readingLevel->name }}</strong> level yet.</p>
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline" style="margin-top:12px;">
                    <i class="fas fa-user-graduate"></i> Manage Students
                </a>
            </div>
        @endforelse
    </div>

</div>

@endsection
