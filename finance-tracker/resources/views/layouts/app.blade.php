<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — FinanceTracker</title>

    {{-- Only load 2 font weights instead of 6, display=swap for non-blocking --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Chart.js only on pages that need it via @stack --}}
    @stack('head-scripts')

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: rgba(99,102,241,0.1);
            --success: #10b981;
            --danger: #f43f5e;
            --warning: #f97316;
            --sky: #0ea5e9;
            --bg: #f5f6fa;
            --surface: #ffffff;
            --border: rgba(0,0,0,0.07);
            --text: #111827;
            --muted: #6b7280;
            --sidebar-w: 256px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { font-size: 15px; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ─── Layout ─────────────────────────────── */
        .layout { display: flex; min-height: 100vh; }

        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 1.25rem 1rem;
            overflow-y: auto;
            z-index: 100;
            transition: transform 0.25s ease;
        }

        .page-body {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 1.75rem 2rem;
            min-height: 100vh;
        }

        /* ─── Sidebar ────────────────────────────── */
        .brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0 0.25rem;
            margin-bottom: 1.5rem;
        }
        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border-radius: 10px;
            display: grid; place-items: center;
        }
        .brand-icon svg { width: 18px; height: 18px; stroke: white; fill: none; stroke-width: 2; }
        .brand-name { font-size: 1rem; font-weight: 800; color: var(--primary); letter-spacing: -0.02em; }

        .nav-label {
            font-size: 0.6rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--muted); padding: 0 0.25rem;
            margin-bottom: 0.375rem;
        }

        .nav-link {
            display: flex; align-items: center; gap: 0.625rem;
            padding: 0.625rem 0.75rem;
            border-radius: 0.75rem;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.875rem; font-weight: 600;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
        }
        .nav-link:hover { background: var(--primary-light); color: var(--primary); }
        .nav-link.active { background: var(--primary-light); color: var(--primary); font-weight: 700; }
        .nav-link svg { width: 17px; height: 17px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }

        .nav-icon {
            width: 30px; height: 30px; border-radius: 8px;
            display: grid; place-items: center; flex-shrink: 0;
            font-size: 0; /* hide text */
        }
        .nav-link.active .nav-icon, .nav-link:hover .nav-icon { /* no separate icon bg in light mode */ }

        /* ─── Cards ──────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
        }
        .card:hover { box-shadow: 0 2px 12px rgba(99,102,241,0.09); }

        /* ─── Buttons ────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.55rem 1.1rem;
            border-radius: 10px; border: none; cursor: pointer;
            font: inherit; font-size: 0.8rem; font-weight: 700;
            text-decoration: none; transition: all 0.15s;
        }
        .btn svg { width: 15px; height: 15px; }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: white;
            box-shadow: 0 2px 8px rgba(99,102,241,0.3);
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-secondary {
            background: white; border: 1px solid var(--border);
            color: var(--muted);
        }
        .btn-secondary:hover { border-color: var(--primary); color: var(--primary); }

        /* ─── Badges ─────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 0.2rem 0.6rem; border-radius: 100px;
            font-size: 0.7rem; font-weight: 700;
        }
        .badge-indigo { background: rgba(99,102,241,0.1);  color: var(--primary); }
        .badge-green  { background: rgba(16,185,129,0.1);  color: var(--success); }
        .badge-red    { background: rgba(244,63,94,0.1);   color: var(--danger); }
        .badge-orange { background: rgba(249,115,22,0.1);  color: var(--warning); }

        /* ─── Form ───────────────────────────────── */
        .form-input {
            width: 100%;
            background: #f8f9ff;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 0.7rem 0.9rem;
            font: inherit; font-size: 0.85rem;
            color: var(--text);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .form-label { display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: var(--muted); margin-bottom: 0.4rem; }

        /* ─── Alert strips ───────────────────────── */
        .alert { display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem; border-radius: 12px; }
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .alert-error   { background: #fff1f2; border: 1px solid #fecdd3; color: #9f1239; }
        .alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
        .alert-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

        /* ─── Table ──────────────────────────────── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { padding: 0.625rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); background: #f9fafb; }
        .data-table td { padding: 0.75rem 1rem; font-size: 0.825rem; border-top: 1px solid var(--border); }
        .data-table tbody tr:hover { background: #f9fafb; }

        /* ─── Scrollbar ──────────────────────────── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }

        /* ─── Mobile ─────────────────────────────── */
        .topbar { display: none; }

        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.1); }
            .page-body { margin-left: 0; padding: 1rem; }
            .topbar {
                display: flex; align-items: center; justify-content: space-between;
                background: white; border-bottom: 1px solid var(--border);
                padding: 0.875rem 1rem; position: sticky; top: 0; z-index: 50;
            }
        }

        /* ─── Inline SVG icons (no CDN needed) ──── */
        .ico { display: inline-block; width: 1em; height: 1em; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; vertical-align: middle; }

        /* ─── Grid helpers ───────────────────────── */
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
        @media (max-width: 900px) { .grid-3 { grid-template-columns: 1fr; } }
        @media (max-width: 700px) { .grid-2 { grid-template-columns: 1fr; } }

        /* page specific */
        .page-title { font-size: 1.3rem; font-weight: 800; letter-spacing: -0.02em; }
        .page-sub   { font-size: 0.8rem; color: var(--muted); margin-top: 0.1rem; }

        /* Overlay for mobile sidebar */
        #sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.3); z-index: 90;
        }
        #sidebar-overlay.show { display: block; }
    </style>
</head>
<body>
@auth

<!-- Mobile Topbar -->
<div class="topbar">
    <div style="display:flex;align-items:center;gap:0.6rem;">
        <button onclick="toggleSidebar()" style="background:none;border:none;cursor:pointer;padding:4px;">
            <svg style="width:22px;height:22px;stroke:#374151;fill:none;stroke-width:2;" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <span style="font-weight:800;font-size:1rem;color:var(--primary);">FinanceTracker</span>
    </div>
    <div style="display:flex;align-items:center;gap:0.75rem;">
        <span style="font-size:0.8rem;font-weight:600;color:var(--muted);">{{ Auth::user()->name }}</span>
    </div>
</div>
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    <div class="brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
        </div>
        <span class="brand-name">FinanceTracker</span>
    </div>

    <div class="nav-label">Navigation</div>
    <nav style="flex:1;">
        @php
        $links = [
            ['route'=>'dashboard',          'label'=>'Dashboard',     'icon'=>'<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>'],
            ['route'=>'transactions.index', 'label'=>'Transactions',  'icon'=>'<polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>'],
            ['route'=>'wallets.index',      'label'=>'Wallets',       'icon'=>'<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>'],
            ['route'=>'budgets.index',      'label'=>'Budgets',       'icon'=>'<path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>'],
            ['route'=>'investments.index',  'label'=>'Investments',   'icon'=>'<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>'],
            ['route'=>'settings.index',     'label'=>'Settings',      'icon'=>'<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>'],
        ];
        @endphp
        @foreach($links as $link)
        <a href="{{ route($link['route']) }}" class="nav-link {{ request()->routeIs($link['route']) ? 'active' : '' }}">
            <svg style="width:17px;height:17px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;" viewBox="0 0 24 24">{!! $link['icon'] !!}</svg>
            {{ $link['label'] }}
        </a>
        @endforeach
    </nav>

    <!-- User -->
    <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-top: 1rem;">
        <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.25rem;">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:grid;place-items:center;flex-shrink:0;">
                <span style="color:white;font-size:0.75rem;font-weight:800;">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:0.8rem;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
                <div style="font-size:0.65rem;color:var(--primary);font-weight:600;">Premium</div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Logout" style="background:none;border:none;cursor:pointer;padding:5px;border-radius:7px;color:var(--muted);" onmouseover="this.style.color='#f43f5e'" onmouseout="this.style.color='var(--muted)'">
                    <svg style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<main class="page-body">
@else
<main style="width:100%;min-height:100vh;">
@endauth

    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">
        <svg style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        <span style="font-size:0.85rem;font-weight:600;">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1rem;flex-direction:column;align-items:flex-start;">
        <div style="display:flex;align-items:center;gap:0.5rem;font-weight:700;font-size:0.85rem;">
            <svg style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Errors
        </div>
        @foreach ($errors->all() as $error)
        <p style="font-size:0.8rem;margin-top:0.3rem;opacity:0.8;">• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    @yield('content')
</main>

<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('sidebar-overlay');
    s.classList.toggle('open');
    o.classList.toggle('show');
}
</script>
@stack('scripts')
</body>
</html>
