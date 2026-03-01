@extends('layouts.teacher')

@section('title', 'My Profile')
@section('page-icon', '👤')
@section('page-heading', 'My Profile')

@section('content')

<div class="page-header">
    <div><h1>My Profile</h1></div>
</div>

<div style="max-width:760px;">
    <div class="grid-2" style="gap:22px;align-items:start;">

        {{-- Profile Card --}}
        <div class="card">
            <div style="padding:32px;text-align:center;">
                <img src="{{ auth()->user()->profilePhotoUrl() }}"
                     class="avatar avatar-lg" style="width:90px;height:90px;margin:0 auto 16px;display:block;">
                <div class="font-bold" style="font-size:18px;margin-bottom:4px;">{{ auth()->user()->name }}</div>
                <div class="text-muted text-small">{{ auth()->user()->email }}</div>
                <span class="badge badge-approved" style="margin-top:8px;">
                    <i class="fas fa-check-circle"></i> Approved Teacher
                </span>
            </div>

            {{-- Photo Upload --}}
            <div style="border-top:1px solid var(--border);padding:20px;">
                <p class="font-semibold" style="font-size:13px;margin-bottom:12px;">Change Profile Photo</p>
                <form method="POST" action="{{ route('teacher.profile.photo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()">
                        <i class="fas fa-camera" style="font-size:28px;color:var(--muted);margin-bottom:8px;display:block;"></i>
                        <p style="font-size:13px;color:var(--muted);">Click to upload new photo</p>
                        <p style="font-size:11px;color:var(--muted);">JPG, JPEG, PNG — max 2MB</p>
                    </div>
                    <input type="file" id="photoInput" name="profile_photo"
                           accept="image/jpg,image/jpeg,image/png"
                           style="display:none;" onchange="this.form.submit()">
                    @error('profile_photo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </form>
            </div>
        </div>

        {{-- Edit Info --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-user-edit"></i> Personal Information</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.profile.update') }}">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}" placeholder="+63 912 345 6789">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3"
                                      placeholder="A short bio about yourself...">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-lock"></i> Change Password</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.profile.password') }}">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Current Password <span class="required">*</span></label>
                            <input type="password" name="current_password"
                                   class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}" required>
                            @error('current_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password <span class="required">*</span></label>
                            <input type="password" name="password" class="form-control" required placeholder="Min. 8 characters">
                            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password <span class="required">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger" style="width:100%;">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
