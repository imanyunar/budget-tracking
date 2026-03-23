@extends('layouts.app')

@section('title', 'Dashboard · FinanceTracker')

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" defer></script>
@endpush

@section('content')
<style>
    .grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
    .grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
    @media(max-width:1200px) { .grid-4 { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:900px) { .grid-4 { grid-template-columns:1fr 1fr; } .grid-3 { grid-template-columns:1fr; } }
    @media(max-width:560px) { .grid-4 { grid-template-columns:1fr; } }
    .chart-row { display:grid; grid-template-columns:2fr 1fr; gap:14px; }
    .bottom-row { display:grid; grid-template-columns:1fr 1.8fr; gap:14px; }
    @media(max-width:1000px) { .chart-row,.bottom-row { grid-template-columns:1fr; } }
    .stat-accent-bar {
        position:absolute; top:0; left:0; right:0; height:3px; border-radius:16px 16px 0 0;
    }
    .alert-strip {
        display:flex; align-items:center; gap:12px; padding:12px 16px;
        border-radius:11px; border:1px solid; font-size:13.5px; margin-bottom:8px;
    }
    .wallet-row {
        display:flex; align-items:center; justify-content:space-between;
        padding:10px 0; border-bottom:1px solid var(--border);
    }
    .wallet-row:last-child { border-bottom:none; }
</style>

<div style="display:flex;flex-direction:column;gap:22px;">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title-main">Financial Overview</h1>
            <p class="page-title-sub">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('transactions.export') }}" class="btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Export CSV
            </a>
            <button onclick="openTxModal()" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                New Transaction
            </button>
        </div>
    </div>

    <!-- Budget Alerts -->
    @if(!empty($budgetAlerts))
    <div>
        @foreach($budgetAlerts as $alert)
        @php
            $styles = [
                'danger'  => 'background:var(--danger-dim);border-color:rgba(244,63,94,0.25);color:#e11d48;',
                'warning' => 'background:var(--warn-dim);border-color:rgba(245,158,11,0.25);color:#d97706;',
                'info'    => 'background:var(--blue-dim);border-color:rgba(59,130,246,0.25);color:#2563eb;',
            ][$alert['type']];
        @endphp
        <div class="alert-strip" style="{{ $styles }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            <div style="flex:1;">
                <div style="font-weight:600;font-size:13px;">{{ $alert['title'] }}</div>
                <div style="font-size:11.5px;opacity:0.75;margin-top:1px;">{{ $alert['message'] }}</div>
            </div>
            <a href="{{ $alert['type'] !== 'info' ? route('budgets.index') : route('transactions.index') }}"
               style="font-size:12px;text-decoration:none;color:inherit;font-weight:600;white-space:nowrap;">
                {{ $alert['type'] !== 'info' ? 'Adjust' : 'View' }} →
            </a>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Stat Cards -->
    <div class="grid-4">
        <!-- Total Wealth -->
        <div class="stat-card">
            <div class="stat-accent-bar" style="background:linear-gradient(90deg,#6366f1,#818cf8);"></div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                <div class="stat-icon" style="background:var(--primary-dim);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="1" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <span class="badge badge-primary">NET WORTH</span>
            </div>
            <div class="stat-label">Total Balance</div>
            <div class="stat-value" style="color:var(--primary);">{{ number_format($totalBalance, 0, ',', '.') }}</div>
            <div style="font-size:11px;color:var(--muted);margin-top:3px;">IDR · All Wallets</div>
        </div>

        <!-- Income -->
        <div class="stat-card">
            <div class="stat-accent-bar" style="background:linear-gradient(90deg,#10b981,#34d399);"></div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                <div class="stat-icon" style="background:var(--success-dim);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="19" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                </div>
                <span class="badge {{ $incomeTrend >= 0 ? 'badge-up' : 'badge-down' }}">
                    {{ $incomeTrend >= 0 ? '↑' : '↓' }} {{ number_format(abs($incomeTrend), 1) }}%
                </span>
            </div>
            <div class="stat-label">Income · {{ now()->format('M') }}</div>
            <div class="stat-value">{{ number_format($monthlyIncome, 0, ',', '.') }}</div>
            <div style="font-size:11px;color:var(--muted);margin-top:3px;">IDR · This month</div>
        </div>

        <!-- Expenses -->
        <div class="stat-card">
            <div class="stat-accent-bar" style="background:linear-gradient(90deg,#f43f5e,#fb7185);"></div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                <div class="stat-icon" style="background:var(--danger-dim);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                </div>
                <span class="badge {{ $expenseTrend <= 0 ? 'badge-up' : 'badge-down' }}">
                    {{ $expenseTrend > 0 ? '↑' : '↓' }} {{ number_format(abs($expenseTrend), 1) }}%
                </span>
            </div>
            <div class="stat-label">Expenses · {{ now()->format('M') }}</div>
            <div class="stat-value" style="color:var(--danger);">{{ number_format($monthlyExpense, 0, ',', '.') }}</div>
            <div style="font-size:11px;color:var(--muted);margin-top:3px;">IDR · This month</div>
        </div>

        <!-- Weekly -->
        <div class="stat-card">
            <div class="stat-accent-bar" style="background:linear-gradient(90deg,#3b82f6,#60a5fa);"></div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                <div class="stat-icon" style="background:var(--blue-dim);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <span class="badge {{ $weeklyTrend <= 0 ? 'badge-up' : 'badge-down' }}">
                    {{ $weeklyTrend <= 0 ? '↓' : '↑' }} {{ number_format(abs($weeklyTrend), 1) }}%
                </span>
            </div>
            <div class="stat-label">Weekly Spent</div>
            <div class="stat-value" style="color:var(--blue);">{{ number_format($spentThisWeek, 0, ',', '.') }}</div>
            <div style="font-size:11px;color:var(--muted);margin-top:3px;">IDR · This week</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="chart-row">
        <!-- Cashflow Chart -->
        <div class="card" style="padding:22px;">
            <div class="section-head">
                <div>
                    <h3>Cashflow Analytics</h3>
                    <div class="section-head-sub">6-month income vs expenses</div>
                </div>
                <div style="display:flex;gap:16px;align-items:center;">
                    <div style="display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--muted);font-weight:500;">
                        <span style="width:9px;height:9px;border-radius:50%;background:#10b981;display:inline-block;flex-shrink:0;"></span> Income
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--muted);font-weight:500;">
                        <span style="width:9px;height:9px;border-radius:50%;background:#f43f5e;display:inline-block;flex-shrink:0;"></span> Expense
                    </div>
                </div>
            </div>
            <div style="height:240px;position:relative;">
                <canvas id="cashflowChart"></canvas>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="card" style="padding:22px;">
            <div class="section-head">
                <div>
                    <h3>Breakdown</h3>
                    <div class="section-head-sub">by category</div>
                </div>
            </div>
            <div style="height:150px;position:relative;display:flex;align-items:center;justify-content:center;">
                <canvas id="categoryChart"></canvas>
            </div>
            <div style="margin-top:14px;display:flex;flex-direction:column;gap:7px;" id="catLegend"></div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="bottom-row">
        <!-- Wallets -->
        <div class="card" style="padding:22px;">
            <div class="section-head">
                <div>
                    <h3>Your Wallets</h3>
                    <div class="section-head-sub">{{ count($portfolios) }} accounts</div>
                </div>
                <a href="{{ route('wallets.index') }}" class="section-link">View all →</a>
            </div>
            <div>
                @forelse($portfolios as $portfolio)
                <div class="wallet-row">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $portfolio->color }}18;border:1px solid {{ $portfolio->color }}30;">
                            <x-icon name="{{ $portfolio->icon ?: 'wallet' }}" style="width:16px;height:16px;color:{{ $portfolio->color }}" />
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--text);">{{ $portfolio->name }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $portfolio->currency }}</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:13.5px;font-weight:700;color:var(--text);">{{ number_format($portfolio->balance, 0, ',', '.') }}</div>
                        <div style="font-size:11px;font-weight:600;color:{{ ($portfolio->roi ?? 0) >= 0 ? 'var(--success)' : 'var(--danger)' }};">{{ ($portfolio->roi ?? 0) >= 0 ? '+' : '' }}{{ number_format($portfolio->roi ?? 0, 1) }}%</div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                    </div>
                    <div class="empty-state-title">No wallets yet</div>
                    <div class="empty-state-text">Add your first wallet to get started</div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card" style="padding:22px;">
            <div class="section-head">
                <div>
                    <h3>Recent Transactions</h3>
                    <div class="section-head-sub">Latest activity</div>
                </div>
                <a href="{{ route('transactions.index') }}" class="section-link">All →</a>
            </div>
            <div>
                @forelse($recentTransactions as $tx)
                <div class="tx-row">
                    <div class="tx-icon" style="background:{{ $tx->type === 'income' ? 'var(--success-dim)' : 'var(--danger-dim)' }};border:1px solid {{ $tx->type === 'income' ? 'rgba(16,185,129,0.2)' : 'rgba(244,63,94,0.2)' }};">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $tx->type === 'income' ? '#10b981' : '#f43f5e' }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            @if($tx->type === 'income')
                                <line x1="12" x2="12" y1="19" y2="5"/><polyline points="5 12 12 5 19 12"/>
                            @else
                                <line x1="12" x2="12" y1="5" y2="19"/><polyline points="19 12 12 19 5 12"/>
                            @endif
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="tx-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $tx->description ?: $tx->category->name }}</div>
                        <div class="tx-meta">{{ $tx->category->name ?? '—' }} · {{ $tx->date->format('d M') }}</div>
                    </div>
                    <div style="font-size:14px;font-weight:700;color:{{ $tx->type === 'income' ? 'var(--success)' : 'var(--danger)' }};flex-shrink:0;">
                        {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);"><path d="M16 3h5v5"/><path d="M8 21H3v-5"/><path d="M21 3l-7 7"/><path d="M3 21l7-7"/></svg>
                    </div>
                    <div class="empty-state-title">No transactions yet</div>
                    <div class="empty-state-text">Record your first transaction above</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Transaction Modal -->
<div id="txModal" class="modal-overlay" onclick="if(event.target===this)closeTxModal()">
    <div class="modal-box" id="txModalBox">
        <div class="modal-header">
            <div>
                <div class="modal-title">New Transaction</div>
                <div class="modal-subtitle">Record a financial activity</div>
            </div>
            <button class="modal-close" onclick="closeTxModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" id="tx_type" value="expense">

            <div class="type-toggle">
                <button type="button" onclick="setType('expense')" id="btn-expense" class="type-btn active-expense">
                    Expense
                </button>
                <button type="button" onclick="setType('income')" id="btn-income" class="type-btn">
                    Income
                </button>
            </div>

            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label class="input-label">Description</label>
                    <input type="text" name="description" required placeholder="e.g. Lunch, Salary" class="input-field">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label class="input-label">Amount (IDR)</label>
                        <input type="number" name="amount" required placeholder="0" class="input-field">
                    </div>
                    <div>
                        <label class="input-label">Date</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="input-field">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label class="input-label">Category</label>
                        <select name="category_id" id="catSelect" required class="input-field" onchange="checkCategory()">
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-name="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="input-label">Wallet</label>
                        <select name="portfolio_id" required class="input-field">
                            @foreach($allPortfolios as $wf)
                            <option value="{{ $wf->id }}">{{ $wf->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                </div>

                <!-- Investment Extra Fields -->
                <div id="invFields" style="display:none;flex-direction:column;gap:14px;padding-top:12px;margin-top:2px;border-top:1px dashed var(--border);">
                    <div style="font-size:11.5px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:0.06em;display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                        Investment Details
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label class="input-label">Asset Type</label>
                            <select name="asset_type" id="invAssetType" class="input-field" onchange="toggleInvType()">
                                <option value="stock">Stock</option>
                                <option value="crypto">Crypto</option>
                            </select>
                        </div>
                        <div>
                            <label class="input-label">Symbol</label>
                            <input type="text" name="symbol" id="invSymbol" placeholder="e.g. BBCA.JK" class="input-field" style="text-transform:uppercase;">
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label class="input-label" id="invPriceLabel">Price per Share</label>
                            <input type="number" name="average_price" id="invPrice" placeholder="0" step="any" class="input-field">
                        </div>
                        <div id="invLotsWrap">
                            <label class="input-label">Lots (1 lot = 100 shares)</label>
                            <input type="number" name="lots" id="invLots" placeholder="0" class="input-field">
                        </div>
                        <div id="invQtyWrap" style="display:none;">
                            <label class="input-label">Quantity</label>
                            <input type="number" name="quantity" id="invQty" placeholder="0" step="any" class="input-field" disabled>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;padding:12px;">
                    Record Transaction
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTxModal() {
        document.getElementById('txModal').classList.add('open');
        setTimeout(() => document.getElementById('txModalBox').classList.add('open'), 10);
        document.body.style.overflow = 'hidden';
    }
    function closeTxModal() {
        document.getElementById('txModalBox').classList.remove('open');
        setTimeout(() => { document.getElementById('txModal').classList.remove('open'); document.body.style.overflow = ''; }, 250);
    }

    function setType(type) {
        document.getElementById('tx_type').value = type;
        const e = document.getElementById('btn-expense');
        const i = document.getElementById('btn-income');
        e.className = 'type-btn' + (type === 'expense' ? ' active-expense' : '');
        i.className = 'type-btn' + (type === 'income' ? ' active-income' : '');
    }

    function checkCategory() {
        const sel = document.getElementById('catSelect');
        const opt = sel.options[sel.selectedIndex];
        const isInv = opt && opt.getAttribute('data-name') === 'investment';
        document.getElementById('invFields').style.display = isInv ? 'flex' : 'none';
        
        // toggle required inputs
        document.getElementById('invSymbol').required = isInv;
        document.getElementById('invPrice').required = isInv;
        
        if(isInv) toggleInvType();
        else {
            document.getElementById('invLots').required = false;
            document.getElementById('invQty').required = false;
        }
    }

    function toggleInvType() {
        const type = document.getElementById('invAssetType').value;
        const isCrypto = type === 'crypto';
        
        document.getElementById('invLotsWrap').style.display = isCrypto ? 'none' : 'block';
        document.getElementById('invQtyWrap').style.display = isCrypto ? 'block' : 'none';
        
        const lotsF = document.getElementById('invLots');
        const qtyF = document.getElementById('invQty');
        
        lotsF.disabled = isCrypto;
        qtyF.disabled = !isCrypto;
        
        lotsF.required = !isCrypto;
        qtyF.required = isCrypto;
        
        document.getElementById('invSymbol').placeholder = isCrypto ? 'BTC' : 'BBCA.JK';
        document.getElementById('invPriceLabel').textContent = isCrypto ? 'Price (IDR/unit)' : 'Price (IDR/share)';
    }

    // Initialize check on modal load or DOM ready
    document.addEventListener('DOMContentLoaded', checkCategory);

    function initCharts() {
        if (typeof Chart === 'undefined') { setTimeout(initCharts, 100); return; }
        Chart.defaults.font.family = "'Inter', sans-serif";

        const cfCtx = document.getElementById('cashflowChart').getContext('2d');
        const data = @json($cashflowData);
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        const gInc = cfCtx.createLinearGradient(0,0,0,220);
        gInc.addColorStop(0,'rgba(16,185,129,0.18)');
        gInc.addColorStop(1,'rgba(16,185,129,0)');
        const gExp = cfCtx.createLinearGradient(0,0,0,220);
        gExp.addColorStop(0,'rgba(244,63,94,0.14)');
        gExp.addColorStop(1,'rgba(244,63,94,0)');

        new Chart(cfCtx, {
            type: 'line',
            data: {
                labels: data.map(d => months[d.month-1]),
                datasets: [
                    { label:'Income', data:data.map(d=>d.income), borderColor:'#10b981', backgroundColor:gInc, borderWidth:2.5, tension:0.4, fill:true, pointBackgroundColor:'#10b981', pointBorderColor:'#fff', pointBorderWidth:2, pointRadius:4, pointHoverRadius:6 },
                    { label:'Expense', data:data.map(d=>d.expense), borderColor:'#f43f5e', backgroundColor:gExp, borderWidth:2.5, tension:0.4, fill:true, pointBackgroundColor:'#f43f5e', pointBorderColor:'#fff', pointBorderWidth:2, pointRadius:4, pointHoverRadius:6 }
                ]
            },
            options: {
                responsive:true, maintainAspectRatio:false,
                interaction:{ intersect:false, mode:'index' },
                plugins: {
                    legend:{ display:false },
                    tooltip:{ backgroundColor:'#0f172a', titleColor:'#f8fafc', bodyColor:'#94a3b8', borderColor:'#1e293b', borderWidth:1, padding:12, boxPadding:6, cornerRadius:10,
                        callbacks:{ label: c => c.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(c.parsed.y) }
                    }
                },
                scales: {
                    x:{ grid:{ display:false }, border:{ display:false }, ticks:{ font:{ size:11, weight:'600' }, color:'#94a3b8' } },
                    y:{ grid:{ color:'rgba(15,23,42,0.06)', borderDash:[4,4] }, border:{ display:false }, ticks:{ font:{ size:11, weight:'600' }, color:'#94a3b8', callback:v => (v/1e6).toFixed(0)+'M' } }
                }
            }
        });

        const catData = @json($categoryBreakdown);
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type:'doughnut',
            data:{
                labels: catData.map(c=>c.name),
                datasets:[{ data:catData.map(c=>c.total), backgroundColor:catData.map(c=>c.color), borderWidth:3, borderColor:'#fff', hoverOffset:6 }]
            },
            options:{
                responsive:true, maintainAspectRatio:false, cutout:'74%',
                plugins:{ legend:{ display:false }, tooltip:{ backgroundColor:'#0f172a', titleColor:'#f8fafc', bodyColor:'#94a3b8', borderColor:'#1e293b', borderWidth:1, padding:10, cornerRadius:10 } }
            }
        });

        const leg = document.getElementById('catLegend');
        catData.forEach(c => {
            const div = document.createElement('div');
            div.style.cssText = 'display:flex;justify-content:space-between;align-items:center;';
            div.innerHTML = `<div style="display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:3px;background:${c.color};flex-shrink:0;display:inline-block;"></span><span style="font-size:12.5px;color:var(--text-2);font-weight:500;">${c.name}</span></div><span style="font-size:12px;color:var(--muted);font-weight:600;">${new Intl.NumberFormat('id-ID').format(c.total)}</span>`;
            leg.appendChild(div);
        });
    }
    document.addEventListener('DOMContentLoaded', initCharts);
</script>
@endpush
@endsection