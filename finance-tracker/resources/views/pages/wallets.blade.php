@extends('layouts.app')

@section('title', 'Wallets - FinanceTracker')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Your Wallets</h1>
            <p class="text-sm text-slate-500 font-medium">Manage and monitor all your storage entities.</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button onclick="openWalletModal()" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                <x-icon name="plus" class="w-4 h-4" /> Create Wallet
            </button>
        </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-indigo-900 rounded-2xl p-6 text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-indigo-800 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-300 mb-1">Total All Assets</p>
                <h3 class="text-3xl font-black mb-2">IDR {{ number_format($totalAssets, 0, ',', '.') }}</h3>
                <p class="text-xs text-indigo-200">Combined value of wallets and investments.</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 text-slate-900 border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-emerald-50 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Total Wallet Balance</p>
                <h3 class="text-3xl font-black text-emerald-600 mb-2">IDR {{ number_format($totalWallet, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500">Liquid cash and bank balances.</p>
            </div>
        </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($portfolios as $portfolio)
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden flex flex-col">
            
            <!-- Decoration -->
            <div class="absolute -right-12 -top-12 opacity-[0.03] scale-150 rotate-12 transition-transform duration-500 group-hover:rotate-0 group-hover:scale-[1.6]">
                <x-icon name="{{ $portfolio->icon ?: 'wallet' }}" class="w-48 h-48" style="color: {{ $portfolio->color }}" />
            </div>
            
            <div class="relative z-10 flex items-start justify-between mb-8">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-colors shadow-sm" style="background: {{ $portfolio->color }}15; border: 1px solid {{ $portfolio->color }}30">
                    <x-icon name="{{ $portfolio->icon ?: 'wallet' }}" class="w-7 h-7" style="color: {{ $portfolio->color }}" />
                </div>
                <div class="text-right">
                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100">
                        Active
                    </span>
                </div>
            </div>
            
            <div class="relative z-10 flex-1">
                <h3 class="text-xl font-bold text-slate-800">{{ $portfolio->name }}</h3>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">{{ $portfolio->currency }} Asset Storage</p>
                <div class="mt-6 flex flex-col">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Assets (IDR)</span>
                    <span class="text-3xl font-black text-slate-900">{{ number_format($portfolio->balance, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="relative z-10 mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                <form action="{{ route('wallets.destroy', $portfolio->id) }}" method="POST" onsubmit="return confirm('WARNING: You can only delete this wallet if there are no transactions linked to it. Proceed?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 transition-colors flex items-center gap-1 p-1 hover:bg-rose-50 rounded">
                        <x-icon name="trash-2" class="w-4 h-4" /> Delete
                    </button>
                </form>
                <div class="flex items-center gap-2">
                    <button onclick="openEditModal({{ $portfolio->id }}, '{{ addslashes($portfolio->name) }}', {{ $portfolio->balance }})"
                        class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors flex items-center gap-1 bg-slate-50 px-3 py-1.5 rounded-lg hover:bg-slate-100">
                        <x-icon name="pencil" class="w-3.5 h-3.5" /> Edit
                    </button>
                    <a href="{{ route('wallets.show', $portfolio->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100">
                        Detail <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Quick Add Card -->
        <div onclick="openWalletModal()" class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl p-8 flex flex-col items-center justify-center text-slate-500 hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50/50 cursor-pointer transition-all group min-h-[300px]">
            <div class="w-14 h-14 rounded-full border-2 border-current flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <x-icon name="plus" class="w-6 h-6" />
            </div>
            <span class="text-sm font-bold">Add New Storage</span>
        </div>
    </div>
</div>

<!-- Modal: Create Wallet -->
<div id="walletModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeWalletModal()"></div>
    
    <div class="bg-white rounded-3xl w-full max-w-md p-6 sm:p-8 relative z-10 shadow-2xl shadow-indigo-500/10 border border-slate-100 transform scale-95 opacity-0 transition-all duration-200" id="walletModalContent">
        <button onclick="closeWalletModal()" class="absolute top-6 right-6 p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-xl transition-colors">
            <x-icon name="x" class="w-5 h-5" />
        </button>

        <div class="mb-6">
            <h2 class="text-xl font-black text-slate-900">Create Wallet</h2>
            <p class="text-sm font-medium text-slate-500 mt-0.5">Define a new financial storage entity</p>
        </div>
        
        <form action="{{ route('wallets.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Wallet Name</label>
                <input type="text" name="name" required placeholder="e.g. Personal Bank, Crypto Wallet" 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Initial Balance (IDR)</label>
                <input type="number" name="balance" required placeholder="0" 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-bold">
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl mt-2 hover:shadow-lg hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                Initialize Wallet
            </button>
        </form>
    </div>
</div>

<!-- Modal: Edit Wallet -->
<div id="editWalletModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    
    <div class="bg-white rounded-3xl w-full max-w-md p-6 sm:p-8 relative z-10 shadow-2xl shadow-indigo-500/10 border border-slate-100 transform scale-95 opacity-0 transition-all duration-200" id="editWalletModalContent">
        <button onclick="closeEditModal()" class="absolute top-6 right-6 p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-xl transition-colors">
            <x-icon name="x" class="w-5 h-5" />
        </button>

        <div class="mb-6">
            <h2 class="text-xl font-black text-slate-900">Edit Wallet</h2>
            <p class="text-sm font-medium text-slate-500 mt-0.5">Update wallet name or correct the balance</p>
        </div>
        
        <form id="editWalletForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Wallet Name</label>
                <input type="text" name="name" id="editWalletName" required
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Balance (IDR)</label>
                <input type="number" name="balance" id="editWalletBalance" required
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-bold">
                <p class="text-xs text-amber-600 mt-1.5 ml-1">⚠️ Mengubah balance langsung tidak mempengaruhi riwayat transaksi.</p>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-slate-700 to-slate-900 text-white font-bold rounded-xl mt-2 hover:shadow-lg transition-all hover:-translate-y-0.5">
                Save Changes
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // --- Create Wallet Modal ---
    const wModal = document.getElementById('walletModal');
    const wModalContent = document.getElementById('walletModalContent');
    
    function openWalletModal() {
        wModal.classList.remove('hidden');
        wModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            wModalContent.classList.remove('scale-95', 'opacity-0');
            wModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeWalletModal() {
        wModalContent.classList.remove('scale-100', 'opacity-100');
        wModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            wModal.classList.add('hidden');
            wModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    // --- Edit Wallet Modal ---
    const eModal = document.getElementById('editWalletModal');
    const eModalContent = document.getElementById('editWalletModalContent');

    function openEditModal(id, name, balance) {
        document.getElementById('editWalletForm').action = `/wallets/${id}`;
        document.getElementById('editWalletName').value = name;
        document.getElementById('editWalletBalance').value = balance;

        eModal.classList.remove('hidden');
        eModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            eModalContent.classList.remove('scale-95', 'opacity-0');
            eModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeEditModal() {
        eModalContent.classList.remove('scale-100', 'opacity-100');
        eModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            eModal.classList.add('hidden');
            eModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }
</script>
@endpush
@endsection
