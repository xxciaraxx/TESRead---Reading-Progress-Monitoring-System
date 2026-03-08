{{-- resources/views/auth/landing.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TESRead – Digital Reading Progress Monitoring</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --blue:      #003A8C;
            --blue-mid:  #0050C2;
            --blue-light:#1a6fd4;
            --red:       #C8102E;
            --white:     #ffffff;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; }

        body {
            font-family: 'Work Sans', sans-serif;
            background: var(--blue);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* ── Background layer ── */
        .bg-layer {
            position: fixed; inset: 0; z-index: 0;
            background-image: url('{{ asset("images/TES.jpg") }}');
            background-size: cover;
            background-position: center 60%;
            filter: brightness(0.38) saturate(0.6);
            transition: background-image 0.4s;
        }

        /* ── Blue gradient overlay (bottom wave effect) ── */
        .bg-overlay {
            position: fixed; inset: 0; z-index: 1;
            background: linear-gradient(
                170deg,
                rgba(0,20,70,0.55) 0%,
                rgba(0,40,120,0.45) 35%,
                rgba(0,55,160,0.30) 60%,
                rgba(255,255,255,0.95) 100%
            );
        }

        /* ── Stars / floating particles ── */
        .stars { position: fixed; inset: 0; z-index: 2; pointer-events: none; overflow: hidden; }
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle var(--d) ease-in-out infinite alternate;
            opacity: 0;
        }
        @keyframes twinkle { 0% { opacity: 0; transform: scale(0.8); } 100% { opacity: var(--o); transform: scale(1.2); } }

        /* ── Shooting stars ── */
        .shoot {
            position: absolute;
            width: 120px; height: 1.5px;
            background: linear-gradient(90deg, rgba(255,255,255,0.9), transparent);
            border-radius: 1px;
            animation: shoot var(--sd) linear infinite;
            opacity: 0;
            top: var(--st); left: var(--sl);
            transform: rotate(-25deg);
        }
        @keyframes shoot { 0%{ opacity:0; transform: rotate(-25deg) translateX(0); } 10%{ opacity:1; } 90%{ opacity:1; } 100%{ opacity:0; transform: rotate(-25deg) translateX(600px); } }

        /* ── Wave SVG at bottom ── */
        .wave-container {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 3;
            line-height: 0;
        }
        .wave-container svg { display: block; width: 100%; }

        /* ── Page wrapper ── */
        .page {
            position: relative; z-index: 10;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Header ── */
        header {
            padding: 28px 48px 0;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .logo-circle {
            width: 80px; height: 80px; border-radius: 50%;
            background: white;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.25);
            border: 3px solid rgba(255,255,255,0.5);
            flex-shrink: 0;
        }
        .logo-circle img { width: 100%; height: 100%; object-fit: cover; }
        .logo-fallback {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            color: var(--blue); font-weight: 900; font-size: 18px;
            font-family: 'Playfair Display', serif;
        }
        .header-text .brand {
            font-family: 'Playfair Display', serif;
            font-size: 26px; font-weight: 900;
            color: white;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }
        .header-text .tagline {
            font-size: 11px; color: rgba(255,255,255,0.72);
            font-weight: 400; letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── Hero content ── */
        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 0 48px 80px;
        }
        .hero-inner {
            max-width: 620px;
        }

        .badge {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 13px; font-weight: 600;
            color: rgba(255,255,255,0.88);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 24px;
            backdrop-filter: blur(8px);
        }
        .badge i { color: #ffd166; }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(48px, 6.5vw, 82px);
            font-weight: 900;
            color: white;
            line-height: 1.08;
            margin-bottom: 22px;
            text-shadow: 0 4px 24px rgba(0,0,0,0.3);
        }
        .hero-title span {
            color: transparent;
            -webkit-text-stroke: 2px rgba(255,255,255,0.6);
        }

        .hero-sub {
            font-size: 17px;
            color: rgba(255,255,255,0.82);
            line-height: 1.75;
            max-width: 520px;
            margin-bottom: 44px;
            font-weight: 400;
        }

        /* ── CTA Buttons ── */
        .cta-group {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-cta {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 15px 36px;
            border-radius: 60px;
            font-family: 'Work Sans', sans-serif;
            font-size: 14px; font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
            position: relative;
            overflow: hidden;
        }
        .btn-cta::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            left: -100%;
            transition: left 0.5s;
        }
        .btn-cta:hover::after { left: 100%; }

        .btn-signin {
            background: white;
            color: var(--blue);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .btn-signin:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 14px 40px rgba(0,0,0,0.3);
        }

        .btn-signup {
            background: var(--red);
            color: white;
            box-shadow: 0 8px 28px rgba(200,16,46,0.4);
        }
        .btn-signup:hover {
            background: #a00d25;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 14px 36px rgba(200,16,46,0.55);
        }

        .btn-cta i { font-size: 15px; }

        /* ── Stats row ── */
        .stats {
            margin-top: 48px;
            display: flex;
            gap: 32px;
            flex-wrap: wrap;
        }
        .stat {
            display: flex; flex-direction: column;
        }
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 28px; font-weight: 900;
            color: white;
            line-height: 1;
        }
        .stat-num span { color: #ffd166; }
        .stat-label {
            font-size: 11px; color: rgba(255,255,255,0.6);
            text-transform: uppercase; letter-spacing: 0.8px;
            margin-top: 4px;
        }
        .stat-divider {
            width: 1px;
            background: rgba(255,255,255,0.2);
            align-self: stretch;
        }

        /* ── Floating card (right side) ── */
        .floating-card {
            position: fixed;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .f-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(16px);
            border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 22px 26px;
            display: flex; align-items: center; gap: 16px;
            min-width: 280px;
            animation: floatCard 3s ease-in-out infinite alternate;
        }
        .f-card:nth-child(2) { animation-delay: 0.8s; }
        .f-card:nth-child(3) { animation-delay: 1.6s; }
        @keyframes floatCard { 0%{ transform: translateY(0); } 100%{ transform: translateY(-8px); } }
        .f-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.15);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; flex-shrink: 0;
        }
        .f-title { font-size: 15px; font-weight: 700; color: white; }
        .f-sub   { font-size: 13px; color: rgba(255,255,255,0.68); margin-top: 4px; }

        /* ── Bottom white wave content ── */
        .wave-content {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 4;
            padding: 14px 48px 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .wave-content p {
            font-size: 12px;
            color: rgba(0,40,100,0.6);
            font-weight: 500;
        }

        @media (max-width: 900px) {
            .floating-card { display: none; }
            header { padding: 22px 28px 0; }
            .hero { padding: 0 28px 60px; }
        }
        @media (max-width: 600px) {
            .hero-title { font-size: 34px; }
            .cta-group { flex-direction: column; }
            .btn-cta { justify-content: center; }
            .stats { gap: 20px; }
            .stat-divider { display: none; }
        }
    </style>
</head>
<body>

    <!-- Background -->
    <div class="bg-layer" id="bgLayer"></div>
    <div class="bg-overlay"></div>

    <!-- Stars -->
    <div class="stars" id="stars"></div>

    <!-- Wave bottom -->
    <div class="wave-container">
        <svg viewBox="0 0 1440 100" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,60 C240,110 480,20 720,60 C960,100 1200,20 1440,60 L1440,100 L0,100 Z" fill="white" opacity="0.95"/>
            <path d="M0,75 C300,30 600,100 900,65 C1100,42 1300,85 1440,70 L1440,100 L0,100 Z" fill="white"/>
        </svg>
    </div>

    <!-- Wave text -->
    <div class="wave-content">
        <p>© {{ date('Y') }} Tampugo Elementary School &nbsp;·&nbsp; TESRead Digital Reading Progress Monitoring System</p>
    </div>

    <!-- Main page -->
    <div class="page">
        <!-- Header -->
        <header>
            <div class="logo-circle">
                <img src="{{ asset('images/TES-logo.jpg') }}" alt="TES Logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="logo-fallback" style="display:none;">T</div>
            </div>
            <div class="header-text">
                <div class="brand">TESRead</div>
                <div class="tagline">Tampugo Elementary School</div>
            </div>
        </header>

        <!-- Hero -->
        <div class="hero">
            <div class="hero-inner">
                <div class="badge">
                    <i class="fas fa-star"></i>
                    Digital Reading Progress Monitoring
                </div>

                <h1 class="hero-title">
                    Empower Every<br>
                    <span>Reader</span> to<br>
                    Shine Bright
                </h1>

                <p class="hero-sub">
                    A centralized platform for tracking, monitoring, and improving pupils' early literacy — giving teachers the insights they need to act early and effectively.
                </p>

                <div class="cta-group">
                    <a href="{{ route('login') }}" class="btn-cta btn-signin">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="btn-cta btn-signup">
                        <i class="fas fa-user-plus"></i>
                        Create Account
                    </a>
                </div>

                <div class="stats">
                    <div class="stat">
                        <div class="stat-num">100<span>%</span></div>
                        <div class="stat-label">Digital Records</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <div class="stat-num">Real<span>-</span>time</div>
                        <div class="stat-label">Progress Tracking</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <div class="stat-num">Early</div>
                        <div class="stat-label">Intervention Support</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating feature cards -->
    <div class="floating-card">
        <div class="f-card">
            <div class="f-icon">📊</div>
            <div>
                <div class="f-title">Progress Tracking</div>
                <div class="f-sub">Monitor reading levels in real-time</div>
            </div>
        </div>
        <div class="f-card">
            <div class="f-icon">🎯</div>
            <div>
                <div class="f-title">Early Intervention</div>
                <div class="f-sub">Identify struggling readers fast</div>
            </div>
        </div>
        <div class="f-card">
            <div class="f-icon">📋</div>
            <div>
                <div class="f-title">Smart Reports</div>
                <div class="f-sub">Data-driven instructional insights</div>
            </div>
        </div>
    </div>

<script>
    // ── Generate stars ──
    const starsEl = document.getElementById('stars');
    for (let i = 0; i < 80; i++) {
        const s = document.createElement('div');
        s.className = 'star';
        const size = Math.random() * 2.5 + 1;
        s.style.cssText = `
            width:${size}px; height:${size}px;
            top:${Math.random()*75}%;
            left:${Math.random()*100}%;
            --d:${Math.random()*3+2}s;
            --o:${Math.random()*0.6+0.2};
            animation-delay:${Math.random()*4}s;
        `;
        starsEl.appendChild(s);
    }
    // ── Shooting stars ──
    for (let i = 0; i < 4; i++) {
        const sh = document.createElement('div');
        sh.className = 'shoot';
        sh.style.cssText = `
            --sd:${Math.random()*6+5}s;
            --st:${Math.random()*50}%;
            --sl:${Math.random()*40}%;
            animation-delay:${Math.random()*8}s;
        `;
        starsEl.appendChild(sh);
    }
</script>
</body>
</html>
