<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TESRead</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --blue:      #003A8C;
            --blue-dark: #002870;
            --red:       #C8102E;
            --red-dark:  #a00d25;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { height: 100%; }

        body {
            font-family: 'Work Sans', sans-serif;
            background: #e8edf5;
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url('/images/TES.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(4px);
            opacity: 0.35;
            z-index: 0;
            pointer-events: none;
            transform: scale(1.05);
        }

        /* ─── Main Card ─── */
        .main-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1060px;
            display: flex;
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 80px rgba(0,40,112,0.22), 0 8px 24px rgba(0,0,0,0.10);
        }

        /* ═══ LEFT PANEL ═══ */
        .left-panel {
            flex: 1.1;
            background: var(--blue);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 44px 40px 40px;
            z-index: 1;
        }

        .left-panel .bg-image {
            position: absolute; inset: 0;
            background-image: url('{{ asset("images/TESS.jpg") }}');
            background-size: cover; background-position: center;
            opacity: 0.10; z-index: 0;
        }

        /* Blobs */
        .blob { position: absolute; border-radius: 50%; pointer-events: none; z-index: 1; }
        .blob-1 { width: 380px; height: 380px; background: rgba(255,255,255,0.06); top: -80px; left: -100px; border-radius: 60% 40% 70% 30% / 50% 60% 40% 50%; }
        .blob-2 { width: 300px; height: 300px; background: rgba(200,16,46,0.18); bottom: -60px; right: -60px; border-radius: 40% 60% 30% 70% / 60% 40% 70% 30%; }
        .blob-3 { width: 200px; height: 200px; background: rgba(255,255,255,0.05); bottom: 80px; left: 30px; border-radius: 70% 30% 50% 50% / 40% 60% 40% 60%; }
        .blob-4 { width: 160px; height: 160px; background: rgba(0,77,179,0.5); top: 120px; right: 20px; border-radius: 50% 50% 30% 70% / 60% 40% 60% 40%; }

        /* Geometric accents */
        .deco { position: absolute; pointer-events: none; z-index: 2; color: rgba(255,255,255,0.25); font-size: 22px; font-weight: 300; line-height: 1; user-select: none; }
        .deco-plus-tl { top: 32px; left: 32px; }
        .deco-plus-bm { bottom: 80px; left: 50%; transform: translateX(-50%); }
        .deco-circle-tl { top: 90px; left: 55%; width: 26px; height: 26px; border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; background: transparent; position: absolute; z-index: 2; }
        .deco-circle-bl { bottom: 60px; left: 28px; width: 36px; height: 36px; border: 2.5px solid rgba(255,255,255,0.25); border-radius: 50%; background: transparent; position: absolute; z-index: 2; }
        .deco-dots { position: absolute; top: 28px; right: 28px; z-index: 2; display: grid; grid-template-columns: repeat(4,1fr); gap: 5px; }
        .deco-dots span { width: 5px; height: 5px; background: rgba(255,255,255,0.35); border-radius: 50%; display: block; }
        .deco-wave { position: absolute; bottom: 0; right: 0; width: 260px; height: 200px; z-index: 2; opacity: 0.18; }

        /* Panel content */
        .panel-content { position: relative; z-index: 3; display: flex; flex-direction: column; align-items: center; text-align: center; width: 100%; }

        .school-logo-circle {
            width: 100px; height: 100px; border-radius: 50%;
            background: white; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 28px rgba(0,0,0,0.22);
            overflow: hidden; flex-shrink: 0;
            border: 4px solid rgba(255,255,255,0.6);
        }
        .school-logo-circle img { width: 100%; height: 100%; object-fit: cover; }
        .logo-placeholder { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; height: 100%; color: var(--blue); font-size: 9px; font-weight: 700; gap: 4px; text-align: center; padding: 8px; line-height: 1.3; }
        .logo-placeholder svg { width: 36px; height: 36px; opacity: 0.5; }

        .school-name { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; color: #fff; line-height: 1.25; margin-bottom: 6px; text-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        .school-tagline { color: rgba(255,255,255,0.80); font-size: 12px; margin-bottom: 20px; }

        .welcome-block {
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.15);
            border-radius: 16px; padding: 18px 22px; width: 100%;
            backdrop-filter: blur(6px);
        }
        .welcome-block h2 { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 8px; }
        .welcome-block p { color: rgba(255,255,255,0.82); font-size: 13px; line-height: 1.55; }

        .announcements-section { margin-top: 18px; width: 100%; }
        .announcements-title { font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: rgba(255,255,255,0.9); margin-bottom: 10px; text-align: left; }
        .announcement-item { display: flex; align-items: flex-start; gap: 10px; padding: 10px 12px; margin-bottom: 8px; background: rgba(255,255,255,0.07); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 10px; transition: background 0.2s; }
        .announcement-item:hover { background: rgba(255,255,255,0.12); }
        .announcement-icon { width: 34px; height: 34px; background: rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .announcement-title { font-size: 12px; font-weight: 700; color: #fff; margin-bottom: 2px; }
        .announcement-text  { font-size: 11px; color: rgba(255,255,255,0.75); line-height: 1.4; }

        /* ═══ RIGHT PANEL ═══ */
        .right-panel { flex: 1; padding: 44px 40px; display: flex; align-items: center; justify-content: center; background: #fff; }
        .card { width: 100%; max-width: 400px; }

        .logo-section { text-align: left; margin-bottom: 20px; }
        .logo { font-family: 'Playfair Display', serif; font-size: 36px; font-weight: 900; color: var(--blue); letter-spacing: -1px; margin-bottom: 4px; display: block; }
        .subtitle { color: #888; font-size: 11px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; }
        .signin-heading { font-size: 26px; font-weight: 700; color: #1a1a2e; margin-bottom: 22px; }

        .form-group { margin-bottom: 14px; }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; padding: 12px 18px 12px 46px;
            border: 1.5px solid #e0e4ec; border-radius: 50px;
            font-size: 14px; font-family: 'Work Sans', sans-serif;
            color: #333; background: #f8f9fc;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
            outline: none;
        }
        input:focus { border-color: var(--blue); background: #fff; box-shadow: 0 0 0 4px rgba(0,58,140,0.10); }
        input.is-invalid { border-color: var(--red) !important; }

        .input-wrap { position: relative; }
        .input-wrap .input-icon { position: absolute; left: 17px; top: 50%; transform: translateY(-50%); color: #aab0c0; font-size: 15px; z-index: 11; pointer-events: none; }

        .password-wrapper { position: relative; }
        .password-wrapper input { padding-right: 46px; }
        .eye-toggle { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #aab0c0; font-size: 16px; padding: 4px; transition: color 0.2s; z-index: 11; }
        .eye-toggle:hover { color: var(--blue); }

        .form-options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .remember-me { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #666; cursor: pointer; user-select: none; }
        .remember-me input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: var(--blue); margin: 0; }
        .forgot-password { color: #888; font-size: 13px; font-weight: 500; text-decoration: none; transition: color 0.2s; }
        .forgot-password:hover { color: var(--red); text-decoration: underline; }

        /* Alerts */
        .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 13px; font-weight: 500; display: flex; align-items: flex-start; gap: 10px; }
        @keyframes shake { 0%,100% { transform: translateX(0); } 20%,60% { transform: translateX(-5px); } 40%,80% { transform: translateX(5px); } }
        .alert-danger  { background: #ffeaea; color: #a00d25; border: 1.5px solid var(--red); animation: shake 0.5s; }
        .alert-success { background: #e8f8ea; color: #1a7a38; border: 1.5px solid #28a745; }
        .alert-warning { background: #fff8e1; color: #856404; border: 1.5px solid #FFC107; }
        .alert i { margin-top: 1px; flex-shrink: 0; }

        .btn { width: 100%; padding: 13px; border: none; border-radius: 50px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; letter-spacing: 0.6px; text-transform: uppercase; position: relative; overflow: hidden; }
        .btn-primary { background: var(--blue); color: white; box-shadow: 0 4px 18px rgba(0,58,140,0.30); }
        .btn-primary::after { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent); left: -100%; transition: left 0.5s; }
        .btn-primary:hover::after { left: 100%; }
        .btn-primary:hover { background: var(--blue-dark); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,58,140,0.38); }

        .register-link { text-align: center; margin-top: 16px; font-size: 13px; color: #888; }
        .register-link a { color: var(--red); text-decoration: none; font-weight: 600; transition: color 0.2s; }
        .register-link a:hover { color: var(--red-dark); text-decoration: underline; }

        @media (max-width: 900px) { .main-container { flex-direction: column; } .left-panel, .right-panel { padding: 36px 28px; } }
        @media (max-width: 600px) { body { padding: 0; } .main-container { border-radius: 0; } }
    </style>
</head>
<body>
<div class="main-container">

    <!-- ═══ LEFT PANEL ═══ -->
    <div class="left-panel">
        <div class="bg-image"></div>
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="blob blob-4"></div>
        <div class="deco deco-plus-tl">+</div>
        <div class="deco deco-plus-bm">+</div>
        <div class="deco-circle-tl"></div>
        <div class="deco-circle-bl"></div>
        <div class="deco-dots">
            @for($i = 0; $i < 20; $i++)<span></span>@endfor
        </div>
        <svg class="deco-wave" viewBox="0 0 260 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 160 Q60 100 100 140 Q140 180 180 120 Q220 60 260 100" stroke="white" stroke-width="2.5" fill="none"/>
            <path d="M0 180 Q50 130 90 160 Q130 190 170 140 Q210 90 260 130" stroke="white" stroke-width="2" fill="none" opacity="0.6"/>
            <path d="M30 200 Q80 160 120 185 Q160 210 200 165 Q240 120 260 150" stroke="white" stroke-width="1.5" fill="none" opacity="0.4"/>
        </svg>

        <div class="panel-content">
            <div class="school-logo-circle">
                <img src="{{ asset('images/TES-logo.jpg') }}" alt="Tampugo Elementary School Logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="logo-placeholder" style="display:none;">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="24" cy="24" r="22" stroke="#003A8C" stroke-width="2" fill="#e8edf5"/>
                        <path d="M14 32 L24 14 L34 32 H26 V24 H22 V32 Z" fill="#003A8C" opacity="0.5"/>
                    </svg>
                    <span style="color:#003A8C;font-size:9px;font-weight:700;opacity:0.6;">SCHOOL LOGO</span>
                </div>
            </div>

            <h1 class="school-name">TESRead</h1>
            <p class="school-tagline">Digital Reading Progress Monitoring System</p>

            <div class="welcome-block">
                <h2>Welcome Back!</h2>
                <p>Sign in to continue monitoring reading progress and supporting your pupils' literacy journey.</p>
            </div>

            <div class="announcements-section">
                <h3 class="announcements-title">Announcements</h3>
                <ul style="list-style:none;padding:0;margin:0;">
                    <li class="announcement-item">
                        <div class="announcement-icon">📖</div>
                        <div>
                            <div class="announcement-title">Final Exam</div>
                            <div class="announcement-text">4th Quarter Exam for S.Y 2025-2026</div>
                        </div>
                    </li>
                    <li class="announcement-item">
                        <div class="announcement-icon">🎓</div>
                        <div>
                            <div class="announcement-title">Graduation Day</div>
                            <div class="announcement-text">Graduation of Grade 6 Students</div>
                        </div>
                    </li>
                    <li class="announcement-item">
                        <div class="announcement-icon">📚</div>
                        <div>
                            <div class="announcement-title">Reading Program</div>
                            <div class="announcement-text">Daily reading sessions for all grade levels</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ═══ RIGHT PANEL ═══ -->
    <div class="right-panel">
        <div class="card">

            <div class="logo-section">
                <span class="logo">Login</span>
                <div class="subtitle">Sign In to Continue</div>
            </div>

            <h1 class="signin-heading">Sign In</h1>

            {{-- Success (e.g. after registration) --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Account pending / rejected / general errors --}}
            @if($errors->any())
                @php
                    $errMsg = $errors->first();
                    $isPending = str_contains($errMsg, 'pending');
                @endphp
                <div class="alert {{ $isPending ? 'alert-warning' : 'alert-danger' }}">
                    <i class="fas {{ $isPending ? 'fa-clock' : 'fa-exclamation-circle' }}"></i>
                    <span>{{ $errMsg }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <div class="input-wrap">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="Email address"
                               required autocomplete="email" autofocus
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrap password-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password"
                               placeholder="Password"
                               required autocomplete="current-password">
                        <button type="button" class="eye-toggle" id="togglePassword" title="Show password">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-me" for="remember">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt" style="margin-right:6px;"></i> Sign In
                </button>
            </form>

            <div class="register-link">
                New here? <a href="{{ route('register') }}">Create an Account</a>
            </div>
        </div>
    </div>

</div>

<script>
    // Password toggle
    const toggleBtn = document.getElementById('togglePassword');
    const passInput = document.getElementById('password');
    const eyeIcon   = document.getElementById('eyeIcon');

    toggleBtn.addEventListener('click', function () {
        const isHidden = passInput.type === 'password';
        passInput.type = isHidden ? 'text' : 'password';
        eyeIcon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
    });
</script>
</body>
</html>