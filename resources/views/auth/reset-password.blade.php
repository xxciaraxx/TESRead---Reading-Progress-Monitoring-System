<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - TESRead</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/jpg" href="{{ asset('favicon.jpg') }}">
    <style>
        :root { --blue:#003A8C; --blue-dark:#002870; --red:#C8102E; --red-dark:#a00d25; }
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; overflow:hidden; }
        body { font-family:'Work Sans',sans-serif; background:#0a1628; display:flex; align-items:center; justify-content:center; }
        .bg-layer { position:fixed; inset:0; z-index:0; background-image:url('/images/TESS.jpg'); background-size:cover; background-position:center 60%; filter:brightness(0.38) saturate(0.6); }
        .bg-overlay { position:fixed; inset:0; z-index:1; background:linear-gradient(170deg,rgba(0,20,70,0.72) 0%,rgba(0,40,120,0.60) 45%,rgba(0,55,160,0.50) 70%,rgba(0,20,60,0.80) 100%); }
        .stars { position:fixed; inset:0; z-index:2; pointer-events:none; }
        .star  { position:absolute; background:white; border-radius:50%; animation:twinkle var(--d) ease-in-out infinite alternate; opacity:0; }
        @keyframes twinkle { 0%{opacity:0;} 100%{opacity:var(--o);} }
        .wave-bottom { position:fixed; bottom:0; left:0; right:0; z-index:3; line-height:0; pointer-events:none; }
        .wave-bottom svg { display:block; width:100%; }
        .page-foot { position:fixed; bottom:0; left:0; right:0; z-index:4; padding:10px 48px 14px; display:flex; justify-content:center; }
        .page-foot p { font-size:11.5px; color:rgba(0,40,100,0.55); font-weight:500; }

        .center-wrap { position:relative; z-index:10; width:100%; max-width:460px; padding:24px 20px; }
        .card { background:rgba(255,255,255,0.97); border-radius:24px; padding:40px 44px; box-shadow:0 32px 80px rgba(0,0,0,0.55),0 0 0 1px rgba(255,255,255,0.08); }

        .back-link { display:inline-flex; align-items:center; gap:6px; color:#b0b8cc; font-size:11px; font-weight:600; text-decoration:none; letter-spacing:0.8px; text-transform:uppercase; margin-bottom:28px; transition:color 0.2s; }
        .back-link:hover { color:var(--blue); }

        .icon-circle { width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg,#e8edf8,#f0f4ff); display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:26px; box-shadow:0 4px 16px rgba(0,58,140,0.12); }
        .page-heading { font-family:'Playfair Display',serif; font-size:28px; font-weight:900; color:#0c1830; text-align:center; margin-bottom:8px; }
        .page-sub { font-size:13px; color:#8892aa; text-align:center; line-height:1.6; margin-bottom:28px; }

        .form-group { margin-bottom:16px; }
        .input-label { display:block; font-size:11px; font-weight:700; color:#606880; letter-spacing:0.6px; text-transform:uppercase; margin-bottom:6px; }
        .input-wrap { position:relative; }
        .input-icon { position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#c4ccdc; font-size:13px; pointer-events:none; }
        input[type="email"], input[type="password"], input[type="text"] { width:100%; padding:12px 18px 12px 44px; border:1.5px solid #e8ecf5; border-radius:12px; font-size:13.5px; font-family:'Work Sans',sans-serif; color:#1a1a2e; background:#f6f8fd; transition:all 0.25s; outline:none; }
        input:focus { border-color:var(--blue); background:#fff; box-shadow:0 0 0 4px rgba(0,58,140,0.08); }
        input.is-invalid { border-color:var(--red) !important; }
        .invalid-feedback { font-size:11.5px; color:var(--red); margin-top:5px; display:block; }

        /* Token input — big digits */
        .token-input { padding:14px 18px 14px 44px !important; font-size:22px !important; font-family:'Playfair Display',serif !important; letter-spacing:8px !important; text-align:center !important; }

        .password-wrapper { position:relative; }
        .password-wrapper input { padding-right:46px; }
        .eye-toggle { position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#c4ccdc; font-size:14px; padding:4px; transition:color 0.2s; z-index:11; }
        .eye-toggle:hover { color:var(--blue); }

        /* Password strength */
        .strength-bar { display:flex; gap:4px; margin-top:6px; }
        .strength-seg { flex:1; height:3px; background:#e8ecf5; border-radius:99px; transition:background 0.3s; }
        .strength-label { font-size:10.5px; margin-top:4px; font-weight:600; color:#aab0c6; }

        .btn { width:100%; padding:13px; border:none; border-radius:12px; font-size:13.5px; font-weight:700; cursor:pointer; transition:all 0.3s; letter-spacing:0.8px; text-transform:uppercase; position:relative; overflow:hidden; margin-top:4px; }
        .btn-primary { background:var(--blue); color:white; box-shadow:0 6px 24px rgba(0,58,140,0.30); }
        .btn-primary:hover { background:var(--blue-dark); transform:translateY(-2px); box-shadow:0 10px 32px rgba(0,58,140,0.40); }
        .btn-primary::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,0.18),transparent); left:-100%; transition:left 0.5s; }
        .btn-primary:hover::after { left:100%; }

        .alert { padding:12px 16px; border-radius:12px; margin-bottom:20px; font-size:12.5px; font-weight:500; display:flex; align-items:flex-start; gap:10px; }
        .alert-danger { background:#fff1f3; color:#a00d25; border:1.5px solid #ffd0d8; }
        .alert i { flex-shrink:0; margin-top:1px; }

        /* Rules hint */
        .rules-hint { font-size:11px; color:#9aa3bb; line-height:1.8; margin-top:5px; }
        .rules-hint span { display:inline-flex; align-items:center; gap:4px; }
        .rules-hint .ok  { color:#0d9448; }
        .rules-hint .bad { color:#e2e8f0; }

        .signin-link { text-align:center; margin-top:20px; font-size:12.5px; color:#aab0c6; }
        .signin-link a { color:var(--blue); text-decoration:none; font-weight:700; }
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

    <div class="center-wrap">
        <div class="card">
            <a href="{{ route('password.request') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back</a>

            <div class="icon-circle">🔒</div>
            <div class="page-heading">Set New Password</div>
            <div class="page-sub">Enter the 6-digit reset code and your new password below.</div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="form-group">
                    <label class="input-label" for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $email) }}"
                               placeholder="your@email.com" required
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    </div>
                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="input-label" for="token">6-Digit Reset Code</label>
                    <div class="input-wrap">
                        <i class="fas fa-key input-icon"></i>
                        <input type="text" id="token" name="token"
                               value="{{ old('token') }}"
                               placeholder="000000"
                               maxlength="6" inputmode="numeric"
                               autocomplete="one-time-code"
                               class="token-input {{ $errors->has('token') ? 'is-invalid' : '' }}">
                    </div>
                    @error('token')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="input-label" for="password">New Password</label>
                    <div class="input-wrap password-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password"
                               placeholder="Create a strong password"
                               required autocomplete="new-password"
                               oninput="checkStrength(this.value)"
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                        <button type="button" class="eye-toggle" onclick="togglePw('password','eye1')">
                            <i class="fas fa-eye" id="eye1"></i>
                        </button>
                    </div>
                    <div class="strength-bar">
                        <div class="strength-seg" id="s1"></div>
                        <div class="strength-seg" id="s2"></div>
                        <div class="strength-seg" id="s3"></div>
                        <div class="strength-seg" id="s4"></div>
                    </div>
                    <div class="strength-label" id="strengthLabel">Enter a password</div>
                    <div class="rules-hint" id="rulesHint">
                        <span id="r1" class="bad"><i class="fas fa-circle" style="font-size:6px;"></i> 8+ characters</span> &nbsp;
                        <span id="r2" class="bad"><i class="fas fa-circle" style="font-size:6px;"></i> Uppercase</span> &nbsp;
                        <span id="r3" class="bad"><i class="fas fa-circle" style="font-size:6px;"></i> Lowercase</span> &nbsp;
                        <span id="r4" class="bad"><i class="fas fa-circle" style="font-size:6px;"></i> Number</span> &nbsp;
                        <span id="r5" class="bad"><i class="fas fa-circle" style="font-size:6px;"></i> Symbol</span>
                    </div>
                    @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="input-label" for="password_confirmation">Confirm Password</label>
                    <div class="input-wrap password-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="Repeat your new password"
                               required autocomplete="new-password">
                        <button type="button" class="eye-toggle" onclick="togglePw('password_confirmation','eye2')">
                            <i class="fas fa-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle" style="margin-right:8px;"></i> Reset Password
                </button>
            </form>

            <div class="signin-link">Remembered it? <a href="{{ route('login') }}">Sign In</a></div>
        </div>
    </div>

<script>
    const starsEl=document.getElementById('stars');
    for(let i=0;i<55;i++){const s=document.createElement('div');s.className='star';const sz=Math.random()*2+0.8;s.style.cssText=`width:${sz}px;height:${sz}px;top:${Math.random()*80}%;left:${Math.random()*100}%;--d:${Math.random()*3+2}s;--o:${Math.random()*0.5+0.15};animation-delay:${Math.random()*4}s;`;starsEl.appendChild(s);}

    function togglePw(id, iconId) {
        const i = document.getElementById(id);
        const e = document.getElementById(iconId);
        const h = i.type === 'password';
        i.type = h ? 'text' : 'password';
        e.className = h ? 'fas fa-eye-slash' : 'fas fa-eye';
    }

    // Only allow digits in token field
    document.getElementById('token').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);
    });

    function checkStrength(val) {
        const rules = [
            { id:'r1', ok: val.length >= 8 },
            { id:'r2', ok: /[A-Z]/.test(val) },
            { id:'r3', ok: /[a-z]/.test(val) },
            { id:'r4', ok: /[0-9]/.test(val) },
            { id:'r5', ok: /[^A-Za-z0-9]/.test(val) },
        ];
        const score = rules.filter(r => r.ok).length;
        rules.forEach(r => {
            document.getElementById(r.id).className = r.ok ? 'ok' : 'bad';
        });
        const colors = ['','#C8102E','#e08c00','#c47d0e','#0d9448','#003A8C'];
        const labels = ['','Weak','Fair','Good','Strong','Very Strong'];
        for(let i=1;i<=4;i++){
            document.getElementById('s'+i).style.background = i<=score ? colors[score] : '#e8ecf5';
        }
        document.getElementById('strengthLabel').textContent = val.length ? labels[score] : 'Enter a password';
        document.getElementById('strengthLabel').style.color = colors[score] || '#aab0c6';
    }
</script>
</body>
</html>