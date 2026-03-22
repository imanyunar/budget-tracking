@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-700">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-0 gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">System Settings</h1>
            <p class="text-gray-400 mt-1">Configure your personal preferences and system settings.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 active:scale-95 transition-all text-white">
                Save System Config
            </button>
        </div>
    </header>

    <div class="space-y-6">
        <!-- Account Profile -->
        <div class="glass-card p-10 flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-10">
            <div class="relative group">
                <div class="w-32 h-32 rounded-3xl overflow-hidden border-4 border-white/5 ring-8 ring-white/[0.02]">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="Profile" class="w-full h-full object-cover">
                </div>
                <button class="absolute -bottom-4 -right-4 p-3 bg-blue-600 text-white rounded-2xl shadow-xl hover:bg-blue-500 transition-colors">
                    <i data-lucide="camera" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-bold text-white mb-1">Demo User</h3>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-4">Silver Membership Status</p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1">Total Savings</span>
                        <span class="text-lg font-black text-blue-500">IDR 26,5M</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1">Stock Equity</span>
                        <span class="text-lg font-black text-purple-500">IDR 50,0M</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Config -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="glass-card p-8 space-y-8">
                <div class="flex items-center space-x-4 border-b border-white/5 pb-4">
                    <div class="p-3 bg-blue-500/10 rounded-2xl">
                        <i data-lucide="user" class="w-6 h-6 text-blue-500"></i>
                    </div>
                    <h3 class="text-lg font-bold">Personal Identity</h3>
                </div>
                <div class="space-y-5">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Display Username</label>
                        <input type="text" value="Demo User" class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-4 py-3.5 font-bold focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Professional Email</label>
                        <input type="email" value="demo@example.com" class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-4 py-3.5 font-bold focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="glass-card p-8 space-y-8">
                <div class="flex items-center space-x-4 border-b border-white/5 pb-4">
                    <div class="p-3 bg-purple-500/10 rounded-2xl">
                        <i data-lucide="bell" class="w-6 h-6 text-purple-500"></i>
                    </div>
                    <h3 class="text-lg font-bold">Alert Strategy</h3>
                </div>
                <div class="space-y-6">
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors">Daily Budget Report</span>
                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-1">Push Notifications</span>
                        </div>
                        <div class="w-12 h-6 bg-blue-600 rounded-full relative flex items-center px-1 shadow-inner shadow-blue-500/20">
                            <div class="w-4 h-4 bg-white rounded-full shadow translate-x-6"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors">Stock Market Alerts</span>
                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-1">Critical Priority ONLY</span>
                        </div>
                        <div class="w-12 h-6 bg-gray-800 rounded-full relative flex items-center px-1 border border-white/5">
                            <div class="w-4 h-4 bg-gray-600 rounded-full shadow"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors">Multi-Currency Export</span>
                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-1">Spreadsheet Compatible</span>
                        </div>
                        <div class="w-12 h-6 bg-blue-600 rounded-full relative flex items-center px-1 shadow-inner shadow-blue-500/20">
                            <div class="w-4 h-4 bg-white rounded-full shadow translate-x-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="glass-card p-8 border-red-500/10 hover:border-red-500/20 transition-all bg-red-500/[0.02]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-bold text-red-500">System Destruction</h3>
                    <p class="text-xs text-gray-500 mt-1">This will permanently delete all your financial records and ledger history.</p>
                </div>
                <form action="{{ route('settings.clear') }}" method="POST" onsubmit="return confirm('WARNING: This will delete ALL transactions and reset balances. Proceed?')">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 rounded-xl text-[10px] font-black uppercase tracking-widest text-red-500 transition-all active:scale-95">
                        Clear Financial Workspace
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
