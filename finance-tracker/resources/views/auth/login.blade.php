<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — FinanceTracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
        }
        .form-input {
            width: 100%; background: #f8f9ff;
            border: 1.5px solid rgba(99,102,241,0.2); border-radius: 10px;
            padding: 0.75rem 1rem; font: inherit; font-size: 0.875rem;
            color: #111827; outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .form-label { display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #6b7280; margin-bottom: 0.4rem; }
    </style>
</head>
<body>
    <!-- bg circles -->
    <div style="position:fixed;top:-80px;left:-80px;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,0.15),transparent 70%);pointer-events:none;"></div>
    <div style="position:fixed;bottom:-80px;right:-80px;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(16,185,129,0.12),transparent 70%);pointer-events:none;"></div>

    <div style="display:grid;grid-template-columns:1fr 1fr;max-width:900px;width:100%;gap:3rem;align-items:center;">

        {{-- Left Branding --}}
        <div style="display:none;" class="branding-panel">
@media (min-width:700px) { .branding-panel { display:block !important; } }
        </div>
        <div style="flex-direction:column;gap:1.75rem;" class="branding-inner">
            {{-- Logo --}}
            <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1rem;">
                <div style="width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:grid;place-items:center;">
                    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:white;fill:none;stroke-width:2;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <span style="font-size:1.1rem;font-weight:800;color:#6366f1;">FinanceTracker</span>
            </div>

            <h1 style="font-size:2rem;font-weight:900;color:#111827;line-height:1.2;letter-spacing:-0.03em;">
                Take control of<br>
                <span style="background:linear-gradient(135deg,#6366f1,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">your finances.</span>
            </h1>
            <p style="font-size:0.9rem;color:#6b7280;line-height:1.6;">Track every rupiah, budget smarter, and grow your wealth with powerful analytics.</p>

            <div style="display:flex;flex-direction:column;gap:0.625rem;margin-top:0.5rem;">
                @foreach([
                    ['#6366f1','Track income & expenses in real-time'],
                    ['#10b981','Smart budget alerts & category breakdown'],
                    ['#f97316','Investment portfolio performance tracking'],
                ] as [$color, $text])
                <div style="display:flex;align-items:center;gap:0.625rem;">
                    <div style="width:28px;height:28px;border-radius:8px;background:{{ $color }}18;display:grid;place-items:center;flex-shrink:0;">
                        <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:{{ $color }};fill:none;stroke-width:2.5;"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span style="font-size:0.85rem;font-weight:600;color:#374151;">{{ $text }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Login Card --}}
        <div style="background:white;border-radius:20px;padding:2rem;box-shadow:0 8px 40px rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.08);">

            {{-- Mobile logo --}}
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.5rem;">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:grid;place-items:center;">
                    <svg viewBox="0 0 24 24" style="width:15px;height:15px;stroke:white;fill:none;stroke-width:2;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <span style="font-size:0.95rem;font-weight:800;color:#6366f1;">FinanceTracker</span>
            </div>

            <div style="margin-bottom:1.5rem;">
                <h2 style="font-size:1.3rem;font-weight:800;color:#111827;">Welcome back 👋</h2>
                <p style="font-size:0.82rem;color:#6b7280;margin-top:0.2rem;">Sign in to your dashboard</p>
            </div>

            @if ($errors->any())
            <div style="display:flex;align-items:center;gap:0.6rem;padding:0.75rem 0.875rem;border-radius:10px;background:#fff1f2;border:1px solid #fecdd3;margin-bottom:1rem;">
                <svg viewBox="0 0 24 24" style="width:15px;height:15px;stroke:#f43f5e;fill:none;stroke-width:2;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p style="font-size:0.8rem;color:#9f1239;font-weight:600;">{{ $errors->first() }}</p>
            </div>
            @endif

            <form method="POST" action="/login" style="display:flex;flex-direction:column;gap:0.875rem;">
                @csrf
                <div>
                    <label class="form-label">Email Address</label>
                    <div style="position:relative;">
                        <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;stroke:#9ca3af;fill:none;stroke-width:2;pointer-events:none;" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="demo@example.com" class="form-input" style="padding-left:2.5rem;" autocomplete="email">
                    </div>
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <div style="position:relative;">
                        <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;stroke:#9ca3af;fill:none;stroke-width:2;pointer-events:none;" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password" required placeholder="••••••••" class="form-input" style="padding-left:2.5rem;" autocomplete="current-password" id="pwd">
                        <button type="button" onclick="togglePwd()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:2px;">
                            <svg id="eye-icon" viewBox="0 0 24 24" style="width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" style="width:100%;padding:0.85rem;border-radius:11px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;font:inherit;font-size:0.875rem;font-weight:700;border:none;cursor:pointer;box-shadow:0 4px 12px rgba(99,102,241,0.3);margin-top:0.25rem;transition:opacity 0.15s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Sign In →
                </button>
            </form>

            <p style="text-align:center;font-size:0.75rem;color:#9ca3af;margin-top:1.25rem;">
                Demo: <strong style="color:#374151;">demo@example.com</strong> / <strong style="color:#374151;">password</strong>
            </p>
        </div>
    </div>

    <script>
    function togglePwd() {
        const p = document.getElementById('pwd');
        const closed = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        const open   = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
        if (p.type === 'password') { p.type='text'; document.getElementById('eye-icon').innerHTML=open; }
        else { p.type='password'; document.getElementById('eye-icon').innerHTML=closed; }
    }
    </script>
</body>
</html>
