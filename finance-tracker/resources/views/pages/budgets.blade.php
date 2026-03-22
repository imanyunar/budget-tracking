@extends('layouts.app')

@section('title', 'Budgets')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-700">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-0 gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Budget Tracking</h1>
            <p class="text-gray-400 mt-1">Set limits and track your spending habits.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="openBudgetModal()" class="flex items-center px-5 py-2.5 gradient-bg rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i> Set New Budget
            </button>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($budgets as $budget)
        <div class="glass-card p-8 flex flex-col h-full hover:border-purple-500/20 transition-all group">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform" style="background: {{ $budget->category->color }}15; border: 1px solid {{ $budget->category->color }}30">
                        <i data-lucide="{{ $budget->category->icon }}" class="w-6 h-6" style="color: {{ $budget->category->color }}"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold group-hover:text-purple-400 transition-colors">{{ $budget->category->name }}</h3>
                        <div class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter mt-0.5">Monthly Limit Monitoring</div>
                    </div>
                </div>
                <div class="p-2.5 bg-white/5 rounded-xl hover:bg-white/10 transition-colors">
                    <i data-lucide="more-horizontal" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>

            <div class="flex-1 mt-4">
                <div class="flex items-end justify-between mb-3 px-1">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1">Total Limit</span>
                        <span class="text-2xl font-black">IDR {{ number_format($budget->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1">Spent</span>
                        <span class="text-lg font-black {{ $budget->percentage > 80 ? 'text-red-500' : 'text-blue-500' }}">IDR {{ number_format($budget->spent, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Custom Progress Bar -->
                <div class="relative h-3 w-full bg-white/5 rounded-full overflow-hidden border border-white/5 shadow-inner p-[1px]">
                    <div class="h-full rounded-full transition-all duration-1000 shadow-lg {{ $budget->percentage > 80 ? 'bg-gradient-to-r from-red-500/80 to-red-400 shadow-red-500/20' : ($budget->percentage > 50 ? 'bg-gradient-to-r from-yellow-500/80 to-yellow-400 shadow-yellow-500/20' : 'bg-gradient-to-r from-blue-600/80 to-blue-400 shadow-blue-500/20') }}" style="width: {{ $budget->percentage }}%"></div>
                </div>
                
                <div class="flex items-center justify-between mt-4 px-1">
                    <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">{{ round($budget->percentage) }}% Utilization</span>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $budget->percentage > 80 ? 'text-red-400' : 'text-gray-500' }}">{{ number_format($budget->amount - $budget->spent, 0, ',', '.') }} Remaining</span>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-colors" onclick="openBudgetModal('{{ $budget->category_id }}', '{{ $budget->amount }}')">Adjust Limits</button>
                <button class="text-[10px] font-black uppercase tracking-widest text-blue-500 hover:text-blue-400 transition-colors">Alert Settings</button>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal for New/Edit Budget -->
<div id="budgetModal" class="fixed inset-0 z-[200] hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md transition-opacity" onclick="closeBudgetModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="glass-card bg-gray-900 border-white/10 p-8 shadow-2xl relative animate-in zoom-in duration-300">
            <button onclick="closeBudgetModal()" class="absolute top-6 right-6 p-2 hover:bg-white/5 rounded-full transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
            <h2 class="text-2xl font-bold mb-1">Set Category Budget</h2>
            <p class="text-sm text-gray-500 mb-8">Define your monthly spending target.</p>
            
            <form action="{{ route('budgets.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Category</label>
                    <select name="category_id" id="budget_category_id" required class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-3 py-3.5 text-xs text-gray-300 outline-none focus:ring-1 focus:ring-blue-500">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Monthly Limit (IDR)</label>
                    <input type="number" name="amount" id="budget_amount" required placeholder="0.00" class="w-full bg-gray-800/50 border border-white/10 rounded-xl px-4 py-3.5 font-bold focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <button type="submit" class="w-full py-4 gradient-bg rounded-xl font-bold mt-4 shadow-lg shadow-blue-500/20 active:scale-95 transition-all">Set Budget</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openBudgetModal(categoryId = '', amount = '') { 
        document.getElementById('budgetModal').classList.remove('hidden'); 
        if (categoryId) document.getElementById('budget_category_id').value = categoryId;
        if (amount) document.getElementById('budget_amount').value = parseFloat(amount);
    }
    function closeBudgetModal() { document.getElementById('budgetModal').classList.add('hidden'); }
</script>
@endsection
