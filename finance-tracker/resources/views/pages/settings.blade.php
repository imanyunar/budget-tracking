@extends('layouts.app')

@section('title', 'Settings - FinanceTracker')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">System Settings</h1>
            <p class="text-sm text-slate-500 font-medium">Configure your personal preferences and system settings.</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                <i data-lucide="save" class="w-4 h-4"></i> Save Config
            </button>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Account Profile -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-10 flex flex-col md:flex-row items-center gap-6 md:gap-10">
            <div class="relative group shrink-0">
                <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-3xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name ?? 'Felix' }}" alt="Profile" class="w-full h-full object-cover">
                </div>
                <button class="absolute -bottom-3 -right-3 p-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-colors border border-indigo-400">
                    <i data-lucide="camera" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </button>
            </div>
            
            <div class="flex-1 text-center md:text-left w-full">
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ Auth::user()->name ?? 'Demo User' }}</h3>
                <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest mb-6">Premium Membership Status</p>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 bg-slate-50 border border-slate-100 rounded-2xl p-4 sm:p-5">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Total Savings</span>
                        <span class="text-lg font-black text-emerald-600">IDR 26,5M</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Stock Equity</span>
                        <span class="text-lg font-black text-indigo-600">IDR 50,0M</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Config -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-8">
                <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Personal Identity</h3>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Display Username</label>
                        <input type="text" value="{{ Auth::user()->name ?? 'Demo User' }}" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-bold text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Professional Email</label>
                        <input type="email" value="{{ Auth::user()->email ?? 'demo@example.com' }}" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-bold text-slate-800" readonly disabled>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-8">
                <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Alert Strategy</h3>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">Daily Budget Report</span>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Push Notifications</span>
                        </div>
                        <div class="w-12 h-6 bg-indigo-600 rounded-full relative flex items-center px-1 shadow-inner cursor-pointer hover:bg-indigo-500 transition-colors">
                            <div class="w-4 h-4 bg-white rounded-full shadow translate-x-6"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">Stock Market Alerts</span>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Critical Priority ONLY</span>
                        </div>
                        <div class="w-12 h-6 bg-slate-200 rounded-full relative flex items-center px-1 shadow-inner cursor-pointer hover:bg-slate-300 transition-colors">
                            <div class="w-4 h-4 bg-white rounded-full shadow translate-x-0"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">Multi-Currency Export</span>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Spreadsheet Compatible</span>
                        </div>
                        <div class="w-12 h-6 bg-indigo-600 rounded-full relative flex items-center px-1 shadow-inner cursor-pointer hover:bg-indigo-500 transition-colors">
                            <div class="w-4 h-4 bg-white rounded-full shadow translate-x-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-rose-50 rounded-2xl border border-rose-200 p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-bold text-rose-700">System Destruction</h3>
                    <p class="text-xs text-rose-600/80 mt-1 font-medium">This will permanently delete all your financial records and ledger history.</p>
                </div>
                <form action="{{ route('settings.clear') }}" method="POST" onsubmit="return confirm('WARNING: This will delete ALL transactions and reset balances. Proceed?')">
                    @csrf
                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-white hover:bg-rose-100 border border-rose-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-rose-600 transition-colors shadow-sm">
                        Clear Financial Workspace
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
