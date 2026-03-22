@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in duration-700">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-0 gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Overview Dashboard</h1>
            <p class="text-gray-400 mt-1 flex items-center">
                <i data-lucide="shield-check" class="w-4 h-4 mr-2 text-blue-500"></i>
                Your financial security is our priority.
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('transactions.export') }}" class="flex items-center px-4 py-2.5 glass-card hover:bg-white/5 text-sm font-semibold transition-all">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i> Export Report
            </a>
            <button onclick="openModal()" class="flex items-center px-5 py-2.5 gradient-bg rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> New Transaction
            </button>
        </div>
    </header>

    @if(!empty($budgetAlerts))
    <div class="space-y-4">
        @foreach($budgetAlerts as $alert)
        <div class="p-5 glass-card {{ $alert['type'] === 'danger' ? 'bg-red-500/10 border-red-500/20 text-red-500' : ($alert['type'] === 'warning' ? 'bg-yellow-500/10 border-yellow-500/20 text-yellow-500' : 'bg-blue-500/10 border-blue-500/20 text-blue-400') }} flex items-center shadow-xl animate-in slide-in-from-right-4 duration-500">
            <div class="p-3 rounded-2xl {{ $alert['type'] === 'danger' ? 'bg-red-500/20' : ($alert['type'] === 'warning' ? 'bg-yellow-500/20' : 'bg-blue-500/20') }} mr-4">
                <i data-lucide="{{ $alert['icon'] }}" class="w-6 h-6"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-black uppercase tracking-widest">{{ $alert['title'] }}</h4>
                <p class="text-xs font-bold opacity-80 mt-1">{{ $alert['message'] }}</p>
            </div>
            @if($alert['type'] !== 'info')
            <a href="{{ route('budgets.index') }}" class="px-4 py-2 rounded-xl bg-white/5 border border-current text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                Adjust Budget
            </a>
            @else
            <a href="{{ route('transactions.index') }}" class="px-4 py-2 rounded-xl bg-white/5 border border-current text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                View Transaction
            </a>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Weekly Performance Report -->
    <div class="glass-card p-8 border-blue-500/10 bg-blue-500/[0.02] relative overflow-hidden">
        <div class="absolute top-0 right-0 p-10 opacity-10">
            <i data-lucide="bar-chart-3" class="w-32 h-32 text-blue-500"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex-1 space-y-2">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-500 text-[10px] font-black uppercase tracking-widest mb-2">
                    Weekly Summary
                </div>
                <h3 class="text-2xl font-black">Performance This Week</h3>
                <p class="text-xs text-gray-500 max-w-md">Your spending behavior is <span class="{{ $weeklyTrend <= 0 ? 'text-green-500' : 'text-red-500' }} font-bold">{{ $weeklyTrend <= 0 ? 'lower' : 'higher' }}</span> by {{ number_format(abs($weeklyTrend), 1) }}% compared to last week.</p>
            </div>
            
            <div class="grid grid-cols-2 gap-8 w-full md:w-auto">
                <div class="space-y-1">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Spent This Week</span>
                    <div class="text-xl font-black">IDR {{ number_format($spentThisWeek, 0, ',', '.') }}</div>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Top Category</span>
                    <div class="text-xl font-black text-blue-400">{{ $topCategoryThisWeek->category->name ?? 'None' }}</div>
                </div>
            </div>

            <div class="w-full md:w-auto">
                <a href="{{ route('transactions.index') }}" class="block text-center px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-white/10 hover:border-blue-500/30 transition-all">
                    Full History <i data-lucide="arrow-right" class="w-3 h-3 ml-2 inline"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Balance -->
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 bg-blue-500/10 w-24 h-24 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Wealth</span>
                    <h2 class="text-3xl font-extrabold mt-1">IDR {{ number_format($totalBalance, 0, ',', '.') }}</h2>
                    <p class="text-xs text-green-400 font-bold mt-2 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +8.2% <span class="text-gray-500 font-normal ml-1">vs last month</span>
                    </p>
                </div>
                <div class="p-3 bg-blue-600/10 rounded-2xl border border-blue-500/20 group-hover:scale-110 transition-transform">
                    <i data-lucide="wallet" class="w-6 h-6 text-blue-500"></i>
                </div>
            </div>
        </div>

        <!-- Monthly Income -->
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 bg-green-500/10 w-24 h-24 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Income ({{ now()->format('M') }})</span>
                    <h2 class="text-3xl font-extrabold mt-1 text-green-500">IDR {{ number_format($monthlyIncome, 0, ',', '.') }}</h2>
                    <p class="text-xs {{ $incomeTrend >= 0 ? 'text-green-400' : 'text-red-400' }} font-bold mt-2 flex items-center">
                        <i data-lucide="{{ $incomeTrend >= 0 ? 'arrow-up' : 'arrow-down' }}" class="w-3 h-3 mr-1"></i> 
                        {{ number_format(abs($incomeTrend), 1) }}% <span class="text-gray-500 font-normal ml-1">from last month</span>
                    </p>
                </div>
                <div class="p-3 bg-green-600/10 rounded-2xl border border-green-500/20 group-hover:scale-110 transition-transform">
                    <i data-lucide="arrow-up-circle" class="w-6 h-6 text-green-500"></i>
                </div>
            </div>
        </div>

        <!-- Monthly Expense -->
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 bg-red-500/10 w-24 h-24 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Expenses ({{ now()->format('M') }})</span>
                    <h2 class="text-3xl font-extrabold mt-1 text-red-500">IDR {{ number_format($monthlyExpense, 0, ',', '.') }}</h2>
                    <p class="text-xs {{ $expenseTrend <= 0 ? 'text-green-400' : 'text-red-400' }} font-bold mt-2 flex items-center">
                        <i data-lucide="{{ $expenseTrend <= 0 ? 'arrow-down' : 'arrow-up' }}" class="w-3 h-3 mr-1"></i> 
                        {{ number_format(abs($expenseTrend), 1) }}% <span class="text-gray-500 font-normal ml-1">from last month</span>
                    </p>
                </div>
                <div class="p-3 bg-red-600/10 rounded-2xl border border-red-500/20 group-hover:scale-110 transition-transform">
                    <i data-lucide="arrow-down-circle" class="w-6 h-6 text-red-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Cashflow Chart -->
        <div class="glass-card p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold">Cashflow Performance</h3>
                    <p class="text-xs text-gray-500 mt-1">Net flow trend for the last 6 months</p>
                </div>
                <div class="flex items-center space-x-2">
                    <select class="bg-gray-800/50 border border-white/10 rounded-lg px-2 py-1 text-[10px] text-gray-300 focus:ring-0 cursor-pointer outline-none">
                        <option value="6monts">Last 6 Months</option>
                        <option value="1year">Last 1 Year</option>
                    </select>
                </div>
            </div>
            <div class="relative flex-1 min-h-[300px]">
                <canvas id="cashflowChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Expense Category -->
            <div class="glass-card p-6 flex flex-col h-full hover:border-blue-500/30 transition-all">
                <h3 class="text-lg font-bold mb-1">Expense Breakdown</h3>
                <p class="text-xs text-gray-500 mb-4">By category</p>
                <div class="relative flex-1 flex items-center justify-center min-h-[200px]">
                    <canvas id="categoryChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">Total</span>
                        <span class="text-lg font-bold">100%</span>
                    </div>
                </div>
            </div>

            <!-- Portfolio Allocation -->
            <div class="glass-card p-6 flex flex-col h-full hover:border-purple-500/30 transition-all">
                <h3 class="text-lg font-bold mb-1">Asset Allocation</h3>
                <p class="text-xs text-gray-500 mb-4">By portfolio</p>
                <div class="relative flex-1 flex items-center justify-center min-h-[200px]">
                    <canvas id="portfolioChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Transactions & Portfolio Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Portfolio Performance -->
        <div class="glass-card p-6 flex flex-col space-y-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-lg font-bold">Investment Status</h3>
                    <p class="text-xs text-gray-500">Real-time performance</p>
                </div>
                <a href="#" class="p-2 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                    <i data-lucide="external-link" class="w-4 h-4 text-gray-400"></i>
                </a>
            </div>
            <div class="flex-1 space-y-3">
                @foreach($portfolios as $portfolio)
                <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-white/5 transition-all border border-transparent hover:border-white/10 group cursor-pointer">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-inner" style="background: {{ $portfolio->color }}15; border: 1px solid {{ $portfolio->color }}30">
                            <i data-lucide="{{ $portfolio->icon ?: 'wallet' }}" class="w-6 h-6" style="color: {{ $portfolio->color }}"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold group-hover:text-blue-400 transition-colors">{{ $portfolio->name }}</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">{{ $portfolio->currency }} Dashboard</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-black">{{ number_format($portfolio->balance, 0, ',', '.') }}</div>
                        <div class="text-[10px] {{ $portfolio->roi >= 0 ? 'text-green-400' : 'text-red-400' }} font-black flex items-center justify-end">
                            <i data-lucide="{{ $portfolio->roi >= 0 ? 'plus' : 'minus' }}" class="w-2 h-2 mr-0.5"></i>
                            {{ number_format(abs($portfolio->roi), 1) }}% ROI
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="w-full py-3 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl text-xs font-bold transition-all mt-4">
                View Detailed Portfolios
            </button>
        </div>

        <!-- Recent Transactions -->
        <div class="lg:col-span-2 glass-card overflow-hidden flex flex-col">
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between border-b border-white/5 gap-4">
                <div>
                    <h3 class="text-xl font-bold">Recent Ledger</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Your latest financial activity</p>
                </div>
                <form action="{{ route('transactions.index') }}" method="GET" class="flex items-center space-x-2">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
                        <input type="text" name="search" placeholder="Search..." class="bg-gray-800/50 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none w-full md:w-64">
                    </div>
                    <button type="submit" class="p-2 bg-gray-800/50 border border-white/10 rounded-xl hover:bg-white/5">
                        <i data-lucide="filter" class="w-4 h-4 text-gray-400"></i>
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-[10px] text-gray-500 uppercase font-black tracking-widest border-b border-white/5 bg-white/[0.02]">
                        <tr>
                            <th class="px-6 py-4">Entity & Category</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Processed Date</th>
                            <th class="px-6 py-4 text-right">Amount (IDR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($recentTransactions as $tx)
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
                                    {{ $tx->type === 'income' ? 'Settled' : 'Cleared' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-400">
                                {{ $tx->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-black {{ $tx->type === 'income' ? 'text-green-500' : 'text-primary' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }} {{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-white/5 text-center">
                <button class="text-xs font-bold text-blue-500 hover:text-blue-400 transition-colors uppercase tracking-widest">Load More Transactions</button>
            </div>
        </div>
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
                <input type="hidden" name="type" id="tx_type_input" value="expense">
                <div class="flex gap-4 p-1 bg-gray-800/50 rounded-xl border border-white/5">
                    <button type="button" onclick="setTxType('expense')" class="tx-type-btn flex-1 py-3 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-lg" data-type="expense">Expense</button>
                    <button type="button" onclick="setTxType('income')" class="tx-type-btn flex-1 py-3 rounded-lg text-xs font-bold transition-all text-gray-400" data-type="income">Income</button>
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
                            @foreach($allPortfolios as $wf)
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
    // Theme Colors
    const colors = {
        primary: '#3b82f6',
        secondary: '#8b5cf6',
        success: '#10b981',
        danger: '#ef4444',
        warning: '#f59e0b',
        muted: '#9ca3af',
        card: 'rgba(23, 27, 34, 0.7)'
    };

    // Helper to get month names
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const currentMonthsData = @json($cashflowData);
    const labels = currentMonthsData.map(d => months[d.month - 1]);
    const incomes = currentMonthsData.map(d => d.income);
    const expenses = currentMonthsData.map(d => d.expense);

    // Cashflow Chart
    const ctxCashflow = document.getElementById('cashflowChart').getContext('2d');
    
    // Create Gradients
    const gradientIncome = ctxCashflow.createLinearGradient(0, 0, 0, 400);
    gradientIncome.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
    gradientIncome.addColorStop(1, 'rgba(16, 185, 129, 0)');

    const gradientExpense = ctxCashflow.createLinearGradient(0, 0, 0, 400);
    gradientExpense.addColorStop(0, 'rgba(239, 68, 68, 0.15)');
    gradientExpense.addColorStop(1, 'rgba(239, 68, 68, 0)');

    new Chart(ctxCashflow, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomes,
                    borderColor: colors.success,
                    backgroundColor: gradientIncome,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 6,
                    pointBackgroundColor: colors.success,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8
                },
                {
                    label: 'Expense',
                    data: expenses,
                    borderColor: colors.danger,
                    backgroundColor: gradientExpense,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 6,
                    pointBackgroundColor: colors.danger,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        color: colors.muted,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: { size: 12, weight: 'bold' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(13, 17, 23, 0.95)',
                    titleColor: '#fff',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 15,
                    cornerRadius: 12,
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    displayColors: true,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)', borderDash: [5, 5] },
                    ticks: { 
                        color: colors.muted, 
                        font: { size: 10, weight: 'bold' },
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000) + 'M';
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: colors.muted, font: { size: 11, weight: 'bold' } }
                }
            }
        }
    });

    // Category Chart
    const catData = @json($categoryBreakdown);
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: catData.map(c => c.name),
            datasets: [{
                data: catData.map(c => c.total),
                backgroundColor: catData.map(c => c.color),
                borderWidth: 4,
                borderColor: '#0a0c10',
                hoverOffset: 15,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(13, 17, 23, 0.95)',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            const val = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const perc = Math.round((val / total) * 100);
                            return ` ${context.label}: ${perc}%`;
                        }
                    }
                }
            }
        }
    });

    // Portfolio Chart
    const portData = @json($portfolios);
    const ctxPortfolio = document.getElementById('portfolioChart').getContext('2d');
    new Chart(ctxPortfolio, {
        type: 'doughnut',
        data: {
            labels: portData.map(p => p.name),
            datasets: [{
                data: portData.map(p => p.balance),
                backgroundColor: portData.map(p => p.color),
                borderWidth: 4,
                borderColor: '#0a0c10',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Modal Logic
    function openModal() {
        const modal = document.getElementById('transactionModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('transactionModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function setTxType(type) {
        document.getElementById('tx_type_input').value = type;
        const btns = document.querySelectorAll('.tx-type-btn');
        btns.forEach(btn => {
            if (btn.dataset.type === type) {
                btn.classList.add('bg-blue-600', 'text-white', 'shadow-lg');
                btn.classList.remove('text-gray-400');
            } else {
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
                btn.classList.add('text-gray-400');
            }
        });
    }

    // Close modal on escape
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endsection

