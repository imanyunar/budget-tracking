<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FinanceTracker')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Icons & Charts -->
    @stack('head-scripts')

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* Hide scrollbar for sidebar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex selection:bg-indigo-500 selection:text-white">

    @auth
    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">
        <!-- Brand -->
        <div class="h-20 flex items-center px-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3 w-full">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 shrink-0">
                    <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-5 h-5 text-white"
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

                </div>
                <span class="text-xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-600">FinanceTracker</span>
            </div>
            <!-- Mobile Close Btn -->
            <button class="lg:hidden p-2 text-slate-400 hover:bg-slate-100 rounded-lg shrink-0" onclick="toggleSidebar()">
                <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-5 h-5"
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
  <path d="M18 6 6 18" />
  <path d="m6 6 12 12" />
</svg>

            </button>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 overflow-y-auto no-scrollbar py-6 px-4 flex flex-col gap-1.5">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Main Menu</div>
            
            @php
                $navItems = [
                    ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'layout-dashboard', 'color' => 'indigo'],
                    ['name' => 'Transactions', 'route' => 'transactions.index', 'icon' => 'arrow-left-right', 'color' => 'emerald'],
                    ['name' => 'Wallets', 'route' => 'wallets.index', 'icon' => 'wallet', 'color' => 'sky'],
                    ['name' => 'Budgets', 'route' => 'budgets.index', 'icon' => 'pie-chart', 'color' => 'orange'],
                    ['name' => 'Investments', 'route' => 'investments.index', 'icon' => 'trending-up', 'color' => 'rose'],
                    ['name' => 'Settings', 'route' => 'settings.index', 'icon' => 'settings', 'color' => 'slate'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" 
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 
                          {{ $isActive ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors 
                                {{ $isActive ? 'bg-indigo-600 shadow-md text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200 group-hover:text-slate-700' }}">
                        <x-icon name="{{ $item['icon'] }}" class="w-4 h-4" />
                    </div>
                    <span class="font-semibold {{ $isActive ? 'font-bold' : '' }} text-sm">{{ $item['name'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- User Profile -->
        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 border border-indigo-200">
                    <span class="text-indigo-700 font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs font-semibold text-indigo-600">Premium Plan</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Log Out">
                        <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-4 h-4"
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
  <path d="m16 17 5-5-5-5" />
  <path d="M21 12H9" />
  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
</svg>

                    </button>
                </form>
            </div>
        </div>
    </aside>
    @endauth

    <!-- Main ContentWrapper -->
    <div class="flex-1 flex flex-col min-h-screen {{ Auth::check() ? 'lg:pl-72' : '' }} w-full transition-all duration-300">
        
        @auth
        <!-- Mobile Topbar -->
        <header class="lg:hidden h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                    <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-5 h-5"
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
  <path d="M4 5h16" />
  <path d="M4 12h16" />
  <path d="M4 19h16" />
</svg>

                </button>
                <span class="text-lg font-bold text-slate-800">FinanceTracker</span>
            </div>
            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center border border-indigo-200">
                <span class="text-indigo-700 font-bold text-xs">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
        </header>
        @endauth

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-7xl mx-auto overflow-x-hidden animate-fade-in-up">
            
            <!-- Global Alerts -->
            @if(session('success'))
            <div id="flash-success" class="mb-6 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                    <x-icon name="check" class="w-4 h-4 text-emerald-600" />
                </div>
                <p class="text-sm font-semibold text-emerald-800 flex-1">{{ session('success') }}</p>
                <button onclick="document.getElementById('flash-success').remove()" class="p-1 text-emerald-600 hover:bg-emerald-100 rounded-lg">
                    <x-icon name="x" class="w-4 h-4" />
                </button>
            </div>
            @endif

            @if(session('warning'))
            <div id="flash-warning" class="mb-6 flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl shadow-sm">
                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                    <x-icon name="alert-triangle" class="w-4 h-4 text-amber-600" />
                </div>
                <p class="text-sm font-semibold text-amber-800 flex-1">{{ session('warning') }}</p>
                <button onclick="document.getElementById('flash-warning').remove()" class="p-1 text-amber-600 hover:bg-amber-100 rounded-lg">
                    <x-icon name="x" class="w-4 h-4" />
                </button>
            </div>
            @endif

            @if($errors->any())
            <div id="flash-error" class="mb-6 bg-rose-50 border border-rose-200 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center shrink-0">
                        <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-4 h-4 text-rose-600"
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
  <circle cx="12" cy="12" r="10" />
  <line x1="12" x2="12" y1="8" y2="12" />
  <line x1="12" x2="12.01" y1="16" y2="16" />
</svg>

                    </div>
                    <h3 class="text-sm font-bold text-rose-800 flex-1">Please fix the following errors</h3>
                    <button onclick="document.getElementById('flash-error').remove()" class="p-1 text-rose-600 hover:bg-rose-100 rounded-lg">
                        <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-4 h-4"
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
  <path d="M18 6 6 18" />
  <path d="m6 6 12 12" />
</svg>

                    </button>
                </div>
                <ul class="list-disc list-inside text-sm font-medium text-rose-700 space-y-1 pl-11">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
            
        </main>
    </div>

    <script>
        // Init Icons
        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if(sidebar) {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
