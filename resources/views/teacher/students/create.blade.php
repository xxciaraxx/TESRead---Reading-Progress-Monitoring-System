@extends('layouts.teacher')

@section('title', 'Add Student')
@section('page-icon', '➕')
@section('page-heading', 'Add Student')

@section('content')

<div class="page-header">
    <div>
        <h1>Add New Student</h1>
    </div>
    <a href="{{ route('teacher.students.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Students
    </a>
</div>

<div style="max-width:760px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user-plus"></i> Student Information</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.students.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Profile Photo --}}
                <div class="form-group" style="text-align:center;margin-bottom:28px;">
                    <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()"
                         style="max-width:260px;margin:0 auto;">
                        <img id="photoPreview"
                             src="https://ui-avatars.com/api/?name=New+Student&background=003A8C&color=fff&size=128"
                             class="photo-preview" style="display:block;margin:0 auto;">
                        <p style="color:var(--muted);font-size:13px;margin-top:10px;">
                            <i class="fas fa-camera"></i> Upload Student Photo
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
                               value="{{ old('first_name') }}" placeholder="e.g. Juan" required>
                        @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Last Name <span class="required">*</span></label>
                        <input type="text" name="last_name"
                               class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                               value="{{ old('last_name') }}" placeholder="e.g. dela Cruz" required>
                        @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control"
                               value="{{ old('middle_name') }}" placeholder="Optional">
                    </div>

                    <div class="form-group">
                        <label class="form-label">LRN
                            <small class="text-muted" style="font-weight:400;">(Learner Reference No.)</small>
                        </label>
                        <input type="text" name="lrn"
                               class="form-control {{ $errors->has('lrn') ? 'is-invalid' : '' }}"
                               value="{{ old('lrn') }}" placeholder="12-digit LRN">
                        @error('lrn') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">— Select —</option>
                            <option value="Male"   {{ old('gender') === 'Male'   ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Birthdate</label>
                        <input type="date" name="birthdate" class="form-control"
                               value="{{ old('birthdate') }}" max="{{ now()->subDay()->toDateString() }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Section / Class</label>
                        <select name="section_id" class="form-control">
                            <option value="">— No section —</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                    Grade {{ $section->grade_level }} – {{ $section->name }}
                                    ({{ $section->school_year }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Reading Level</label>
                        <select name="reading_level_id" class="form-control">
                            <option value="">— Not set —</option>
                            @foreach($readingLevels as $level)
                                <option value="{{ $level->id }}"
                                    {{ old('reading_level_id') == $level->id ? 'selected' : '' }}>
                                    Grade {{ $level->grade_level }} – {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:8px;border-top:1px solid var(--border);margin-top:8px;">
                    <a href="{{ route('teacher.students.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Student
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
