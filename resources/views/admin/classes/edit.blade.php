@extends('layouts.admin')

@section('title', 'Update Class')
@section('page-icon', '✏️')
@section('page-heading', 'Classes')

@section('content')

<div class="page-header">
    <div>
        <h1>Update Class</h1>
        <div class="page-subtitle">Updating — {{ $class->name }}, Grade {{ $class->grade_level }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-outline">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="max-width:620px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-chalkboard" style="color:var(--primary);"></i>
                {{ $class->name }}
                <span class="text-muted" style="font-size:13px;font-weight:400;">· Grade {{ $class->grade_level }}</span>
            </div>
            <span class="badge {{ $class->is_active ? 'badge-success' : 'badge-secondary' }}">
                {{ $class->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.classes.update', $class) }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Class Name <span class="required">*</span></label>
                    <input type="text" name="name"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', $class->name) }}"
                           placeholder="e.g. Sampaguita" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Grade Level <span class="required">*</span></label>
                        <select name="grade_level" class="form-control" required>
                            @for($g = 1; $g <= 6; $g++)
                                <option value="{{ $g }}"
                                    {{ old('grade_level', $class->grade_level) == $g ? 'selected' : '' }}>
                                    Grade {{ $g }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">School Year <span class="required">*</span></label>
                        <input type="text" name="school_year"
                               class="form-control {{ $errors->has('school_year') ? 'is-invalid' : '' }}"
                               value="{{ old('school_year', $class->school_year) }}"
                               required>
                        @error('school_year')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Assigned Teacher</label>
                    <select name="teacher_id" class="form-control">
                        <option value="">— No teacher assigned —</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint">Only approved teachers are shown.</div>
                </div>

                <div class="form-group" style="padding:14px 16px;background:#f8faff;border-radius:10px;">
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;margin:0;">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $class->is_active) ? 'checked' : '' }}
                               style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;">
                        <div>
                            <div class="form-label" style="margin:0;">Active Class</div>
                            <div style="font-size:12px;color:var(--muted);">
                                Inactive classes are hidden from student enrollment forms.
                            </div>
                        </div>
                    </label>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;
                            padding-top:16px;border-top:1px solid var(--border);margin-top:8px;">
                    <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Danger Zone --}}
    <div class="card" style="margin-top:20px;border:1px solid #fdd;">
        <div class="card-header" style="background:#fff8f8;">
            <div class="card-title" style="color:var(--danger);">
                <i class="fas fa-exclamation-triangle"></i> Danger Zone
            </div>
        </div>
        <div style="padding:18px 22px;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;">
            <div>
                <div style="font-size:14px;font-weight:600;margin-bottom:4px;">Delete this class</div>
                <div style="font-size:12.5px;color:var(--muted);line-height:1.5;">
                    Permanently removes the class record. All
                    <strong>{{ $class->students->count() ?? 0 }} enrolled students</strong>
                    will be unlinked but not deleted.
                </div>
            </div>
            <form method="POST" action="{{ route('admin.classes.destroy', $class) }}"
                  onsubmit="return confirm('Delete class \'{{ addslashes($class->name) }}\'?\n\nStudents will be unlinked but not deleted.\nThis cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Class
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
