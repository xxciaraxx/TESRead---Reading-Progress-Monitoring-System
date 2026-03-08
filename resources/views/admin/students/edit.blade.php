@extends('layouts.admin')

@section('title', 'Student Profile Management')
@section('page-icon', '✏️')
@section('page-heading', "Update Students' Profile")

@push('styles')
<style>
/* ── Edit page layout ─────────────────────────────── */
.edit-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 22px;
    align-items: start;
}

/* ── Profile sidebar card ─────────────────────────── */
.profile-sidebar {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    position: sticky;
    top: 20px;
}
.sidebar-hero {
    background: var(--gradient);
    padding: 28px 20px 24px;
    text-align: center;
}
.photo-ring {
    position: relative;
    width: 90px;
    height: 90px;
    margin: 0 auto 12px;
    cursor: pointer;
}
.photo-ring img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.35);
    display: block;
    transition: opacity .2s;
}
.photo-ring:hover img { opacity: .75; }
.photo-ring-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(0,0,0,.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity .2s;
}
.photo-ring:hover .photo-ring-overlay { opacity: 1; }
.photo-ring-overlay i { color: #fff; font-size: 18px; }
.sidebar-name {
    font-size: 15px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 3px;
    line-height: 1.3;
}
.sidebar-lrn {
    font-size: 11.5px;
    color: rgba(255,255,255,.7);
}
.photo-hint {
    font-size: 11px;
    color: rgba(255,255,255,.55);
    margin-top: 8px;
}
.sidebar-meta {
    padding: 16px 20px;
}
.sidebar-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f4f6fb;
    font-size: 12.5px;
}
.sidebar-meta-row:last-child { border-bottom: none; }
.sidebar-meta-label { color: var(--muted); font-weight: 600; }
.sidebar-meta-value { font-weight: 700; color: #1e293b; text-align: right; }

/* ── Main form card ───────────────────────────────── */
.form-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.form-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    background: #fafbff;
}
.form-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.form-card-title i { color: var(--primary); }
.form-card-body { padding: 24px; }

/* ── Field sections ───────────────────────────────── */
.field-section {
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
}
.field-section:last-of-type { margin-bottom: 0; }
.field-section-head {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 11px 18px;
    background: #f8faff;
    border-bottom: 1px solid var(--border);
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .9px;
    color: var(--muted);
}
.field-section-head i { color: var(--primary); font-size: 11px; }
.field-section-body { padding: 18px; }

/* ── Form grid ────────────────────────────────────── */
.fg-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.fg-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.fg-full { grid-column: 1 / -1; }

/* ── Danger zone ──────────────────────────────────── */
.danger-zone {
    border: 1px solid #fdd;
    border-radius: 10px;
    background: #fff8f8;
    padding: 16px 18px;
    margin-top: 20px;
}
.danger-zone-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .9px;
    color: var(--danger);
    margin-bottom: 12px;
}
.danger-zone-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

/* ── Form actions ─────────────────────────────────── */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 18px 24px;
    border-top: 1px solid var(--border);
    background: #fafbff;
}

/* ── Responsive ───────────────────────────────────── */
@media (max-width: 1024px) {
    .edit-layout { grid-template-columns: 220px 1fr; gap: 16px; }
}
@media (max-width: 768px) {
    .edit-layout { grid-template-columns: 1fr; }
    .profile-sidebar { position: static; }
    .sidebar-hero { padding: 20px; }
    .fg-2, .fg-3 { grid-template-columns: 1fr; }
    .fg-full { grid-column: auto; }
    .form-card-body { padding: 16px; }
    .form-actions { flex-direction: column-reverse; gap: 8px; }
    .form-actions .btn { width: 100%; justify-content: center; }
}
@media (max-width: 480px) {
    .field-section-body { padding: 14px; }
}
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1>Update Student Profile</h1>
        <div class="page-subtitle">{{ $student->fullName() }}</div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline">
            <i class="fas fa-eye"></i> View Profile
        </a>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.students.update', $student) }}"
      enctype="multipart/form-data" id="editForm">
    @csrf
    @method('PUT')

    {{-- Hidden photo input --}}
    <input type="file" id="photoInput" name="profile_photo"
           accept="image/jpg,image/jpeg,image/png"
           style="display:none;" onchange="previewPhoto(this)">

    <div class="edit-layout">

        {{-- ── LEFT: Profile sidebar ── --}}
        <div class="profile-sidebar">
            <div class="sidebar-hero">
                <div class="photo-ring" onclick="document.getElementById('photoInput').click()"
                     title="Click to change photo">
                    <img id="photoPreview" src="{{ $student->profilePhotoUrl() }}" alt="Photo">
                    <div class="photo-ring-overlay">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <div class="sidebar-name">{{ $student->fullName() }}</div>
                @if($student->lrn)
                    <div class="sidebar-lrn">LRN: {{ $student->lrn }}</div>
                @endif
                <div class="photo-hint">
                    <i class="fas fa-camera"></i> Click photo to change<br>
                    <span style="font-size:10px;">JPG, JPEG, PNG · Max 2 MB</span>
                </div>
            </div>

            @error('profile_photo')
                <div style="padding:10px 16px;background:#fff5f5;color:var(--danger);font-size:12px;text-align:center;">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror

            <div class="sidebar-meta">
                <div class="sidebar-meta-row">
                    <span class="sidebar-meta-label">Gender</span>
                    <span class="sidebar-meta-value">{{ $student->gender ?? '—' }}</span>
                </div>
                <div class="sidebar-meta-row">
                    <span class="sidebar-meta-label">Date of Birth</span>
                    <span class="sidebar-meta-value">
                        {{ $student->birthdate?->format('M d, Y') ?? '—' }}
                    </span>
                </div>
                <div class="sidebar-meta-row">
                    <span class="sidebar-meta-label">Class</span>
                    <span class="sidebar-meta-value">
                        @if($student->section)
                            Gr.{{ $student->section->grade_level }} – {{ $student->section->name }}
                        @else —
                        @endif
                    </span>
                </div>
                <div class="sidebar-meta-row">
                    <span class="sidebar-meta-label">Teacher</span>
                    <span class="sidebar-meta-value">{{ $student->teacher?->name ?? '—' }}</span>
                </div>
                <div class="sidebar-meta-row">
                    <span class="sidebar-meta-label">Reading Level</span>
                    <span class="sidebar-meta-value">{{ $student->philIriLabel() ?? '—' }}</span>
                </div>
                <div class="sidebar-meta-row">
                    <span class="sidebar-meta-label">Status</span>
                    <span class="sidebar-meta-value">
                        <span class="badge {{ $student->is_archived ? 'badge-secondary' : 'badge-success' }}">
                            {{ $student->is_archived ? 'Archived' : 'Active' }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Form card ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-title">
                    <i class="fas fa-user-edit"></i>
                    Student Information
                </div>
                <span class="badge badge-secondary" style="font-size:11px;">
                    ID #{{ $student->id }}
                </span>
            </div>

            <div class="form-card-body">

                {{-- Personal Information --}}
                <div class="field-section">
                    <div class="field-section-head">
                        <i class="fas fa-user"></i> Personal Information
                    </div>
                    <div class="field-section-body">
                        <div class="fg-2">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">
                                    First Name <span class="required">*</span>
                                </label>
                                <input type="text" name="first_name"
                                       class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                       value="{{ old('first_name', $student->first_name) }}"
                                       placeholder="e.g. Juan" required>
                                @error('first_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">
                                    Last Name <span class="required">*</span>
                                </label>
                                <input type="text" name="last_name"
                                       class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                       value="{{ old('last_name', $student->last_name) }}"
                                       placeholder="e.g. Dela Cruz" required>
                                @error('last_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control"
                                       value="{{ old('middle_name', $student->middle_name) }}"
                                       placeholder="Optional">
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">
                                    LRN
                                    <span style="font-size:11px;font-weight:400;color:var(--muted);">
                                        (Learner Reference Number)
                                    </span>
                                </label>
                                <input type="text" name="lrn"
                                       class="form-control {{ $errors->has('lrn') ? 'is-invalid' : '' }}"
                                       value="{{ old('lrn', $student->lrn) }}"
                                       placeholder="12-digit LRN" maxlength="20">
                                @error('lrn')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Sex</label>
                                <select name="gender" class="form-control">
                                    <option value="">— Select —</option>
                                    <option value="Male"
                                        {{ old('gender', $student->gender) === 'Male' ? 'selected' : '' }}>
                                        Male
                                    </option>
                                    <option value="Female"
                                        {{ old('gender', $student->gender) === 'Female' ? 'selected' : '' }}>
                                        Female
                                    </option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="birthdate" class="form-control"
                                       value="{{ old('birthdate', $student->birthdate?->format('Y-m-d')) }}"
                                       max="{{ now()->subDay()->toDateString() }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Academic Assignment --}}
                <div class="field-section">
                    <div class="field-section-head">
                        <i class="fas fa-school"></i> Academic Assignment
                    </div>
                    <div class="field-section-body">
                        <div class="fg-2">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Assigned Teacher</label>
                                <select name="teacher_id" class="form-control">
                                    <option value="">— No teacher assigned —</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}"
                                            {{ old('teacher_id', $student->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-hint">Only approved teachers are shown.</div>
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Assigned Class</label>
                                <select name="section_id" class="form-control">
                                    <option value="">— No class assigned —</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                            Grade {{ $section->grade_level }} – {{ $section->name }}
                                            ({{ $section->school_year }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            
                        </div>
                    </div>
                </div>

                {{-- Archive (Danger Zone) --}}
                <div class="danger-zone">
                    <div class="danger-zone-label">
                        <i class="fas fa-exclamation-triangle"></i> Danger Zone
                    </div>
                    <div class="danger-zone-row">
                        <div>
                            <div style="font-size:13.5px;font-weight:700;color:#0f172a;margin-bottom:3px;">
                                Archive this student
                            </div>
                            <div style="font-size:12.5px;color:var(--muted);">
                                Hides the student from active lists while preserving all records and assessments.
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.students.archive', $student) }}"
                              onsubmit="return confirm('Archive {{ addslashes($student->fullName()) }}?\n\nThey will be hidden from active lists but all records will be preserved.')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-outline btn-sm"
                                    style="color:var(--danger);border-color:var(--danger);white-space:nowrap;">
                                <i class="fas fa-archive"></i> Archive Student
                            </button>
                        </form>
                    </div>
                </div>

            </div>{{-- /form-card-body --}}

            <div class="form-actions">
                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>{{-- /form-card --}}

    </div>{{-- /edit-layout --}}
</form>

@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate size client-side (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Photo must be 2 MB or smaller. Please choose a different image.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
