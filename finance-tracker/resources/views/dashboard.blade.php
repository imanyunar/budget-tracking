@extends('layouts.app')

@section('title', 'Dashboard - FinanceTracker')

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" defer></script>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Financial Overview</h1>
            <p class="text-sm text-slate-500 font-medium">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('transactions.export') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-sm">
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
  <path d="M12 15V3" />
  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
  <path d="m7 10 5 5 5-5" />
</svg>

                Export
            </a>
            <button onclick="openModal()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
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
  <path d="M5 12h14" />
  <path d="M12 5v14" />
</svg>

                New Transaction
            </button>
        </div>
    </div>

    <!-- Budget Alerts -->
    @if(!empty($budgetAlerts))
    <div class="flex flex-col gap-3">
        @foreach($budgetAlerts as $alert)
        @php
            $alertColors = [
                'danger'  => 'bg-rose-50 border-rose-200 text-rose-800 icon-rose',
                'warning' => 'bg-orange-50 border-orange-200 text-orange-800 icon-orange',
                'info'    => 'bg-blue-50 border-blue-200 text-blue-800 icon-blue',
            ][$alert['type']];
        @endphp
        <div class="flex items-center justify-between p-4 rounded-2xl border {{ $alertColors }}">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 
                    {{ $alert['type'] === 'danger' ? 'bg-rose-100' : ($alert['type'] === 'warning' ? 'bg-orange-100' : 'bg-blue-100') }}">
                    <x-icon name="{{ $alert['icon'] }}" class="w-5 h-5 {{ $alert['type'] === 'danger' ? 'text-rose-600' : ($alert['type'] === 'warning' ? 'text-orange-600' : 'text-blue-600') }}" />
                </div>
                <div>
                    <h4 class="text-sm font-bold">{{ $alert['title'] }}</h4>
                    <p class="text-xs font-medium opacity-80 mt-0.5">{{ $alert['message'] }}</p>
                </div>
            </div>
            <a href="{{ $alert['type'] !== 'info' ? route('budgets.index') : route('transactions.index') }}" 
               class="px-4 py-2 bg-white/60 hover:bg-white rounded-lg text-xs font-bold transition-colors whitespace-nowrap">
                {{ $alert['type'] !== 'info' ? 'Adjust' : 'View' }} &rarr;
            </a>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Weekly Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-lg shadow-indigo-500/20">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-48 h-48 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 bg-fuchsia-400/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 text-white w-full md:w-auto text-center md:text-left">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 rounded-full text-[10px] font-bold uppercase tracking-wider mb-3">
                <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-3 h-3"
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
 Weekly Summary
            </div>
            <h2 class="text-2xl sm:text-3xl font-black mb-2">Performance This Week</h2>
            <p class="text-indigo-100 text-sm font-medium">
                Spending is 
                <span class="font-bold {{ $weeklyTrend <= 0 ? 'text-emerald-300' : 'text-rose-300' }}">
                    {{ $weeklyTrend <= 0 ? '↓ down' : '↑ up' }}
                </span>
                {{ number_format(abs($weeklyTrend), 1) }}% compared to last week
            </p>
        </div>

        <div class="relative z-10 flex gap-6 sm:gap-12 text-center">
            <div>
                <p class="text-[10px] uppercase tracking-wider text-indigo-200 font-bold mb-1">Spent</p>
                <p class="text-xl sm:text-2xl font-black text-white">Rp{{ number_format($spentThisWeek, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-wider text-indigo-200 font-bold mb-1">Top Cat</p>
                <p class="text-xl sm:text-2xl font-black text-white">{{ $topCategoryThisWeek->category->name ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Wealth -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center border border-indigo-200">
                        <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-6 h-6 text-indigo-600"
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
  <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1" />
  <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4" />
</svg>

                    </div>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Wealth</p>
                <h3 class="text-2xl font-black text-slate-900">IDR {{ number_format($totalBalance, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Income -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center border border-emerald-200">
                        <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-6 h-6 text-emerald-600"
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
  <path d="M17 7 7 17" />
  <path d="M17 17H7V7" />
</svg>

                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $incomeTrend >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $incomeTrend >= 0 ? '+' : '' }}{{ number_format($incomeTrend, 1) }}%
                    </span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Income ({{ now()->format('M') }})</p>
                <h3 class="text-2xl font-black text-emerald-600">IDR {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Expense -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center border border-rose-200">
                        <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-6 h-6 text-rose-600"
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
  <path d="M7 7h10v10" />
  <path d="M7 17 17 7" />
</svg>

                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $expenseTrend <= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $expenseTrend > 0 ? '+' : '' }}{{ number_format($expenseTrend, 1) }}%
                    </span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Expenses ({{ now()->format('M') }})</p>
                <h3 class="text-2xl font-black text-rose-600">IDR {{ number_format($monthlyExpense, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cashflow -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Cashflow Analytics</h3>
                    <p class="text-xs font-medium text-slate-500">Last 6 months performance</p>
                </div>
                <!-- Legend Custom -->
                <div class="hidden sm:flex items-center gap-4 text-xs font-bold text-slate-600">
                    <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>Income</div>
                    <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div>Expense</div>
                </div>
            </div>
            <div class="h-[260px] relative w-full"><canvas id="cashflowChart"></canvas></div>
        </div>

        <!-- Categories -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="mb-6">
                <h3 class="text-base font-bold text-slate-900">Expense Breakdown</h3>
                <p class="text-xs font-medium text-slate-500">By category</p>
            </div>
            <div class="h-[220px] relative w-full flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Wallets -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-bold text-slate-900">Your Wallets</h3>
                <a href="{{ route('wallets.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">View All</a>
            </div>
            <div class="flex-1 flex flex-col gap-3">
                @foreach($portfolios as $portfolio)
                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $portfolio->color }}15; border: 1px solid {{ $portfolio->color }}30;">
                            <x-icon name="{{ $portfolio->icon ?: 'wallet' }}" class="w-5 h-5" style="color: {{ $portfolio->color }}" />
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $portfolio->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $portfolio->currency }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-900">{{ number_format($portfolio->balance, 0, ',', '.') }}</p>
                        <p class="text-xs font-bold {{ $portfolio->roi >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $portfolio->roi >= 0 ? '+' : '' }}{{ number_format($portfolio->roi, 1) }}%
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Recent Transactions</h3>
                    <p class="text-xs font-medium text-slate-500">Your latest financial activity</p>
                </div>
                <form action="{{ route('transactions.index') }}" method="GET" class="flex gap-2 relative">
                    <!-- @license lucide-static v0.577.0 - ISC -->
<svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"
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
  <path d="m21 21-4.34-4.34" />
  <circle cx="11" cy="11" r="8" />
</svg>

                    <input type="text" name="search" placeholder="Search..." class="pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all w-full sm:w-48">
                </form>
            </div>
            
            <!-- Mobile View (Cards) -->
            <div class="block md:hidden divide-y divide-slate-100">
                @if($recentTransactions->isEmpty())
                    <div class="p-6 text-center text-slate-500 text-sm font-medium">No recent transactions.</div>
                @else
                    @foreach($recentTransactions as $tx)
                    <div class="p-4 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border {{ $tx->type === 'income' ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100' }}">
                                    <x-icon name="{{ $tx->type === 'income' ? 'arrow-down-left' : 'arrow-up-right' }}" class="w-5 h-5 {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $tx->description }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $tx->category->color ?? '#cbd5e1' }}"></span>
                                        <span class="text-xs font-medium text-slate-500">{{ $tx->category->name ?? 'Uncategorized' }} &bull; {{ $tx->date->format('d M') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-sm {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            <!-- Desktop View (Table) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Transaction</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-center">Type</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-right">Date</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-right">Amount (IDR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentTransactions as $tx)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border 
                                        {{ $tx->type === 'income' ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100' }}">
                                        <x-icon name="{{ $tx->type === 'income' ? 'arrow-down-left' : 'arrow-up-right' }}" class="w-5 h-5 {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}" />
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
                                    {{ $tx->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xs font-medium text-slate-500">{{ $tx->date->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-black text-sm {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-center mt-auto">
                <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                    View All Activity &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="txModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-3xl w-full max-w-md p-6 sm:p-8 relative z-10 shadow-2xl shadow-indigo-500/10 border border-slate-100 transform scale-95 opacity-0 transition-all duration-200" id="txModalContent">
        <button onclick="closeModal()" class="absolute top-6 right-6 p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-xl transition-colors">
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
  <path d="M18 6 6 18" />
  <path d="m6 6 12 12" />
</svg>

        </button>

        <div class="mb-6">
            <h2 class="text-xl font-black text-slate-900">New Transaction</h2>
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
                <input type="text" name="description" required placeholder="e.g. Lunch, Salary" 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Amount (IDR)</label>
                    <input type="number" name="amount" required placeholder="0" 
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
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
                    <select name="category_id" required 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none text-slate-700">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Wallet</label>
                    <select name="portfolio_id" required 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none text-slate-700">
                        @foreach($allPortfolios as $wf)
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
    // Modal Logic
    const modal = document.getElementById('txModal');
    const modalContent = document.getElementById('txModalContent');
    
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

    // Initialize Charts when Chart.js is loaded
    function initCharts() {
        if (typeof Chart === 'undefined') {
            setTimeout(initCharts, 100); return;
        }

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#94a3b8';

        // Cashflow Chart
        const ctxCf = document.getElementById('cashflowChart').getContext('2d');
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const data = @json($cashflowData);
        
        const gradInc = ctxCf.createLinearGradient(0,0,0,250);
        gradInc.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
        gradInc.addColorStop(1, 'rgba(16, 185, 129, 0)');
        
        const gradExp = ctxCf.createLinearGradient(0,0,0,250);
        gradExp.addColorStop(0, 'rgba(244, 63, 94, 0.2)');
        gradExp.addColorStop(1, 'rgba(244, 63, 94, 0)');

        new Chart(ctxCf, {
            type: 'line',
            data: {
                labels: data.map(d => months[d.month-1]),
                datasets: [
                    {
                        label: 'Income', data: data.map(d => d.income),
                        borderColor: '#10b981', backgroundColor: gradInc,
                        borderWidth: 3, tension: 0.4, fill: true,
                        pointBackgroundColor: '#10b981', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4
                    },
                    {
                        label: 'Expense', data: data.map(d => d.expense),
                        borderColor: '#f43f5e', backgroundColor: gradExp,
                        borderWidth: 3, tension: 0.4, fill: true,
                        pointBackgroundColor: '#f43f5e', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.95)', titleColor: '#0f172a', bodyColor: '#475569',
                        borderColor: '#e2e8f0', borderWidth: 1, padding: 12, boxPadding: 6,
                        callbacks: { label: c => c.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(c.parsed.y) }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { weight: 600, size: 10 } } },
                    y: { 
                        grid: { borderDash: [4,4], color: '#f1f5f9' }, 
                        border: { display: false },
                        ticks: { font: { weight: 600, size: 10 }, callback: v => 'Rp ' + (v/1e6).toFixed(0) + 'M' }
                    }
                }
            }
        });

        // Category Donut
        const catData = @json($categoryBreakdown);
        new Chart(document.getElementById('categoryChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: catData.map(c => c.name),
                datasets: [{
                    data: catData.map(c => c.total),
                    backgroundColor: catData.map(c => c.color),
                    borderWidth: 3, borderColor: '#fff', hoverOffset: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.95)', titleColor: '#0f172a', bodyColor: '#475569',
                        borderColor: '#e2e8f0', borderWidth: 1, padding: 12,
                        callbacks: {
                            label: c => {
                                const total = c.dataset.data.reduce((a,b) => a+b, 0);
                                return ' ' + c.label + ': ' + Math.round(c.parsed/total*100) + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    document.addEventListener("DOMContentLoaded", initCharts);
</script>
@endpush
@endsection
