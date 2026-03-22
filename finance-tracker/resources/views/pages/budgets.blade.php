@extends('layouts.app')

@section('title', 'Budgets - FinanceTracker')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Budget Tracking</h1>
            <p class="text-sm text-slate-500 font-medium">Set limits and track your spending habits.</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button onclick="openBudgetModal()" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                <i data-lucide="plus" class="w-4 h-4"></i> Set New Budget
            </button>
        </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($budgets as $budget)
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full">
            
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 shadow-sm" style="background: {{ $budget->category->color }}15; border: 1px solid {{ $budget->category->color }}30">
                        <i data-lucide="{{ $budget->category->icon }}" class="w-6 h-6" style="color: {{ $budget->category->color }}"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $budget->category->name }}</h3>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mt-0.5">Monthly Limit Monitoring</p>
                    </div>
                </div>
                <button class="p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-xl transition-colors">
                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="flex-1 flex flex-col justify-end mt-4">
                <div class="flex items-end justify-between mb-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Total Limit</p>
                        <p class="text-xl font-black text-slate-900">IDR {{ number_format($budget->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Spent</p>
                        <p class="text-lg font-black {{ $budget->percentage > 80 ? 'text-rose-600' : 'text-indigo-600' }}">
                            IDR {{ number_format($budget->spent, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Custom Progress Bar -->
                <div class="relative h-3 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                    <div class="h-full rounded-full transition-all duration-1000 
                        {{ $budget->percentage > 80 ? 'bg-gradient-to-r from-rose-500 to-rose-400' : 
                          ($budget->percentage > 50 ? 'bg-gradient-to-r from-amber-500 to-amber-400' : 'bg-gradient-to-r from-indigo-500 to-violet-500') }}" 
                        style="width: {{ min(100, max(0, $budget->percentage)) }}%">
                    </div>
                </div>
                
                <div class="flex items-center justify-between mt-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ round($budget->percentage) }}% Used</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider {{ $budget->percentage > 80 ? 'text-rose-600' : 'text-slate-500' }}">
                        {{ number_format(max(0, $budget->amount - $budget->spent), 0, ',', '.') }} Remaining
                    </span>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="text-[10px] font-bold uppercase tracking-wider text-slate-500 hover:text-indigo-600 transition-colors" onclick="openBudgetModal('{{ $budget->category_id }}', '{{ $budget->amount }}')">
                    Adjust Limits
                </button>
                <button class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 hover:text-indigo-800 transition-colors">
                    Alert Settings
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
<div id="budgetModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeBudgetModal()"></div>
    
    <div class="bg-white rounded-3xl w-full max-w-md p-6 sm:p-8 relative z-10 shadow-2xl shadow-indigo-500/10 border border-slate-100 transform scale-95 opacity-0 transition-all duration-200" id="budgetModalContent">
        <button onclick="closeBudgetModal()" class="absolute top-6 right-6 p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-xl transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="mb-6">
            <h2 class="text-xl font-black text-slate-900">Set Category Budget</h2>
            <p class="text-sm font-medium text-slate-500 mt-0.5">Define your monthly spending target</p>
        </div>
        
        <form action="{{ route('budgets.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Category</label>
                <select name="category_id" id="budget_category_id" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none text-slate-700">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Monthly Limit (IDR)</label>
                <input type="number" name="amount" id="budget_amount" required placeholder="0.00" 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-bold">
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl mt-2 hover:shadow-lg hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                Save Budget
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const bModal = document.getElementById('budgetModal');
    const bModalContent = document.getElementById('budgetModalContent');

    function openBudgetModal(categoryId = '', amount = '') { 
        bModal.classList.remove('hidden');
        bModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        
        if (categoryId) document.getElementById('budget_category_id').value = categoryId;
        if (amount) document.getElementById('budget_amount').value = parseFloat(amount);
        
        setTimeout(() => {
            bModalContent.classList.remove('scale-95', 'opacity-0');
            bModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeBudgetModal() { 
        bModalContent.classList.remove('scale-100', 'opacity-100');
        bModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            bModal.classList.add('hidden');
            bModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }
</script>
@endpush
@endsection
