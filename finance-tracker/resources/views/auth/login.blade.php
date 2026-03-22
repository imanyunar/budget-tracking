@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="w-full max-w-md animate-in fade-in zoom-in duration-500">
        <div class="glass-card p-10 space-y-8">
            <div class="text-center">
                <h1 class="text-3xl font-black gradient-text mb-2">Welcome Back</h1>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Secure Access to FinanceTracker</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                @if($errors->any())
                <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-bold leading-relaxed">
                    {{ $errors->first() }}
                </div>
                @endif
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Email Address</label>
                    <div class="relative group">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="email" name="email" required value="{{ old('email') }}" class="w-full bg-gray-900/50 border border-white/5 rounded-2xl pl-12 pr-4 py-4 text-sm focus:ring-1 focus:ring-blue-500 outline-none transition-all" placeholder="name@company.com">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Password</label>
                    <div class="relative group">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="password" name="password" required class="w-full bg-gray-900/50 border border-white/5 rounded-2xl pl-12 pr-4 py-4 text-sm focus:ring-1 focus:ring-blue-500 outline-none transition-all" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded bg-gray-800 border-white/10 text-blue-600 focus:ring-0">
                        <label for="remember" class="ml-2 text-xs text-gray-500 font-bold tracking-tight">Remember me</label>
                    </div>
                    <a href="#" class="text-xs text-blue-500 font-bold hover:text-blue-400 transition-colors tracking-tight">Forgot password?</a>
                </div>

                <button type="submit" class="w-full py-4 gradient-bg rounded-2xl font-black text-sm shadow-xl shadow-blue-500/20 active:scale-95 transition-all">
                    Sign In to Portal
                </button>
            </form>

            <div class="text-center pt-4 border-t border-white/5">
                <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest italic opacity-50">
                    Proprietary & Confidential - Authorized Personnel Only
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
