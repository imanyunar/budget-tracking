@extends('layouts.app')

@section('title', 'Wallets · FinanceTracker')

@section('content')
<style>
    .wallet-card {
        background:var(--surface); border:1px solid var(--border);
        border-radius:16px; padding:22px;
        box-shadow:var(--shadow-sm); transition:all 0.25s;
        position:relative; overflow:hidden; display:flex; flex-direction:column;
    }
    .wallet-card:hover { box-shadow:var(--shadow); transform:translateY(-2px); border-color:var(--border-2); }
    .add-wallet-card {
        border:1.5px dashed var(--border-2); border-radius:16px; padding:22px;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        min-height:220px; cursor:pointer; transition:all 0.2s;
        color:var(--muted); background:transparent;
    }
    .add-wallet-card:hover { border-color:var(--primary); color:var(--primary); background:var(--primary-dim); }
    .summary-card { background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:20px 22px; box-shadow:var(--shadow-sm); }
</style>

<div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title-main">Your Wallets</h1>
            <p class="page-title-sub">Manage all your financial accounts</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            Create Wallet
        </button>
    </div>

    <!-- Summary Strip -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;">
        <div class="summary-card">
            <div style="font-size:11.5px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">Total All Assets</div>
            <div style="font-size:25px;font-weight:800;color:var(--primary);letter-spacing:-0.03em;">{{ number_format($totalAssets, 0, ',', '.') }}</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px;">IDR · Wallets + Investments</div>
        </div>
        <div class="summary-card">
            <div style="font-size:11.5px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">Liquid Balance</div>
            <div style="font-size:25px;font-weight:800;color:var(--text);letter-spacing:-0.03em;">{{ number_format($totalWallet, 0, ',', '.') }}</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px;">IDR · Cash & bank wallets</div>
        </div>
    </div>

    <!-- Wallet Grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(265px,1fr));gap:14px;">
        @foreach($portfolios as $portfolio)
        <div class="wallet-card">
            <!-- Gradient tint bg -->
            <div style="position:absolute;inset:0;background:radial-gradient(circle at top right,{{ $portfolio->color }}12 0%,transparent 60%);pointer-events:none;"></div>
            <!-- Top accent line -->
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:{{ $portfolio->color }};border-radius:16px 16px 0 0;"></div>

            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;position:relative;">
                <div style="width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;background:{{ $portfolio->color }}18;border:1px solid {{ $portfolio->color }}30;">
                    <x-icon name="{{ $portfolio->icon ?: 'wallet' }}" style="width:19px;height:19px;color:{{ $portfolio->color }}" />
                </div>
                <span class="badge badge-up" style="font-size:10px;">ACTIVE</span>
            </div>

            <div style="flex:1;position:relative;">
                <div style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:2px;">{{ $portfolio->name }}</div>
                <div style="font-size:12px;color:var(--muted);">{{ $portfolio->currency }}</div>
                <div style="margin-top:14px;">
                    <div style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:4px;">Balance</div>
                    <div style="font-size:24px;font-weight:800;color:var(--text);letter-spacing:-0.03em;">{{ number_format($portfolio->balance, 0, ',', '.') }}</div>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:18px;padding-top:14px;border-top:1px solid var(--border);position:relative;">
                <form action="{{ route('wallets.destroy', $portfolio->id) }}" method="POST" onsubmit="return confirm('Delete wallet? Only possible if no transactions linked.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger" style="padding:6px 10px;font-size:12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        Delete
                    </button>
                </form>
                <div style="display:flex;gap:8px;">
                    <button onclick="openEditModal({{ $portfolio->id }}, '{{ addslashes($portfolio->name) }}', {{ $portfolio->balance }})" class="btn-secondary" style="padding:7px 12px;font-size:12.5px;">
                        Edit
                    </button>
                    <a href="{{ route('wallets.show', $portfolio->id) }}" class="btn-primary" style="padding:7px 14px;font-size:12.5px;">
                        Detail →
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Add Card -->
        <div class="add-wallet-card" onclick="openCreateModal()">
            <div style="width:46px;height:46px;border-radius:12px;border:1.5px dashed currentColor;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            </div>
            <div style="font-size:13.5px;font-weight:600;">Add New Wallet</div>
            <div style="font-size:12px;margin-top:4px;opacity:0.7;">Bank, e-wallet, cash</div>
        </div>
    </div>
</div>

<!-- Create Wallet Modal -->
<div id="createModal" class="modal-overlay" onclick="if(event.target===this)closeCreateModal()">
    <div class="modal-box" id="createModalBox">
        <div class="modal-header">
            <div><div class="modal-title">Create Wallet</div><div class="modal-subtitle">Define a new financial account</div></div>
            <button class="modal-close" onclick="closeCreateModal()"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg></button>
        </div>
        <form action="{{ route('wallets.store') }}" method="POST">
            @csrf
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div><label class="input-label">Wallet Name</label><input type="text" name="name" required placeholder="e.g. BCA, GoPay, Cash" class="input-field"></div>
                <div><label class="input-label">Initial Balance (IDR)</label><input type="number" name="balance" required placeholder="0" class="input-field"></div>
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;margin-top:4px;">Create Wallet</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Wallet Modal -->
<div id="editModal" class="modal-overlay" onclick="if(event.target===this)closeEditModal()">
    <div class="modal-box" id="editModalBox">
        <div class="modal-header">
            <div><div class="modal-title">Edit Wallet</div><div class="modal-subtitle">Update wallet details</div></div>
            <button class="modal-close" onclick="closeEditModal()"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg></button>
        </div>
        <form id="editWalletForm" method="POST">
            @csrf @method('PUT')
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div><label class="input-label">Wallet Name</label><input type="text" name="name" id="editName" required class="input-field"></div>
                <div>
                    <label class="input-label">Balance (IDR)</label>
                    <input type="number" name="balance" id="editBalance" required class="input-field">
                    <div style="margin-top:7px;padding:9px 12px;background:var(--warn-dim);border:1px solid rgba(245,158,11,0.2);border-radius:8px;font-size:12px;color:#d97706;">
                        ⚠ Direct balance changes won't affect transaction history
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.add('open');
        setTimeout(() => document.getElementById('createModalBox').classList.add('open'), 10);
        document.body.style.overflow = 'hidden';
    }
    function closeCreateModal() {
        document.getElementById('createModalBox').classList.remove('open');
        setTimeout(() => { document.getElementById('createModal').classList.remove('open'); document.body.style.overflow = ''; }, 250);
    }
    function openEditModal(id, name, balance) {
        document.getElementById('editWalletForm').action = `/wallets/${id}`;
        document.getElementById('editName').value = name;
        document.getElementById('editBalance').value = balance;
        document.getElementById('editModal').classList.add('open');
        setTimeout(() => document.getElementById('editModalBox').classList.add('open'), 10);
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        document.getElementById('editModalBox').classList.remove('open');
        setTimeout(() => { document.getElementById('editModal').classList.remove('open'); document.body.style.overflow = ''; }, 250);
    }
</script>
@endpush
@endsection