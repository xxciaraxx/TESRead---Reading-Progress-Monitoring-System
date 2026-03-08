{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TESRead</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/jpg" href="{{ asset('favicon.jpg') }}">
    <style>
        :root { --blue:#003A8C; --blue-dark:#002870; --blue-mid:#0050C2; --red:#C8102E; --red-dark:#a00d25; }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; }

        body {
            font-family: 'Work Sans', sans-serif;
            background: #0a1628;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Background ── */
        .bg-layer { position:fixed; inset:0; z-index:0; background-image:url('/images/TESS.jpg'); background-size:cover; background-position:center 60%; filter:brightness(0.38) saturate(0.6); }
        .bg-overlay { position:fixed; inset:0; z-index:1; background:linear-gradient(170deg, rgba(0,20,70,0.72) 0%, rgba(0,40,120,0.60) 45%, rgba(0,55,160,0.50) 70%, rgba(0,20,60,0.80) 100%); }
        .stars { position:fixed; inset:0; z-index:2; pointer-events:none; }
        .star  { position:absolute; background:white; border-radius:50%; animation:twinkle var(--d) ease-in-out infinite alternate; opacity:0; }
        @keyframes twinkle { 0%{ opacity:0; } 100%{ opacity:var(--o); } }

        .wave-bottom { position:fixed; bottom:0; left:0; right:0; z-index:3; line-height:0; pointer-events:none; }
        .wave-bottom svg { display:block; width:100%; }

        /* ── Card ── */
        .main-container {
            position: relative; z-index: 10;
            width: 100%; max-width: 1060px;
            height: calc(100vh - 48px);
            max-height: 720px;
            display: flex;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.55), 0 0 0 1px rgba(255,255,255,0.08);
            margin: 24px 20px;
        }

        /* ══ LEFT PANEL ══ */
        .left-panel {
            flex: 1.1;
            background: linear-gradient(160deg, rgba(0,18,60,0.88) 0%, rgba(0,40,110,0.82) 55%, rgba(0,15,55,0.92) 100%);
            backdrop-filter: blur(24px);
            border-right: 1px solid rgba(255,255,255,0.09);
            position: relative; overflow: hidden;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 44px 40px;
        }

        .glow { position:absolute; border-radius:50%; pointer-events:none; filter:blur(70px); }
        .glow-1 { width:340px; height:340px; background:rgba(0,70,200,0.30); top:-100px; left:-100px; }
        .glow-2 { width:280px; height:280px; background:rgba(200,16,46,0.18); bottom:-80px; right:-80px; }
        .glow-3 { width:200px; height:200px; background:rgba(0,100,255,0.15); top:50%; left:50%; transform:translate(-50%,-50%); }

        .dot-pattern { position:absolute; inset:0; pointer-events:none; background-image:radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px); background-size:24px 24px; }
        .deco-dots { position:absolute; top:22px; right:22px; display:grid; grid-template-columns:repeat(5,1fr); gap:5px; }
        .deco-dots span { width:4px; height:4px; background:rgba(255,255,255,0.22); border-radius:50%; display:block; }

        .panel-wave { position:absolute; bottom:0; left:0; right:0; line-height:0; pointer-events:none; opacity:0.15; }
        .panel-wave svg { display:block; width:100%; }

        .panel-content { position:relative; z-index:3; display:flex; flex-direction:column; align-items:center; text-align:center; width:100%; }

        .school-logo-circle { width:80px; height:80px; border-radius:50%; background:white; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; box-shadow:0 8px 32px rgba(0,0,0,0.35), 0 0 0 4px rgba(255,255,255,0.12); overflow:hidden; flex-shrink:0; }
        .school-logo-circle img { width:100%; height:100%; object-fit:cover; }
        .logo-placeholder { display:flex; align-items:center; justify-content:center; width:100%; height:100%; }
        .logo-placeholder span { color:var(--blue); font-family:'Playfair Display',serif; font-size:28px; font-weight:900; }

        .brand-badge { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.20); border-radius:50px; padding:5px 14px; font-size:10.5px; font-weight:600; color:rgba(255,255,255,0.80); letter-spacing:1px; text-transform:uppercase; margin-bottom:14px; }
        .brand-badge i { color:#ffd166; font-size:10px; }

        .brand-name    { font-family:'Playfair Display',serif; font-size:42px; font-weight:900; color:#fff; letter-spacing:-1px; line-height:1; margin-bottom:6px; text-shadow:0 4px 20px rgba(0,0,0,0.3); }
        .brand-tagline { color:rgba(255,255,255,0.55); font-size:10.5px; letter-spacing:1.8px; text-transform:uppercase; margin-bottom:26px; }
        .divider { width:40px; height:1.5px; background:linear-gradient(90deg,transparent,rgba(255,255,255,0.35),transparent); margin:0 auto 20px; }

        .welcome-card { background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.11); border-radius:16px; padding:18px 22px; width:100%; text-align:left; backdrop-filter:blur(8px); margin-bottom:14px; }
        .welcome-card h2 { font-family:'Playfair Display',serif; font-size:18px; color:#fff; margin-bottom:6px; }
        .welcome-card p  { color:rgba(255,255,255,0.65); font-size:12.5px; line-height:1.6; }

        .feature-list { width:100%; display:flex; flex-direction:column; gap:8px; }
        .feature-item { display:flex; align-items:center; gap:12px; padding:10px 14px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.09); border-radius:11px; transition:background 0.2s; }
        .feature-item:hover { background:rgba(255,255,255,0.09); }
        .feature-icon { width:32px; height:32px; background:rgba(255,255,255,0.11); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
        .feature-text { font-size:12px; color:rgba(255,255,255,0.78); line-height:1.4; text-align:left; }

        /* ══ RIGHT PANEL ══ */
        .right-panel {
            flex: 1;
            background: rgba(255,255,255,0.96);
            display: flex; align-items: stretch; justify-content: center;
            overflow: hidden;
        }

        /* Only the form scrolls */
        .form-scroll {
            width: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 36px 40px;
        }
        .form-scroll::-webkit-scrollbar { width: 4px; }
        .form-scroll::-webkit-scrollbar-track { background: transparent; }
        .form-scroll::-webkit-scrollbar-thumb { background: #dde2ee; border-radius: 4px; }

        .card { width: 100%; max-width: 370px; }

        .back-link { display:inline-flex; align-items:center; gap:6px; color:#b0b8cc; font-size:11px; font-weight:600; text-decoration:none; letter-spacing:0.8px; text-transform:uppercase; margin-bottom:20px; transition:color 0.2s; }
        .back-link:hover { color:var(--blue); }
        .back-link i { font-size:10px; }

        .logo-section { margin-bottom:18px; }
        .logo-eyebrow { font-size:11px; font-weight:700; color:var(--blue); letter-spacing:2px; text-transform:uppercase; display:block; margin-bottom:5px; opacity:0.8; }
        .create-heading { font-family:'Playfair Display',serif; font-size:32px; font-weight:900; color:#0c1830; line-height:1.1; }

        .form-group { margin-bottom:12px; }
        .input-label { display:block; font-size:11px; font-weight:700; color:#606880; letter-spacing:0.6px; text-transform:uppercase; margin-bottom:5px; }

        .input-wrap { position:relative; }
        .input-wrap .input-icon { position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#c4ccdc; font-size:13px; z-index:11; pointer-events:none; }

        input[type="email"], input[type="password"], input[type="text"] {
            width:100%; padding:11px 18px 11px 43px;
            border:1.5px solid #e8ecf5; border-radius:12px;
            font-size:13.5px; font-family:'Work Sans',sans-serif;
            color:#1a1a2e; background:#f6f8fd;
            transition:all 0.25s; outline:none;
        }
        input:focus { border-color:var(--blue); background:#fff; box-shadow:0 0 0 4px rgba(0,58,140,0.08); }
        input.is-invalid { border-color:var(--red) !important; }

        select {
            width:100%; padding:11px 40px 11px 18px;
            border:1.5px solid #e8ecf5; border-radius:12px;
            font-size:13.5px; font-family:'Work Sans',sans-serif;
            color:#1a1a2e; background:#f6f8fd;
            transition:all 0.25s; outline:none; appearance:none; cursor:pointer;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23003A8C' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 16px center;
        }
        select:focus { border-color:var(--blue); background-color:#fff; box-shadow:0 0 0 4px rgba(0,58,140,0.08); }

        .hint-text { display:block; margin-top:4px; padding-left:4px; font-size:10.5px; color:#b0b8cc; font-style:italic; }
        .invalid-feedback { display:block; margin-top:4px; padding-left:4px; font-size:10.5px; color:var(--red); }

        .password-wrapper { position:relative; }
        .password-wrapper input { padding-right:44px; }
        .eye-toggle { position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#c4ccdc; font-size:14px; padding:4px; transition:color 0.2s; z-index:11; }
        .eye-toggle:hover { color:var(--blue); }

        /* Password strength */
        .password-strength { margin-top:8px; padding:10px 14px; border-radius:12px; background:#f4f6fb; border-left:3px solid var(--blue); display:none; }
        .password-strength.visible { display:block; }
        .strength-indicator { display:flex; align-items:center; gap:8px; margin-bottom:7px; }
        .strength-bar { flex:1; height:4px; background:#dde2ee; border-radius:3px; overflow:hidden; }
        .strength-fill { height:100%; width:0%; transition:all 0.4s; border-radius:3px; }
        .strength-fill.weak   { width:33%; background:var(--red); }
        .strength-fill.medium { width:66%; background:#FFC107; }
        .strength-fill.strong { width:100%; background:#28a745; }
        .strength-text { font-weight:700; font-size:11.5px; }
        .strength-text.weak   { color:var(--red); }
        .strength-text.medium { color:#b8860b; }
        .strength-text.strong { color:#28a745; }
        .requirements { font-size:10.5px; color:#888; line-height:1.5; }
        .requirement { display:flex; align-items:center; gap:5px; margin:1px 0; }
        .requirement::before { content:'○'; color:#ccc; font-weight:bold; }
        .requirement.met::before { content:'✓'; color:#28a745; }

        /* Pending notice */
        .pending-notice { background:#fffbeb; border:1.5px solid #fde68a; border-radius:12px; padding:10px 14px; margin-bottom:12px; font-size:12px; color:#92400e; display:none; gap:9px; align-items:flex-start; }
        .pending-notice.visible { display:flex; }
        .pending-notice i { flex-shrink:0; margin-top:1px; }

        .alert { padding:11px 14px; border-radius:12px; margin-bottom:14px; font-size:12.5px; font-weight:500; display:flex; align-items:flex-start; gap:9px; }
        @keyframes shake { 0%,100%{ transform:translateX(0); } 20%,60%{ transform:translateX(-5px); } 40%,80%{ transform:translateX(5px); } }
        .alert-danger { background:#fff1f3; color:#a00d25; border:1.5px solid #ffd0d8; animation:shake 0.5s; }
        .alert i { flex-shrink:0; margin-top:1px; }

        .btn { width:100%; padding:12px; border:none; border-radius:12px; font-size:13.5px; font-weight:700; cursor:pointer; transition:all 0.3s; letter-spacing:0.8px; text-transform:uppercase; position:relative; overflow:hidden; margin-top:4px; }
        .btn-primary { background:var(--blue); color:white; box-shadow:0 6px 24px rgba(0,58,140,0.30); }
        .btn-primary::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,0.18),transparent); left:-100%; transition:left 0.5s; }
        .btn-primary:hover::after { left:100%; }
        .btn-primary:hover { background:var(--blue-dark); transform:translateY(-2px); box-shadow:0 10px 32px rgba(0,58,140,0.40); }

        .login-link { text-align:center; margin-top:16px; font-size:12.5px; color:#aab0c6; }
        .login-link a { color:var(--red); text-decoration:none; font-weight:700; }
        .login-link a:hover { color:var(--red-dark); }

        .page-foot { position:fixed; bottom:0; left:0; right:0; z-index:4; padding:10px 48px 14px; display:flex; justify-content:center; }
        .page-foot p { font-size:11.5px; color:rgba(0,40,100,0.55); font-weight:500; }

        @media (max-width: 900px) { .main-container { flex-direction:column; max-height:none; height:auto; } .left-panel { padding:32px 28px; } }
        @media (max-width: 600px) { body { overflow-y:auto; } html { overflow:auto; } .main-container { margin:0; border-radius:0; height:auto; } }
    </style>
</head>
<body>
    <div class="bg-layer"></div>
    <div class="bg-overlay"></div>
    <div class="stars" id="stars"></div>

    <div class="wave-bottom">
        <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,45 C240,85 480,15 720,45 C960,75 1200,15 1440,45 L1440,80 L0,80 Z" fill="white" opacity="0.9"/>
            <path d="M0,58 C300,22 600,78 900,52 C1100,36 1300,68 1440,55 L1440,80 L0,80 Z" fill="white"/>
        </svg>
    </div>

    <div class="page-foot">
        <p>© {{ date('Y') }} Tampugo Elementary School &nbsp;·&nbsp; TESRead Digital Reading Progress Monitoring</p>
    </div>

<div class="main-container">

    <!-- ══ LEFT ══ -->
    <div class="left-panel">
        <div class="glow glow-1"></div>
        <div class="glow glow-2"></div>
        <div class="glow glow-3"></div>
        <div class="dot-pattern"></div>
        <div class="deco-dots">
            @for($i = 0; $i < 25; $i++)<span></span>@endfor
        </div>
        <div class="panel-wave">
            <svg viewBox="0 0 500 60" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0,30 Q60,10 120,30 T240,30 T360,30 T500,30 L500,60 L0,60 Z" fill="white"/>
            </svg>
        </div>

        <div class="panel-content">
            <div class="school-logo-circle">
                <img src="{{ asset('images/TES-logo.jpg') }}" alt="TES Logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="logo-placeholder" style="display:none;"><span>T</span></div>
            </div>

            <div class="brand-badge">
                <i class="fas fa-star"></i> Reading Progress Monitoring
            </div>

            <div class="brand-name">TESRead</div>
            <div class="brand-tagline">Tampugo Elementary School</div>
            <div class="divider"></div>

            <div class="welcome-card">
                <h2>Join TESRead!</h2>
                <p>Create your account to start tracking and supporting your pupils' reading journey with organized, data-driven insights.</p>
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div class="feature-text">Track reading progress and assessments in one place</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🎯</div>
                    <div class="feature-text">Identify pupils who need early reading intervention</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📋</div>
                    <div class="feature-text">Generate organized reports for instructional decisions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ RIGHT ══ -->
    <div class="right-panel">
        <div class="form-scroll">
            <div class="card">

                <a href="{{ route('landing') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>

                <div class="logo-section">
                    <span class="logo-eyebrow">TESRead</span>
                    <div class="create-heading">Sign Up</div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="pending-notice" id="pendingNotice">
                    <i class="fas fa-clock"></i>
                    <span>Teacher accounts require admin approval before you can log in. You'll be notified once approved.</span>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label class="input-label" for="role">Register As</label>
                        <select id="role" name="role" required class="{{ $errors->has('role') ? 'is-invalid' : '' }}">
                            <option value="">— Select Role —</option>
                            <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="name">Full Name</label>
                        <div class="input-wrap">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="name" name="name" required autocomplete="name"
                                   placeholder="Your full name" value="{{ old('name') }}"
                                   class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="email">Email Address</label>
                        <div class="input-wrap">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                   placeholder="your@email.com" value="{{ old('email') }}"
                                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                        </div>
                    </div>

                    <div class="form-group" id="teacherCodeGroup" style="display:none;">
                        <label class="input-label" for="teacher_code">Teacher Access Code</label>
                        <div class="input-wrap">
                            <i class="fas fa-key input-icon"></i>
                            <input type="password" id="teacher_code" name="teacher_code"
                                   autocomplete="off" placeholder="Enter teacher access code"
                                   class="{{ $errors->has('teacher_code') ? 'is-invalid' : '' }}">
                        </div>
                        @error('teacher_code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <small class="hint-text">Contact your administrator to get the access code</small>
                    </div>

                    <div class="form-group" id="adminCodeGroup" style="display:none;">
                        <label class="input-label" for="admin_code">Administrator Access Code</label>
                        <div class="input-wrap">
                            <i class="fas fa-shield-alt input-icon"></i>
                            <input type="password" id="admin_code" name="admin_code"
                                   autocomplete="off" placeholder="Enter admin access code"
                                   class="{{ $errors->has('admin_code') ? 'is-invalid' : '' }}">
                        </div>
                        @error('admin_code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <small class="hint-text">Only authorized administrators have this code</small>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="password">Password</label>
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
                        <label class="input-label" for="password_confirmation">Confirm Password</label>
                        <div class="input-wrap">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   required autocomplete="new-password" placeholder="Re-enter your password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus" style="margin-right:8px;"></i> Create Account
                    </button>
                </form>

                <div class="login-link">
                    Already have an account? <a href="{{ route('login') }}">Sign In</a>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    const starsEl = document.getElementById('stars');
    for (let i = 0; i < 55; i++) {
        const s = document.createElement('div');
        s.className = 'star';
        const sz = Math.random() * 2 + 0.8;
        s.style.cssText = `width:${sz}px;height:${sz}px;top:${Math.random()*80}%;left:${Math.random()*100}%;--d:${Math.random()*3+2}s;--o:${Math.random()*0.5+0.15};animation-delay:${Math.random()*4}s;`;
        starsEl.appendChild(s);
    }

    // Role toggle — UNCHANGED LOGIC
    const roleSelect       = document.getElementById('role');
    const teacherCodeGroup = document.getElementById('teacherCodeGroup');
    const adminCodeGroup   = document.getElementById('adminCodeGroup');
    const teacherCodeInput = document.getElementById('teacher_code');
    const adminCodeInput   = document.getElementById('admin_code');
    const pendingNotice    = document.getElementById('pendingNotice');

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
            pendingNotice.classList.add('visible');
        } else if (role === 'admin') {
            adminCodeGroup.style.display = 'block';
            adminCodeInput.setAttribute('required', 'required');
        }
    });
    if (roleSelect.value) roleSelect.dispatchEvent(new Event('change'));

    // Password toggle — UNCHANGED LOGIC
    document.getElementById('togglePassword').addEventListener('click', function () {
        const p = document.getElementById('password');
        const e = document.getElementById('eyeIcon');
        const hidden = p.type === 'password';
        p.type = hidden ? 'text' : 'password';
        e.className = hidden ? 'fas fa-eye-slash' : 'fas fa-eye';
    });

    // Password strength — UNCHANGED LOGIC
    document.getElementById('password').addEventListener('input', function () {
        const p = this.value;
        const box = document.getElementById('passwordStrength');
        if (!p.length) { box.classList.remove('visible'); return; }
        box.classList.add('visible');
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
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');
        fill.className = 'strength-fill';
        text.className = 'strength-text';
        if (score <= 2) { fill.classList.add('weak');   text.classList.add('weak');   text.textContent = 'Weak'; }
        else if (score <= 4) { fill.classList.add('medium'); text.classList.add('medium'); text.textContent = 'Medium'; }
        else { fill.classList.add('strong'); text.classList.add('strong'); text.textContent = 'Strong'; }
    });

    // Form submit validation — UNCHANGED LOGIC
    document.querySelector('form').addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const confirm  = document.getElementById('password_confirmation').value;
        if (password !== confirm) { e.preventDefault(); alert('Passwords do not match. Please try again.'); return; }
        const isStrong = /[A-Z]/.test(password) && /[a-z]/.test(password) && /[0-9]/.test(password) && /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
        if (password.length < 8 || !isStrong) { e.preventDefault(); alert('Please create a stronger password that meets all requirements.'); }
    });
</script>
</body>
</html>