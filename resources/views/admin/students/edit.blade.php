@extends('layouts.admin')

@section('title', 'Edit Student')
@section('page-icon', '✏️')
@section('page-heading', 'Edit Student')

@section('content')

<div class="page-header">
    <div>
        <h1>Edit Student</h1>
        <div class="page-subtitle">Updating record for {{ $student->fullName() }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline">
            <i class="fas fa-eye"></i> View Profile
        </a>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="max-width:800px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <img src="{{ $student->profilePhotoUrl() }}"
                     style="width:32px;height:32px;border-radius:50%;object-fit:cover;margin-right:8px;">
                {{ $student->fullName() }}
            </div>
            @if($student->lrn)
                <span class="badge badge-secondary">LRN: {{ $student->lrn }}</span>
            @endif
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.update', $student) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Photo Upload --}}
                <div class="form-group" style="text-align:center;margin-bottom:28px;">
                    <div class="photo-upload-area"
                         onclick="document.getElementById('photoInput').click()"
                         style="max-width:240px;margin:0 auto;cursor:pointer;">
                        <img id="photoPreview"
                             src="{{ $student->profilePhotoUrl() }}"
                             class="photo-preview"
                             style="display:block;margin:0 auto;width:100px;height:100px;
                                    border-radius:50%;object-fit:cover;border:3px solid var(--border);">
                        <p style="color:var(--muted);font-size:13px;margin-top:10px;">
                            <i class="fas fa-camera"></i> Change Photo
                        </p>
                        <p style="color:var(--muted);font-size:11px;">JPG, JPEG, PNG · Max 2MB</p>
                    </div>
                    <input type="file" id="photoInput" name="profile_photo"
                           accept="image/jpg,image/jpeg,image/png"
                           style="display:none;" onchange="previewPhoto(this)">
                    @error('profile_photo')
                        <span class="invalid-feedback" style="display:block;text-align:center;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Name Fields --}}
                <div style="background:#f8faff;border-radius:10px;padding:18px 20px;margin-bottom:20px;">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;
                                color:var(--muted);margin-bottom:14px;">
                        <i class="fas fa-user"></i> Personal Information
                    </div>
                    <div class="grid-2">
                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">First Name <span class="required">*</span></label>
                            <input type="text" name="first_name"
                                   class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                   value="{{ old('first_name', $student->first_name) }}"
                                   placeholder="e.g. Juan" required>
                            @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">Last Name <span class="required">*</span></label>
                            <input type="text" name="last_name"
                                   class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                   value="{{ old('last_name', $student->last_name) }}"
                                   placeholder="e.g. dela Cruz" required>
                            @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control"
                                   value="{{ old('middle_name', $student->middle_name) }}"
                                   placeholder="Optional">
                        </div>

                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">LRN
                                <small class="text-muted" style="font-weight:400;">(Learner Reference No.)</small>
                            </label>
                            <input type="text" name="lrn"
                                   class="form-control {{ $errors->has('lrn') ? 'is-invalid' : '' }}"
                                   value="{{ old('lrn', $student->lrn) }}"
                                   placeholder="12-digit LRN" maxlength="20">
                            @error('lrn') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-control">
                                <option value="">— Select —</option>
                                <option value="Male"   {{ old('gender', $student->gender) === 'Male'   ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $student->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Birthdate</label>
                            <input type="date" name="birthdate" class="form-control"
                                   value="{{ old('birthdate', $student->birthdate?->format('Y-m-d')) }}"
                                   max="{{ now()->subDay()->toDateString() }}">
                        </div>
                    </div>
                </div>

                {{-- Academic Assignment --}}
                <div style="background:#f8faff;border-radius:10px;padding:18px 20px;margin-bottom:20px;">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;
                                color:var(--muted);margin-bottom:14px;">
                        <i class="fas fa-school"></i> Academic Assignment
                    </div>
                    <div class="grid-2">
                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">Assigned Teacher</label>
                            <select name="teacher_id" class="form-control">
                                <option value="">— No teacher —</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ old('teacher_id', $student->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-hint">Only approved teachers shown.</div>
                        </div>

                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">Section / Class</label>
                            <select name="section_id" class="form-control">
                                <option value="">— No section —</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                        Grade {{ $section->grade_level }} – {{ $section->name }}
                                        ({{ $section->school_year }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                            <label class="form-label">Reading Level</label>
                            <select name="reading_level_id" class="form-control">
                                <option value="">— Not set —</option>
                                @foreach($readingLevels as $level)
                                    <option value="{{ $level->id }}"
                                        {{ old('reading_level_id', $student->reading_level_id) == $level->id ? 'selected' : '' }}>
                                        Grade {{ $level->grade_level }} – {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                            <div style="font-size:13.5px;font-weight:600;">Archive this student</div>
                            <div style="font-size:12px;color:var(--muted);">
                                Hides the student from active lists while preserving all records.
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.students.archive', $student) }}"
                              onsubmit="return confirm('Archive {{ addslashes($student->fullName()) }}?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-outline btn-sm"
                                    style="color:var(--danger);border-color:var(--danger);">
                                <i class="fas fa-archive"></i> Archive
                            </button>
                        </form>
                    </div>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;
                            padding-top:16px;border-top:1px solid var(--border);">
                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('photoPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
