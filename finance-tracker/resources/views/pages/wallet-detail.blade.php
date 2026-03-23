@extends('layouts.app')

@section('title', $portfolio->name . ' Details - FinanceTracker')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('wallets.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:bg-slate-50 transition-colors">
                <x-icon name="arrow-left" class="w-5 h-5" />
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $portfolio->name }}</h1>
                <p class="text-sm text-slate-500 font-medium">Wallet transaction history</p>
            </div>
        </div>
        <div class="bg-emerald-50 border border-emerald-100 px-5 py-2.5 rounded-xl">
            <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 mb-0.5">Current Balance</p>
            <h3 class="text-xl font-black text-emerald-700">IDR {{ number_format($portfolio->balance, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">History</h3>
        </div>

        @if($transactions->isEmpty())
            <div class="p-8 text-center text-slate-500 text-sm font-medium">No transactions found in this wallet.</div>
        @else
            <!-- Mobile View (Cards) -->
            <div class="block md:hidden divide-y divide-slate-100">
                @foreach($transactions as $tx)
                <div class="p-4 flex justify-between items-start gap-4 hover:bg-slate-50">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background-color: {{ $tx->category->color }}15; color: {{ $tx->category->color }}">
                            <x-icon name="{{ $tx->category->icon }}" class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-900 leading-tight">{{ $tx->description ?: $tx->category->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">{{ $tx->date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-black {{ $tx->type === 'income' ? 'text-emerald-500' : 'text-slate-900' }}">
                            {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                        </p>
                        <form action="{{ route('transactions.destroy', $tx) }}" method="POST" onsubmit="return confirm('Delete this transaction? This will impact your wallet balance.')" class="mt-1 inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[10px] font-bold text-rose-500 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop View (Table) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white">
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Date</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Category</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Description</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-right">Amount</th>
                            <th class="px-6 py-4 border-b border-slate-100"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $tx)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 text-sm font-bold text-slate-600">{{ $tx->date->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded flex items-center justify-center shrink-0" style="background-color: {{ $tx->category->color }}15; color: {{ $tx->category->color }}">
                                        <x-icon name="{{ $tx->category->icon }}" class="w-3 h-3" />
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">{{ $tx->category->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $tx->description ?: '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-black {{ $tx->type === 'income' ? 'text-emerald-500' : 'text-slate-900' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('transactions.destroy', $tx) }}" method="POST" onsubmit="return confirm('Delete this transaction? This will impact your wallet balance.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg opacity-0 group-hover:opacity-100 transition-all">
                                        <x-icon name="trash-2" class="w-4 h-4" />
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
