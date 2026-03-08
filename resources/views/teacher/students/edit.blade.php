@extends('layouts.teacher')

@section('title', 'Update Student Profile')
@section('page-icon', '✏️')
@section('page-heading', 'Update Student Profile')


@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
.scroll-body { flex: 1; min-height: 0; overflow-y: auto; overflow-x: hidden; padding-bottom: 20px; }
.scroll-body::-webkit-scrollbar { width: 5px; }
.scroll-body::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }
</style>
@endpush
@section('content')

<div style="display:flex;flex-direction:column;height:100%;">
<div class="page-header">
    <div>
        <h1>Manage Students' Profile</h1>
        <div class="page-subtitle">Update {{ $student->fullName() }}'s information</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-outline">
            <i class="fas fa-eye"></i> View Profile
        </a>
        <a href="{{ route('teacher.students.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="scroll-body">
<div style="max-width:760px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <img src="{{ $student->profilePhotoUrl() }}" class="avatar avatar-sm">
                {{ $student->fullName() }}
            </div>
            @if($student->lrn)
                <span class="badge badge-info">LRN: {{ $student->lrn }}</span>
            @endif
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.students.update', $student) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Photo Upload --}}
                <div class="form-group" style="text-align:center;margin-bottom:28px;">
                    <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()"
                         style="max-width:260px;margin:0 auto;">
                        <img id="photoPreview"
                             src="{{ $student->profilePhotoUrl() }}"
                             class="photo-preview" style="display:block;margin:0 auto;">
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

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">First Name <span class="required">*</span></label>
                        <input type="text" name="first_name"
                               class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                               value="{{ old('first_name', $student->first_name) }}" required>
                        @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Last Name <span class="required">*</span></label>
                        <input type="text" name="last_name"
                               class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                               value="{{ old('last_name', $student->last_name) }}" required>
                        @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control"
                               value="{{ old('middle_name', $student->middle_name) }}" placeholder="Optional">
                    </div>

                    <div class="form-group">
                        <label class="form-label">LRN
                            <small class="text-muted" style="font-weight:400;">(Learner Reference No.)</small>
                        </label>
                        <input type="text" name="lrn"
                               class="form-control {{ $errors->has('lrn') ? 'is-invalid' : '' }}"
                               value="{{ old('lrn', $student->lrn) }}" placeholder="12-digit LRN">
                        @error('lrn') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">— Select —</option>
                            <option value="Male"   {{ old('gender', $student->gender) === 'Male'   ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $student->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Birthdate</label>
                        <input type="date" name="birthdate" class="form-control"
                               value="{{ old('birthdate', $student->birthdate?->format('Y-m-d')) }}"
                               max="{{ now()->subDay()->toDateString() }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Class</label>
                        <select name="section_id" class="form-control">
                            <option value="">— No class —</option>
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

                {{-- Danger zone --}}
                <div style="margin-top:24px;padding:16px 18px;background:#fff8f8;border:1px solid #fdd;border-radius:var(--radius-sm);">
                    <div style="font-size:12px;font-weight:700;color:var(--danger);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:8px;">
                        <i class="fas fa-exclamation-triangle"></i> Danger Zone
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <div style="font-size:13.5px;font-weight:600;">Delete this student</div>
                            <div style="font-size:12px;color:var(--muted);">Permanently removes the student and all their records.</div>
                        </div>
                        <form method="POST" action="{{ route('teacher.students.destroy', $student) }}"
                              onsubmit="return confirm('Permanently delete {{ addslashes($student->fullName()) }}? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete Student
                            </button>
                        </form>
                    </div>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:20px;border-top:1px solid var(--border);margin-top:20px;">
                    <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
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