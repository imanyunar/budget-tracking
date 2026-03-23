<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="FinanceTracker — A simple, modern finance management app. Track wallets, budgets, investments, and cashflow in one place.">
    <title>FinanceTracker | Take Control of Your Money</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/tailwind-v4-fallback.css') }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-2: #475569;
            --muted: #94a3b8;
            --primary: #6366f1;
            --primary-2: #4f46e5;
            --success: #10b981;
            --danger: #f43f5e;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Ticker */
        .ticker-wrap { overflow:hidden; background:var(--primary); border-bottom:none; }
        .ticker-track { display:flex; animation:ticker 32s linear infinite; width:max-content; }
        @keyframes ticker { from { transform:translateX(0); } to { transform:translateX(-50%); } }
        .ticker-item { display:flex; align-items:center; gap:10px; padding:8px 28px; border-right:1px solid rgba(255,255,255,0.2); white-space:nowrap; }
        .ticker-label { font-size:11.5px; font-weight:600; color:rgba(255,255,255,0.85); letter-spacing:0.04em; }
        .ticker-val-up { font-size:11.5px; font-weight:700; color:#a7f3d0; }
        .ticker-val-down { font-size:11.5px; font-weight:700; color:#fca5a5; }

        /* Nav */
        nav {
            position: sticky; top:0; z-index:50;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .logo-mark {
            width:34px; height:34px;
            background:linear-gradient(135deg,var(--primary),#818cf8);
            border-radius:9px;
            display:flex; align-items:center; justify-content:center;
            box-shadow: 0 4px 12px rgba(99,102,241,0.35);
        }
        .logo-mark svg { color:#fff; }
        .logo-text { font-size:15px; font-weight:800; letter-spacing:-0.03em; color:var(--text); }
        .logo-text span { color:var(--primary); }

        .btn-nav-primary {
            background:linear-gradient(135deg,var(--primary),var(--primary-2));
            color:#fff; font-weight:600; font-size:13.5px;
            border:none; border-radius:10px; padding:9px 20px;
            cursor:pointer; text-decoration:none;
            display:inline-flex; align-items:center; gap:7px;
            box-shadow:0 2px 10px rgba(99,102,241,0.35);
            transition:all 0.2s;
        }
        .btn-nav-primary:hover { box-shadow:0 4px 16px rgba(99,102,241,0.5); transform:translateY(-1px); }
        .btn-nav-ghost {
            color:var(--text-2); font-weight:500; font-size:13.5px;
            border:1px solid var(--border); border-radius:10px; padding:8px 18px;
            background:transparent; cursor:pointer; text-decoration:none;
            display:inline-flex; align-items:center; gap:7px;
            transition:all 0.15s;
        }
        .btn-nav-ghost:hover { background:var(--bg); color:var(--text); }

        /* Hero */
        .hero {
            max-width:1160px; margin:0 auto;
            padding:90px 40px 80px;
            position:relative;
        }
        .hero-blob {
            position:absolute; pointer-events:none; z-index:0;
            filter:blur(80px); opacity:0.6;
        }
        .hero-tag {
            display:inline-flex; align-items:center; gap:8px;
            padding:5px 14px;
            background:var(--primary-dim,rgba(99,102,241,0.09));
            border:1px solid rgba(99,102,241,0.2);
            border-radius:100px;
            font-size:11.5px; font-weight:600;
            color:var(--primary); letter-spacing:0.04em;
            margin-bottom:28px;
        }
        .hero-tag-dot {
            width:6px; height:6px; border-radius:50%;
            background:var(--success);
            animation:blink 1.8s ease infinite;
        }
        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }
        h1 {
            font-size:clamp(40px, 6.5vw, 80px);
            font-weight:900;
            line-height:1.0;
            letter-spacing:-0.04em;
            color:var(--text);
            position:relative; z-index:1;
        }
        h1 .gradient-text {
            background:linear-gradient(135deg,var(--primary) 0%,#a78bfa 50%,#818cf8 100%);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            background-clip:text;
        }
        .hero-desc {
            font-size:18px; color:var(--text-2);
            max-width:540px; line-height:1.7;
            margin-top:24px; font-weight:400;
            position:relative; z-index:1;
        }
        .hero-actions { display:flex; gap:14px; margin-top:40px; flex-wrap:wrap; position:relative; z-index:1; }
        .btn-hero {
            background:linear-gradient(135deg,var(--primary),var(--primary-2));
            color:#fff; font-weight:700; font-size:15.5px;
            border:none; border-radius:12px; padding:15px 34px;
            cursor:pointer; text-decoration:none;
            display:inline-flex; align-items:center; gap:9px;
            box-shadow:0 4px 20px rgba(99,102,241,0.4);
            transition:all 0.2s; letter-spacing:-0.01em;
        }
        .btn-hero:hover { box-shadow:0 8px 28px rgba(99,102,241,0.55); transform:translateY(-2px); }
        .btn-hero-outline {
            color:var(--text-2); font-weight:600; font-size:15px;
            border:1.5px solid var(--border); border-radius:12px; padding:14px 28px;
            background:var(--surface); cursor:pointer; text-decoration:none;
            display:inline-flex; align-items:center; gap:8px;
            transition:all 0.15s;
            box-shadow:0 1px 3px rgba(15,23,42,0.06);
        }
        .btn-hero-outline:hover { border-color:var(--primary); color:var(--primary); }

        /* Stats strip */
        .stats-strip {
            display:grid; grid-template-columns:repeat(4,1fr);
            gap:12px; margin-top:56px; position:relative; z-index:1;
        }
        @media(max-width:720px){ .stats-strip { grid-template-columns:1fr 1fr; } }
        .stat-pill {
            background:var(--surface); border:1px solid var(--border);
            border-radius:14px; padding:18px 20px;
            box-shadow:0 1px 3px rgba(15,23,42,0.06);
            transition:box-shadow 0.2s;
        }
        .stat-pill:hover { box-shadow:0 4px 12px rgba(15,23,42,0.08); }
        .stat-pill-label { font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:0.07em; margin-bottom:6px; }
        .stat-pill-value { font-size:28px; font-weight:800; letter-spacing:-0.04em; line-height:1; }
        .stat-pill-sub { font-size:12px; color:var(--muted); margin-top:4px; }

        /* Features */
        .features { max-width:1160px; margin:0 auto; padding:0 40px 100px; }
        .section-header { text-align:center; margin-bottom:48px; }
        .section-tag {
            display:inline-flex; align-items:center; gap:7px;
            padding:4px 12px; background:var(--primary-dim,rgba(99,102,241,0.09));
            border:1px solid rgba(99,102,241,0.2); border-radius:100px;
            font-size:11px; font-weight:600; color:var(--primary);
            letter-spacing:0.08em; text-transform:uppercase; margin-bottom:16px;
        }
        .section-title { font-size:clamp(28px,4vw,40px); font-weight:800; letter-spacing:-0.03em; }
        .section-desc { font-size:16px; color:var(--text-2); margin-top:10px; line-height:1.65; }

        .features-grid {
            display:grid; grid-template-columns:repeat(3,1fr); gap:16px;
        }
        @media(max-width:900px){ .features-grid { grid-template-columns:1fr 1fr; } }
        @media(max-width:600px){ .features-grid { grid-template-columns:1fr; } }
        .feature-card {
            background:var(--surface); border:1px solid var(--border);
            border-radius:16px; padding:26px;
            box-shadow:0 1px 3px rgba(15,23,42,0.05);
            transition:all 0.25s;
        }
        .feature-card:hover {
            border-color:rgba(99,102,241,0.25);
            box-shadow:0 8px 24px rgba(15,23,42,0.09);
            transform:translateY(-3px);
        }
        .feature-icon {
            width:46px; height:46px; border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            margin-bottom:18px;
        }
        .feature-title { font-size:15.5px; font-weight:700; letter-spacing:-0.01em; margin-bottom:8px; }
        .feature-desc { font-size:13.5px; color:var(--text-2); line-height:1.65; }

        /* CTA Banner */
        .cta-banner {
            max-width:1160px; margin:0 auto 80px;
            padding:0 40px;
        }
        .cta-inner {
            background:linear-gradient(135deg,var(--primary) 0%,#4338ca 100%);
            border-radius:20px; padding:60px 48px;
            text-align:center; position:relative; overflow:hidden;
        }
        .cta-inner::before {
            content:''; position:absolute; inset:0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 60%);
        }
        .cta-title { font-size:clamp(26px,3.5vw,40px); font-weight:800; color:#fff; letter-spacing:-0.03em; margin-bottom:12px; position:relative; z-index:1; }
        .cta-desc { font-size:16px; color:rgba(255,255,255,0.75); max-width:480px; margin:0 auto 32px; line-height:1.65; position:relative; z-index:1; }
        .btn-cta {
            background:#fff; color:var(--primary); font-weight:700; font-size:15px;
            border:none; border-radius:12px; padding:15px 36px;
            cursor:pointer; text-decoration:none;
            display:inline-flex; align-items:center; gap:8px;
            box-shadow:0 4px 16px rgba(0,0,0,0.15);
            transition:all 0.2s; position:relative; z-index:1;
        }
        .btn-cta:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.2); }

        /* Footer */
        footer {
            border-top:1px solid var(--border);
            padding:28px 40px;
            display:flex; justify-content:space-between; align-items:center;
            flex-wrap:wrap; gap:12px;
            background:var(--surface);
        }
        .footer-logo { font-size:14px; font-weight:700; color:var(--text-2); }
        .footer-text { font-size:12.5px; color:var(--muted); }

        /* Animations */
        @keyframes fade-up { from{opacity:0;transform:translateY(22px);} to{opacity:1;transform:translateY(0);} }
        .fade-up { animation:fade-up 0.65s ease forwards; }
        .delay-1 { animation-delay:0.05s; opacity:0; }
        .delay-2 { animation-delay:0.15s; opacity:0; }
        .delay-3 { animation-delay:0.28s; opacity:0; }
        .delay-4 { animation-delay:0.4s; opacity:0; }
        .delay-5 { animation-delay:0.55s; opacity:0; }

        @media(max-width:768px){
            nav { padding:0 20px; }
            .hero { padding:60px 20px 50px; }
            .features,.cta-banner { padding-left:20px; padding-right:20px; }
            .cta-inner { padding:40px 28px; }
            footer { padding:20px; }
        }
    </style>
</head>
<body>
    <!-- Ticker Tape -->
    <div class="ticker-wrap">
        <div class="ticker-track">
            @foreach(['BBCA','TLKM','GOTO','ASII','BMRI','BTC','ETH','BNB','SOL','IHSG','BBRI','UNVR','PGAS','ADRO','BBCA','TLKM','GOTO','ASII','BMRI','BTC','ETH','BNB','SOL','IHSG','BBRI','UNVR','PGAS','ADRO'] as $i => $sym)
            @php
                $vals = ['BBCA'=>'+2.14%','TLKM'=>'-0.87%','GOTO'=>'+5.31%','ASII'=>'+1.02%','BMRI'=>'+0.64%','BTC'=>'$104,200','ETH'=>'$3,890','BNB'=>'$620','SOL'=>'$185','IHSG'=>'7,421','BBRI'=>'+1.88%','UNVR'=>'-0.43%','PGAS'=>'+3.12%','ADRO'=>'+0.77%'];
                $v = $vals[$sym] ?? '+0%'; $up = strpos($v,'+')!==false || strpos($v,'$')!==false;
            @endphp
            <div class="ticker-item">
                <span class="ticker-label">{{ $sym }}</span>
                <span class="{{ $up ? 'ticker-val-up' : 'ticker-val-down' }}">{{ $v }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Nav -->
    <nav>
        <a href="/" class="logo">
            <div class="logo-mark">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            </div>
            <span class="logo-text">Finance<span>Tracker</span></span>
        </a>
        <div style="display:flex;gap:10px;align-items:center;">
            <span style="font-size:12.5px;color:var(--muted);">{{ now()->format('D, d M Y') }}</span>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-nav-primary">
                    Dashboard
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-nav-ghost">Sign In</a>
                <a href="{{ route('login') }}" class="btn-nav-primary">
                    Get Started
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            @endauth
        </div>
    </nav>

    <!-- Hero -->
    <section>
        <div class="hero">
            <!-- Background blobs -->
            <div class="hero-blob" style="top:-100px;left:-80px;width:500px;height:500px;background:radial-gradient(ellipse,rgba(99,102,241,0.12),transparent 65%);"></div>
            <div class="hero-blob" style="top:50px;right:-100px;width:400px;height:400px;background:radial-gradient(ellipse,rgba(16,185,129,0.10),transparent 65%);"></div>

            <div class="fade-up delay-1">
                <div class="hero-tag">
                    <span class="hero-tag-dot"></span>
                    Live market data · Real-time tracking
                </div>
            </div>

            <h1 class="fade-up delay-2">
                Take full control<br>of your <span class="gradient-text">finances.</span>
            </h1>

            <p class="hero-desc fade-up delay-3">
                Track wallets, budgets, investments, and cashflow — all in one beautifully simple dashboard. Built for clarity, designed for action.
            </p>

            <div class="hero-actions fade-up delay-4">
                @guest
                <a href="{{ route('login') }}" class="btn-hero">
                    Start Tracking Free
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="btn-hero">
                    Open Dashboard
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                @endguest
                <a href="#features" class="btn-hero-outline">
                    Explore Features
                </a>
            </div>

            <div class="stats-strip fade-up delay-5">
                <div class="stat-pill">
                    <div class="stat-pill-label">Wallets</div>
                    <div class="stat-pill-value" style="color:var(--primary);">∞</div>
                    <div class="stat-pill-sub">No limit</div>
                </div>
                <div class="stat-pill">
                    <div class="stat-pill-label">Market Data</div>
                    <div class="stat-pill-value" style="color:var(--success);">Live</div>
                    <div class="stat-pill-sub">Yahoo + Binance</div>
                </div>
                <div class="stat-pill">
                    <div class="stat-pill-label">Privacy</div>
                    <div class="stat-pill-value" style="color:var(--text);">100%</div>
                    <div class="stat-pill-sub">Your data only</div>
                </div>
                <div class="stat-pill">
                    <div class="stat-pill-label">Currency</div>
                    <div class="stat-pill-value" style="color:#f59e0b;">IDR</div>
                    <div class="stat-pill-sub">Rupiah native</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features">
        <div class="features">
            <div class="section-header">
                <div class="section-tag">Core Modules</div>
                <h2 class="section-title">Everything you need, nothing you don't</h2>
                <p class="section-desc">Six powerful modules to give you a complete picture of your financial health.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(99,102,241,0.09);border:1px solid rgba(99,102,241,0.18);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="feature-title">Multi-Wallet Tracking</div>
                    <p class="feature-desc">Bank, e-wallet, cash — track all your asset pools with real-time balance updates and full transaction history.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(16,185,129,0.09);border:1px solid rgba(16,185,129,0.18);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div class="feature-title">Live Investment Portfolio</div>
                    <p class="feature-desc">Stocks and crypto with live prices from Yahoo Finance and Binance. Monitor P&L and performance in real-time.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(245,158,11,0.09);border:1px solid rgba(245,158,11,0.18);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div class="feature-title">Smart Budget Alerts</div>
                    <p class="feature-desc">Category-based monthly budgets with threshold alerts. Get warned before you overspend — automatically.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(168,85,247,0.09);border:1px solid rgba(168,85,247,0.18);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </div>
                    <div class="feature-title">Cashflow Analytics</div>
                    <p class="feature-desc">Beautiful 6-month income vs expense charts. Expense category breakdowns and weekly performance trends.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(20,184,166,0.09);border:1px solid rgba(20,184,166,0.18);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    </div>
                    <div class="feature-title">CSV Export</div>
                    <p class="feature-desc">Export your full transaction history in one click. Compatible with Excel, Google Sheets, and any spreadsheet tool.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(244,63,94,0.09);border:1px solid rgba(244,63,94,0.18);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div class="feature-title">Fully Private & Secure</div>
                    <p class="feature-desc">Your financial data lives on your own server. No third-party access, no data mining, no subscriptions needed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <div class="cta-banner">
        <div class="cta-inner">
            <h2 class="cta-title">Ready to get your finances in order?</h2>
            <p class="cta-desc">Join thousands of users who've simplified their money management with FinanceTracker.</p>
            @guest
            <a href="{{ route('login') }}" class="btn-cta">
                Get Started Free
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
            @else
            <a href="{{ route('dashboard') }}" class="btn-cta">
                Open Dashboard
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
            @endguest
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <span class="footer-logo">© {{ date('Y') }} FinanceTracker</span>
        <span class="footer-text">Built for control. Designed for clarity.</span>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>