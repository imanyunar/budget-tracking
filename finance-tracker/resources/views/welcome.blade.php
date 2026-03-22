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
                <i data-lucide="sparkles" class="w-3 h-3 mr-2"></i> Next-Gen Asset Management
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
                    Launch Platform <i data-lucide="arrow-right" class="w-5 h-5 ml-2 inline"></i>
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="px-12 py-5 bg-blue-600 hover:bg-blue-500 rounded-2xl text-lg font-black shadow-2xl shadow-blue-500/40 transition-all active:scale-95">
                    Goto Dashboard <i data-lucide="layout-dashboard" class="w-5 h-5 ml-2 inline"></i>
                </a>
                @endguest
            </div>
        </div>

        <div class="mt-24 w-full max-w-5xl opacity-50 select-none pointer-events-none">
            <div class="glass-card h-64 border-b-0 rounded-b-none translate-y-8 flex items-center justify-center">
                <div class="flex space-x-12">
                    <div class="flex items-center space-x-3"><i data-lucide="pie-chart" class="w-8 h-8 text-blue-500"></i> <span class="font-black text-xl">Analytics</span></div>
                    <div class="flex items-center space-x-3"><i data-lucide="shield" class="w-8 h-8 text-purple-500"></i> <span class="font-black text-xl">Security</span></div>
                    <div class="flex items-center space-x-3"><i data-lucide="zap" class="w-8 h-8 text-yellow-500"></i> <span class="font-black text-xl">Real-time</span></div>
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
