<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Finance Dashboard') - FinanceTracker</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --bg-main: #0a0c10;
            --bg-card: rgba(23, 27, 34, 0.7);
            --card-border: rgba(255, 255, 255, 0.08);
            --accent-primary: #3b82f6;
            --accent-secondary: #8b5cf6;
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 1.25rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
        }

        /* Sidebar Sidebar Styles */
        .sidebar {
            width: 280px;
            background: rgba(13, 17, 23, 0.95);
            border-right: 1px solid var(--card-border);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 50;
            padding: 2rem 1.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: var(--text-secondary);
            border-radius: 0.75rem;
            text-decoration: none;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-primary);
        }

        .sidebar-link i {
            margin-right: 0.75rem;
            width: 20px;
        }

        /* Content Area */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            width: calc(100% - 280px);
        }

        /* Responsive Mobile Styles */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .mobile-header {
                display: flex !important;
            }
        }

        .mobile-header {
            display: none;
            background: var(--bg-card);
            border-bottom: 1px solid var(--card-border);
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-main);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--card-border);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
    </style>
</head>
<body>
    @auth
    <!-- Mobile Header -->
    <div class="mobile-header items-center justify-between">
        <div class="flex items-center">
            <i data-lucide="menu" class="w-6 h-6 mr-3 cursor-pointer" onclick="toggleSidebar()"></i>
            <span class="text-xl font-bold gradient-text">FinanceTracker</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative p-2 text-gray-400 hover:text-white transition-colors cursor-pointer group">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="absolute top-2 right-2 w-2 h-2 bg-yellow-500 rounded-full border-2 border-[#0a0c10] group-hover:scale-110 transition-transform"></span>
            </div>
            <div class="w-8 h-8 rounded-full bg-blue-500 overflow-hidden border border-white/10">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" alt="Profile">
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar flex flex-col">
        <div class="mb-10 flex items-center justify-between px-4">
            <span class="text-2xl font-bold gradient-text">FinanceTracker</span>
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>
        
        <nav class="flex-grow">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('transactions.index') }}" class="sidebar-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
                <i data-lucide="arrow-left-right"></i>
                <span>Transactions</span>
            </a>
            <a href="{{ route('wallets.index') }}" class="sidebar-link {{ request()->routeIs('wallets.index') ? 'active' : '' }}">
                <i data-lucide="wallet"></i>
                <span>Wallets</span>
            </a>
            <a href="{{ route('budgets.index') }}" class="sidebar-link {{ request()->routeIs('budgets.index') ? 'active' : '' }}">
                <i data-lucide="pie-chart"></i>
                <span>Budgets</span>
            </a>
            <a href="{{ route('investments.index') }}" class="sidebar-link {{ request()->routeIs('investments.index') ? 'active' : '' }}">
                <i data-lucide="trending-up"></i>
                <span>Investments</span>
            </a>
            <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                <i data-lucide="settings"></i>
                <span>Settings</span>
            </a>
            
            <div class="mt-8 px-4">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 ml-1">Alert Center</div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 glass-card bg-yellow-500/5 border-yellow-500/10 group cursor-pointer hover:bg-yellow-500/10 transition-all">
                        <div class="flex items-center">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-yellow-500 mr-3"></i>
                            <span class="text-[10px] font-bold text-gray-300">Budget Warning</span>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                    </div>
                </div>
            </div>
        </nav>

        <div class="mt-auto p-4 space-y-4">
            <!-- User Profile & Logout -->
            <div class="p-4 rounded-xl glass-card bg-white/[0.02] border-white/5 flex items-center justify-between group">
                <div class="flex items-center overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex-shrink-0 mr-3 overflow-hidden">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" alt="">
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-black truncate">{{ Auth::user()->name }}</div>
                        <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Active Member</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-gray-500 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all" title="Sign Out">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            <div class="p-4 rounded-xl bg-gradient-to-br from-blue-600/20 to-purple-600/20 border border-blue-500/10">
                <div class="text-sm font-black mb-1">Elite Plan</div>
                <button class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg transition-all shadow-lg shadow-blue-500/20">Upgrade</button>
            </div>
        </div>
    </aside>
    @endauth

    <main class="main-content {{ !Auth::check() ? 'ml-0 w-full p-0' : '' }}">
        @if(session('success'))
        <div id="alert-success" class="max-w-7xl mx-auto mb-8 p-4 glass-card bg-green-500/10 border-green-500/20 text-green-500 flex items-center animate-in fade-in slide-in-from-top-4 duration-500">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
            <span class="text-sm font-bold">{{ session('success') }}</span>
            <button onclick="document.getElementById('alert-success').remove()" class="ml-auto p-1 hover:bg-white/5 rounded-lg"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        @endif

        @if($errors->any())
        <div id="alert-error" class="max-w-7xl mx-auto mb-8 p-4 glass-card bg-red-500/10 border-red-500/20 text-red-500 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="flex items-center mb-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mr-3"></i>
                <span class="text-sm font-bold uppercase tracking-widest">Action Failed</span>
                <button onclick="document.getElementById('alert-error').remove()" class="ml-auto p-1 hover:bg-white/5 rounded-lg"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
            <ul class="list-disc list-inside text-xs font-medium space-y-1 opacity-80">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        window.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const mobileHeader = document.querySelector('.mobile-header');
            if (window.innerWidth <= 1024 && !sidebar.contains(e.target) && !mobileHeader.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>
