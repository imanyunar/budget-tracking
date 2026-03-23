@extends('layouts.app')

@section('title', $portfolio->name . ' · FinanceTracker')

@section('content')
<style>
    .detail-table { width:100%; border-collapse:collapse; }
    .detail-table th {
        font-size:11px; font-weight:700; letter-spacing:0.07em; text-transform:uppercase;
        color:var(--muted); padding:10px 16px; border-bottom:1px solid var(--border);
        text-align:left; background:var(--surface-2);
    }
    .detail-table td { padding:12px 16px; border-bottom:1px solid var(--border); font-size:13.5px; color:var(--text-2); vertical-align:middle; }
    .detail-table tbody tr { transition:background 0.12s; }
    .detail-table tbody tr:hover { background:var(--surface-2); }
    .detail-table tbody tr:last-child td { border-bottom:none; }
    .mobile-tx-card { display:flex; align-items:center; gap:12px; padding:13px 16px; border-bottom:1px solid var(--border); }
    .mobile-tx-card:last-child { border-bottom:none; }
    .del-btn { width:30px; height:30px; border-radius:7px; background:transparent; border:1px solid transparent; color:var(--muted); cursor:pointer; display:inline-flex; align-items:center; justify-content:center; transition:all 0.15s; }
    .del-btn:hover { background:var(--danger-dim); border-color:rgba(244,63,94,0.2); color:var(--danger); }
    @media(max-width:768px) { .desktop-view { display:none !important; } }
    @media(min-width:769px) { .mobile-view { display:none !important; } }
</style>

<div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Header -->
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:14px;">
            <a href="{{ route('wallets.index') }}" class="btn-secondary" style="padding:9px 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            <div>
                <h1 style="font-size:20px;font-weight:800;color:var(--text);letter-spacing:-0.03em;">{{ $portfolio->name }}</h1>
                <p style="font-size:12px;color:var(--muted);margin-top:3px;">Transaction history</p>
            </div>
        </div>
        <div style="padding:14px 20px;background:var(--success-dim);border:1px solid rgba(16,185,129,0.2);border-radius:12px;">
            <div style="font-size:11px;font-weight:700;color:var(--success);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:4px;">Current Balance</div>
            <div style="font-size:20px;font-weight:800;color:var(--success);letter-spacing:-0.03em;">IDR {{ number_format($portfolio->balance, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Transaction List -->
    <div class="card">
        <div style="padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:14px;font-weight:700;color:var(--text);">Transactions</div>
            <div style="font-size:12.5px;color:var(--muted);">{{ $transactions->total() }} records</div>
        </div>

        @if($transactions->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);"><path d="M16 3h5v5"/><path d="M8 21H3v-5"/><path d="M21 3l-7 7"/><path d="M3 21l7-7"/></svg>
            </div>
            <div class="empty-state-title">No transactions yet</div>
            <div class="empty-state-text">Transactions recorded in this wallet will appear here</div>
        </div>
        @else

        <!-- Desktop Table -->
        <div class="desktop-view" style="overflow-x:auto;">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th style="text-align:right;">Amount</th>
                        <th style="width:48px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $tx)
                    <tr>
                        <td><span style="font-size:12.5px;color:var(--muted);">{{ $tx->date->format('d M Y') }}</span></td>
                        <td><div style="font-size:13.5px;font-weight:500;color:var(--text);">{{ $tx->description ?: $tx->category->name }}</div></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:7px;">
                                <div style="width:24px;height:24px;border-radius:6px;display:flex;align-items:center;justify-content:center;background:{{ $tx->category->color }}15;border:1px solid {{ $tx->category->color }}28;flex-shrink:0;">
                                    <x-icon name="{{ $tx->category->icon }}" style="width:12px;height:12px;color:{{ $tx->category->color }}" />
                                </div>
                                <span style="font-size:12.5px;color:var(--text-2);">{{ $tx->category->name }}</span>
                            </div>
                        </td>
                        <td style="text-align:right;">
                            <span style="font-size:14px;font-weight:700;color:{{ $tx->type === 'income' ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <form action="{{ route('transactions.destroy', $tx) }}" method="POST" onsubmit="return confirm('Delete? This will affect wallet balance.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="del-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="mobile-view">
            @foreach($transactions as $tx)
            <div class="mobile-tx-card">
                <div style="width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $tx->category->color }}15;border:1px solid {{ $tx->category->color }}28;">
                    <x-icon name="{{ $tx->category->icon }}" style="width:16px;height:16px;color:{{ $tx->category->color }}" />
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13.5px;font-weight:500;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $tx->description ?: $tx->category->name }}</div>
                    <div style="font-size:11.5px;color:var(--muted);margin-top:2px;">{{ $tx->date->format('d M Y') }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:13.5px;font-weight:700;color:{{ $tx->type === 'income' ? 'var(--success)' : 'var(--danger)' }};">
                        {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                    </div>
                    <form action="{{ route('transactions.destroy', $tx) }}" method="POST" onsubmit="return confirm('Delete?')" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;font-size:11.5px;color:var(--danger);cursor:pointer;padding:0;margin-top:2px;">Del</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="padding:14px 18px;border-top:1px solid var(--border);">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection