@extends('layouts.admin')

@section('title', 'Edit Reading Level')
@section('page-icon', '✏️')
@section('page-heading', 'Reading Levels')

@section('content')

<div class="page-header">
    <div>
        <h1>Edit Reading Level</h1>
        <div class="page-subtitle">{{ $readingLevel->name }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.reading-levels.show', $readingLevel) }}" class="btn btn-outline">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="{{ route('admin.reading-levels.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="max-width:580px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div style="width:14px;height:14px;border-radius:50%;
                            background:{{ $readingLevel->color_code ?? '#003A8C' }};
                            display:inline-block;margin-right:8px;vertical-align:middle;"></div>
                {{ $readingLevel->name }}
            </div>
            <span class="badge {{ $readingLevel->is_active ? 'badge-success' : 'badge-secondary' }}">
                {{ $readingLevel->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reading-levels.update', $readingLevel) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Level Name <span class="required">*</span></label>
                    <input type="text" name="name"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', $readingLevel->name) }}" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Grade Level <span class="required">*</span></label>
                        <select name="grade_level"
                                class="form-control {{ $errors->has('grade_level') ? 'is-invalid' : '' }}"
                                required>
                            @for($g = 1; $g <= 6; $g++)
                                <option value="{{ $g }}"
                                    {{ old('grade_level', $readingLevel->grade_level) == $g ? 'selected' : '' }}>
                                    Grade {{ $g }}
                                </option>
                            @endfor
                        </select>
                        @error('grade_level') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Color Code</label>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <input type="color" id="colorPicker"
                                   value="{{ old('color_code', $readingLevel->color_code ?? '#003A8C') }}"
                                   oninput="document.getElementById('colorInput').value = this.value;
                                            document.getElementById('colorDot').style.background = this.value;"
                                   style="width:44px;height:40px;border:1px solid var(--border);
                                          border-radius:8px;cursor:pointer;padding:2px;">
                            <input type="text" id="colorInput" name="color_code"
                                   class="form-control"
                                   value="{{ old('color_code', $readingLevel->color_code) }}"
                                   placeholder="#003A8C" maxlength="20"
                                   oninput="document.getElementById('colorPicker').value = this.value;
                                            document.getElementById('colorDot').style.background = this.value;">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Brief description of this reading level...">{{ old('description', $readingLevel->description) }}</textarea>
                    <div class="form-hint">Optional. Visible to teachers when assigning reading levels.</div>
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $readingLevel->is_active) ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:var(--primary);">
                        <span class="form-label" style="margin:0;">Active</span>
                    </label>
                </div>

                {{-- Preview --}}
                <div style="padding:14px 16px;background:#f8faff;border-radius:10px;margin-bottom:20px;">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;
                                color:var(--muted);margin-bottom:10px;">Preview</div>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div id="colorDot"
                             style="width:14px;height:14px;border-radius:50%;
                                    background:{{ old('color_code', $readingLevel->color_code ?? '#003A8C') }};
                                    flex-shrink:0;"></div>
                        <span style="font-weight:600;font-size:13.5px;">{{ $readingLevel->name }}</span>
                        <span style="font-size:12px;color:var(--muted);">Grade {{ $readingLevel->grade_level }}</span>
                    </div>
                </div>

                {{-- Danger Zone --}}
                <div style="padding:16px 18px;background:#fff8f8;border:1px solid #fdd;
                            border-radius:var(--radius-sm);margin-bottom:20px;">
                    <div style="font-size:11px;font-weight:700;color:var(--danger);
                                text-transform:uppercase;letter-spacing:0.8px;margin-bottom:8px;">
                        <i class="fas fa-exclamation-triangle"></i> Danger Zone
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                        <div>
                            <div style="font-size:13.5px;font-weight:600;">Delete this level</div>
                            <div style="font-size:12px;color:var(--muted);">
                                Permanently removes this reading level. Students assigned to it will be unlinked.
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.reading-levels.destroy', $readingLevel) }}"
                              onsubmit="return confirm('Delete reading level \'{{ addslashes($readingLevel->name) }}\'? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;
                            padding-top:8px;border-top:1px solid var(--border);">
                    <a href="{{ route('admin.reading-levels.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
