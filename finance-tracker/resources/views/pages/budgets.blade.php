@extends('layouts.app')

@section('title', 'Budgets · FinanceTracker')

@section('content')
<style>
    .budget-card {
        background:var(--surface); border:1px solid var(--border);
        border-radius:16px; padding:22px;
        box-shadow:var(--shadow-sm); transition:all 0.2s;
        display:flex; flex-direction:column;
    }
    .budget-card:hover { box-shadow:var(--shadow); border-color:var(--border-2); }
    .progress-track { height:6px; background:var(--surface-2); border-radius:6px; overflow:hidden; margin:10px 0 6px; }
    .progress-fill { height:100%; border-radius:6px; transition:width 1s cubic-bezier(0.4,0,0.2,1); }
</style>

<div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title-main">Budget Tracking</h1>
            <p class="page-title-sub">Monthly spending limits by category</p>
        </div>
        <button onclick="openBudgetModal()" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            Set Budget
        </button>
    </div>

    <!-- Grid -->
    @if($budgets->isEmpty())
    <div style="text-align:center;padding:64px 20px;border:1.5px dashed var(--border-2);border-radius:16px;">
        <div class="empty-state-icon" style="margin:0 auto 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div class="empty-state-title">No budgets configured</div>
        <div class="empty-state-text" style="margin-bottom:20px;">Set spending limits to track your category expenses</div>
        <button onclick="openBudgetModal()" class="btn-primary">Set First Budget</button>
    </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;">
        @foreach($budgets as $budget)
        @php
            $pct = min(100, max(0, $budget->percentage));
            $barColor = $pct > 80 ? 'var(--danger)' : ($pct > 50 ? 'var(--warn)' : 'var(--success)');
            $barHex = $pct > 80 ? '#f43f5e' : ($pct > 50 ? '#f59e0b' : '#10b981');
            $badgeClass = $pct > 80 ? 'badge-down' : ($pct > 50 ? 'badge-warn' : 'badge-up');
            $badgeLabel = $pct > 80 ? 'Over Limit' : ($pct > 50 ? 'Moderate' : 'On Track');
        @endphp
        <div class="budget-card">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $budget->category->color }}18;border:1px solid {{ $budget->category->color }}30;">
                    <x-icon name="{{ $budget->category->icon }}" style="width:17px;height:17px;color:{{ $budget->category->color }}" />
                </div>
                <div style="flex:1;">
                    <div style="font-size:14.5px;font-weight:700;color:var(--text);">{{ $budget->category->name }}</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:1px;">Monthly limit</div>
                </div>
                <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:flex-end;">
                <div>
                    <div style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:3px;">Spent</div>
                    <div style="font-size:20px;font-weight:800;letter-spacing:-0.03em;color:{{ $barHex }};">{{ number_format($budget->spent, 0, ',', '.') }}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:3px;">Limit</div>
                    <div style="font-size:20px;font-weight:800;letter-spacing:-0.03em;color:var(--text-2);">{{ number_format($budget->amount, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="progress-track">
                <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $barHex }};"></div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div style="font-size:12px;color:var(--muted);">{{ round($pct) }}% used</div>
                <div style="font-size:12px;font-weight:600;color:{{ $pct > 80 ? 'var(--danger)' : 'var(--muted)' }};">
                    {{ number_format(max(0, $budget->amount - $budget->spent), 0, ',', '.') }} remaining
                </div>
            </div>

            <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
                <button onclick="openBudgetModal('{{ $budget->category_id }}', '{{ $budget->amount }}')" class="section-link" style="background:none;border:none;cursor:pointer;font-size:13px;font-weight:500;color:var(--primary);">
                    Adjust Budget →
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Budget Modal -->
<div id="budgetModal" class="modal-overlay" onclick="if(event.target===this)closeBudgetModal()">
    <div class="modal-box" id="budgetModalBox">
        <div class="modal-header">
            <div><div class="modal-title">Set Category Budget</div><div class="modal-subtitle">Define a monthly spending limit</div></div>
            <button class="modal-close" onclick="closeBudgetModal()"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg></button>
        </div>
        <form action="{{ route('budgets.store') }}" method="POST">
            @csrf
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label class="input-label">Category</label>
                    <select name="category_id" id="budget_category_id" required class="input-field">
                        @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="input-label">Monthly Limit (IDR)</label>
                    <input type="number" name="amount" id="budget_amount" required placeholder="0" class="input-field">
                </div>
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;margin-top:4px;">Save Budget</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openBudgetModal(catId='', amount='') {
        if (catId) document.getElementById('budget_category_id').value = catId;
        if (amount) document.getElementById('budget_amount').value = parseFloat(amount);
        document.getElementById('budgetModal').classList.add('open');
        setTimeout(() => document.getElementById('budgetModalBox').classList.add('open'), 10);
        document.body.style.overflow = 'hidden';
    }
    function closeBudgetModal() {
        document.getElementById('budgetModalBox').classList.remove('open');
        setTimeout(() => { document.getElementById('budgetModal').classList.remove('open'); document.body.style.overflow = ''; }, 250);
    }
</script>
@endpush
@endsection