@extends('layouts.app')

@section('title', 'Transactions - FinanceTracker')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Financial Ledger</h1>
            <p class="text-sm text-slate-500 font-medium">Manage and track your every movement.</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button onclick="openModal()" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                <i data-lucide="plus" class="w-4 h-4"></i> New Entry
            </button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        
        <!-- Filters -->
        <form method="GET" action="{{ route('transactions.index') }}" class="p-5 border-b border-slate-100 flex flex-col lg:flex-row items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-1">
                <!-- Search -->
                <div class="relative w-full sm:w-80">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by description..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                </div>
                
                <!-- Category Filter -->
                <select name="category_id" onchange="this.form.submit()" 
                        class="w-full sm:w-auto px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <!-- Type Filter -->
                <select name="type" onchange="this.form.submit()" 
                        class="w-full sm:w-auto px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="">All Types</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 w-full lg:w-auto">
                <a href="{{ route('transactions.index') }}" class="p-2.5 w-full sm:w-auto flex justify-center bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-slate-500" title="Clear Filters">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </a>
                <a href="{{ route('transactions.export') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i> Export
                </a>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white">
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Transaction</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Wallet</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Date</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-right">Amount</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border
                                    {{ $tx->type === 'income' ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100' }}">
                                    <i data-lucide="{{ $tx->type === 'income' ? 'arrow-down-left' : 'arrow-up-right' }}" 
                                       class="w-5 h-5 {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $tx->description }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="w-2 h-2 rounded-full" style="background-color: {{ $tx->category->color ?? '#cbd5e1' }}"></span>
                                        <span class="text-xs font-medium text-slate-500">{{ $tx->category->name ?? 'Uncategorized' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                {{ $tx->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $tx->type === 'income' ? 'Received' : 'Paid' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                                <i data-lucide="{{ $tx->portfolio->icon ?? 'wallet' }}" class="w-4 h-4 text-slate-400"></i>
                                {{ $tx->portfolio->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-slate-500">{{ $tx->date->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-sm {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $tx->type === 'income' ? '+' : '-' }} {{ number_format($tx->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm font-medium">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i data-lucide="search-x" class="w-6 h-6"></i>
                                </div>
                                <p>No transactions found matching your filters.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="p-4 sm:p-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/50 mt-auto">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest text-center sm:text-left">
                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
            </div>
            <div>
                {{ $transactions->links('vendor.pagination.tailwind-simple') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal for New Transaction -->
<div id="transactionModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal()"></div>
    
    <div class="bg-white rounded-3xl w-full max-w-md p-6 sm:p-8 relative z-10 shadow-2xl shadow-indigo-500/10 border border-slate-100 transform scale-95 opacity-0 transition-all duration-200" id="transactionModalContent">
        <button onclick="closeModal()" class="absolute top-6 right-6 p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-xl transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="mb-6">
            <h2 class="text-xl font-black text-slate-900">New Entry</h2>
            <p class="text-sm font-medium text-slate-500 mt-0.5">Record your financial activity</p>
        </div>
        
        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="type" id="tx_type" value="expense">

            <!-- Type Selector -->
            <div class="flex p-1 bg-slate-100 rounded-2xl">
                <button type="button" onclick="setTxType('expense')" id="btn-tx-expense" class="flex-1 py-2.5 rounded-xl text-sm font-bold bg-white text-rose-600 shadow-sm transition-all">
                    Expense
                </button>
                <button type="button" onclick="setTxType('income')" id="btn-tx-income" class="flex-1 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-700 transition-all">
                    Income
                </button>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Description</label>
                <input type="text" name="description" required placeholder="e.g. Starbucks, Salary" 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Amount</label>
                    <input type="number" name="amount" required placeholder="0.00" 
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Date</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" 
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none text-slate-700">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Category</label>
                    <select name="category_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none text-slate-700">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Wallet</label>
                    <select name="portfolio_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none text-slate-700">
                        @foreach($portfolios as $wf)
                        <option value="{{ $wf->id }}">{{ $wf->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl mt-2 hover:shadow-lg hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                Record Transaction
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('transactionModal');
    const modalContent = document.getElementById('transactionModalContent');
    
    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    function setTxType(type) {
        document.getElementById('tx_type').value = type;
        const btnExp = document.getElementById('btn-tx-expense');
        const btnInc = document.getElementById('btn-tx-income');
        
        if (type === 'expense') {
            btnExp.className = 'flex-1 py-2.5 rounded-xl text-sm font-bold bg-white text-rose-600 shadow-sm transition-all';
            btnInc.className = 'flex-1 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-700 transition-all';
        } else {
            btnExp.className = 'flex-1 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-700 transition-all';
            btnInc.className = 'flex-1 py-2.5 rounded-xl text-sm font-bold bg-white text-emerald-600 shadow-sm transition-all';
        }
    }
</script>
@endpush
@endsection
