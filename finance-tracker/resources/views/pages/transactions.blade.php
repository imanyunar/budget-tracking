@extends('layouts.app')

@section('title', 'Transactions · FinanceTracker')

@section('content')
<style>
    .tx-table { width:100%; border-collapse:collapse; }
    .tx-table th {
        font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em;
        color:var(--muted); padding:10px 16px; border-bottom:1px solid var(--border);
        text-align:left; background:var(--surface-2); white-space:nowrap;
    }
    .tx-table th:first-child { border-radius:0; }
    .tx-table td { padding:12px 16px; border-bottom:1px solid var(--border); font-size:13.5px; color:var(--text-2); vertical-align:middle; }
    .tx-table tbody tr { transition:background 0.12s; }
    .tx-table tbody tr:hover { background:var(--surface-2); }
    .tx-table tbody tr:last-child td { border-bottom:none; }
    .chip { display:inline-flex; align-items:center; gap:5px; padding:3px 9px; border-radius:6px; font-size:11.5px; font-weight:600; }
    .chip-inc { background:var(--success-dim); color:#059669; }
    .chip-exp { background:var(--danger-dim); color:var(--danger); }
    .mobile-tx { display:none; }
    @media(max-width:768px) { .desktop-tx { display:none !important; } .mobile-tx { display:block; } }
    .mobile-tx-item { display:flex;align-items:center;gap:12px;padding:13px 0;border-bottom:1px solid var(--border); }
    .mobile-tx-item:last-child { border-bottom:none; }
    .del-btn { width:30px;height:30px;border-radius:7px;background:transparent;border:1px solid transparent;color:var(--muted);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:all 0.15s; }
    .del-btn:hover { background:var(--danger-dim);border-color:rgba(244,63,94,0.2);color:var(--danger); }
</style>

<div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title-main">Transactions</h1>
            <p class="page-title-sub">All financial records</p>
        </div>
        <button onclick="openTxModal()" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            New Entry
        </button>
    </div>

    <!-- Filter + Table Card -->
    <div class="card">
        <!-- Filters -->
        <form method="GET" action="{{ route('transactions.index') }}" style="padding:16px 18px;border-bottom:1px solid var(--border);display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <div style="position:relative;flex:1;min-width:200px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--muted);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..." class="input-field" style="padding-left:34px;">
            </div>
            <select name="category_id" onchange="this.form.submit()" class="input-field" style="width:auto;min-width:140px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="type" onchange="this.form.submit()" class="input-field" style="width:auto;min-width:120px;">
                <option value="">All Types</option>
                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
            <button type="submit" class="btn-primary" style="padding:9px 16px;">Search</button>
            <a href="{{ route('transactions.index') }}" class="btn-secondary" style="padding:9px 12px;" title="Reset">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            </a>
            <a href="{{ route('transactions.export') }}" class="btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Export
            </a>
        </form>

        <!-- Desktop Table -->
        <div class="desktop-tx" style="overflow-x:auto;">
            <table class="tx-table">
                <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Type</th>
                        <th>Wallet</th>
                        <th>Date</th>
                        <th style="text-align:right;">Amount</th>
                        <th style="width:48px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @if($transactions->isEmpty())
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);"><path d="M16 3h5v5"/><path d="M8 21H3v-5"/><path d="M21 3l-7 7"/><path d="M3 21l7-7"/></svg></div>
                                <div class="empty-state-title">No records found</div>
                                <div class="empty-state-text">Try adjusting your filters or add a new entry</div>
                            </div>
                        </td>
                    </tr>
                    @else
                    @foreach($transactions as $tx)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $tx->type === 'income' ? 'var(--success-dim)' : 'var(--danger-dim)' }};border:1px solid {{ $tx->type === 'income' ? 'rgba(16,185,129,0.18)' : 'rgba(244,63,94,0.18)' }};">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="{{ $tx->type === 'income' ? '#10b981' : '#f43f5e' }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        @if($tx->type === 'income')<line x1="12" x2="12" y1="19" y2="5"/><polyline points="5 12 12 5 19 12"/>
                                        @else<line x1="12" x2="12" y1="5" y2="19"/><polyline points="19 12 12 19 5 12"/>@endif
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size:13.5px;font-weight:500;color:var(--text);">{{ $tx->description ?: $tx->category->name }}</div>
                                    <div style="display:flex;align-items:center;gap:5px;margin-top:2px;">
                                        <span style="width:6px;height:6px;border-radius:2px;background:{{ $tx->category->color ?? '#94a3b8' }};display:inline-block;flex-shrink:0;"></span>
                                        <span style="font-size:11.5px;color:var(--muted);">{{ $tx->category->name ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td><span class="chip {{ $tx->type === 'income' ? 'chip-inc' : 'chip-exp' }}">{{ $tx->type === 'income' ? 'Income' : 'Expense' }}</span></td>
                        <td><span style="font-size:12.5px;color:var(--text-2);font-weight:500;">{{ $tx->portfolio->name }}</span></td>
                        <td><span style="font-size:12.5px;color:var(--muted);">{{ $tx->date->format('d M Y') }}</span></td>
                        <td style="text-align:right;">
                            <span style="font-size:14px;font-weight:700;color:{{ $tx->type === 'income' ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Delete this transaction?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="del-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="mobile-tx" style="padding:0 16px;">
            @if($transactions->isEmpty())
            <div class="empty-state">
                <div class="empty-state-title">No records found</div>
            </div>
            @else
            @foreach($transactions as $tx)
            <div class="mobile-tx-item">
                <div style="width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $tx->type === 'income' ? 'var(--success-dim)' : 'var(--danger-dim)' }};">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ $tx->type === 'income' ? '#10b981' : '#f43f5e' }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        @if($tx->type === 'income')<line x1="12" x2="12" y1="19" y2="5"/><polyline points="5 12 12 5 19 12"/>
                        @else<line x1="12" x2="12" y1="5" y2="19"/><polyline points="19 12 12 19 5 12"/>@endif
                    </svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13.5px;font-weight:500;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $tx->description ?: $tx->category->name }}</div>
                    <div style="font-size:11.5px;color:var(--muted);margin-top:2px;">{{ $tx->category->name }} · {{ $tx->date->format('d M Y') }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:13.5px;font-weight:700;color:{{ $tx->type === 'income' ? 'var(--success)' : 'var(--danger)' }};">
                        {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                    </div>
                    <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Delete?')" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;font-size:11.5px;color:var(--danger);cursor:pointer;padding:0;margin-top:2px;">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div style="padding:14px 18px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
            <div style="font-size:12px;color:var(--muted);">
                {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }} records
            </div>
            {{ $transactions->links('vendor.pagination.tailwind-simple') }}
        </div>
        @endif
    </div>
</div>

<!-- Transaction Modal -->
<div id="txModal" class="modal-overlay" onclick="if(event.target===this)closeTxModal()">
    <div class="modal-box" id="txModalBox">
        <div class="modal-header">
            <div>
                <div class="modal-title">New Entry</div>
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
                <button type="button" onclick="setType('expense')" id="btn-expense" class="type-btn active-expense">Expense</button>
                <button type="button" onclick="setType('income')" id="btn-income" class="type-btn">Income</button>
            </div>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label class="input-label">Description</label>
                    <input type="text" name="description" required placeholder="e.g. Starbucks, Salary" class="input-field">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div><label class="input-label">Amount (IDR)</label><input type="number" name="amount" required placeholder="0" class="input-field"></div>
                    <div><label class="input-label">Date</label><input type="date" name="date" required value="{{ date('Y-m-d') }}" class="input-field"></div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label class="input-label">Category</label>
                        <select name="category_id" id="catSelect" required class="input-field" onchange="checkCategory()">
                            @foreach($categories as $cat)<option value="{{ $cat->id }}" data-name="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="input-label">Wallet</label>
                        <select name="portfolio_id" required class="input-field">
                            @foreach($portfolios as $wf)<option value="{{ $wf->id }}">{{ $wf->name }}</option>@endforeach
                        </select>
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

                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;">Record Transaction</button>
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
        document.getElementById('btn-expense').className = 'type-btn' + (type === 'expense' ? ' active-expense' : '');
        document.getElementById('btn-income').className = 'type-btn' + (type === 'income' ? ' active-income' : '');
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
</script>
@endpush
@endsection