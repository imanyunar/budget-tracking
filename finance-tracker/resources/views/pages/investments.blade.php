@extends('layouts.app')

@section('title', 'Investments - FinanceTracker')

@push('head-scripts')
<style>
    .inv-card { transition: all 0.2s ease; }
    .inv-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px -6px rgba(99,102,241,0.18); }
    .crypto-card:hover { box-shadow: 0 8px 30px -6px rgba(245,158,11,0.18); }
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.6;transform:scale(1.4);} }
    .live-dot { animation: pulse-dot 2s ease-in-out infinite; }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Investment Portfolio</h1>
            <div class="flex items-center gap-3 mt-1 flex-wrap">
                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 live-dot inline-block"></span>
                    Live prices
                </span>
                <span class="text-slate-300">|</span>
                <span class="text-xs font-semibold text-indigo-500">📈 Stocks via Yahoo Finance</span>
                <span class="text-xs font-semibold text-amber-500">₿ Crypto via Binance</span>
            </div>
        </div>
        <button onclick="openModal()" id="addAssetBtn"
            class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all">
            <x-icon name="plus" class="w-4 h-4" /> Add Asset
        </button>
    </div>

    {{-- ===== SUMMARY ROW ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Total Invested --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Total Invested</p>
            <p class="text-2xl font-black text-slate-900">IDR {{ number_format($totalInvested, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">Modal yang ditanamkan</p>
        </div>
        {{-- Current Value --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nilai Sekarang</p>
            <p class="text-2xl font-black text-sky-600">IDR {{ number_format($totalCurrentValue, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">Harga pasar live</p>
        </div>
        {{-- Unrealized P/L --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Unrealized P/L</p>
            <div class="flex items-end gap-2">
                <p class="text-2xl font-black {{ $totalProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $totalProfit >= 0 ? '+' : '' }}IDR {{ number_format(abs($totalProfit), 0, ',', '.') }}
                </p>
                @if($totalInvested > 0)
                <span class="mb-0.5 px-2 py-0.5 text-xs font-bold rounded-lg {{ $totalProfit >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                    {{ $totalProfit >= 0 ? '+' : '' }}{{ number_format(($totalProfit / $totalInvested) * 100, 2) }}%
                </span>
                @endif
            </div>
            <p class="text-xs text-slate-400 mt-1">Belum direalisasikan</p>
        </div>
    </div>

    {{-- ===== PORTFOLIO CARDS ===== --}}
    @if($investments->isEmpty())
    <div class="bg-white border-2 border-dashed border-slate-200 rounded-2xl p-16 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-indigo-50 flex items-center justify-center">
            <x-icon name="trending-up" class="w-8 h-8 text-indigo-400" />
        </div>
        <h3 class="text-base font-bold text-slate-700 mb-1">Belum ada aset</h3>
        <p class="text-sm text-slate-400 mb-4">Mulai tambahkan saham atau crypto kamu</p>
        <button onclick="openModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:-translate-y-0.5 transition-all">
            <x-icon name="plus" class="w-4 h-4" /> Tambah Aset Pertama
        </button>
    </div>
    @else

    {{-- Stock section --}}
    @php 
        $stocks = $investments->where('asset_type', 'stock'); 
        $cryptos = $investments->where('asset_type', 'crypto'); 
    @endphp

    @if($stocks->count())
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center shadow-sm">
                    <x-icon name="trending-up" class="w-4 h-4 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">Saham / Stocks</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $stocks->count() }} INSTRUMEN</p>
                </div>
            </div>
            
            {{-- Stock Sub-summary --}}
            <div class="flex items-center gap-4 bg-white border border-slate-100 rounded-2xl px-4 py-2 shadow-sm">
                <div class="text-center sm:text-left">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Stock Value</p>
                    <p class="text-xs font-black text-slate-700">IDR {{ number_format($stockCurrentValue, 0, ',', '.') }}</p>
                </div>
                <div class="w-px h-6 bg-slate-100"></div>
                <div class="text-center sm:text-left">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Stock P/L</p>
                    <p class="text-xs font-black {{ $stockProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $stockProfit >= 0 ? '+' : '-' }}IDR {{ number_format(abs($stockProfit), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stocks as $inv)
            {{-- ... item card ... --}}
            <div class="inv-card bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col gap-4">
                {{-- Top row --}}
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-md shadow-indigo-500/30 shrink-0">
                            <span class="text-white font-black text-sm">{{ strtoupper(substr($inv->symbol, 0, 2)) }}</span>
                        </div>
                        <div>
                            <p class="font-black text-slate-900 leading-tight">{{ $inv->symbol }}</p>
                            <p class="text-[11px] text-slate-400 font-semibold mt-0.5">{{ $inv->display_volume }} Lot · {{ $inv->display_volume * 100 }} Lembar</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full
                            {{ $inv->price_source === 'yahoo' ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-500' }}">
                            {{ $inv->price_source === 'yahoo' ? '● Live' : '○ Fallback' }}
                        </span>
                    </div>
                </div>
                {{-- Price row --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Avg Beli</p>
                        <p class="text-sm font-bold text-slate-700">{{ number_format($inv->average_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-indigo-50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-0.5">Harga Kini</p>
                        <p class="text-sm font-bold text-indigo-700">{{ number_format($inv->current_price, 0, ',', '.') }}</p>
                    </div>
                </div>
                {{-- Value + P/L --}}
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Nilai</p>
                        <p class="text-base font-black text-slate-900">IDR {{ number_format($inv->current_value, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1.5 rounded-xl text-xs font-black {{ $inv->roi >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $inv->roi >= 0 ? '+' : '' }}{{ number_format($inv->roi, 2) }}%
                        </span>
                        <p class="text-[10px] {{ $inv->unrealized_profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold mt-1">
                            {{ $inv->unrealized_profit >= 0 ? '+' : '' }}IDR {{ number_format(abs($inv->unrealized_profit), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                {{-- Actions --}}
                <div class="flex items-center gap-2 -mt-1">
                    <button type="button" 
                        onclick="openSellModal({{ json_encode($inv) }})"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-bold text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl transition-colors">
                        <x-icon name="dollar-sign" class="w-3.5 h-3.5" /> Jual
                    </button>
                    <button type="button" 
                        onclick="openEditModal({{ json_encode($inv) }})"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-bold text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                        <x-icon name="edit-3" class="w-3.5 h-3.5" /> Edit
                    </button>
                    <form action="{{ route('investments.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Hapus aset ini?')" class="flex-1 text-center">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 text-xs font-bold text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                            <x-icon name="trash-2" class="w-3.5 h-3.5" /> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Crypto section --}}
    @if($cryptos->count())
    <div class="mt-10 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center shadow-sm">
                    <span class="text-amber-600 font-black text-xs">₿</span>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">Cryptocurrency</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $cryptos->count() }} INSTRUMEN</p>
                </div>
            </div>

            {{-- Crypto Sub-summary --}}
            <div class="flex items-center gap-4 bg-white border border-amber-100 rounded-2xl px-4 py-2 shadow-sm">
                <div class="text-center sm:text-left">
                    <p class="text-[9px] font-bold text-amber-500 uppercase tracking-tighter">Crypto Value</p>
                    <p class="text-xs font-black text-slate-700">IDR {{ number_format($cryptoCurrentValue, 0, ',', '.') }}</p>
                </div>
                <div class="w-px h-6 bg-amber-100"></div>
                <div class="text-center sm:text-left">
                    <p class="text-[9px] font-bold text-amber-500 uppercase tracking-tighter">Crypto P/L</p>
                    <p class="text-xs font-black {{ $cryptoProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $cryptoProfit >= 0 ? '+' : '-' }}IDR {{ number_format(abs($cryptoProfit), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($cryptos as $inv)
            <div class="inv-card crypto-card bg-white border border-amber-100 rounded-2xl p-5 shadow-sm flex flex-col gap-4" style="background: linear-gradient(135deg, #fff 70%, #fffbeb 100%);">
                {{-- Top row --}}
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-md shadow-amber-500/30 shrink-0">
                            <span class="text-white font-black text-sm">{{ strtoupper(substr($inv->symbol, 0, 2)) }}</span>
                        </div>
                        <div>
                            <p class="font-black text-slate-900 leading-tight">{{ $inv->symbol }}</p>
                            <p class="text-[11px] text-slate-400 font-semibold mt-0.5">{{ number_format((float)$inv->display_volume, 8, '.', '') }} Unit</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full
                            {{ in_array($inv->price_source, ['binance', 'yahoo', 'yahoo_fx']) ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-500' }}">
                            {{ in_array($inv->price_source, ['binance', 'yahoo', 'yahoo_fx']) ? '● Live' : '○ Fallback' }}
                        </span>
                    </div>
                </div>
                {{-- Price row --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Avg Beli</p>
                        <p class="text-sm font-bold text-slate-700">IDR {{ number_format($inv->average_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-amber-500 uppercase tracking-wider mb-0.5">Harga Kini</p>
                        <p class="text-sm font-bold text-amber-700">IDR {{ number_format($inv->current_price, 2, ',', '.') }}</p>
                        @if(isset($inv->price_usd))
                        <p class="text-[10px] text-amber-400 mt-0.5">≈ ${{ number_format($inv->price_usd, 4) }}</p>
                        @endif
                    </div>
                </div>
                {{-- Value + P/L --}}
                <div class="pt-3 border-t border-amber-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Nilai</p>
                        <p class="text-base font-black text-slate-900">IDR {{ number_format($inv->current_value, 2, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1.5 rounded-xl text-xs font-black {{ $inv->roi >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $inv->roi >= 0 ? '+' : '' }}{{ number_format($inv->roi, 2) }}%
                        </span>
                        <p class="text-[10px] {{ $inv->unrealized_profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold mt-1">
                            {{ $inv->unrealized_profit >= 0 ? '+' : '' }}IDR {{ number_format(abs($inv->unrealized_profit), 2, ',', '.') }}
                        </p>
                    </div>
                </div>
                {{-- Actions --}}
                <div class="flex items-center gap-2 -mt-1">
                    <button type="button" 
                        onclick="openSellModal({{ json_encode($inv) }})"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-bold text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl transition-colors">
                        <x-icon name="dollar-sign" class="w-3.5 h-3.5" /> Jual
                    </button>
                    <button type="button" 
                        onclick="openEditModal({{ json_encode($inv) }})"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-bold text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-colors">
                        <x-icon name="edit-3" class="w-3.5 h-3.5" /> Edit
                    </button>
                    <form action="{{ route('investments.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Hapus aset ini?')" class="flex-1 text-center">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 text-xs font-bold text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                            <x-icon name="trash-2" class="w-3.5 h-3.5" /> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @endif
</div>

{{-- ===== ADD ASSET MODAL ===== --}}
<div id="invModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div id="invModalContent" class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 transform scale-95 opacity-0 transition-all duration-200 relative z-10 overflow-hidden">

        {{-- Modal header gradient --}}
        <div id="modalHeader" class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 pt-6 pb-5">
            <div class="flex items-center justify-between mb-1">
                <h2 class="text-lg font-black text-white">Tambah Aset</h2>
                <button onclick="closeModal()" class="p-1.5 text-white/70 hover:text-white hover:bg-white/10 rounded-xl">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>
            {{-- Type selector --}}
            <div class="flex gap-2 mt-3">
                <button type="button" id="btnStock" onclick="setType('stock')"
                    class="flex-1 py-2 rounded-xl text-sm font-bold bg-white text-indigo-700 shadow transition-all">
                    📈 Saham
                </button>
                <button type="button" id="btnCrypto" onclick="setType('crypto')"
                    class="flex-1 py-2 rounded-xl text-sm font-bold text-white/70 hover:text-white hover:bg-white/10 transition-all">
                    ₿ Crypto
                </button>
            </div>
        </div>

        {{-- Form body --}}
        <form action="{{ route('investments.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="asset_type" id="assetTypeInput" value="stock">

            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 mb-2" id="addWalletSelectWrap">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-indigo-600 mb-2">Pilih Sumber Dana (Wallet)</label>
                <select name="wallet_id" required class="w-full bg-white border border-indigo-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-200 transition-all">
                    <option value="">-- Pilih Wallet --</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }} (IDR {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-indigo-500 mt-2 font-medium">Saldo akan dipotong untuk pembelian ini.</p>
            </div>

            <div>
                <label id="labelSymbol" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                    Kode Saham (Yahoo Finance, e.g. BBCA.JK)
                </label>
                <input type="text" name="symbol" id="fieldSymbol" required placeholder="BBCA.JK"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold uppercase text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div id="fieldLotsWrap">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Lot (1 Lot = 100 Lembar)</label>
                    <input type="number" name="lots" id="fieldLots" placeholder="10" min="1"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                </div>
                <div id="fieldQtyWrap" class="hidden">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Jumlah Unit</label>
                    <input type="number" name="quantity" id="fieldQty" placeholder="0.5" min="0.00000001" step="any"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition-all">
                </div>
                <div>
                    <label id="labelAvgPrice" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Avg Beli (IDR/unit)</label>
                    <input type="number" name="average_price" required placeholder="9500" min="0" step="any"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                </div>
            </div>

            <p id="cryptoHint" class="hidden text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5 leading-relaxed">
                💡 Symbol Binance tanpa "USDT", contoh: <strong>BTC</strong>, <strong>ETH</strong>, <strong>SOL</strong>, <strong>BNB</strong>.<br>
                Harga akan diambil real-time dari Binance API & dikonversi ke IDR.
            </p>

            <button type="submit" id="btnSubmit"
                class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 hover:-translate-y-0.5 transition-all">
                Konfirmasi Pembelian
            </button>
        </form>
    </div>
</div>

{{-- ===== EDIT ASSET MODAL ===== --}}
{{-- ===== SELL ASSET MODAL ===== --}}
<div id="sellInvModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeSellModal()"></div>
    <div id="sellInvModalContent" class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 transform scale-95 opacity-0 transition-all duration-200 relative z-10 overflow-hidden">
        
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 pt-6 pb-5">
            <div class="flex items-center justify-between mb-1">
                <h2 class="text-lg font-black text-white">Jual Aset</h2>
                <button onclick="closeSellModal()" class="p-1.5 text-white/70 hover:text-white hover:bg-white/10 rounded-xl">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>
            <p id="sellModalSubtitle" class="text-white/80 text-xs font-bold uppercase tracking-widest"></p>
        </div>

        <form id="sellInvForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-2">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-emerald-600 mb-2">Pilih Wallet Tujuan</label>
                <select name="wallet_id" required class="w-full bg-white border border-emerald-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-200">
                    <option value="">-- Pilih Wallet --</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }} (IDR {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-emerald-500 mt-2 font-medium">Uang hasil penjualan akan otomatis masuk ke wallet ini.</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label id="sellLabelAmount" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Jumlah Dijual</label>
                    <input type="number" name="sell_amount" id="sellFieldAmount" required min="0" step="any" oninput="updateProceeds()"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:border-emerald-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Harga Jual (IDR/unit)</label>
                    <input type="number" name="sell_price" id="sellFieldPrice" required min="0" step="any" oninput="updateProceeds()"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:border-emerald-500 outline-none transition-all">
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Dana Diterima</p>
                <p class="text-xl font-black text-slate-900" id="sellTotalDisplay">IDR 0</p>
            </div>

            <div id="sellHighVolumeWarning" class="hidden animate-bounce-subtle bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
                <x-icon name="alert-triangle" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                <div>
                    <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest leading-tight">High Volume Warning</p>
                    <p class="text-[10px] font-bold text-amber-800 leading-tight mt-0.5">Penarikan ini ≥ 40% dari total aset Anda. Lanjutkan dengan hati-hati!</p>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/25 hover:-translate-y-0.5 transition-all">
                Konfirmasi Penjualan
            </button>
        </form>
    </div>
</div>

<div id="editInvModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div id="editInvModalContent" class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 transform scale-95 opacity-0 transition-all duration-200 relative z-10 overflow-hidden">

        {{-- Modal header gradient --}}
        <div id="editModalHeader" class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 pt-6 pb-5">
            <div class="flex items-center justify-between mb-1">
                <h2 class="text-lg font-black text-white">Edit Aset</h2>
                <button onclick="closeEditModal()" class="p-1.5 text-white/70 hover:text-white hover:bg-white/10 rounded-xl">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>
            <p id="editTypeText" class="text-white/80 text-xs font-bold uppercase tracking-widest"></p>
        </div>

        {{-- Form body --}}
        <form id="editInvForm" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="asset_type" id="editAssetType">

            <div>
                <label id="editLabelSymbol" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5"></label>
                <input type="text" name="symbol" id="editFieldSymbol" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold uppercase text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div id="editFieldLotsWrap">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Lot</label>
                    <input type="number" name="lots" id="editFieldLots" min="1"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                </div>
                <div id="editFieldQtyWrap" class="hidden">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Jumlah Unit</label>
                    <input type="number" name="quantity" id="editFieldQty" min="0.00000001" step="any"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition-all">
                </div>
                <div>
                    <label id="editLabelAvgPrice" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Avg Beli</label>
                    <input type="number" name="average_price" id="editFieldAvgPrice" required min="0" step="any"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                </div>
            </div>

            <button type="submit" id="editBtnSubmit"
                class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 hover:-translate-y-0.5 transition-all">
                Update Aset
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('invModal');
    const modalContent = document.getElementById('invModalContent');
    const editModal = document.getElementById('editInvModal');
    const editModalContent = document.getElementById('editInvModalContent');
    const sellModal = document.getElementById('sellInvModal');
    const sellModalContent = document.getElementById('sellInvModalContent');

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

    function openEditModal(inv) {
        const isCrypto = inv.asset_type === 'crypto';
        const form = document.getElementById('editInvForm');
        form.action = `/investments/${inv.id}`;

        document.getElementById('editAssetType').value = inv.asset_type;
        document.getElementById('editFieldSymbol').value = inv.symbol;
        document.getElementById('editFieldAvgPrice').value = inv.average_price;
        document.getElementById('editFieldLots').value = inv.lots;
        document.getElementById('editFieldQty').value = inv.quantity;

        const header = document.getElementById('editModalHeader');
        const submit = document.getElementById('editBtnSubmit');
        const typeText = document.getElementById('editTypeText');

        if (isCrypto) {
            header.className = 'bg-gradient-to-r from-amber-500 to-orange-500 px-6 pt-6 pb-5';
            submit.className = 'w-full py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl shadow-lg shadow-amber-500/25 hover:-translate-y-0.5 transition-all';
            typeText.innerText = '₿ CRYPTOCURRENCY';
            
            document.getElementById('editFieldLotsWrap').classList.add('hidden');
            document.getElementById('editFieldQtyWrap').classList.remove('hidden');
            
            document.getElementById('editLabelSymbol').innerText = 'KODE CRYPTO (BINANCE)';
            document.getElementById('editLabelAvgPrice').innerText = 'AVG BELI (IDR/UNIT)';
            
            // Disable hidden field & enable visible one to avoid validation/redundancy
            document.getElementById('editFieldLots').disabled = true;
            document.getElementById('editFieldQty').disabled = false;
            document.getElementById('editFieldQty').setAttribute('required', 'required');
        } else {
            header.className = 'bg-gradient-to-r from-indigo-600 to-violet-600 px-6 pt-6 pb-5';
            submit.className = 'w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 hover:-translate-y-0.5 transition-all';
            typeText.innerText = '📈 STOCK / SAHAM';
            
            document.getElementById('editFieldLotsWrap').classList.remove('hidden');
            document.getElementById('editFieldQtyWrap').classList.add('hidden');
            
            document.getElementById('editLabelSymbol').innerText = 'KODE SAHAM (YAHOO FINANCE)';
            document.getElementById('editLabelAvgPrice').innerText = 'AVG BELI (IDR/LEMBAR)';
            
            // Disable hidden field & enable visible one to avoid validation/redundancy
            document.getElementById('editFieldLots').disabled = false;
            document.getElementById('editFieldLots').setAttribute('required', 'required');
            document.getElementById('editFieldQty').disabled = true;
        }

        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            editModalContent.classList.remove('scale-95', 'opacity-0', 'pointer-events-none');
            editModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeEditModal() {
        editModalContent.classList.remove('scale-100', 'opacity-100');
        editModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    // --- SELL MODAL LOGIC ---
    let currentSellAssetType = 'stock';
    function openSellModal(inv) {
        currentSellAssetType = inv.asset_type || 'stock';
        const form = document.getElementById('sellInvForm');
        form.action = `/investments/${inv.id}/sell`;

        document.getElementById('sellModalSubtitle').innerText = `${inv.symbol} - ${inv.asset_type.toUpperCase()}`;
        document.getElementById('sellFieldAmount').value = '';
        document.getElementById('sellFieldPrice').value = Math.round(inv.current_price);
        
        if (currentSellAssetType === 'stock') {
            document.getElementById('sellLabelAmount').innerText = 'Jumlah Lot';
            document.getElementById('sellFieldAmount').max = inv.lots;
            document.getElementById('sellFieldAmount').placeholder = `Max: ${inv.lots}`;
        } else {
            document.getElementById('sellLabelAmount').innerText = 'Jumlah Unit';
            document.getElementById('sellFieldAmount').max = inv.quantity;
            document.getElementById('sellFieldAmount').placeholder = `Max: ${inv.quantity}`;
        }

        updateProceeds();

        sellModal.classList.remove('hidden');
        sellModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            sellModalContent.classList.remove('scale-95', 'opacity-0');
            sellModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeSellModal() {
        sellModalContent.classList.remove('scale-100', 'opacity-100');
        sellModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            sellModal.classList.add('hidden');
            sellModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    function updateProceeds() {
        const amount = parseFloat(document.getElementById('sellFieldAmount').value) || 0;
        const price = parseFloat(document.getElementById('sellFieldPrice').value) || 0;
        
        const currentSymbol = document.getElementById('sellModalSubtitle').innerText.split(' - ')[0];
        let proceeds = amount * price;

        if (currentSellAssetType === 'stock') {
            proceeds = (amount * 100) * price; // 1 lot = 100 shares
        }
        
        document.getElementById('sellTotalDisplay').innerText = 'IDR ' + new Intl.NumberFormat('id-ID').format(Math.round(proceeds));

        // 40% threshold warning
        const warningDiv = document.getElementById('sellHighVolumeWarning');
        // Extract the numeric value from the label text, assuming it's in the format "Label: 123.45" or "Label 123.45"
        const totalOwnedText = document.getElementById('sellFieldAmount').max; // Use max attribute for total owned
        const totalOwned = parseFloat(totalOwnedText) || 0;
        
        if (amount >= (totalOwned * 0.4) && totalOwned > 0) { // Add check for totalOwned > 0 to avoid division by zero or false positive
            warningDiv.classList.remove('hidden');
        } else {
            warningDiv.classList.add('hidden');
        }
    }

    function setType(type) {
        const isCrypto = type === 'crypto';
        document.getElementById('assetTypeInput').value = type;

        const header = document.getElementById('modalHeader');
        const btnStock  = document.getElementById('btnStock');
        const btnCrypto = document.getElementById('btnCrypto');
        const submit    = document.getElementById('btnSubmit');

        if (isCrypto) {
            header.className = 'bg-gradient-to-r from-amber-500 to-orange-500 px-6 pt-6 pb-5';
            btnCrypto.className = 'flex-1 py-2 rounded-xl text-sm font-bold bg-white text-amber-700 shadow transition-all';
            btnStock.className  = 'flex-1 py-2 rounded-xl text-sm font-bold text-white/70 hover:text-white hover:bg-white/10 transition-all';
            submit.className    = 'w-full py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl shadow-lg shadow-amber-500/25 hover:-translate-y-0.5 transition-all';
            
            // Adjust wallet wrapper color
            const walletWrap = document.getElementById('addWalletSelectWrap');
            if(walletWrap) {
                walletWrap.className = 'bg-amber-50 border border-amber-100 rounded-2xl p-4 mb-2';
                walletWrap.querySelector('label').className = 'block text-[10px] font-bold uppercase tracking-wider text-amber-600 mb-2';
                walletWrap.querySelector('select').className = 'w-full bg-white border border-amber-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-amber-200 transition-all';
                walletWrap.querySelector('p').className = 'text-[10px] text-amber-500 mt-2 font-medium';
            }
        } else {
            header.className = 'bg-gradient-to-r from-indigo-600 to-violet-600 px-6 pt-6 pb-5';
            btnStock.className  = 'flex-1 py-2 rounded-xl text-sm font-bold bg-white text-indigo-700 shadow transition-all';
            btnCrypto.className = 'flex-1 py-2 rounded-xl text-sm font-bold text-white/70 hover:text-white hover:bg-white/10 transition-all';
            submit.className    = 'w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 hover:-translate-y-0.5 transition-all';
            
            // Adjust wallet wrapper color
            const walletWrap = document.getElementById('addWalletSelectWrap');
            if(walletWrap) {
                walletWrap.className = 'bg-indigo-50 border border-indigo-100 rounded-2xl p-4 mb-2';
                walletWrap.querySelector('label').className = 'block text-[10px] font-bold uppercase tracking-wider text-indigo-600 mb-2';
                walletWrap.querySelector('select').className = 'w-full bg-white border border-indigo-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-200 transition-all';
                walletWrap.querySelector('p').className = 'text-[10px] text-indigo-500 mt-2 font-medium';
            }
        }


        document.getElementById('fieldLotsWrap').classList.toggle('hidden', isCrypto);
        document.getElementById('fieldQtyWrap').classList.toggle('hidden', !isCrypto);
        document.getElementById('cryptoHint').classList.toggle('hidden', !isCrypto);

        document.getElementById('labelSymbol').textContent = isCrypto
            ? 'Kode Crypto Binance (e.g. BTC, ETH, SOL)'
            : 'Kode Saham Yahoo Finance (e.g. BBCA.JK)';
        document.getElementById('fieldSymbol').placeholder = isCrypto ? 'BTC' : 'BBCA.JK';
        document.getElementById('labelAvgPrice').textContent = isCrypto ? 'Avg Beli (IDR/unit)' : 'Avg Beli (IDR/lembar)';

        if (isCrypto) {
            document.getElementById('fieldLots').disabled = true;
            document.getElementById('fieldLots').removeAttribute('required');
            document.getElementById('fieldQty').disabled = false;
            document.getElementById('fieldQty').setAttribute('required', 'required');
        } else {
            document.getElementById('fieldLots').disabled = false;
            document.getElementById('fieldLots').setAttribute('required', 'required');
            document.getElementById('fieldQty').disabled = true;
            document.getElementById('fieldQty').removeAttribute('required');
        }
    }
</script>
@endpush
@endsection
