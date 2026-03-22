@extends('layouts.app')
@section('title', 'Dashboard')

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" defer></script>
@endpush

@section('content')
<div style="max-width:1280px;margin:0 auto;">

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-sub">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div style="display:flex;gap:0.6rem;align-items:center;">
            <a href="{{ route('transactions.export') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
            </a>
            <button onclick="openModal()" class="btn btn-primary">
                <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Transaction
            </button>
        </div>
    </div>

    {{-- Budget Alerts --}}
    @if(!empty($budgetAlerts))
    <div style="display:flex;flex-direction:column;gap:0.6rem;margin-bottom:1.25rem;">
        @foreach($budgetAlerts as $alert)
        <div class="alert {{ $alert['type'] === 'danger' ? 'alert-error' : ($alert['type'] === 'warning' ? 'alert-warning' : 'alert-info') }}"
             style="justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:0.6rem;">
                <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    <div style="font-size:0.8rem;font-weight:700;">{{ $alert['title'] }}</div>
                    <div style="font-size:0.75rem;opacity:0.8;">{{ $alert['message'] }}</div>
                </div>
            </div>
            <a href="{{ $alert['type'] !== 'info' ? route('budgets.index') : route('transactions.index') }}"
               style="font-size:0.75rem;font-weight:700;padding:0.3rem 0.8rem;border-radius:7px;background:rgba(0,0,0,0.06);text-decoration:none;color:inherit;white-space:nowrap;">
                {{ $alert['type'] !== 'info' ? 'Adjust →' : 'View →' }}
            </a>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Weekly Banner --}}
    <div style="background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:16px;padding:1.25rem 1.5rem;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
            <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.65);margin-bottom:0.3rem;">This Week</div>
            <div style="font-size:1.1rem;font-weight:800;color:white;">Weekly Performance</div>
            <div style="font-size:0.8rem;color:rgba(255,255,255,0.75);margin-top:0.2rem;">
                Spending is
                <strong style="color:{{ $weeklyTrend <= 0 ? '#6ee7b7' : '#fca5a5' }}">{{ $weeklyTrend <= 0 ? '↓ down' : '↑ up' }}</strong>
                {{ number_format(abs($weeklyTrend), 1) }}% vs last week
            </div>
        </div>
        <div style="display:flex;gap:2rem;">
            <div style="text-align:center;">
                <div style="font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:0.07em;">Spent</div>
                <div style="font-size:1rem;font-weight:800;color:white">IDR {{ number_format($spentThisWeek, 0, ',', '.') }}</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:0.07em;">Top Cat</div>
                <div style="font-size:1rem;font-weight:800;color:white">{{ $topCategoryThisWeek->category->name ?? '—' }}</div>
            </div>
        </div>
        <a href="{{ route('transactions.index') }}" style="padding:0.5rem 1.1rem;border-radius:9px;background:rgba(255,255,255,0.18);color:white;text-decoration:none;font-size:0.8rem;font-weight:700;border:1px solid rgba(255,255,255,0.25);">
            History →
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid-3" style="margin-bottom:1.25rem;">
        {{-- Total Balance --}}
        <div class="card" style="position:relative;overflow:hidden;">
            <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:rgba(99,102,241,0.08);"></div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.875rem;">
                <div style="width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:grid;place-items:center;">
                    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:white;fill:none;stroke-width:2;"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <span class="badge badge-indigo">+8.2%</span>
            </div>
            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--muted);">Total Wealth</div>
            <div style="font-size:1.5rem;font-weight:800;color:var(--text);margin-top:0.2rem;letter-spacing:-0.02em;">IDR {{ number_format($totalBalance, 0, ',', '.') }}</div>
            <div style="font-size:0.75rem;color:var(--success);margin-top:0.3rem;font-weight:600;">↑ vs last month</div>
        </div>

        {{-- Income --}}
        <div class="card" style="position:relative;overflow:hidden;">
            <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:rgba(16,185,129,0.08);"></div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.875rem;">
                <div style="width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,#10b981,#059669);display:grid;place-items:center;">
                    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:white;fill:none;stroke-width:2;"><circle cx="12" cy="12" r="10"/><polyline points="8 12 12 16 16 12"/><line x1="12" y1="8" x2="12" y2="16"/></svg>
                </div>
                <span class="badge {{ $incomeTrend >= 0 ? 'badge-green' : 'badge-red' }}">{{ $incomeTrend >= 0 ? '+' : '' }}{{ number_format($incomeTrend, 1) }}%</span>
            </div>
            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--muted);">Income ({{ now()->format('M') }})</div>
            <div style="font-size:1.5rem;font-weight:800;color:#059669;margin-top:0.2rem;letter-spacing:-0.02em;">IDR {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
            <div style="font-size:0.75rem;color:var(--muted);margin-top:0.3rem;font-weight:600;">{{ $incomeTrend >= 0 ? '↑' : '↓' }} from last month</div>
        </div>

        {{-- Expenses --}}
        <div class="card" style="position:relative;overflow:hidden;">
            <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:rgba(244,63,94,0.06);"></div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.875rem;">
                <div style="width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,#f97316,#f43f5e);display:grid;place-items:center;">
                    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:white;fill:none;stroke-width:2;"><circle cx="12" cy="12" r="10"/><polyline points="8 12 12 8 16 12"/><line x1="12" y1="16" x2="12" y2="8"/></svg>
                </div>
                <span class="badge {{ $expenseTrend <= 0 ? 'badge-green' : 'badge-red' }}">{{ $expenseTrend > 0 ? '+' : '' }}{{ number_format($expenseTrend, 1) }}%</span>
            </div>
            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--muted);">Expenses ({{ now()->format('M') }})</div>
            <div style="font-size:1.5rem;font-weight:800;color:#f43f5e;margin-top:0.2rem;letter-spacing:-0.02em;">IDR {{ number_format($monthlyExpense, 0, ',', '.') }}</div>
            <div style="font-size:0.75rem;color:var(--muted);margin-top:0.3rem;font-weight:600;">{{ $expenseTrend <= 0 ? '↓' : '↑' }} from last month</div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.25rem;margin-bottom:1.25rem;" class="charts-row">
        {{-- Cashflow Chart --}}
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                <div>
                    <div style="font-size:0.9rem;font-weight:700;color:var(--text);">Cashflow</div>
                    <div style="font-size:0.75rem;color:var(--muted);">Last 6 months</div>
                </div>
                <div style="display:flex;gap:1rem;font-size:0.75rem;font-weight:600;">
                    <span style="display:flex;align-items:center;gap:0.3rem;color:var(--success);"><span style="width:10px;height:10px;border-radius:50%;background:var(--success);display:inline-block;"></span>Income</span>
                    <span style="display:flex;align-items:center;gap:0.3rem;color:var(--danger);"><span style="width:10px;height:10px;border-radius:50%;background:var(--danger);display:inline-block;"></span>Expense</span>
                </div>
            </div>
            <div style="height:240px;position:relative;"><canvas id="cashflowChart"></canvas></div>
        </div>

        {{-- Category Donut --}}
        <div class="card">
            <div style="font-size:0.9rem;font-weight:700;margin-bottom:0.25rem;">By Category</div>
            <div style="font-size:0.75rem;color:var(--muted);margin-bottom:0.75rem;">Expense breakdown</div>
            <div style="height:180px;position:relative;">
                <canvas id="categoryChart"></canvas>
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                    <span style="font-size:0.65rem;color:var(--muted);font-weight:700;text-transform:uppercase;">Total</span>
                    <span style="font-size:1rem;font-weight:800;color:var(--text);">100%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Row --}}
    <div style="display:grid;grid-template-columns:1fr 2fr;gap:1.25rem;" class="bottom-row">
        {{-- Portfolios --}}
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                <div style="font-size:0.9rem;font-weight:700;">Portfolios</div>
                <a href="{{ route('investments.index') }}" style="font-size:0.75rem;font-weight:600;color:var(--primary);text-decoration:none;">View all →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
                @foreach($portfolios as $portfolio)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.625rem 0.75rem;border-radius:10px;transition:background 0.15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                    <div style="display:flex;align-items:center;gap:0.625rem;">
                        <div style="width:36px;height:36px;border-radius:9px;display:grid;place-items:center;flex-shrink:0;background:{{ $portfolio->color }}18;border:1.5px solid {{ $portfolio->color }}30;">
                            <span style="font-size:0.8rem;">💼</span>
                        </div>
                        <div>
                            <div style="font-size:0.82rem;font-weight:700;color:var(--text);">{{ $portfolio->name }}</div>
                            <div style="font-size:0.65rem;color:var(--muted);font-weight:600;text-transform:uppercase;">{{ $portfolio->currency }}</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:0.82rem;font-weight:800;color:var(--text);">{{ number_format($portfolio->balance, 0, ',', '.') }}</div>
                        <div style="font-size:0.7rem;font-weight:700;color:{{ $portfolio->roi >= 0 ? 'var(--success)' : 'var(--danger)' }};">{{ $portfolio->roi >= 0 ? '+' : '' }}{{ number_format($portfolio->roi, 1) }}%</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:1rem 1.25rem;border-bottom:1px solid var(--border);">
                <div>
                    <div style="font-size:0.9rem;font-weight:700;">Recent Transactions</div>
                    <div style="font-size:0.75rem;color:var(--muted);">Latest activity</div>
                </div>
                <form action="{{ route('transactions.index') }}" method="GET" style="display:flex;gap:0.4rem;">
                    <div style="position:relative;">
                        <svg style="position:absolute;left:9px;top:50%;transform:translateY(-50%);width:13px;height:13px;stroke:var(--muted);fill:none;stroke-width:2;pointer-events:none;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="search" placeholder="Search..." class="form-input" style="padding-left:2rem;padding-top:0.5rem;padding-bottom:0.5rem;width:160px;font-size:0.8rem;">
                    </div>
                    <button type="submit" class="btn btn-secondary" style="padding:0.5rem;">
                        <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    </button>
                </form>
            </div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th style="text-align:right;">Amount (IDR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $tx)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.6rem;">
                                    <div style="width:34px;height:34px;border-radius:9px;display:grid;place-items:center;flex-shrink:0;background:{{ $tx->type === 'income' ? 'rgba(16,185,129,0.1)' : 'rgba(244,63,94,0.1)' }};">
                                        @if($tx->type === 'income')
                                        <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:#10b981;fill:none;stroke-width:2;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                        @else
                                        <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:#f43f5e;fill:none;stroke-width:2;"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-size:0.825rem;font-weight:600;color:var(--text);">{{ $tx->description }}</div>
                                        <div style="font-size:0.7rem;color:var(--muted);display:flex;align-items:center;gap:0.3rem;margin-top:1px;">
                                            <span style="width:6px;height:6px;border-radius:50%;background:{{ $tx->category->color ?? '#9ca3af' }};display:inline-block;flex-shrink:0;"></span>
                                            {{ $tx->category->name ?? 'Uncategorized' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $tx->type === 'income' ? 'badge-green' : 'badge-indigo' }}">
                                    {{ $tx->type === 'income' ? 'Income' : 'Expense' }}
                                </span>
                            </td>
                            <td style="color:var(--muted);font-size:0.8rem;">{{ $tx->date->format('d M Y') }}</td>
                            <td style="text-align:right;">
                                <span style="font-size:0.875rem;font-weight:800;color:{{ $tx->type === 'income' ? '#059669' : '#f43f5e' }};">
                                    {{ $tx->type === 'income' ? '+' : '−' }} {{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:0.75rem 1.25rem;border-top:1px solid var(--border);text-align:center;">
                <a href="{{ route('transactions.index') }}" style="font-size:0.8rem;font-weight:700;color:var(--primary);text-decoration:none;">View all transactions →</a>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div id="txModal" style="display:none;position:fixed;inset:0;z-index:200;">
    <div onclick="closeModal()" style="position:absolute;inset:0;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);"></div>
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:100%;max-width:420px;padding:1rem;">
        <div style="background:white;border-radius:20px;padding:1.75rem;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.25rem;">
                <div>
                    <h2 style="font-size:1.1rem;font-weight:800;color:var(--text);">New Transaction</h2>
                    <p style="font-size:0.8rem;color:var(--muted);margin-top:0.15rem;">Record your financial activity</p>
                </div>
                <button onclick="closeModal()" style="background:none;border:none;cursor:pointer;color:var(--muted);padding:4px;border-radius:8px;line-height:1;">
                    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <form action="{{ route('transactions.store') }}" method="POST" style="display:flex;flex-direction:column;gap:0.875rem;">
                @csrf
                <input type="hidden" name="type" id="tx_type" value="expense">

                {{-- Type Toggle --}}
                <div style="display:flex;gap:0.5rem;background:#f3f4f6;padding:0.3rem;border-radius:11px;">
                    <button type="button" onclick="setType('expense')" id="btn-expense"
                        style="flex:1;padding:0.55rem;border-radius:8px;border:none;cursor:pointer;font-size:0.82rem;font-weight:700;background:white;color:#f43f5e;box-shadow:0 1px 4px rgba(0,0,0,0.08);font-family:inherit;">
                        Expense
                    </button>
                    <button type="button" onclick="setType('income')" id="btn-income"
                        style="flex:1;padding:0.55rem;border-radius:8px;border:none;cursor:pointer;font-size:0.82rem;font-weight:700;background:transparent;color:var(--muted);font-family:inherit;">
                        Income
                    </button>
                </div>

                <div>
                    <label class="form-label">Description</label>
                    <input type="text" name="description" required placeholder="e.g. Lunch, Salary" class="form-input">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                    <div>
                        <label class="form-label">Amount (IDR)</label>
                        <input type="number" name="amount" required placeholder="0" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Date</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="form-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category_id" required class="form-input">
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Wallet</label>
                        <select name="portfolio_id" required class="form-input">
                            @foreach($allPortfolios as $wf)
                            <option value="{{ $wf->id }}">{{ $wf->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="justify-content:center;width:100%;padding:0.8rem;">
                    Save Transaction
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Wait for Chart.js to load
function initCharts() {
    if (typeof Chart === 'undefined') { setTimeout(initCharts, 100); return; }

    const cf = document.getElementById('cashflowChart');
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const data = @json($cashflowData);
    const labels = data.map(d => months[d.month-1]);
    const incomes  = data.map(d => d.income);
    const expenses = data.map(d => d.expense);

    const ctx = cf.getContext('2d');
    const gI = ctx.createLinearGradient(0,0,0,220);
    gI.addColorStop(0,'rgba(16,185,129,0.15)'); gI.addColorStop(1,'rgba(16,185,129,0)');
    const gE = ctx.createLinearGradient(0,0,0,220);
    gE.addColorStop(0,'rgba(244,63,94,0.12)');  gE.addColorStop(1,'rgba(244,63,94,0)');

    new Chart(ctx, {
        type:'line',
        data: { labels, datasets:[
            { label:'Income',  data:incomes,  borderColor:'#10b981', backgroundColor:gI, fill:true, tension:0.4, borderWidth:2.5, pointRadius:4, pointBackgroundColor:'#10b981', pointBorderColor:'#fff', pointBorderWidth:2 },
            { label:'Expense', data:expenses, borderColor:'#f43f5e', backgroundColor:gE, fill:true, tension:0.4, borderWidth:2.5, pointRadius:4, pointBackgroundColor:'#f43f5e', pointBorderColor:'#fff', pointBorderWidth:2 },
        ]},
        options: { responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{display:false}, tooltip:{ backgroundColor:'rgba(255,255,255,0.98)', titleColor:'#111827', bodyColor:'#6b7280', borderColor:'rgba(0,0,0,0.08)', borderWidth:1, padding:10, cornerRadius:10,
                callbacks:{ label: c => c.dataset.label + ': IDR ' + new Intl.NumberFormat('id-ID').format(c.parsed.y) }
            }},
            scales:{ y:{ grid:{ color:'rgba(0,0,0,0.05)' }, ticks:{ color:'#9ca3af', font:{size:10,weight:'600'}, callback:v=>'Rp'+(v/1e6).toFixed(0)+'M' }},
                     x:{ grid:{display:false}, ticks:{color:'#9ca3af', font:{size:10,weight:'600'}} }}
        }
    });

    const cat = @json($categoryBreakdown);
    new Chart(document.getElementById('categoryChart').getContext('2d'), {
        type:'doughnut',
        data:{ labels:cat.map(c=>c.name), datasets:[{ data:cat.map(c=>c.total), backgroundColor:cat.map(c=>c.color), borderWidth:2.5, borderColor:'#fff', hoverOffset:6 }]},
        options:{ responsive:true, maintainAspectRatio:false, cutout:'78%', plugins:{ legend:{display:false},
            tooltip:{ backgroundColor:'rgba(255,255,255,0.98)', titleColor:'#111827', bodyColor:'#6b7280', borderColor:'rgba(0,0,0,0.08)', borderWidth:1, padding:8, cornerRadius:8,
                callbacks:{ label:c=>{ const t=c.dataset.data.reduce((a,b)=>a+b,0); return c.label+': '+Math.round(c.parsed/t*100)+'%'; } } } }}
    });
}

initCharts();

function openModal()  { document.getElementById('txModal').style.display='block'; document.body.style.overflow='hidden'; }
function closeModal() { document.getElementById('txModal').style.display='none';  document.body.style.overflow=''; }
window.addEventListener('keydown', e => { if(e.key==='Escape') closeModal(); });

function setType(t) {
    document.getElementById('tx_type').value = t;
    const base = 'flex:1;padding:0.55rem;border-radius:8px;border:none;cursor:pointer;font-size:0.82rem;font-weight:700;font-family:inherit;';
    const active = base + 'background:white;box-shadow:0 1px 4px rgba(0,0,0,0.08);';
    const idle   = base + 'background:transparent;color:var(--muted);';
    document.getElementById('btn-expense').style.cssText = t==='expense' ? active+'color:#f43f5e;' : idle;
    document.getElementById('btn-income').style.cssText  = t==='income'  ? active+'color:#10b981;' : idle;
}
</script>
@endpush

<style>
@media (max-width:900px) {
  .charts-row { grid-template-columns:1fr !important; }
  .bottom-row  { grid-template-columns:1fr !important; }
}
</style>
@endsection
