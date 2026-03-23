<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — FinanceTracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/tailwind-v4-fallback.css') }}">
    @endif
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Inter',sans-serif;background:#f8fafc;color:#0f172a;-webkit-font-smoothing:antialiased;min-height:100vh;display:flex;}
        ::-webkit-scrollbar{width:4px;} ::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:4px;}

        /* Left panel */
        .left-panel{
            flex:1;min-width:0;
            background:linear-gradient(145deg,#4338ca 0%,#6366f1 40%,#7c3aed 100%);
            display:flex;flex-direction:column;justify-content:space-between;
            padding:48px;position:relative;overflow:hidden;
        }
        @media(max-width:900px){.left-panel{display:none;}}
        .left-blob{position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;}
        .left-logo{display:flex;align-items:center;gap:10px;}
        .left-logo-mark{width:36px;height:36px;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25);border-radius:10px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px);}
        .left-logo-text{font-size:16px;font-weight:800;color:#fff;letter-spacing:-0.03em;}
        .left-headline{font-size:clamp(32px,3.5vw,48px);font-weight:900;color:#fff;letter-spacing:-0.04em;line-height:1.1;margin-bottom:14px;}
        .left-sub{font-size:16px;color:rgba(255,255,255,0.7);line-height:1.65;max-width:420px;}
        .left-feature{display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid rgba(255,255,255,0.1);}
        .left-feature:last-child{border-bottom:none;}
        .left-feat-icon{width:40px;height:40px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .left-feat-title{font-size:14px;font-weight:700;color:#fff;}
        .left-feat-sub{font-size:12.5px;color:rgba(255,255,255,0.6);margin-top:1px;}
        .left-users{display:flex;align-items:center;gap:14px;padding-top:28px;border-top:1px solid rgba(255,255,255,0.15);}
        .avatar-stack{display:flex;}
        .avatar-stack img{width:34px;height:34px;border-radius:50%;border:2px solid rgba(255,255,255,0.8);margin-left:-8px;}
        .avatar-stack img:first-child{margin-left:0;}
        .user-count-label{font-size:12px;font-weight:600;color:rgba(255,255,255,0.8);}

        /* Right panel */
        .right-panel{
            width:520px;min-width:320px;
            background:#fff;
            display:flex;flex-direction:column;justify-content:center;
            padding:64px 56px;overflow-y:auto;
        }
        @media(max-width:900px){.right-panel{width:100%;padding:40px 28px;}}
        @media(max-width:480px){.right-panel{padding:32px 20px;}}

        /* Mobile logo */
        .mobile-logo{display:none;align-items:center;gap:10px;margin-bottom:32px;justify-content:center;}
        @media(max-width:900px){.mobile-logo{display:flex;}}
        .mobile-logo-mark{width:36px;height:36px;background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(99,102,241,0.35);}

        .form-title{font-size:26px;font-weight:800;letter-spacing:-0.03em;margin-bottom:6px;}
        .form-sub{font-size:14px;color:#64748b;margin-bottom:32px;}

        .error-box{padding:13px 16px;background:#fff1f2;border:1px solid #fecdd3;border-radius:10px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;}
        .error-box-text{font-size:13.5px;color:#e11d48;font-weight:500;}

        .form-group{margin-bottom:16px;}
        .form-label{display:flex;justify-content:space-between;align-items:center;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px;}
        .form-label a{font-size:12px;color:#6366f1;text-decoration:none;font-weight:500;}
        .form-label a:hover{text-decoration:underline;}
        .input-wrap{position:relative;}
        .input-icon{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#94a3b8;width:16px;height:16px;pointer-events:none;}
        .form-input{
            width:100%;padding:10px 13px 10px 40px;
            background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;
            font-size:14px;font-family:'Inter',sans-serif;color:#0f172a;
            outline:none;transition:border-color 0.15s,box-shadow 0.15s;
        }
        .form-input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,0.12);}
        .form-input::placeholder{color:#94a3b8;}
        .pwd-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:2px;line-height:0;}
        .pwd-toggle:hover{color:#6366f1;}

        .remember-row{display:flex;align-items:center;gap:8px;margin-bottom:22px;}
        .remember-row input{accent-color:#6366f1;width:16px;height:16px;cursor:pointer;}
        .remember-row label{font-size:13.5px;color:#64748b;cursor:pointer;}

        .btn-submit{
            width:100%;padding:12px;
            background:linear-gradient(135deg,#6366f1,#4f46e5);
            color:#fff;font-weight:700;font-size:14.5px;
            border:none;border-radius:10px;cursor:pointer;
            transition:all 0.2s;letter-spacing:-0.01em;
            box-shadow:0 3px 10px rgba(99,102,241,0.35);
        }
        .btn-submit:hover{box-shadow:0 6px 18px rgba(99,102,241,0.45);transform:translateY(-1px);}
        .btn-submit:active{transform:translateY(0);}
    </style>
</head>
<body>

    <!-- Left Branding Panel -->
    <div class="left-panel">
        <!-- Background blobs -->
        <div class="left-blob" style="width:500px;height:500px;background:rgba(255,255,255,0.06);top:-150px;right:-100px;"></div>
        <div class="left-blob" style="width:400px;height:400px;background:rgba(124,58,237,0.3);bottom:-100px;left:-100px;"></div>

        <!-- Logo -->
        <div class="left-logo">
            <div class="left-logo-mark">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            </div>
            <span class="left-logo-text">FinanceTracker</span>
        </div>

        <!-- Main copy -->
        <div>
            <h1 class="left-headline">Master your money.<br>Design your future.</h1>
            <p class="left-sub">Track every expense, optimize your budgets, and monitor your investments — all in one beautiful dashboard.</p>

            <div style="margin-top:36px;">
                <div class="left-feature">
                    <div class="left-feat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.9)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div>
                        <div class="left-feat-title">Real-time Analytics</div>
                        <div class="left-feat-sub">Understand your cashflow at a glance</div>
                    </div>
                </div>
                <div class="left-feature">
                    <div class="left-feat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.9)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div class="left-feat-title">Smart Budgeting</div>
                        <div class="left-feat-sub">Set limits and get alerts automatically</div>
                    </div>
                </div>
                <div class="left-feature">
                    <div class="left-feat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.9)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <div class="left-feat-title">Fully Private</div>
                        <div class="left-feat-sub">Your data stays on your server</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social proof -->
        <div class="left-users">
            <div class="avatar-stack">
                <img src="https://i.pravatar.cc/100?img=1" alt="">
                <img src="https://i.pravatar.cc/100?img=2" alt="">
                <img src="https://i.pravatar.cc/100?img=3" alt="">
                <img src="https://i.pravatar.cc/100?img=4" alt="">
            </div>
            <div>
                <div style="display:flex;gap:2px;color:#fbbf24;margin-bottom:2px;">
                    @for($i=0;$i<5;$i++)<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
                </div>
                <div class="user-count-label">Trusted by thousands of users</div>
            </div>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="right-panel">
        <!-- Mobile logo -->
        <div class="mobile-logo">
            <div class="mobile-logo-mark">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            </div>
            <span style="font-size:18px;font-weight:800;letter-spacing:-0.03em;">FinanceTracker</span>
        </div>

        <div class="form-title">Welcome back 👋</div>
        <div class="form-sub">Sign in to your account to continue</div>

        @if($errors->any())
        <div class="error-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            <div class="error-box-text">{{ $errors->first() }}</div>
        </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <!-- Email -->
            <div class="form-group">
                <div class="form-label"><span>Email Address</span></div>
                <div class="input-wrap">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="you@example.com" class="form-input" autocomplete="email">
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <div class="form-label">
                    <span>Password</span>
                    <a href="#">Forgot password?</a>
                </div>
                <div class="input-wrap">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" name="password" required id="pwdInput" placeholder="••••••••" class="form-input" style="padding-right:42px;" autocomplete="current-password">
                    <button type="button" class="pwd-toggle" onclick="togglePwd()" id="pwdToggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <!-- Remember -->
            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me for 30 days</label>
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
        </form>

        <div style="text-align:center;margin-top:28px;font-size:12.5px;color:#94a3b8;">
            © {{ date('Y') }} FinanceTracker · Built for clarity
        </div>
    </div>

    <script>
        function togglePwd() {
            const pwd = document.getElementById('pwdInput');
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
