@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-700">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-0 gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Financial Ledger</h1>
            <p class="text-gray-400 mt-1">Manage and track your every movement.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="openModal()" class="flex items-center px-5 py-2.5 gradient-bg rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> New Entry
            </button>
        </div>
    </header>

    <div class="glass-card overflow-hidden">
        <form method="GET" action="{{ route('transactions.index') }}" class="p-6 flex flex-col md:flex-row md:items-center justify-between border-b border-white/5 gap-4">
            <div class="flex items-center space-x-4 flex-1">
                <div class="relative w-full md:w-96">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by description..." class="w-full bg-gray-800/50 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                </div>
                <div class="flex items-center space-x-2">
                    <select name="category_id" onchange="this.form.submit()" class="bg-gray-800/50 border border-white/10 rounded-xl px-3 py-2.5 text-xs text-gray-300 outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="type" onchange="this.form.submit()" class="bg-gray-800/50 border border-white/10 rounded-xl px-3 py-2.5 text-xs text-gray-300 outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('transactions.index') }}" class="p-2.5 bg-gray-800/50 border border-white/10 rounded-xl hover:bg-white/5 transition-colors text-gray-400" title="Clear Filters">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </a>
                <a href="{{ route('transactions.export') }}" class="flex items-center px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-xs font-bold hover:bg-white/10 transition-colors">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i> Export
                </a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="text-[10px] text-gray-500 uppercase font-black tracking-widest border-b border-white/5 bg-white/[0.02]">
                    <tr>
                        <th class="px-6 py-4">Transaction</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Wallet</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-white/5 transition-all group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-gray-800/80 border border-white/5 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i data-lucide="{{ $tx->type === 'income' ? 'arrow-down-left' : 'arrow-up-right' }}" class="w-4 h-4 {{ $tx->type === 'income' ? 'text-green-500' : 'text-red-500' }}"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold">{{ $tx->description }}</div>
                                    <div class="flex items-center mt-0.5 text-[10px]">
                                        <div class="w-2 h-2 rounded-full mr-2 opacity-70" style="background-color: {{ $tx->category->color ?? '#555' }}"></div>
                                        <span class="text-gray-500 font-bold uppercase tracking-tighter">{{ $tx->category->name ?? 'Uncategorized' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $tx->type === 'income' ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                {{ $tx->type === 'income' ? 'Received' : 'Paid' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold">
                            <div class="flex items-center">
                                <i data-lucide="{{ $tx->portfolio->icon ?? 'wallet' }}" class="w-3 h-3 mr-2 opacity-50"></i>
                                {{ $tx->portfolio->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-gray-400">
                            {{ $tx->date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black {{ $tx->type === 'income' ? 'text-green-500' : 'text-primary' }}">
                                {{ $tx->type === 'income' ? '+' : '-' }} {{ number_format($tx->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 hover:bg-red-500/10 rounded-lg text-gray-600 hover:text-red-500 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">No transactions found matching your filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="p-6 border-t border-white/5 flex items-center justify-between">
            <div class="text-xs text-gray-500 font-bold uppercase tracking-widest">
                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
            </div>
            <div class="flex space-x-2">
                {{ $transactions->links('vendor.pagination.tailwind-simple') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal for New Transaction -->
<div id="transactionModal" class="fixed inset-0 z-[200] hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="glass-card bg-gray-900 border-white/10 p-8 shadow-2xl relative animate-in zoom-in duration-300">
            <button onclick="closeModal()" class="absolute top-6 right-6 p-2 hover:bg-white/5 rounded-full transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
            <h2 class="text-2xl font-bold mb-1">New Entry</h2>
            <p class="text-sm text-gray-500 mb-8">Record your financial activity.</p>
            
            <form action="{{ route('transactions.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="flex gap-4 p-1 bg-gray-800/50 rounded-xl border border-white/5">
                    <label class="flex-1">
                        <input type="radio" name="type" value="expense" class="hidden peer" checked>
                        <div class="py-3 rounded-lg text-xs font-bold text-center transition-all cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white text-gray-400">Expense</div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="type" value="income" class="hidden peer">
                        <div class="py-3 rounded-lg text-xs font-bold text-center transition-all cursor-pointer peer-checked:bg-green-600 peer-checked:text-white text-gray-400">Income</div>
                    </label>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Description</label>
                    <input type="text" name="description" required placeholder="e.g. Starbucks, Salary" class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-4 py-3.5 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Amount</label>
                        <input type="number" name="amount" required placeholder="0.00" class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-4 py-3.5 font-bold focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Date</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-4 py-3.5 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Category</label>
                        <select name="category_id" required class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-3 py-3.5 text-xs text-gray-300 outline-none focus:ring-1 focus:ring-blue-500">
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Wallet</label>
                        <select name="portfolio_id" required class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-3 py-3.5 text-xs text-gray-300 outline-none focus:ring-1 focus:ring-blue-500">
                            @foreach($portfolios as $wf)
                            <option value="{{ $wf->id }}">{{ $wf->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 gradient-bg rounded-xl font-bold mt-4 shadow-lg shadow-blue-500/20 active:scale-95 transition-all">Record Transaction</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal() { document.getElementById('transactionModal').classList.remove('hidden'); }
    function closeModal() { document.getElementById('transactionModal').classList.add('hidden'); }
</script>
@endsection
