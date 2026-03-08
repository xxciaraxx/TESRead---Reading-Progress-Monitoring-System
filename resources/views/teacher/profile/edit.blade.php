@extends('layouts.teacher')

@section('title', 'My Profile')
@section('page-icon', '👤')
@section('page-heading', 'My Profile')

@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden !important; display: flex; flex-direction: column; padding-bottom: 0 !important; }

.profile-layout { display: flex; flex-direction: column; flex: 1; min-height: 0; }
.profile-grid {
    flex: 1; min-height: 0;
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 18px;
    align-items: start;
    overflow: hidden;
}
.profile-left  { display: flex; flex-direction: column; gap: 0; overflow-y: auto; overflow-x: hidden; }
.profile-left::-webkit-scrollbar { width: 4px; }
.profile-left::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }
.profile-right { display: flex; flex-direction: column; gap: 16px; }

.profile-avatar { width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid var(--border); display:block; margin: 0 auto 10px; }
.profile-card-top { padding: 20px 16px 14px; text-align: center; border-bottom: 1px solid var(--border); }
.profile-photo-zone { padding: 14px 16px; }
.photo-upload-area {
    border: 1.5px dashed var(--border); border-radius: 10px;
    padding: 12px; text-align: center; cursor: pointer;
    transition: border-color .15s, background .15s;
}
.photo-upload-area:hover { border-color: var(--primary); background: #f5f8ff; }
.form-group { margin-bottom: 10px !important; }
.form-label { font-size: 11.5px !important; margin-bottom: 4px !important; }
.form-control { padding: 7px 11px !important; font-size: 13px !important; }
</style>
@endpush

@section('content')
<div class="profile-layout">

    <div class="page-header" style="flex-shrink:0;">
        <div><h1>My Profile</h1></div>
    </div>

    <div class="profile-grid">

        {{-- LEFT: Avatar + photo upload --}}
        <div class="profile-left">
            <div class="card">
                <div class="profile-card-top">
                    <img src="{{ auth()->user()->profilePhotoUrl() }}" class="profile-avatar">
                    <div style="font-size:15px;font-weight:800;margin-bottom:3px;">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size:12px;margin-bottom:8px;">{{ auth()->user()->email }}</div>
                    <span class="badge badge-approved">
                        <i class="fas fa-check-circle"></i> Approved Teacher
                    </span>
                </div>
                <div class="profile-photo-zone">
                    <p style="font-size:11px;font-weight:700;color:var(--muted);margin-bottom:10px;text-transform:uppercase;letter-spacing:.6px;">
                        Profile Photo
                    </p>
                    <form method="POST" action="{{ route('teacher.profile.photo') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()">
                            <i class="fas fa-camera" style="font-size:22px;color:var(--muted);margin-bottom:6px;display:block;"></i>
                            <p style="font-size:12px;color:var(--muted);margin:0;">Click to upload</p>
                            <p style="font-size:10.5px;color:var(--muted);margin:2px 0 0;">JPG, PNG — max 2MB</p>
                        </div>
                        <input type="file" id="photoInput" name="profile_photo"
                               accept="image/jpg,image/jpeg,image/png"
                               style="display:none;" onchange="this.form.submit()">
                        @error('profile_photo')
                            <span style="font-size:11.5px;color:var(--danger);display:block;margin-top:6px;">{{ $message }}</span>
                        @enderror
                    </form>
                </div>
            </div>

            {{-- Assigned Classes --}}
            <div class="card" style="margin-top:16px;">
                <div class="card-header" style="padding:11px 16px;">
                    <div class="card-title" style="font-size:12.5px;">
                        <i class="fas fa-chalkboard" style="color:var(--primary);"></i> My Classes
                    </div>
                    <span class="badge badge-info" style="font-size:10.5px;">{{ $sections->count() }}</span>
                </div>
                @forelse($sections as $sec)
                    <div style="padding:10px 16px;border-bottom:1px solid var(--border);
                                display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:9px;background:rgba(0,58,140,.08);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:13px;font-weight:800;color:var(--primary);">{{ $sec->grade_level }}</span>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:13px;font-weight:700;line-height:1.2;">
                                Grade {{ $sec->grade_level }} – {{ $sec->name }}
                            </div>
                            <div style="font-size:11px;color:var(--muted);margin-top:1px;">
                                {{ $sec->students_count }} {{ Str::plural('student', $sec->students_count) }}
                                @if($sec->school_year) &bull; SY {{ $sec->school_year }} @endif
                            </div>
                        </div>
                        @if($sec->is_active)
                            <span class="badge badge-success" style="font-size:10px;">Active</span>
                        @else
                            <span class="badge badge-secondary" style="font-size:10px;">Inactive</span>
                        @endif
                    </div>
                @empty
                    <div style="padding:20px 16px;text-align:center;">
                        <i class="fas fa-chalkboard" style="font-size:22px;color:var(--muted);opacity:.4;display:block;margin-bottom:6px;"></i>
                        <p style="font-size:12px;color:var(--muted);margin:0;">No classes assigned yet.</p>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- RIGHT: Info + Password --}}
        <div class="profile-right">

            <div class="card">
                <div class="card-header" style="padding:12px 18px;">
                    <div class="card-title"><i class="fas fa-user-edit"></i> Personal Information</div>
                </div>
                <div class="card-body" style="padding:14px 18px;">
                    <form method="POST" action="{{ route('teacher.profile.update') }}">
                        @csrf @method('PUT')
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                            <div class="form-group">
                                <label class="form-label">Full Name <span class="required">*</span></label>
                                <input type="text" name="name"
                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address <span class="required">*</span></label>
                                <input type="email" name="email"
                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
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
                                <input type="text" name="bio" class="form-control"
                                       value="{{ old('bio', $user->bio) }}" placeholder="A short bio...">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:4px;">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="padding:12px 18px;">
                    <div class="card-title"><i class="fas fa-lock"></i> Change Password</div>
                </div>
                <div class="card-body" style="padding:14px 18px;">
                    <form method="POST" action="{{ route('teacher.profile.password') }}">
                        @csrf @method('PUT')
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
                            <div class="form-group">
                                <label class="form-label">Current Password <span class="required">*</span></label>
                                <input type="password" name="current_password"
                                       class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}" required>
                                @error('current_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">New Password <span class="required">*</span></label>
                                <input type="password" name="password" class="form-control" required placeholder="Min. 8 chars">
                                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm New Password <span class="required">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger" style="width:100%;margin-top:4px;">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection