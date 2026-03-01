@extends('layouts.admin')

@section('title', 'Add Reading Level')
@section('page-icon', '📚')
@section('page-heading', 'Reading Levels')

@section('content')

<div class="page-header">
    <div>
        <h1>Add Reading Level</h1>
        <div class="page-subtitle">Define a new reading proficiency level</div>
    </div>
    <a href="{{ route('admin.reading-levels.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width:580px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-layer-group"></i> Level Information
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reading-levels.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Level Name <span class="required">*</span></label>
                    <input type="text" name="name"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name') }}"
                           placeholder="e.g. Emergent Reader, Fluent Reader">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Grade Level <span class="required">*</span></label>
                        <select name="grade_level"
                                class="form-control {{ $errors->has('grade_level') ? 'is-invalid' : '' }}"
                                required>
                            <option value="">— Select —</option>
                            @for($g = 1; $g <= 6; $g++)
                                <option value="{{ $g }}" {{ old('grade_level') == $g ? 'selected' : '' }}>
                                    Grade {{ $g }}
                                </option>
                            @endfor
                        </select>
                        @error('grade_level') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Color Code
                            <small class="text-muted" style="font-weight:400;">(hex)</small>
                        </label>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <input type="color" id="colorPicker"
                                   value="{{ old('color_code', '#003A8C') }}"
                                   oninput="document.getElementById('colorInput').value = this.value"
                                   style="width:44px;height:40px;border:1px solid var(--border);
                                          border-radius:8px;cursor:pointer;padding:2px;">
                            <input type="text" id="colorInput" name="color_code"
                                   class="form-control"
                                   value="{{ old('color_code', '#003A8C') }}"
                                   placeholder="#003A8C" maxlength="20"
                                   oninput="document.getElementById('colorPicker').value = this.value">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Brief description of what this reading level means...">{{ old('description') }}</textarea>
                    <div class="form-hint">Optional. Helps teachers understand the proficiency expectations.</div>
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:var(--primary);">
                        <span class="form-label" style="margin:0;">Active
                            <small class="text-muted" style="font-weight:400;">
                                — inactive levels won't appear in student forms
                            </small>
                        </span>
                    </label>
                </div>

                {{-- Live Preview --}}
                <div style="padding:14px 16px;background:#f8faff;border-radius:10px;margin-bottom:20px;">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;
                                color:var(--muted);margin-bottom:10px;">Preview</div>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div id="colorDot" style="width:14px;height:14px;border-radius:50%;
                                                   background:{{ old('color_code', '#003A8C') }};
                                                   flex-shrink:0;"></div>
                        <span id="levelPreview" style="font-weight:600;font-size:13.5px;">
                            {{ old('name') ?: 'Level Name' }}
                        </span>
                        <span id="gradePreview" style="font-size:12px;color:var(--muted);">
                            {{ old('grade_level') ? 'Grade ' . old('grade_level') : '' }}
                        </span>
                    </div>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;
                            padding-top:8px;border-top:1px solid var(--border);">
                    <a href="{{ route('admin.reading-levels.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Level
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live name preview
    document.querySelector('[name="name"]').addEventListener('input', function() {
        document.getElementById('levelPreview').textContent = this.value || 'Level Name';
    });
    // Live grade preview
    document.querySelector('[name="grade_level"]').addEventListener('change', function() {
        document.getElementById('gradePreview').textContent = this.value ? 'Grade ' + this.value : '';
    });
    // Live color preview
    document.getElementById('colorInput').addEventListener('input', function() {
        document.getElementById('colorDot').style.background = this.value;
        try { document.getElementById('colorPicker').value = this.value; } catch(e) {}
    });
</script>
@endpush
