@extends('layouts.admin')

@section('title', 'Add Section')
@section('page-icon', '🏫')
@section('page-heading', 'Add Section')

@section('content')

<div class="page-header">
    <div><h1>Add New Section</h1></div>
    <a href="{{ route('admin.sections.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width:580px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-door-open"></i> Section Information</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sections.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Section Name <span class="required">*</span></label>
                    <input type="text" name="name"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name') }}" placeholder="e.g. Sampaguita" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Grade Level <span class="required">*</span></label>
                        <select name="grade_level" class="form-control {{ $errors->has('grade_level') ? 'is-invalid' : '' }}" required>
                            <option value="">— Select —</option>
                            @for($g = 1; $g <= 6; $g++)
                                <option value="{{ $g }}" {{ old('grade_level') == $g ? 'selected' : '' }}>Grade {{ $g }}</option>
                            @endfor
                        </select>
                        @error('grade_level') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">School Year <span class="required">*</span></label>
                        <input type="text" name="school_year"
                               class="form-control {{ $errors->has('school_year') ? 'is-invalid' : '' }}"
                               value="{{ old('school_year', date('Y') . '-' . (date('Y') + 1)) }}"
                               placeholder="e.g. 2025-2026" required>
                        @error('school_year') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Assign Teacher</label>
                    <select name="teacher_id" class="form-control">
                        <option value="">— Unassigned —</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint">Only approved teachers are shown.</div>
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:var(--primary);">
                        <span class="form-label" style="margin:0;">Active Section</span>
                    </label>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:8px;">
                    <a href="{{ route('admin.sections.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
