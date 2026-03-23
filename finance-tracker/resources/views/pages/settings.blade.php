@extends('layouts.app')

@section('title', 'Settings - FinanceTracker')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">System Settings</h1>
                <p class="text-sm text-slate-500 font-medium">Configure your personal preferences and system settings.</p>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none flex items-center justify-center gap-2.5 px-6 py-3 bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 text-white text-sm font-black rounded-xl hover:shadow-[0_10px_20px_-10px_rgba(79,70,229,0.5)] transition-all hover:-translate-y-0.5 active:scale-95 group">
                <svg class="w-4 h-4 transition-transform group-hover:rotate-12"
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                  <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                  <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                </svg>
                <span>Save Configuration</span>
            </button>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Account Profile -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-10 flex flex-col md:flex-row items-center gap-6 md:gap-10">
            <div class="relative group shrink-0">
                <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-3xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50">
                    <img id="avatarPreview" src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $user->avatar_seed ?? $user->name }}" alt="Profile" class="w-full h-full object-cover">
                </div>
                <input type="hidden" name="avatar_seed" id="avatarSeed" value="{{ $user->avatar_seed }}">
                <button type="button" onclick="randomizeAvatar()" class="absolute -bottom-3 -right-3 p-3 bg-white text-indigo-600 rounded-2xl shadow-xl shadow-slate-200/50 hover:bg-slate-50 transition-all border border-slate-200 group-hover:scale-110 active:scale-95 group/btn">
                    <svg class="w-5 h-5 transition-transform group-hover/btn:rotate-12"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2.5"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z" />
                      <circle cx="12" cy="13" r="3" />
                    </svg>
                </button>
            </div>
            
            <div class="flex-1 text-center md:text-left w-full">
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ Auth::user()->name }}</h3>
                <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest mb-6">Premium Membership Status</p>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 bg-slate-50 border border-slate-100 rounded-2xl p-4 sm:p-5">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status</span>
                        <span class="text-lg font-black text-emerald-600">Active Account</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Security</span>
                        <span class="text-lg font-black text-indigo-600">Verified</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Config -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-8">
                <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100">
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
  <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
  <circle cx="12" cy="7" r="4" />
</svg>

                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Personal Identity</h3>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Display Username</label>
                        <input type="text" name="name" value="{{ $user->name }}" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-bold text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Professional Email</label>
                        <input type="email" value="{{ Auth::user()->email }}" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-bold text-slate-800" readonly disabled>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-8">
                <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100">
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
  <path d="M10.268 21a2 2 0 0 0 3.464 0" />
  <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
</svg>

                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Alert Strategy</h3>
                </div>
                
                <div class="space-y-6">
                    <label class="flex items-center justify-between group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">Daily Budget Report</span>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Push Notifications</span>
                        </div>
                        <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out rounded-full pointer-events-none">
                            <input type="checkbox" name="daily_report" value="1" {{ ($user->settings['daily_report'] ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 transition-colors duration-200"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-6"></div>
                        </div>
                    </label>
                    <label class="flex items-center justify-between group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">Stock Market Alerts</span>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Critical Priority ONLY</span>
                        </div>
                        <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out rounded-full pointer-events-none">
                            <input type="checkbox" name="stock_alerts" value="1" {{ ($user->settings['stock_alerts'] ?? false) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 transition-colors duration-200"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-6"></div>
                        </div>
                    </label>
                    <label class="flex items-center justify-between group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">Multi-Currency Export</span>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Spreadsheet Compatible</span>
                        </div>
                        <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out rounded-full pointer-events-none">
                            <input type="checkbox" name="spreadsheet_compat" value="1" {{ ($user->settings['spreadsheet_compat'] ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 transition-colors duration-200"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-6"></div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </form>

    <!-- Danger Zone -->
        <div class="bg-rose-50 rounded-2xl border border-rose-200 p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-bold text-rose-700">System Destruction</h3>
                    <p class="text-xs text-rose-600/80 mt-1 font-medium">This will permanently delete all your financial records and ledger history.</p>
                </div>
                <form action="{{ route('settings.clear') }}" method="POST" onsubmit="return confirm('WARNING: This will delete ALL transactions and reset balances. Proceed?')">
                    @csrf
                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-white border-2 border-rose-200 hover:border-rose-400 text-[10px] font-black uppercase tracking-[0.2em] text-rose-600 rounded-xl transition-all hover:bg-rose-50 hover:shadow-md active:scale-95">
                        Clear Financial Workspace
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function randomizeAvatar() {
        const seed = Math.random().toString(36).substring(7);
        document.getElementById('avatarSeed').value = seed;
        document.getElementById('avatarPreview').src = `https://api.dicebear.com/7.x/avataaars/svg?seed=${seed}`;
    }
</script>
@endsection
