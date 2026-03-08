<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - TESRead</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/jpg" href="{{ asset('favicon.jpg') }}">
    <style>
        :root { --blue:#003A8C; --blue-dark:#002870; --blue-mid:#0050C2; --red:#C8102E; --red-dark:#a00d25; }
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; overflow:hidden; }
        body { font-family:'Work Sans',sans-serif; background:#0a1628; display:flex; align-items:center; justify-content:center; }

        .bg-layer { position:fixed; inset:0; z-index:0; background-image:url('/images/TESS.jpg'); background-size:cover; background-position:center 60%; filter:brightness(0.38) saturate(0.6); }
        .bg-overlay { position:fixed; inset:0; z-index:1; background:linear-gradient(170deg,rgba(0,20,70,0.72) 0%,rgba(0,40,120,0.60) 45%,rgba(0,55,160,0.50) 70%,rgba(0,20,60,0.80) 100%); }
        .stars { position:fixed; inset:0; z-index:2; pointer-events:none; }
        .star { position:absolute; background:white; border-radius:50%; animation:twinkle var(--d) ease-in-out infinite alternate; opacity:0; }
        @keyframes twinkle { 0%{opacity:0;} 100%{opacity:var(--o);} }
        .wave-bottom { position:fixed; bottom:0; left:0; right:0; z-index:3; line-height:0; pointer-events:none; }
        .wave-bottom svg { display:block; width:100%; }
        .page-foot { position:fixed; bottom:0; left:0; right:0; z-index:4; padding:10px 48px 14px; display:flex; justify-content:center; }
        .page-foot p { font-size:11.5px; color:rgba(0,40,100,0.55); font-weight:500; }

        .main-container { position:relative; z-index:10; width:100%; max-width:1060px; height:calc(100vh - 48px); max-height:680px; display:flex; border-radius:28px; overflow:hidden; box-shadow:0 32px 80px rgba(0,0,0,0.55),0 0 0 1px rgba(255,255,255,0.08); margin:24px 20px; }

        /* LEFT */
        .left-panel { flex:1.1; background:linear-gradient(160deg,rgba(0,18,60,0.88) 0%,rgba(0,40,110,0.82) 55%,rgba(0,15,55,0.92) 100%); backdrop-filter:blur(24px); border-right:1px solid rgba(255,255,255,0.09); position:relative; overflow:hidden; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:44px 40px; }
        .glow { position:absolute; border-radius:50%; pointer-events:none; filter:blur(70px); }
        .glow-1 { width:340px; height:340px; background:rgba(0,70,200,0.30); top:-100px; left:-100px; }
        .glow-2 { width:280px; height:280px; background:rgba(200,16,46,0.18); bottom:-80px; right:-80px; }
        .glow-3 { width:200px; height:200px; background:rgba(0,100,255,0.15); top:50%; left:50%; transform:translate(-50%,-50%); }
        .dot-pattern { position:absolute; inset:0; pointer-events:none; background-image:radial-gradient(circle,rgba(255,255,255,0.07) 1px,transparent 1px); background-size:24px 24px; }
        .deco-dots { position:absolute; top:22px; right:22px; display:grid; grid-template-columns:repeat(5,1fr); gap:5px; }
        .deco-dots span { width:4px; height:4px; background:rgba(255,255,255,0.22); border-radius:50%; display:block; }
        .panel-wave { position:absolute; bottom:0; left:0; right:0; line-height:0; pointer-events:none; opacity:0.15; }
        .panel-wave svg { display:block; width:100%; }
        .panel-content { position:relative; z-index:3; display:flex; flex-direction:column; align-items:center; text-align:center; width:100%; }

        .school-logo-circle { width:80px; height:80px; border-radius:50%; background:white; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; box-shadow:0 8px 32px rgba(0,0,0,0.35),0 0 0 4px rgba(255,255,255,0.12); overflow:hidden; flex-shrink:0; }
        .school-logo-circle img { width:100%; height:100%; object-fit:cover; }
        .logo-placeholder { display:flex; align-items:center; justify-content:center; width:100%; height:100%; }
        .logo-placeholder span { color:var(--blue); font-family:'Playfair Display',serif; font-size:28px; font-weight:900; }
        .brand-badge { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.20); border-radius:50px; padding:5px 14px; font-size:10.5px; font-weight:600; color:rgba(255,255,255,0.80); letter-spacing:1px; text-transform:uppercase; margin-bottom:14px; }
        .brand-badge i { color:#ffd166; font-size:10px; }
        .brand-name { font-family:'Playfair Display',serif; font-size:42px; font-weight:900; color:#fff; letter-spacing:-1px; line-height:1; margin-bottom:6px; text-shadow:0 4px 20px rgba(0,0,0,0.3); }
        .brand-tagline { color:rgba(255,255,255,0.55); font-size:10.5px; letter-spacing:1.8px; text-transform:uppercase; margin-bottom:26px; }
        .divider { width:40px; height:1.5px; background:linear-gradient(90deg,transparent,rgba(255,255,255,0.35),transparent); margin:0 auto 22px; }

        .steps-card { background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.11); border-radius:16px; padding:18px 22px; width:100%; text-align:left; backdrop-filter:blur(8px); margin-bottom:14px; }
        .steps-card h2 { font-family:'Playfair Display',serif; font-size:15px; color:#fff; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
        .step-item { display:flex; align-items:flex-start; gap:12px; margin-bottom:11px; }
        .step-item:last-child { margin-bottom:0; }
        .step-num { width:22px; height:22px; border-radius:50%; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#fff; flex-shrink:0; margin-top:1px; }
        .step-text { font-size:12px; color:rgba(255,255,255,0.72); line-height:1.5; text-align:left; }
        .step-text strong { color:rgba(255,255,255,0.95); }
        .info-strip { width:100%; background:rgba(255,209,102,0.12); border:1px solid rgba(255,209,102,0.25); border-radius:11px; padding:10px 14px; display:flex; align-items:center; gap:10px; font-size:11.5px; color:rgba(255,220,130,0.90); }
        .info-strip i { flex-shrink:0; font-size:13px; color:#ffd166; }

        /* RIGHT */
        .right-panel { flex:1; background:rgba(255,255,255,0.96); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
        .form-scroll { width:100%; height:100%; overflow-y:auto; overflow-x:hidden; display:flex; align-items:center; justify-content:center; padding:40px 44px; }
        .form-scroll::-webkit-scrollbar { width:4px; }
        .form-scroll::-webkit-scrollbar-thumb { background:#dde2ee; border-radius:4px; }
        .card { width:100%; max-width:360px; }

        .back-link { display:inline-flex; align-items:center; gap:6px; color:#b0b8cc; font-size:11px; font-weight:600; text-decoration:none; letter-spacing:0.8px; text-transform:uppercase; margin-bottom:24px; transition:color 0.2s; }
        .back-link:hover { color:var(--blue); }
        .icon-circle { width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg,#e8edf8,#f0f4ff); display:flex; align-items:center; justify-content:center; margin:0 0 18px; font-size:26px; box-shadow:0 4px 16px rgba(0,58,140,0.12); }
        .icon-circle.success { background:linear-gradient(135deg,#dcfce7,#f0fdf4); box-shadow:0 4px 16px rgba(22,163,74,0.15); }
        .logo-eyebrow { font-size:11px; font-weight:700; color:var(--blue); letter-spacing:2px; text-transform:uppercase; display:block; margin-bottom:6px; opacity:0.8; }
        .page-heading { font-family:'Playfair Display',serif; font-size:30px; font-weight:900; color:#0c1830; line-height:1.1; margin-bottom:8px; }
        .page-sub { font-size:13px; color:#8892aa; line-height:1.6; margin-bottom:24px; }

        .form-group { margin-bottom:16px; }
        .input-label { display:block; font-size:11px; font-weight:700; color:#606880; letter-spacing:0.6px; text-transform:uppercase; margin-bottom:6px; }
        .input-wrap { position:relative; }
        .input-wrap .input-icon { position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#c4ccdc; font-size:13px; z-index:11; pointer-events:none; }
        input[type="email"] { width:100%; padding:12px 18px 12px 44px; border:1.5px solid #e8ecf5; border-radius:12px; font-size:13.5px; font-family:'Work Sans',sans-serif; color:#1a1a2e; background:#f6f8fd; transition:all 0.25s; outline:none; }
        input:focus { border-color:var(--blue); background:#fff; box-shadow:0 0 0 4px rgba(0,58,140,0.08); }
        input.is-invalid { border-color:var(--red) !important; }
        .invalid-feedback { font-size:11.5px; color:var(--red); margin-top:5px; display:block; }

        .btn { width:100%; padding:13px; border:none; border-radius:12px; font-size:13.5px; font-weight:700; cursor:pointer; transition:all 0.3s; letter-spacing:0.8px; text-transform:uppercase; position:relative; overflow:hidden; }
        .btn-primary { background:var(--blue); color:white; box-shadow:0 6px 24px rgba(0,58,140,0.30); }
        .btn-primary::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,0.18),transparent); left:-100%; transition:left 0.5s; }
        .btn-primary:hover::after { left:100%; }
        .btn-primary:hover { background:var(--blue-dark); transform:translateY(-2px); box-shadow:0 10px 32px rgba(0,58,140,0.40); }
        .btn-outline { background:transparent; color:var(--blue); border:1.5px solid #c5d3f0; box-shadow:none; margin-top:10px; }
        .btn-outline:hover { background:#f0f4ff; transform:translateY(-1px); }

        .alert { padding:11px 14px; border-radius:12px; margin-bottom:16px; font-size:12.5px; font-weight:500; display:flex; align-items:flex-start; gap:9px; }
        @keyframes shake { 0%,100%{transform:translateX(0);} 20%,60%{transform:translateX(-5px);} 40%,80%{transform:translateX(5px);} }
        .alert-danger  { background:#fff1f3; color:#a00d25; border:1.5px solid #ffd0d8; animation:shake 0.5s; }
        .alert-success { background:#f0fdf4; color:#166534; border:1.5px solid #bbf7d0; }
        .alert i { flex-shrink:0; margin-top:1px; }

        .token-box { background:linear-gradient(135deg,#f0f4ff,#e8edf8); border:2px solid #c5d3f0; border-radius:16px; padding:22px 20px; text-align:center; margin-bottom:18px; }
        .token-label { font-size:10.5px; font-weight:700; color:#5a6a8a; text-transform:uppercase; letter-spacing:1px; margin-bottom:10px; }
        .token-code { font-family:'Playfair Display',serif; font-size:44px; font-weight:900; color:var(--blue); letter-spacing:12px; margin-bottom:10px; line-height:1; }
        .token-timer { display:inline-flex; align-items:center; gap:5px; background:rgba(200,16,46,0.08); border:1px solid rgba(200,16,46,0.18); border-radius:50px; padding:4px 12px; font-size:11.5px; font-weight:600; color:var(--red); margin-bottom:10px; }
        .token-note { font-size:12px; color:#7a8aaa; line-height:1.6; }

        .signin-link { text-align:center; margin-top:18px; font-size:12.5px; color:#aab0c6; }
        .signin-link a { color:var(--blue); text-decoration:none; font-weight:700; transition:color 0.2s; }
        .signin-link a:hover { color:var(--blue-dark); }

        @media (max-width:900px) { .main-container { flex-direction:column; max-height:none; height:auto; } .left-panel { padding:32px 28px; } }
        @media (max-width:600px) { body { overflow-y:auto; } html { overflow:auto; } .main-container { margin:0; border-radius:0; height:auto; } }
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
        <p>© {{ date('Y') }} Tampugo Elementary School &nbsp;&middot;&nbsp; TESRead Digital Reading Progress Monitoring</p>
    </div>

    <div class="main-container">

        <!-- LEFT PANEL -->
        <div class="left-panel">
            <div class="glow glow-1"></div>
            <div class="glow glow-2"></div>
            <div class="glow glow-3"></div>
            <div class="dot-pattern"></div>
            <div class="deco-dots">@for($i=0;$i<25;$i++)<span></span>@endfor</div>
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
                <div class="brand-badge"><i class="fas fa-lock"></i> Account Recovery</div>
                <div class="brand-name">TESRead</div>
                <div class="brand-tagline">Tampugo Elementary School</div>
                <div class="divider"></div>
                <div class="steps-card">
                    <h2><i class="fas fa-route" style="font-size:13px;opacity:0.7;"></i> How it works</h2>
                    <div class="step-item">
                        <div class="step-num">1</div>
                        <div class="step-text"><strong>Enter your email</strong> — the one you registered your account with.</div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">2</div>
                        <div class="step-text"><strong>Get your reset code</strong> — a 6-digit code will appear on screen instantly.</div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">3</div>
                        <div class="step-text"><strong>Set a new password</strong> — enter the code and choose a new secure password.</div>
                    </div>
                </div>
                <div class="info-strip">
                    <i class="fas fa-info-circle"></i>
                    <span>Reset codes expire after <strong style="color:#ffd166;">1 minute</strong> for your security.</span>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="form-scroll">
                <div class="card">

                    <a href="{{ route('login') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to Sign In
                    </a>

                    @if(session('reset_token'))
                        <div class="icon-circle success">&#128273;</div>
                        <span class="logo-eyebrow">TESRead</span>
                        <div class="page-heading">Reset Code Ready</div>
                        <p class="page-sub">Copy your 6-digit code below, then proceed to set a new password.</p>

                        <div class="token-box">
                            <div class="token-label"><i class="fas fa-shield-alt"></i> &nbsp;Your Reset Code</div>
                            <div class="token-code" id="tokenCode">{{ session('reset_token') }}</div>
                            <div class="token-timer"><i class="fas fa-clock"></i>&nbsp;<span id="countdown">1:00</span> remaining</div>
                            <div class="token-note">Write it down before it expires. Do not share this code with anyone.</div>
                        </div>

                        <a href="{{ route('password.reset', ['token' => session('reset_token'), 'email' => session('reset_email')]) }}"
                           class="btn btn-primary" style="display:block;text-align:center;text-decoration:none;padding:13px;">
                            <i class="fas fa-key" style="margin-right:8px;"></i> Enter Code &amp; Reset Password
                        </a>
                        <button type="button" onclick="copyToken(this)" class="btn btn-outline">
                            <i class="fas fa-copy" style="margin-right:8px;"></i> Copy Code
                        </button>
                        <div class="signin-link">Remembered it? <a href="{{ route('login') }}">Sign In Instead</a></div>

                    @else
                        <div class="icon-circle">&#128272;</div>
                        <span class="logo-eyebrow">TESRead</span>
                        <div class="page-heading">Forgot Password?</div>
                        <p class="page-sub">No worries. Enter your registered email and we'll generate a reset code for you right away.</p>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $errors->first() }}</span>
                            </div>
                        @endif

                        @if(session('status'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group">
                                <label class="input-label" for="email">Email Address</label>
                                <div class="input-wrap">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" id="email" name="email"
                                           value="{{ old('email') }}"
                                           placeholder="your@email.com"
                                           required autocomplete="email" autofocus
                                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                                </div>
                                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane" style="margin-right:8px;"></i> Send Reset Code
                            </button>
                        </form>

                        <div class="signin-link" style="margin-top:16px;">Remembered it? <a href="{{ route('login') }}">Sign In</a></div>
                    @endif

                </div>
            </div>
        </div>

    </div>

<script>
    const starsEl = document.getElementById('stars');
    for (let i = 0; i < 55; i++) {
        const s = document.createElement('div'); s.className = 'star';
        const sz = Math.random() * 2 + 0.8;
        s.style.cssText = `width:${sz}px;height:${sz}px;top:${Math.random()*80}%;left:${Math.random()*100}%;--d:${Math.random()*3+2}s;--o:${Math.random()*0.5+0.15};animation-delay:${Math.random()*4}s;`;
        starsEl.appendChild(s);
    }

    @if(session('reset_token'))
    let total = 1 * 60;
    const el = document.getElementById('countdown');
    if (el) {
        const tick = setInterval(() => {
            total--;
            if (total <= 0) { clearInterval(tick); el.textContent = '00:00'; return; }
            el.textContent = String(Math.floor(total/60)).padStart(2,'0')+':'+String(total%60).padStart(2,'0');
        }, 1000);
    }
    function copyToken(btn) {
        const code = document.getElementById('tokenCode')?.textContent?.trim();
        if (!code) return;
        navigator.clipboard.writeText(code).then(() => {
            btn.innerHTML = '<i class="fas fa-check" style="margin-right:8px;"></i> Copied!';
            btn.style.color = '#166534'; btn.style.borderColor = '#bbf7d0';
            setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy" style="margin-right:8px;"></i> Copy Code'; btn.style.color=''; btn.style.borderColor=''; }, 2000);
        });
    }
    @endif
</script>
</body>
</html>