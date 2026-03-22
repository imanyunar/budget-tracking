@extends('layouts.app')

@section('title', 'Wallets')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-700">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-0 gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Your Wallets</h1>
            <p class="text-gray-400 mt-1">Manage and monitor all your storage entities.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="openWalletModal()" class="flex items-center px-5 py-2.5 gradient-bg rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create Wallet
            </button>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($portfolios as $portfolio)
        <div class="glass-card p-8 group relative overflow-hidden transition-all hover:border-blue-500/20 hover:scale-[1.02] cursor-pointer">
            <div class="absolute -right-12 -top-12 opacity-5 scale-150 rotate-12 transition-transform group-hover:rotate-0">
                <i data-lucide="{{ $portfolio->icon ?: 'wallet' }}" class="w-32 h-32" style="color: {{ $portfolio->color }}"></i>
            </div>
            
            <div class="flex items-start justify-between mb-8">
                <div class="p-4 rounded-2xl flex items-center justify-center shadow-lg transition-colors group-hover:shadow-[0_0_20px_rgba(59,130,246,0.1)]" style="background: {{ $portfolio->color }}15; border: 1px solid {{ $portfolio->color }}30">
                    <i data-lucide="{{ $portfolio->icon ?: 'wallet' }}" class="w-8 h-8" style="color: {{ $portfolio->color }}"></i>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 block mb-1">Status</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-green-500/10 text-green-500 border border-green-500/20">Active</span>
                </div>
            </div>
            
            <div>
                <h3 class="text-xl font-bold group-hover:text-blue-400 transition-colors">{{ $portfolio->name }}</h3>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter mt-1">{{ $portfolio->currency }} Asset Storage</p>
                <div class="mt-6 flex flex-col">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Assets</span>
                    <span class="text-3xl font-black">IDR {{ number_format($portfolio->balance, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center group-hover:rotate-12 transition-all">
                        <i data-lucide="trending-up" class="w-3 h-3 text-white"></i>
                    </div>
                    <span class="text-[10px] font-bold text-green-400">+2.4%</span>
                </div>
                <button class="text-[10px] font-black uppercase tracking-widest text-blue-500 hover:text-blue-400 transition-colors flex items-center">
                    Analyze Details <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                </button>
            </div>
        </div>
        @endforeach

        <!-- Quick Add Card -->
        <div onclick="openWalletModal()" class="border-2 border-dashed border-white/5 rounded-3xl p-8 flex flex-col items-center justify-center text-gray-600 hover:border-blue-500/20 hover:text-blue-400 cursor-pointer transition-all group">
            <div class="w-12 h-12 rounded-full border border-current flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="plus" class="w-6 h-6"></i>
            </div>
            <span class="text-xs font-black uppercase tracking-widest">Add New Storage</span>
        </div>
    </div>
</div>

<!-- Modal for New Wallet -->
<div id="walletModal" class="fixed inset-0 z-[200] hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md transition-opacity" onclick="closeWalletModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="glass-card bg-gray-900 border-white/10 p-8 shadow-2xl relative animate-in zoom-in duration-300">
            <button onclick="closeWalletModal()" class="absolute top-6 right-6 p-2 hover:bg-white/5 rounded-full transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
            <h2 class="text-2xl font-bold mb-1">Create Wallet</h2>
            <p class="text-sm text-gray-500 mb-8">Define a new financial storage entity.</p>
            
            <form action="{{ route('wallets.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Wallet Name</label>
                    <input type="text" name="name" required placeholder="e.g. Personal Bank, Crypto Wallet" class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-4 py-3.5 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Initial Balance (IDR)</label>
                    <input type="number" name="balance" required placeholder="0.00" class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-4 py-3.5 font-bold focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <button type="submit" class="w-full py-4 gradient-bg rounded-xl font-bold mt-4 shadow-lg shadow-blue-500/20 active:scale-95 transition-all">Initialize Wallet</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openWalletModal() { document.getElementById('walletModal').classList.remove('hidden'); }
    function closeWalletModal() { document.getElementById('walletModal').classList.add('hidden'); }
</script>
@endsection
