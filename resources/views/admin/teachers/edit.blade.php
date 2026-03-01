@extends('layouts.admin')

@section('title', 'Edit Teacher')
@section('page-icon', '✏️')
@section('page-heading', 'Edit Teacher')

@section('content')

<div class="page-header">
    <div>
        <h1>Edit Teacher Account</h1>
    </div>
    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width:680px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <img src="{{ $teacher->profilePhotoUrl() }}" class="avatar avatar-sm" style="margin-right:4px;">
                {{ $teacher->name }}
            </div>
            <span class="badge badge-{{ strtolower($teacher->account_status) }}">{{ $teacher->account_status }}</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="grid-2">
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Profile Photo</label>
                        <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()">
                            <img id="photoPreview"
                                 src="{{ $teacher->profilePhotoUrl() }}"
                                 class="photo-preview" style="display:block;margin:0 auto;">
                            <p style="color:var(--muted);font-size:13px;margin-top:8px;">
                                <i class="fas fa-camera"></i> Click to change photo
                            </p>
                            <p style="color:var(--muted);font-size:11px;">JPG, JPEG, PNG — max 2MB</p>
                        </div>
                        <input type="file" id="photoInput" name="profile_photo" accept="image/jpg,image/jpeg,image/png"
                               style="display:none;" onchange="previewPhoto(this)">
                        @error('profile_photo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Name <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $teacher->name) }}" required>
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email', $teacher->email) }}" required>
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password <small class="text-muted">(leave blank to keep)</small></label>
                        <input type="password" name="password" class="form-control" placeholder="New password">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                    </div>

                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Account Status <span class="required">*</span></label>
                        <select name="account_status" class="form-control" required>
                            @foreach(['Approved','Pending','Rejected'] as $status)
                                <option value="{{ $status }}" {{ old('account_status', $teacher->account_status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:8px;">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline">Cancel</a>
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
