<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TESRead</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --blue: #003A8C; --blue-dark: #002870; --red: #C8102E; --red-dark: #a00d25; }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { height: 100%; }

        body {
            font-family: 'Work Sans', sans-serif;
            background: #e8edf5;
            min-height: 100%;
            display: flex; align-items: center; justify-content: center;
            padding: 24px 20px;
            overflow-y: auto; overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url('/images/TES.jpg');
            background-size: cover; background-position: center;
            filter: blur(4px); opacity: 0.35;
            z-index: 0; pointer-events: none; transform: scale(1.05);
        }

        .main-container {
            position: relative; z-index: 10;
            width: 100%; max-width: 1060px;
            display: flex; background: white;
            border-radius: 28px; overflow: hidden;
            box-shadow: 0 24px 80px rgba(0,40,112,0.22), 0 8px 24px rgba(0,0,0,0.10);
        }

        /* ═══ LEFT PANEL ═══ */
        .left-panel {
            flex: 1.1; background: var(--blue);
            position: relative; overflow: hidden;
            display: flex; flex-direction: column;
            align-items: center; justify-content: flex-start;
            padding: 44px 40px 40px; z-index: 1;
        }
        .left-panel .bg-image { position: absolute; inset: 0; background-image: url('{{ asset("images/TESS.jpg") }}'); background-size: cover; background-position: center; opacity: 0.10; z-index: 0; }

        .blob { position: absolute; border-radius: 50%; pointer-events: none; z-index: 1; }
        .blob-1 { width: 380px; height: 380px; background: rgba(255,255,255,0.06); top: -80px; left: -100px; border-radius: 60% 40% 70% 30% / 50% 60% 40% 50%; }
        .blob-2 { width: 300px; height: 300px; background: rgba(200,16,46,0.18); bottom: -60px; right: -60px; border-radius: 40% 60% 30% 70% / 60% 40% 70% 30%; }
        .blob-3 { width: 200px; height: 200px; background: rgba(255,255,255,0.05); bottom: 80px; left: 30px; border-radius: 70% 30% 50% 50% / 40% 60% 40% 60%; }
        .blob-4 { width: 160px; height: 160px; background: rgba(0,77,179,0.5); top: 120px; right: 20px; border-radius: 50% 50% 30% 70% / 60% 40% 60% 40%; }

        .deco { position: absolute; pointer-events: none; z-index: 2; color: rgba(255,255,255,0.25); font-size: 22px; font-weight: 300; user-select: none; }
        .deco-plus-tl { top: 32px; left: 32px; }
        .deco-plus-bm { bottom: 80px; left: 50%; transform: translateX(-50%); }
        .deco-circle-tl { top: 90px; left: 55%; width: 26px; height: 26px; border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; background: transparent; position: absolute; z-index: 2; }
        .deco-circle-bl { bottom: 60px; left: 28px; width: 36px; height: 36px; border: 2.5px solid rgba(255,255,255,0.25); border-radius: 50%; background: transparent; position: absolute; z-index: 2; }
        .deco-dots { position: absolute; top: 28px; right: 28px; z-index: 2; display: grid; grid-template-columns: repeat(4,1fr); gap: 5px; }
        .deco-dots span { width: 5px; height: 5px; background: rgba(255,255,255,0.35); border-radius: 50%; display: block; }
        .deco-wave { position: absolute; bottom: 0; right: 0; width: 260px; height: 200px; z-index: 2; opacity: 0.18; }

        .panel-content { position: relative; z-index: 3; display: flex; flex-direction: column; align-items: center; text-align: center; width: 100%; }

        .school-logo-circle { width: 100px; height: 100px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; box-shadow: 0 8px 28px rgba(0,0,0,0.22); overflow: hidden; flex-shrink: 0; border: 4px solid rgba(255,255,255,0.6); }
        .school-logo-circle img { width: 100%; height: 100%; object-fit: cover; }
        .logo-placeholder { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; height: 100%; color: var(--blue); font-size: 9px; font-weight: 700; gap: 4px; padding: 8px; text-align: center; }
        .logo-placeholder svg { width: 36px; height: 36px; opacity: 0.5; }

        .school-name { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; color: #fff; margin-bottom: 6px; text-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        .school-tagline { color: rgba(255,255,255,0.80); font-size: 12px; margin-bottom: 20px; }

        .welcome-block { background: rgba(255,255,255,0.08); border: 1.5px solid rgba(255,255,255,0.15); border-radius: 16px; padding: 18px 22px; width: 100%; backdrop-filter: blur(6px); text-align: left; }
        .welcome-block h2 { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 8px; }
        .welcome-block p { color: rgba(255,255,255,0.82); font-size: 13px; line-height: 1.55; }

        .info-points { margin-top: 18px; width: 100%; }
        .info-point { display: flex; align-items: center; gap: 10px; padding: 10px 12px; margin-bottom: 8px; background: rgba(255,255,255,0.07); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 10px; transition: background 0.2s; }
        .info-point:hover { background: rgba(255,255,255,0.12); }
        .info-point-icon { width: 34px; height: 34px; background: rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .info-point-text { font-size: 12.5px; color: rgba(255,255,255,0.88); line-height: 1.4; text-align: left; }

        /* ═══ RIGHT PANEL ═══ */
        .right-panel { flex: 1; background: #fff; display: flex; align-items: flex-start; justify-content: center; overflow-y: auto; }
        .card { width: 100%; max-width: 400px; padding: 44px 40px; }

        .logo-section { text-align: left; margin-bottom: 20px; }
        .logo { font-family: 'Playfair Display', serif; font-size: 34px; font-weight: 900; color: var(--blue); letter-spacing: -1px; margin-bottom: 4px; display: block; }
        .subtitle { color: #888; font-size: 11px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; }
        .create-heading { font-size: 24px; font-weight: 700; color: #1a1a2e; margin-bottom: 18px; }

        .form-group { margin-bottom: 13px; }
        label { display: block; margin-bottom: 5px; color: #444; font-weight: 600; font-size: 12.5px; letter-spacing: 0.3px; }

        .input-wrap { position: relative; }
        .input-wrap .input-icon { position: absolute; left: 17px; top: 50%; transform: translateY(-50%); color: #aab0c0; font-size: 14px; z-index: 11; pointer-events: none; }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; padding: 11px 18px 11px 44px;
            border: 1.5px solid #e0e4ec; border-radius: 50px;
            font-size: 13.5px; font-family: 'Work Sans', sans-serif;
            color: #333; background: #f8f9fc;
            transition: border-color 0.25s, box-shadow 0.25s; outline: none;
        }
        input:focus { border-color: var(--blue); background: #fff; box-shadow: 0 0 0 4px rgba(0,58,140,0.10); }
        input.is-invalid { border-color: var(--red) !important; }

        select {
            width: 100%; padding: 11px 40px 11px 18px;
            border: 1.5px solid #e0e4ec; border-radius: 50px;
            font-size: 13.5px; font-family: 'Work Sans', sans-serif;
            color: #333; background: #f8f9fc;
            transition: border-color 0.25s; outline: none;
            appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23003A8C' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 18px center;
        }
        select:focus { border-color: var(--blue); background-color: #fff; box-shadow: 0 0 0 4px rgba(0,58,140,0.10); }

        .hint-text { display: block; margin-top: 5px; padding-left: 14px; font-size: 11px; color: #aaa; font-style: italic; }
        .invalid-feedback { display: block; margin-top: 5px; padding-left: 14px; font-size: 11px; color: var(--red); }

        .password-wrapper { position: relative; }
        .password-wrapper input { padding-right: 44px; }
        .eye-toggle { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #aab0c0; font-size: 15px; padding: 4px; transition: color 0.2s; z-index: 11; }
        .eye-toggle:hover { color: var(--blue); }

        /* Password strength */
        .password-strength { margin-top: 10px; padding: 12px 16px; border-radius: 14px; background: #f4f6fb; border-left: 4px solid var(--blue); display: none; }
        .password-strength.visible { display: block; }
        .strength-indicator { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
        .strength-bar { flex: 1; height: 5px; background: #dde2ee; border-radius: 3px; overflow: hidden; }
        .strength-fill { height: 100%; width: 0%; transition: all 0.4s; border-radius: 3px; }
        .strength-fill.weak   { width: 33%; background: var(--red); }
        .strength-fill.medium { width: 66%; background: #FFC107; }
        .strength-fill.strong { width: 100%; background: #28a745; }
        .strength-text { font-weight: 700; font-size: 12px; }
        .strength-text.weak   { color: var(--red); }
        .strength-text.medium { color: #b8860b; }
        .strength-text.strong { color: #28a745; }
        .requirements { font-size: 11px; color: #888; line-height: 1.5; }
        .requirement { display: flex; align-items: center; gap: 6px; margin: 2px 0; }
        .requirement::before { content: '○'; color: #ccc; font-weight: bold; }
        .requirement.met::before { content: '✓'; color: #28a745; }

        /* Pending notice for teachers */
        .pending-notice {
            background: #fff8e1; border: 1.5px solid #FFC107;
            border-radius: 12px; padding: 12px 16px;
            margin-bottom: 14px; font-size: 12.5px;
            color: #856404; display: none;
            gap: 10px; align-items: flex-start;
        }
        .pending-notice.visible { display: flex; }
        .pending-notice i { flex-shrink: 0; margin-top: 1px; }

        .alert { padding: 11px 16px; border-radius: 12px; margin-bottom: 14px; font-size: 13px; font-weight: 500; display: flex; align-items: flex-start; gap: 10px; }
        @keyframes shake { 0%,100%{ transform:translateX(0); } 20%,60%{ transform:translateX(-5px); } 40%,80%{ transform:translateX(5px); } }
        .alert-danger { background: #ffeaea; color: #a00d25; border: 1.5px solid var(--red); animation: shake 0.5s; }
        .alert i { flex-shrink: 0; margin-top: 1px; }

        .btn { width: 100%; padding: 13px; border: none; border-radius: 50px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; letter-spacing: 0.6px; text-transform: uppercase; position: relative; overflow: hidden; margin-top: 4px; }
        .btn-primary { background: var(--blue); color: white; box-shadow: 0 4px 18px rgba(0,58,140,0.30); }
        .btn-primary::after { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent); left: -100%; transition: left 0.5s; }
        .btn-primary:hover::after { left: 100%; }
        .btn-primary:hover { background: var(--blue-dark); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,58,140,0.38); }

        .login-link { text-align: center; margin-top: 16px; font-size: 13px; color: #888; }
        .login-link a { color: var(--red); text-decoration: none; font-weight: 600; }
        .login-link a:hover { color: var(--red-dark); text-decoration: underline; }

        @media (max-width: 900px) { .main-container { flex-direction: column; } .left-panel, .right-panel { padding: 36px 28px; } .card { padding: 36px 28px; } }
        @media (max-width: 600px) { body { padding: 0; } .main-container { border-radius: 0; } }
    </style>
</head>
<body>
<div class="main-container">

    <!-- ═══ LEFT PANEL ═══ -->
    <div class="left-panel">
        <div class="bg-image"></div>
        <div class="blob blob-1"></div><div class="blob blob-2"></div>
        <div class="blob blob-3"></div><div class="blob blob-4"></div>
        <div class="deco deco-plus-tl">+</div>
        <div class="deco deco-plus-bm">+</div>
        <div class="deco-circle-tl"></div>
        <div class="deco-circle-bl"></div>
        <div class="deco-dots">
            @for($i = 0; $i < 20; $i++)<span></span>@endfor
        </div>
        <svg class="deco-wave" viewBox="0 0 260 200" fill="none">
            <path d="M20 160 Q60 100 100 140 Q140 180 180 120 Q220 60 260 100" stroke="white" stroke-width="2.5" fill="none"/>
            <path d="M0 180 Q50 130 90 160 Q130 190 170 140 Q210 90 260 130" stroke="white" stroke-width="2" fill="none" opacity="0.6"/>
            <path d="M30 200 Q80 160 120 185 Q160 210 200 165 Q240 120 260 150" stroke="white" stroke-width="1.5" fill="none" opacity="0.4"/>
        </svg>

        <div class="panel-content">
            <div class="school-logo-circle">
                <img src="{{ asset('images/TES-logo.jpg') }}" alt="Tampugo Elementary School Logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="logo-placeholder" style="display:none;">
                    <svg viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" stroke="#003A8C" stroke-width="2" fill="#e8edf5"/><path d="M14 32 L24 14 L34 32 H26 V24 H22 V32 Z" fill="#003A8C" opacity="0.5"/></svg>
                    <span style="color:#003A8C;font-size:9px;font-weight:700;opacity:0.6;">SCHOOL LOGO</span>
                </div>
            </div>

            <h1 class="school-name">TESRead</h1>
            <p class="school-tagline">Digital Reading Progress Monitoring System</p>

            <div class="welcome-block">
                <h2>Join TESRead!</h2>
                <p>Create your account to start tracking and supporting your pupils' reading journey with organized, data-driven insights.</p>
            </div>

            <div class="info-points">
                <div class="info-point">
                    <div class="info-point-icon">📊</div>
                    <div class="info-point-text">Track reading progress and assessments in one place</div>
                </div>
                <div class="info-point">
                    <div class="info-point-icon">🎯</div>
                    <div class="info-point-text">Identify pupils who need early reading intervention</div>
                </div>
                <div class="info-point">
                    <div class="info-point-icon">📋</div>
                    <div class="info-point-text">Generate organized reports for instructional decisions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ RIGHT PANEL ═══ -->
    <div class="right-panel">
        <div class="card">

            <div class="logo-section">
                <span class="logo">TESRead</span>
                <div class="subtitle">Create Your Account</div>
            </div>

            <h1 class="create-heading">Sign Up</h1>

            {{-- Server-side validation errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Pending approval notice (shown dynamically for teachers) --}}
            <div class="pending-notice" id="pendingNotice">
                <i class="fas fa-clock"></i>
                <span>Teacher accounts require admin approval before you can log in. You'll be notified once approved.</span>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="role">Register As</label>
                    <select id="role" name="role" required class="{{ $errors->has('role') ? 'is-invalid' : '' }}">
                        <option value="">— Select Role —</option>
                        <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrap">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="name" name="name" required autocomplete="name"
                               placeholder="Your full name" value="{{ old('name') }}"
                               class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" required autocomplete="email"
                               placeholder="your@email.com" value="{{ old('email') }}"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                {{-- Teacher Access Code --}}
                <div class="form-group" id="teacherCodeGroup" style="display:none;">
                    <label for="teacher_code">Teacher Access Code</label>
                    <div class="input-wrap">
                        <i class="fas fa-key input-icon"></i>
                        <input type="password" id="teacher_code" name="teacher_code"
                               autocomplete="off" placeholder="Enter teacher access code"
                               class="{{ $errors->has('teacher_code') ? 'is-invalid' : '' }}">
                    </div>
                    @error('teacher_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="hint-text">Contact your administrator to get the access code</small>
                </div>

                {{-- Admin Access Code --}}
                <div class="form-group" id="adminCodeGroup" style="display:none;">
                    <label for="admin_code">Administrator Access Code</label>
                    <div class="input-wrap">
                        <i class="fas fa-shield-alt input-icon"></i>
                        <input type="password" id="admin_code" name="admin_code"
                               autocomplete="off" placeholder="Enter admin access code"
                               class="{{ $errors->has('admin_code') ? 'is-invalid' : '' }}">
                    </div>
                    @error('admin_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="hint-text">Only authorized administrators have this code</small>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap password-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" required
                               autocomplete="new-password" placeholder="Create a strong password">
                        <button type="button" class="eye-toggle" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div id="passwordStrength" class="password-strength">
                        <div class="strength-indicator">
                            <div class="strength-bar"><div id="strengthFill" class="strength-fill"></div></div>
                            <span id="strengthText" class="strength-text"></span>
                        </div>
                        <div class="requirements">
                            <div class="requirement" id="req-length">Minimum 8 characters</div>
                            <div class="requirement" id="req-uppercase">At least 1 uppercase letter</div>
                            <div class="requirement" id="req-lowercase">At least 1 lowercase letter</div>
                            <div class="requirement" id="req-number">At least 1 number</div>
                            <div class="requirement" id="req-special">At least 1 special character</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               required autocomplete="new-password" placeholder="Re-enter your password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-user-plus" style="margin-right:6px;"></i> Create Account
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </div>
        </div>
    </div>

</div>

<script>
    const roleSelect        = document.getElementById('role');
    const teacherCodeGroup  = document.getElementById('teacherCodeGroup');
    const adminCodeGroup    = document.getElementById('adminCodeGroup');
    const teacherCodeInput  = document.getElementById('teacher_code');
    const adminCodeInput    = document.getElementById('admin_code');
    const pendingNotice     = document.getElementById('pendingNotice');
    const passwordInput     = document.getElementById('password');
    const toggleBtn         = document.getElementById('togglePassword');
    const eyeIcon           = document.getElementById('eyeIcon');
    const strengthBox       = document.getElementById('passwordStrength');
    const strengthFill      = document.getElementById('strengthFill');
    const strengthText      = document.getElementById('strengthText');

    // ── Role toggle ──
    roleSelect.addEventListener('change', function () {
        const role = this.value;

        teacherCodeGroup.style.display = 'none';
        adminCodeGroup.style.display   = 'none';
        pendingNotice.classList.remove('visible');

        teacherCodeInput.removeAttribute('required');
        adminCodeInput.removeAttribute('required');

        if (role === 'teacher') {
            teacherCodeGroup.style.display = 'block';
            teacherCodeInput.setAttribute('required', 'required');
            pendingNotice.classList.add('visible');   // show pending warning
        } else if (role === 'admin') {
            adminCodeGroup.style.display = 'block';
            adminCodeInput.setAttribute('required', 'required');
        }
    });

    // Restore state on page reload (validation errors)
    if (roleSelect.value) roleSelect.dispatchEvent(new Event('change'));

    // ── Password toggle ──
    toggleBtn.addEventListener('click', function () {
        const hidden = passwordInput.type === 'password';
        passwordInput.type = hidden ? 'text' : 'password';
        eyeIcon.className  = hidden ? 'fas fa-eye-slash' : 'fas fa-eye';
    });

    // ── Password strength ──
    passwordInput.addEventListener('input', function () {
        const p = this.value;
        if (!p.length) { strengthBox.classList.remove('visible'); return; }
        strengthBox.classList.add('visible');

        const checks = {
            length:    p.length >= 8,
            uppercase: /[A-Z]/.test(p),
            lowercase: /[a-z]/.test(p),
            number:    /[0-9]/.test(p),
            special:   /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(p),
        };

        Object.entries(checks).forEach(([k, v]) => {
            document.getElementById('req-' + k).classList.toggle('met', v);
        });

        const score = Object.values(checks).filter(Boolean).length;
        strengthFill.className = 'strength-fill';
        strengthText.className = 'strength-text';

        if (score <= 2) {
            strengthFill.classList.add('weak');   strengthText.classList.add('weak');   strengthText.textContent = 'Weak';
        } else if (score <= 4) {
            strengthFill.classList.add('medium'); strengthText.classList.add('medium'); strengthText.textContent = 'Medium';
        } else {
            strengthFill.classList.add('strong'); strengthText.classList.add('strong'); strengthText.textContent = 'Strong';
        }
    });

    // ── Client-side pre-validation ──
    document.querySelector('form').addEventListener('submit', function (e) {
        const password = passwordInput.value;
        const confirm  = document.getElementById('password_confirmation').value;

        if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match. Please try again.');
            return;
        }

        const isStrong = /[A-Z]/.test(password) && /[a-z]/.test(password) &&
                         /[0-9]/.test(password) && /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

        if (password.length < 8 || !isStrong) {
            e.preventDefault();
            alert('Please create a stronger password that meets all requirements.');
        }
    });
</script>
</body>
</html>