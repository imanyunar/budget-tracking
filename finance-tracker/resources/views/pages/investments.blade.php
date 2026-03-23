@extends('layouts.app')

@section('title', 'Investments · FinanceTracker')

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

@section('content')
<style>
    .inv-card {
        background:var(--surface); border:1px solid var(--border);
        border-radius:16px; padding:20px;
        box-shadow:var(--shadow-sm); transition:all 0.2s;
        position:relative; overflow:hidden;
    }
    .inv-card:hover { box-shadow:var(--shadow); transform:translateY(-2px); }
    .inv-card.stock { border-top:3px solid#3b82f6; }
    .inv-card.crypto { border-top:3px solid #f59e0b; }
    .live-dot {
        width:7px; height:7px; border-radius:50%;
        background:var(--success); display:inline-block;
        animation:blink 1.8s ease infinite;
    }
    .inv-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin:12px 0; }
    .inv-metric {
        background:var(--surface-2); border:1px solid var(--border);
        border-radius:9px; padding:10px 12px;
    }
    .inv-metric-label { font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:0.07em; margin-bottom:3px; }
    .inv-metric-value { font-size:13.5px; font-weight:700; color:var(--text); }
    .pl-row {
        display:flex; align-items:center; justify-content:space-between;
        padding:10px 14px; background:var(--surface-2);
        border:1px solid var(--border); border-radius:9px; margin-top:8px;
    }
    .section-divider { display:flex; align-items:center; gap:12px; margin:20px 0 14px; }
    .section-divider hr { flex:1; border:none; border-top:1px solid var(--border); }
    .section-divider span { font-size:11.5px; font-weight:700; color:var(--text-2); white-space:nowrap; }
    .summary-card { background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:18px 22px; box-shadow:var(--shadow-sm); }
</style>

<div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title-main">Investment Portfolio</h1>
            <div style="display:flex;align-items:center;gap:12px;margin-top:5px;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted);">
                    <span class="live-dot"></span> Live prices
                </div>
                <span style="color:var(--border-2);">·</span>
                <span style="font-size:12px;color:var(--blue);font-weight:500;">Stocks via Yahoo Finance</span>
                <span style="font-size:12px;color:#f59e0b;font-weight:500;">Crypto via Binance</span>
            </div>
        </div>
        <button onclick="openAddModal()" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            Add Asset
        </button>
    </div>

    <!-- Summary -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
        <div class="summary-card">
            <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">Total Invested</div>
            <div style="font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.03em;">{{ number_format($totalInvested, 0, ',', '.') }}</div>
            <div style="font-size:11.5px;color:var(--muted);margin-top:3px;">IDR · Capital deployed</div>
        </div>
        <div class="summary-card">
            <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">Current Value</div>
            <div style="font-size:22px;font-weight:800;color:var(--blue);letter-spacing:-0.03em;">{{ number_format($totalCurrentValue, 0, ',', '.') }}</div>
            <div style="font-size:11.5px;color:var(--muted);margin-top:3px;">IDR · Live market price</div>
        </div>
        <div class="summary-card">
            <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">Unrealized P/L</div>
            <div style="display:flex;align-items:baseline;gap:8px;">
                <div style="font-size:22px;font-weight:800;color:{{ $totalProfit >= 0 ? 'var(--success)' : 'var(--danger)' }};letter-spacing:-0.03em;">
                    {{ $totalProfit >= 0 ? '+' : '' }}{{ number_format(abs($totalProfit), 0, ',', '.') }}
                </div>
                @if($totalInvested > 0)
                <span class="badge {{ $totalProfit >= 0 ? 'badge-up' : 'badge-down' }}">
                    {{ $totalProfit >= 0 ? '+' : '' }}{{ number_format(($totalProfit / $totalInvested) * 100, 2) }}%
                </span>
                @endif
            </div>
            <div style="font-size:11.5px;color:var(--muted);margin-top:3px;">IDR · Unrealized</div>
        </div>
    </div>

    @if($investments->isEmpty())
    <div style="text-align:center;padding:60px;border:1.5px dashed var(--border-2);border-radius:16px;">
        <div class="empty-state-icon" style="margin:0 auto 14px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
        </div>
        <div class="empty-state-title">No assets configured</div>
        <div class="empty-state-text" style="margin-bottom:20px;">Add stocks or crypto to start tracking your portfolio</div>
        <button onclick="openAddModal()" class="btn-primary">Add First Asset</button>
    </div>
    @else

    @php $stocks = $investments->where('asset_type', 'stock'); $cryptos = $investments->where('asset_type', 'crypto'); @endphp

    @if($stocks->count())
    <div>
        <div class="section-divider">
            <hr><span>📈 Stocks · {{ $stocks->count() }} instruments</span><hr>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;">
            @foreach($stocks as $inv)
            @php
                $pl = $inv->unrealized_profit ?? 0;
                $plPct = $inv->roi ?? 0;
                $up = $pl >= 0;
            @endphp
            <div class="inv-card stock">
                <div style="position:absolute;inset:0;background:radial-gradient(circle at top right,rgba(59,130,246,0.06) 0%,transparent 60%);pointer-events:none;"></div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;position:relative;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:38px;height:38px;border-radius:10px;background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#3b82f6;letter-spacing:-0.02em;">{{ substr($inv->symbol, 0, 2) }}</div>
                        <div>
                            <div style="font-size:14px;font-weight:700;color:var(--text);">{{ $inv->symbol }}</div>
                            <div style="display:flex;align-items:center;gap:5px;margin-top:1px;">
                                <span class="live-dot" style="width:5px;height:5px;"></span>
                                <span style="font-size:10.5px;color:var(--muted);font-weight:500;">LIVE</span>
                            </div>
                        </div>
                    </div>
                    <button onclick="openChartModal('{{ $inv->symbol }}')" class="btn-secondary" style="padding:6px 10px;font-size:11.5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                        Chart
                    </button>
                </div>
                <div class="inv-grid">
                    <div class="inv-metric"><div class="inv-metric-label">Avg Buy</div><div class="inv-metric-value">{{ number_format($inv->average_price, 0, ',', '.') }}</div></div>
                    <div class="inv-metric"><div class="inv-metric-label">Live Price</div><div class="inv-metric-value" style="color:#3b82f6;">{{ number_format($inv->current_price ?? 0, 0, ',', '.') }}</div></div>
                    <div class="inv-metric"><div class="inv-metric-label">Lots</div><div class="inv-metric-value">{{ number_format($inv->lots ?? 0, 0, ',', '.') }}</div></div>
                    <div class="inv-metric"><div class="inv-metric-label">Shares</div><div class="inv-metric-value">{{ number_format(($inv->lots ?? 0) * 100, 0, ',', '.') }}</div></div>
                </div>
                <div class="pl-row">
                    <div style="font-size:12px;font-weight:600;color:var(--muted);">Unrealized P/L</div>
                    <div style="text-align:right;">
                        <div style="font-size:13.5px;font-weight:700;color:{{ $up ? 'var(--success)' : 'var(--danger)' }};">{{ $up ? '+' : '' }}{{ number_format($pl, 0, ',', '.') }}</div>
                        <div class="badge {{ $up ? 'badge-up' : 'badge-down' }}" style="margin-top:3px;">{{ $up ? '+' : '' }}{{ number_format($plPct, 2) }}%</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($cryptos->count())
    <div>
        <div class="section-divider">
            <hr><span>₿ Crypto · {{ $cryptos->count() }} assets</span><hr>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;">
            @foreach($cryptos as $inv)
            @php
                $pl = $inv->unrealized_profit ?? 0;
                $plPct = $inv->roi ?? 0;
                $up = $pl >= 0;
            @endphp
            <div class="inv-card crypto">
                <div style="position:absolute;inset:0;background:radial-gradient(circle at top right,rgba(245,158,11,0.06) 0%,transparent 60%);pointer-events:none;"></div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;position:relative;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#f59e0b;letter-spacing:-0.02em;">{{ substr($inv->symbol, 0, 3) }}</div>
                        <div>
                            <div style="font-size:14px;font-weight:700;color:var(--text);">{{ $inv->symbol }}</div>
                            <div style="display:flex;align-items:center;gap:5px;margin-top:1px;">
                                <span class="live-dot" style="width:5px;height:5px;background:#f59e0b;"></span>
                                <span style="font-size:10.5px;color:var(--muted);font-weight:500;">BINANCE</span>
                            </div>
                        </div>
                    </div>
                    <button onclick="openChartModal('{{ $inv->symbol }}')" class="btn-secondary" style="padding:6px 10px;font-size:11.5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                        Chart
                    </button>
                </div>
                <div class="inv-grid">
                    <div class="inv-metric"><div class="inv-metric-label">Avg Buy</div><div class="inv-metric-value">{{ number_format($inv->average_price, 0, ',', '.') }}</div></div>
                    <div class="inv-metric"><div class="inv-metric-label">Live Price</div><div class="inv-metric-value" style="color:#f59e0b;">{{ number_format($inv->current_price ?? 0, 0, ',', '.') }}</div></div>
                    <div class="inv-metric"><div class="inv-metric-label">Quantity</div><div class="inv-metric-value">{{ number_format($inv->quantity ?? 0, 4) }}</div></div>
                    <div class="inv-metric"><div class="inv-metric-label">Value (IDR)</div><div class="inv-metric-value">{{ number_format($inv->current_value ?? 0, 0, ',', '.') }}</div></div>
                </div>
                <div class="pl-row">
                    <div style="font-size:12px;font-weight:600;color:var(--muted);">Unrealized P/L</div>
                    <div style="text-align:right;">
                        <div style="font-size:13.5px;font-weight:700;color:{{ $up ? 'var(--success)' : 'var(--danger)' }};">{{ $up ? '+' : '' }}{{ number_format($pl, 0, ',', '.') }}</div>
                        <div class="badge {{ $up ? 'badge-up' : 'badge-down' }}" style="margin-top:3px;">{{ $up ? '+' : '' }}{{ number_format($plPct, 2) }}%</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

</div>

<!-- Add Asset Modal -->
<div id="addModal" class="modal-overlay" onclick="if(event.target===this)closeAddModal()">
    <div class="modal-box" id="addModalBox">
        <div class="modal-header">
            <div><div class="modal-title">Add Asset</div><div class="modal-subtitle">Add investment to your portfolio</div></div>
            <button class="modal-close" onclick="closeAddModal()"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg></button>
        </div>

        <div class="type-toggle">
            <button type="button" onclick="setAssetType('stock')" id="btn-stock" class="type-btn active-income" style="color:#3b82f6;background:rgba(59,130,246,0.09);border-color:rgba(59,130,246,0.25);">Stocks</button>
            <button type="button" onclick="setAssetType('crypto')" id="btn-crypto" class="type-btn">Crypto</button>
        </div>

        <form action="{{ route('investments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="asset_type" id="assetType" value="stock">
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label class="input-label" id="labelSymbol">Symbol (e.g. BBCA.JK)</label>
                    <input type="text" name="symbol" id="fieldSymbol" required placeholder="BBCA.JK" class="input-field" style="text-transform:uppercase;">
                </div>
                <div>
                    <label class="input-label" id="labelAvgPrice">Avg Buy Price (IDR)</label>
                    <input type="number" name="average_price" required placeholder="0" class="input-field">
                </div>
                <div id="fieldLotsWrap">
                    <label class="input-label">Lots (1 lot = 100 shares)</label>
                    <input type="number" name="lots" id="fieldLots" placeholder="0" class="input-field">
                </div>
                <div id="fieldQtyWrap" style="display:none;">
                    <label class="input-label">Quantity (e.g. 0.01)</label>
                    <input type="number" name="quantity" id="fieldQty" placeholder="0" step="any" class="input-field" disabled>
                </div>
@if(isset($wallets) && $wallets->count())
                <div>
                    <label class="input-label">Source Wallet <span style="color:var(--danger);">*</span></label>
                    <select name="wallet_id" class="input-field" required>
                        <option value="">— No wallet link —</option>
                        @foreach($wallets as $w)<option value="{{ $w->id }}" {{ old('wallet_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>@endforeach
                    </select>
                </div>
                @endif
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;margin-top:4px;">Add to Portfolio</button>
            </div>
        </form>
    </div>
</div>

<!-- Chart Modal -->
<div id="chartModal" class="modal-overlay" onclick="if(event.target===this)closeChartModal()">
    <div class="modal-box" id="chartModalBox" style="max-width:640px;">
        <div class="modal-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <div id="chartAssetIconBox" style="width:38px;height:38px;border-radius:10px;background:var(--primary-dim);border:1px solid var(--primary-mid);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:var(--primary);">—</div>
                <div>
                    <div class="modal-title" id="chartAssetSymbol">—</div>
                    <div class="modal-subtitle">Price history</div>
                </div>
            </div>
            <button class="modal-close" onclick="closeChartModal()"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg></button>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">
            <div class="inv-metric"><div class="inv-metric-label">Avg Buy Price</div><div class="inv-metric-value" id="chartAvgPrice">—</div></div>
            <div class="inv-metric"><div class="inv-metric-label">Total Qty</div><div class="inv-metric-value" id="chartTotalQty">—</div></div>
        </div>
        <div id="chartLoading" style="display:flex;align-items:center;justify-content:center;height:200px;color:var(--muted);font-size:13px;">Loading chart...</div>
        <div id="priceChart"></div>
    </div>
</div>

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('addModal').classList.add('open');
        setTimeout(() => document.getElementById('addModalBox').classList.add('open'), 10);
        document.body.style.overflow = 'hidden';
    }
    function closeAddModal() {
        document.getElementById('addModalBox').classList.remove('open');
        setTimeout(() => { document.getElementById('addModal').classList.remove('open'); document.body.style.overflow = ''; }, 250);
    }
    function setAssetType(type) {
        document.getElementById('assetType').value = type;
        const isCrypto = type === 'crypto';
        const btnStock = document.getElementById('btn-stock');
        const btnCrypto = document.getElementById('btn-crypto');
        btnStock.style.cssText = !isCrypto ? 'color:#3b82f6;background:rgba(59,130,246,0.09);border-color:rgba(59,130,246,0.25);' : '';
        btnCrypto.style.cssText = isCrypto ? 'color:#f59e0b;background:rgba(245,158,11,0.09);border-color:rgba(245,158,11,0.25);' : '';
        document.getElementById('fieldLotsWrap').style.display = isCrypto ? 'none' : 'block';
        document.getElementById('fieldQtyWrap').style.display = isCrypto ? 'block' : 'none';
        document.getElementById('fieldLots').disabled = isCrypto;
        document.getElementById('fieldQty').disabled = !isCrypto;
        document.getElementById('labelSymbol').textContent = isCrypto ? 'Crypto Symbol (e.g. BTC, ETH)' : 'Stock Symbol (e.g. BBCA.JK)';
        document.getElementById('fieldSymbol').placeholder = isCrypto ? 'BTC' : 'BBCA.JK';
        document.getElementById('labelAvgPrice').textContent = isCrypto ? 'Avg Buy Price (IDR/unit)' : 'Avg Buy Price (IDR/share)';
    }
    document.getElementById('fieldSymbol').addEventListener('input', function() { this.value = this.value.toUpperCase(); });

    let apexInstance = null;
    function openChartModal(symbol) {
        document.getElementById('chartAssetSymbol').textContent = symbol;
        document.getElementById('chartAssetIconBox').textContent = symbol.substring(0, 2);
        document.getElementById('chartAvgPrice').textContent = '...';
        document.getElementById('chartTotalQty').textContent = '...';
        document.getElementById('chartLoading').style.display = 'flex';
        document.getElementById('priceChart').innerHTML = '';
        if (apexInstance) { apexInstance.destroy(); apexInstance = null; }
        document.getElementById('chartModal').classList.add('open');
        setTimeout(() => document.getElementById('chartModalBox').classList.add('open'), 10);
        document.body.style.overflow = 'hidden';
        fetch(`/investments/${symbol}/chart`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('chartLoading').style.display = 'none';
                if (data.error) { alert('Failed to load chart.'); return; }
                document.getElementById('chartAvgPrice').textContent = new Intl.NumberFormat('id-ID').format(data.average_price);
                document.getElementById('chartTotalQty').textContent = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 8 }).format(data.total_quantity);
                renderChart(data.data, data.name || symbol, data.average_price);
            })
            .catch(() => { document.getElementById('chartLoading').style.display = 'none'; });
    }
    function closeChartModal() {
        document.getElementById('chartModalBox').classList.remove('open');
        setTimeout(() => { document.getElementById('chartModal').classList.remove('open'); document.body.style.overflow = ''; if(apexInstance){apexInstance.destroy();apexInstance=null;} }, 250);
    }
    function renderChart(seriesData, name, avgPrice) {
        apexInstance = new ApexCharts(document.getElementById('priceChart'), {
            series: [{ name: 'Price (IDR)', data: seriesData }],
            chart: { type: 'area', height: 260, fontFamily: "'Inter', sans-serif", toolbar: { show: false }, background: 'transparent', animations: { enabled: true, speed: 600 } },
            colors: ['#6366f1'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.2, opacityTo: 0.01, stops: [0, 90] } },
            stroke: { curve: 'smooth', width: 2.5 },
            dataLabels: { enabled: false },
            xaxis: { type: 'datetime', labels: { style: { colors: '#94a3b8', fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { labels: { formatter: v => new Intl.NumberFormat('id-ID').format(v), style: { colors: '#94a3b8', fontSize: '11px' } } },
            grid: { borderColor: 'rgba(15,23,42,0.06)', strokeDashArray: 4 },
            tooltip: { theme: 'light', x: { format: 'dd MMM yyyy' }, y: { formatter: v => 'IDR ' + new Intl.NumberFormat('id-ID').format(v) } },
            annotations: { yaxis: [{ y: avgPrice, borderColor: '#f59e0b', strokeDashArray: 4, label: { borderColor: '#f59e0b', style: { color: '#fff', background: '#f59e0b', fontSize: '10px', fontWeight: 700 }, text: 'AVG BUY' } }] },
            theme: { mode: 'light' }
        });
        apexInstance.render();
    }
</script>
@endpush
@endsection