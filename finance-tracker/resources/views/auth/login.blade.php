<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - FinanceTracker</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Core Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen selection:bg-indigo-500 selection:text-white flex text-slate-900 antialiased overflow-hidden">
    
    <!-- Left: Branding Panel -->
    <div class="hidden lg:flex flex-col flex-1 w-full bg-indigo-900 relative overflow-hidden text-white pt-12 pb-16 px-16 xl:px-24 animate-fade-in-up" style="animation-duration: 0.7s;">
        <!-- Abstract Decoration -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-br from-violet-500/20 to-fuchsia-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-gradient-to-tr from-indigo-500/20 to-blue-500/20 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>

        <div class="relative z-10 flex flex-col h-full justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20">
                    <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-5 h-5 text-indigo-300"
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
                <span class="text-xl font-extrabold tracking-tight">FinanceTracker</span>
            </div>

            <div class="max-w-xl">
                <h1 class="text-4xl xl:text-5xl font-black leading-tight mb-6">
                    Master your money.<br>
                    <span class="text-indigo-300">Design your life.</span>
                </h1>
                <p class="text-indigo-200/80 text-lg font-medium leading-relaxed mb-12 max-w-md">
                    Track every expense, optimize your budgets, and monitor your investments—all in one beautiful dashboard.
                </p>

                <!-- Features -->
                <div class="space-y-5">
                    @foreach([
                        ['icon' => 'line-chart', 'title' => 'Real-time Analytics', 'desc' => 'Understand your cashflow instantly'],
                        ['icon' => 'target', 'title' => 'Smart Budgeting', 'desc' => 'Set limits and stay on track automatically'],
                        ['icon' => 'shield-check', 'title' => 'Bank-level Security', 'desc' => 'Your financial data is private and secure']
                    ] as $feat)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center backdrop-blur-sm border border-white/10 shrink-0">
                            <x-icon name="{{ $feat['icon'] }}" class="w-5 h-5 text-indigo-300" />
                        </div>
                        <div>
                            <h3 class="font-bold text-white">{{ $feat['title'] }}</h3>
                            <p class="text-sm font-medium text-indigo-200/70 mt-0.5">{{ $feat['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Testimonial placeholder -->
            <div class="mt-12 pt-8 border-t border-white/10 flex items-center gap-4">
                <div class="flex -space-x-2">
                    <img class="w-10 h-10 rounded-full border-2 border-indigo-900" src="https://i.pravatar.cc/100?img=1" alt="Avatar">
                    <img class="w-10 h-10 rounded-full border-2 border-indigo-900" src="https://i.pravatar.cc/100?img=2" alt="Avatar">
                    <img class="w-10 h-10 rounded-full border-2 border-indigo-900" src="https://i.pravatar.cc/100?img=3" alt="Avatar">
                </div>
                <div>
                    <div class="flex text-amber-400">
                        @for($i=0; $i<5; $i++) <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-3 h-3 fill-current"
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
  <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
</svg>
 @endfor
                    </div>
                    <p class="text-xs font-bold text-indigo-200 mt-1">Trusted by 10,000+ users</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Login Form -->
    <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-24 w-full max-w-[600px] mx-auto overflow-y-auto animate-fade-in-up">
        
        <!-- Mobile Logo -->
        <div class="lg:hidden flex items-center gap-3 mb-10 mx-auto">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
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
            <span class="text-2xl font-black text-slate-900 tracking-tight">FinanceTracker</span>
        </div>

        <div class="mb-10 text-center lg:text-left">
            <h2 class="text-3xl font-black text-slate-900">Welcome Back</h2>
            <p class="text-sm font-medium text-slate-500 mt-2">Sign in to your account to continue</p>
        </div>

        @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 flex flex-col gap-1">
            <div class="flex items-center gap-2 text-rose-800">
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
  <circle cx="12" cy="12" r="10" />
  <line x1="12" x2="12" y1="8" y2="12" />
  <line x1="12" x2="12.01" y1="16" y2="16" />
</svg>

                <h3 class="text-sm font-bold">Authentication failed</h3>
            </div>
            <p class="text-sm font-medium text-rose-600 ml-6">{{ $errors->first() }}</p>
        </div>
        @endif

        <form method="POST" action="/login" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2 pl-1">Email <span class="text-rose-500">*</span></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors"
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
  <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
  <rect x="2" y="4" width="20" height="16" rx="2" />
</svg>

                    </div>
                    <input type="email" name="email" required value="{{ old('email') }}" 
                           placeholder="you@example.com"
                           class="block w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none" 
                           autocomplete="email">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2 pl-1 flex justify-between">
                    <span>Password <span class="text-rose-500">*</span></span>
                    <a href="#" class="text-indigo-600 hover:text-indigo-700 capitalize tracking-normal">Forgot?</a>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors"
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
  <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
  <path d="M7 11V7a5 5 0 0 1 10 0v4" />
</svg>

                    </div>
                    <input type="password" name="password" required 
                           placeholder="••••••••" id="pwd"
                           class="block w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none" 
                           autocomplete="current-password">
                    <button type="button" onclick="togglePwd()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
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
  <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
  <circle cx="12" cy="12" r="3" />
</svg>

                    </button>
                </div>
            </div>

            <div class="flex items-center mt-2">
                <input id="remember" type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer">
                <label for="remember" class="ml-2 text-sm font-medium text-slate-600 cursor-pointer">Remember me for 30 days</label>
            </div>

            <button type="submit" 
                    class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold rounded-xl mt-4 shadow-lg shadow-indigo-500/25 transition-all hover:-translate-y-0.5 active:translate-y-0">
                Sign In
            </button>
        </form>


        
    </div>

    <script>
        function togglePwd() {
            const pwd = document.getElementById('pwd');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                pwd.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            }
    </script>
</body>
</html>
