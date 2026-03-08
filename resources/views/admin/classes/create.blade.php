@extends('layouts.admin')

@section('title', 'Add Class')
@section('page-icon', '🏫')
@section('page-heading', 'Classes')

@section('content')

<div class="page-header">
    <div>
        <h1>Add New Class</h1>
        <div class="page-subtitle">Create a new grade-level class and assign a teacher</div>
    </div>
    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Classes
    </a>
</div>

<div style="max-width:620px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-chalkboard" style="color:var(--primary);"></i>
                Class Information
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.classes.store') }}">
                @csrf

                {{-- Class Name --}}
                <div class="form-group">
                    <label class="form-label">
                        Class Name <span class="required">*</span>
                    </label>
                    <input type="text" name="name"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name') }}"
                           placeholder="e.g. Sampaguita, Rosal, Orchid">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <div class="form-hint">Use a name that distinguishes this class (flower, color, number, etc.)</div>
                </div>

                <div class="grid-2">
                    {{-- Grade Level --}}
                    <div class="form-group">
                        <label class="form-label">
                            Grade Level <span class="required">*</span>
                        </label>
                        <select name="grade_level"
                                class="form-control {{ $errors->has('grade_level') ? 'is-invalid' : '' }}"
                                required>
                            <option value="">— Select Grade —</option>
                            @for($g = 1; $g <= 6; $g++)
                                <option value="{{ $g }}" {{ old('grade_level') == $g ? 'selected' : '' }}>
                                    Grade {{ $g }}
                                </option>
                            @endfor
                        </select>
                        @error('grade_level')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- School Year --}}
                    <div class="form-group">
                        <label class="form-label">
                            School Year <span class="required">*</span>
                        </label>
                        <input type="text" name="school_year"
                               class="form-control {{ $errors->has('school_year') ? 'is-invalid' : '' }}"
                               value="{{ old('school_year', date('Y') . '-' . (date('Y') + 1)) }}"
                               placeholder="e.g. 2025-2026" required>
                        @error('school_year')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Assign Teacher --}}
                <div class="form-group">
                    <label class="form-label">Assign Teacher</label>
                    <select name="teacher_id" class="form-control">
                        <option value="">— No teacher assigned —</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                                <span style="color:var(--muted);">({{ $teacher->email }})</span>
                            </option>
                        @endforeach
                    </select>
                    @if($teachers->isEmpty())
                        <div class="form-hint" style="color:var(--warning);">
                            <i class="fas fa-exclamation-triangle"></i>
                            No approved teachers yet.
                            <a href="{{ route('admin.teachers.index') }}">Approve a teacher first.</a>
                        </div>
                    @else
                        <div class="form-hint">Only approved teachers are shown.</div>
                    @endif
                </div>

                {{-- Status --}}
                <div class="form-group" style="padding:14px 16px;background:#f8faff;border-radius:10px;">
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;margin:0;">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}
                               style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;">
                        <div>
                            <div class="form-label" style="margin:0;">Active Class</div>
                            <div style="font-size:12px;color:var(--muted);">
                                Inactive classes won't appear in student enrollment forms.
                            </div>
                        </div>
                    </label>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;
                            padding-top:16px;border-top:1px solid var(--border);margin-top:8px;">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
