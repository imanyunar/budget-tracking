<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FinanceTracker')</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/tailwind-v4-fallback.css') }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('head-scripts')
    <style>
        /* ============================
           BRIGHT MODERN DESIGN SYSTEM
           ============================ */
        :root {
            --bg:          #f8fafc;
            --surface:     #ffffff;
            --surface-2:   #f1f5f9;
            --surface-3:   #e8f0fe;
            --border:      #e2e8f0;
            --border-2:    #cbd5e1;
            --text:        #0f172a;
            --text-2:      #475569;
            --muted:       #94a3b8;
            --primary:     #6366f1;
            --primary-2:   #4f46e5;
            --primary-dim: rgba(99,102,241,0.08);
            --primary-mid: rgba(99,102,241,0.15);
            --success:     #10b981;
            --success-dim: rgba(16,185,129,0.09);
            --danger:      #f43f5e;
            --danger-dim:  rgba(244,63,94,0.09);
            --warn:        #f59e0b;
            --warn-dim:    rgba(245,158,11,0.09);
            --blue:        #3b82f6;
            --blue-dim:    rgba(59,130,246,0.09);
            --sidebar-w:   252px;
            --topbar-h:    60px;
            --radius:      12px;
            --shadow-sm:   0 1px 3px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04);
            --shadow:      0 4px 12px rgba(15,23,42,0.08), 0 2px 4px rgba(15,23,42,0.04);
            --shadow-lg:   0 20px 48px rgba(15,23,42,0.12), 0 4px 16px rgba(15,23,42,0.06);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        .mono { font-family: 'JetBrains Mono', monospace; }

        /* ============================
           SIDEBAR
           ============================ */
        #sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
        }

        /* Logo */
        .sidebar-logo {
            padding: 22px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-mark {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(99,102,241,0.35);
        }
        .logo-mark svg { color: #fff; }
        .logo-text {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text);
        }
        .logo-text span { color: var(--primary); }

        /* User info */
        .sidebar-user {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--primary-dim);
            border: 2px solid var(--border);
            overflow: hidden;
            flex-shrink: 0;
        }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.2; }
        .user-role {
            font-size: 11px;
            color: var(--primary);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 1px;
        }
        .user-role::before {
            content: '';
            display: inline-block;
            width: 6px; height: 6px;
            background: var(--success);
            border-radius: 50%;
        }

        /* Nav sections */
        .nav-section-label {
            padding: 18px 18px 6px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: var(--muted);
            text-transform: uppercase;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            margin: 2px 8px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-2);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.15s;
            cursor: pointer;
            border: none;
            background: transparent;
            width: calc(100% - 16px);
            text-align: left;
        }
        .nav-item:hover {
            color: var(--text);
            background: var(--surface-2);
        }
        .nav-item.active {
            color: var(--primary);
            background: var(--primary-dim);
            font-weight: 600;
        }
        .nav-item.active svg { color: var(--primary); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; transition: color 0.15s; }
        .nav-item span { flex: 1; }
        .nav-item-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            background: var(--primary-dim);
            color: var(--primary);
            border-radius: 100px;
        }

        /* Sidebar footer */
        .sidebar-footer {
            margin-top: auto;
            padding: 16px 18px;
            border-top: 1px solid var(--border);
        }
        .storage-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--muted);
            margin-bottom: 6px;
        }
        .storage-bar {
            height: 4px;
            background: var(--border);
            border-radius: 4px;
            overflow: hidden;
        }
        .storage-bar-fill {
            height: 100%;
            width: 45%;
            background: linear-gradient(90deg, var(--primary), #818cf8);
            border-radius: 4px;
        }

        /* ============================
           TOPBAR
           ============================ */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 40;
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .hamburger {
            display: none;
            width: 36px; height: 36px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: transparent;
            color: var(--text-2);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }
        .hamburger:hover { background: var(--surface-2); color: var(--text); }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--muted);
        }
        .breadcrumb svg { width: 14px; height: 14px; }
        .breadcrumb-current {
            color: var(--text);
            font-weight: 600;
            text-transform: capitalize;
        }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .live-clock {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 0.04em;
            padding: 4px 10px;
            background: var(--surface-2);
            border-radius: 8px;
            border: 1px solid var(--border);
        }
        .live-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background: rgba(16,185,129,0.09);
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
            color: var(--success);
            letter-spacing: 0.04em;
        }
        .live-badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--success);
            animation: blink 1.8s ease infinite;
        }
        @keyframes blink {
            0%,100% { opacity:1; }
            50% { opacity:0.3; }
        }

        /* ============================
           MAIN CONTENT
           ============================ */
        #main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 28px 28px 48px;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* ============================
           FLASH MESSAGES
           ============================ */
        .flash-msg {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 16px;
            border-radius: var(--radius);
            border: 1px solid;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .flash-success { background: var(--success-dim); border-color: rgba(16,185,129,0.25); color: #059669; }
        .flash-error   { background: var(--danger-dim);  border-color: rgba(244,63,94,0.25);  color: #e11d48; }
        .flash-warning { background: var(--warn-dim);    border-color: rgba(245,158,11,0.25); color: #d97706; }
        .flash-msg svg { flex-shrink: 0; }

        /* ============================
           SHARED COMPONENTS
           ============================ */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .card:hover { box-shadow: var(--shadow); }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            color: #fff;
            font-weight: 600;
            font-size: 13.5px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            letter-spacing: -0.01em;
            box-shadow: 0 2px 8px rgba(99,102,241,0.35);
            white-space: nowrap;
        }
        .btn-primary:hover {
            box-shadow: 0 4px 16px rgba(99,102,241,0.45);
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: translateY(0); box-shadow: 0 2px 8px rgba(99,102,241,0.3); }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            background: var(--surface);
            color: var(--text-2);
            font-weight: 500;
            font-size: 13.5px;
            border: 1px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
            box-shadow: var(--shadow-sm);
        }
        .btn-secondary:hover { border-color: var(--border-2); color: var(--text); background: var(--surface-2); }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            background: var(--danger-dim);
            color: var(--danger);
            font-weight: 600;
            font-size: 13px;
            border: 1px solid rgba(244,63,94,0.2);
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .btn-danger:hover { background: rgba(244,63,94,0.15); }

        .input-field {
            width: 100%;
            padding: 9px 13px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--text);
            font-size: 13.5px;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        }
        .input-field::placeholder { color: var(--muted); }
        select.input-field option { background: #fff; color: var(--text); }

        .input-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-2);
            margin-bottom: 5px;
            letter-spacing: 0.01em;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.45);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            width: 100%;
            max-width: 480px;
            padding: 28px;
            position: relative;
            transform: scale(0.96) translateY(12px);
            opacity: 0;
            transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
            box-shadow: var(--shadow-lg);
        }
        .modal-box.open { transform: scale(1) translateY(0); opacity: 1; }
        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .modal-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
        }
        .modal-subtitle {
            font-size: 12.5px;
            color: var(--muted);
            margin-top: 3px;
        }
        .modal-close {
            width: 30px; height: 30px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-2);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
            flex-shrink: 0;
        }
        .modal-close:hover { background: var(--border); color: var(--text); }
        .modal-close svg { width: 14px; height: 14px; }

        /* Type toggle */
        .type-toggle {
            display: flex;
            gap: 4px;
            padding: 4px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 11px;
            margin-bottom: 18px;
        }
        .type-btn {
            flex: 1;
            padding: 8px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: all 0.15s;
            background: transparent;
            color: var(--muted);
        }
        .type-btn.active-expense {
            background: var(--surface);
            border-color: rgba(244,63,94,0.25);
            color: var(--danger);
            box-shadow: var(--shadow-sm);
        }
        .type-btn.active-income {
            background: var(--surface);
            border-color: rgba(16,185,129,0.25);
            color: var(--success);
            box-shadow: var(--shadow-sm);
        }
        .type-btn:not(.active-expense):not(.active-income):hover { color: var(--text-2); }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
        }
        .badge-up   { background: var(--success-dim); color: #059669; }
        .badge-down { background: var(--danger-dim);  color: var(--danger); }
        .badge-blue { background: var(--blue-dim); color: var(--blue); }
        .badge-warn { background: var(--warn-dim); color: #d97706; }
        .badge-primary { background: var(--primary-dim); color: var(--primary); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--muted); }

        /* Divider */
        .divider { border: none; border-top: 1px solid var(--border); margin: 0; }

        /* Mobile overlay */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.4);
            z-index: 45;
            backdrop-filter: blur(4px);
        }

        /* Page header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }
        .page-title-main {
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.03em;
        }
        .page-title-sub {
            font-size: 13px;
            color: var(--muted);
            margin-top: 3px;
        }

        /* Section header */
        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .section-head h3 {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
        }
        .section-head-sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 1px;
        }
        .section-link {
            font-size: 12.5px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.15s;
        }
        .section-link:hover { opacity: 0.75; }

        /* Stat card */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px 22px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover { box-shadow: var(--shadow); }
        .stat-icon {
            width: 42px; height: 42px;
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 6px;
        }
        .stat-value {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text);
        }

        /* Transaction row */
        .tx-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            transition: background 0.1s;
        }
        .tx-row:last-child { border-bottom: none; }
        .tx-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .tx-name {
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text);
            line-height: 1.2;
        }
        .tx-meta {
            font-size: 11.5px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--muted);
        }
        .empty-state-icon {
            width: 52px; height: 52px;
            background: var(--surface-2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
        .empty-state-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-2);
            margin-bottom: 4px;
        }
        .empty-state-text { font-size: 13px; }

        /* ============================
           RESPONSIVE
           ============================ */
        @media (max-width: 900px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #topbar { left: 0; }
            #main-content { margin-left: 0; }
            .hamburger { display: flex; }
            #sidebar-overlay.open { display: block; }
        }
        @media (max-width: 640px) {
            #main-content { padding: 20px 16px 40px; }
            #topbar { padding: 0 16px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-mark">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            </div>
            <div class="logo-text">Finance<span>Tracker</span></div>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">
                @if(isset(Auth::user()->settings['avatar_path']))
                    <img src="{{ asset('storage/' . Auth::user()->settings['avatar_path']) }}" alt="">
                @else
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode(Auth::user()->name) }}&backgroundColor=6366f1&textColor=ffffff" alt="">
                @endif
            </div>
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">Active</div>
            </div>
        </div>

        <nav style="flex:1; overflow-y:auto; padding:10px 0;">
            <div class="nav-section-label">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"/><path d="M8 21H3v-5"/><path d="M21 3l-7 7"/><path d="M3 21l7-7"/></svg>
                <span>Transactions</span>
            </a>
            <a href="{{ route('wallets.index') }}" class="nav-item {{ request()->routeIs('wallets.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                <span>Wallets</span>
            </a>
            <a href="{{ route('budgets.index') }}" class="nav-item {{ request()->routeIs('budgets.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>Budgets</span>
            </a>
            <a href="{{ route('investments.index') }}" class="nav-item {{ request()->routeIs('investments.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                <span>Investments</span>
            </a>

            <div class="nav-section-label" style="margin-top:8px;">System</div>
            <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                <span>Settings</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item" style="color:#f43f5e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </nav>

        <div class="sidebar-footer">
            <div class="storage-bar-label">
                <span>Active Session</span>
                <span style="color:var(--success); font-weight:600;">Online</span>
            </div>
            <div class="storage-bar">
                <div class="storage-bar-fill"></div>
            </div>
        </div>
    </aside>

    <!-- Sidebar overlay -->
    <div id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
            </button>
            <div class="breadcrumb">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                <span class="breadcrumb-current">{{ request()->segment(1) ?: 'dashboard' }}</span>
            </div>
        </div>
        <div class="topbar-right">
            <div class="live-clock" id="liveClock"></div>
            <div class="live-badge">
                <span class="live-badge-dot"></span>
                Live
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="flash-msg flash-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flash-msg flash-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if(session('warning'))
        <div class="flash-msg flash-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            {{ session('warning') }}
        </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Clock
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2,'0');
            const m = String(now.getMinutes()).padStart(2,'0');
            const s = String(now.getSeconds()).padStart(2,'0');
            const el = document.getElementById('liveClock');
            if (el) el.textContent = `${h}:${m}:${s}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Sidebar toggle
        function toggleSidebar() {
            const s = document.getElementById('sidebar');
            const o = document.getElementById('sidebar-overlay');
            s.classList.toggle('open');
            o.classList.toggle('open');
        }

        // Lucide icons
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>