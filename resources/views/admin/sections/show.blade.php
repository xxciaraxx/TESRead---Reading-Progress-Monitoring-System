@extends('layouts.admin')

@section('title', $section->name)
@section('page-icon', '🏫')
@section('page-heading', 'Section Detail')

@section('content')

<div class="page-header">
    <div><h1>{{ $section->name }}</h1></div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-primary">
            <i class="fas fa-pencil-alt"></i> Edit
        </a>
        <a href="{{ route('admin.sections.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="grid-2" style="gap:22px;align-items:start;">

    {{-- Section Info --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div style="background:var(--gradient);padding:24px 28px;border-radius:var(--radius) var(--radius) 0 0;">
                <div style="font-size:28px;font-weight:800;color:#fff;margin-bottom:4px;">{{ $section->name }}</div>
                <div style="color:rgba(255,255,255,0.80);font-size:14px;">Grade {{ $section->grade_level }} · {{ $section->school_year }}</div>
            </div>
            <div style="padding:20px 24px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Grade Level</div>
                        <div style="font-size:16px;font-weight:800;color:var(--primary);">Grade {{ $section->grade_level }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">School Year</div>
                        <div style="font-size:16px;font-weight:800;">{{ $section->school_year }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Students</div>
                        <div style="font-size:28px;font-weight:800;color:var(--primary);">{{ $section->students->count() }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--muted);margin-bottom:3px;">Status</div>
                        <span class="badge {{ $section->is_active ? 'badge-success' : 'badge-secondary' }}" style="font-size:13px;padding:5px 14px;">
                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Assigned Teacher --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chalkboard-teacher" style="color:var(--primary);"></i> Assigned Teacher</div>
                <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-exchange-alt"></i> Change
                </a>
            </div>
            @if($section->teacher)
                <div style="padding:20px;display:flex;align-items:center;gap:16px;">
                    <img src="{{ $section->teacher->profilePhotoUrl() }}"
                         style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:3px solid var(--border);">
                    <div>
                        <div style="font-size:16px;font-weight:700;">{{ $section->teacher->name }}</div>
                        <div class="text-muted text-small">{{ $section->teacher->email }}</div>
                        <span class="badge badge-approved" style="margin-top:6px;">
                            <i class="fas fa-check-circle"></i> Approved
                        </span>
                    </div>
                    <a href="{{ route('admin.teachers.show', $section->teacher) }}"
                       class="btn-icon" style="margin-left:auto;" title="View Teacher">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            @else
                <div class="empty-state" style="padding:28px;">
                    <div class="empty-state-icon" style="font-size:32px;">👨‍🏫</div>
                    <h3>No teacher assigned</h3>
                    <p>Assign an approved teacher to this section.</p>
                    <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-primary" style="margin-top:10px;">
                        Assign Teacher
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Student List --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-user-graduate" style="color:var(--primary);"></i>
                Students
            </div>
            <span class="badge badge-primary">{{ $section->students->count() }}</span>
        </div>

        @forelse($section->students as $student)
            <div style="padding:12px 20px;border-bottom:1px solid var(--border);
                        display:flex;align-items:center;gap:12px;">
                <img src="{{ $student->profilePhotoUrl() }}" class="user-avatar-sm">
                <div style="flex:1;">
                    <div style="font-weight:600;font-size:13.5px;">{{ $student->fullName() }}</div>
                    <div style="font-size:12px;color:var(--muted);">{{ $student->lrn ?? 'No LRN' }}</div>
                </div>
                <a href="{{ route('admin.students.show', $student) }}" class="btn-icon" title="View Student">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        @empty
            <div class="empty-state" style="padding:36px;">
                <div class="empty-state-icon" style="font-size:36px;">🎓</div>
                <h3>No students enrolled</h3>
                <p>No students have been assigned to this section yet.</p>
            </div>
        @endforelse
    </div>

</div>

@endsection
