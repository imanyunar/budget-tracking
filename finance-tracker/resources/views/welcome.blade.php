<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FinanceTracker | Elevate Your Wealth</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/tailwind-v4-fallback.css') }}">
    @endif
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0a0c10; color: white; overflow-x: hidden; }
        .grid-bg { background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0); background-size: 40px 40px; }
        .gradient-text { background: linear-gradient(135deg, #60a5fa 0%, #a855f7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-glow { position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 100%; height: 600px; background: radial-gradient(circle at center, rgba(59, 130, 246, 0.15) 0%, transparent 70%); pointer-events: none; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 24px; }
    </style>
</head>
<body class="grid-bg min-h-screen flex flex-col">
    <div class="hero-glow"></div>

    <nav class="max-w-7xl mx-auto w-full p-8 flex justify-between items-center relative z-10">
        <span class="text-2xl font-black gradient-text">FinanceTracker</span>
        <div class="space-x-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold hover:bg-white/10 transition-all">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-black shadow-xl shadow-blue-500/20 transition-all active:scale-95">Access Terminal</a>
            @endauth
        </div>
    </nav>

    <main class="flex-grow flex flex-col items-center justify-center p-8 text-center relative z-10">
        <div class="space-y-6 animate-in fade-in slide-in-from-bottom-8 duration-1000">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-black uppercase tracking-widest mb-4">
                <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-3 h-3 mr-2"
  xmlns="http://www.w3.org/2000/svg"
  width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  stroke="currentColor"
  stroke-width="2"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z" />
  <path d="M20 2v4" />
  <path d="M22 4h-4" />
  <circle cx="4" cy="20" r="2" />
</svg>
 Next-Gen Asset Management
            </div>
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter leading-none">
                Master Your <br> <span class="gradient-text">Capital Flow.</span>
            </h1>
            <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto font-medium">
                The elite dashboard for tracking wallets, budgets, and investments with real-time analytics and predictive insights.
            </p>
            <div class="pt-8 flex flex-col md:flex-row gap-4 justify-center">
                @guest
                <a href="{{ route('login') }}" class="px-12 py-5 bg-blue-600 hover:bg-blue-500 rounded-2xl text-lg font-black shadow-2xl shadow-blue-500/40 transition-all active:scale-95">
                    Launch Platform <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-5 h-5 ml-2 inline"
  xmlns="http://www.w3.org/2000/svg"
  width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  stroke="currentColor"
  stroke-width="2"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path d="M5 12h14" />
  <path d="m12 5 7 7-7 7" />
</svg>

                </a>
                @else
                <a href="{{ route('dashboard') }}" class="px-12 py-5 bg-blue-600 hover:bg-blue-500 rounded-2xl text-lg font-black shadow-2xl shadow-blue-500/40 transition-all active:scale-95">
                    Goto Dashboard <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-5 h-5 ml-2 inline"
  xmlns="http://www.w3.org/2000/svg"
  width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  stroke="currentColor"
  stroke-width="2"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <rect width="7" height="9" x="3" y="3" rx="1" />
  <rect width="7" height="5" x="14" y="3" rx="1" />
  <rect width="7" height="9" x="14" y="12" rx="1" />
  <rect width="7" height="5" x="3" y="16" rx="1" />
</svg>

                </a>
                @endguest
            </div>
        </div>

        <div class="mt-24 w-full max-w-5xl opacity-50 select-none pointer-events-none">
            <div class="glass-card h-64 border-b-0 rounded-b-none translate-y-8 flex items-center justify-center">
                <div class="flex space-x-12">
                    <div class="flex items-center space-x-3"><!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-8 h-8 text-blue-500"
  xmlns="http://www.w3.org/2000/svg"
  width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  stroke="currentColor"
  stroke-width="2"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z" />
  <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
</svg>
 <span class="font-black text-xl">Analytics</span></div>
                    <div class="flex items-center space-x-3"><!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-8 h-8 text-purple-500"
  xmlns="http://www.w3.org/2000/svg"
  width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  stroke="currentColor"
  stroke-width="2"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
</svg>
 <span class="font-black text-xl">Security</span></div>
                    <div class="flex items-center space-x-3"><!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-8 h-8 text-yellow-500"
  xmlns="http://www.w3.org/2000/svg"
  width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  stroke="currentColor"
  stroke-width="2"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" />
</svg>
 <span class="font-black text-xl">Real-time</span></div>
                </div>
            </div>
        </div>
    </main>

    <footer class="p-8 text-center text-gray-600 text-[10px] font-black uppercase tracking-widest border-t border-white/5 bg-black/20">
        &copy; {{ date('Y') }} FinanceTracker Elite. All rights reserved.
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>
