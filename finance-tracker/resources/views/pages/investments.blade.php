@extends('layouts.app')

@section('title', 'Investments - FinanceTracker')

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" defer></script>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Investment Hub</h1>
            <p class="text-sm text-slate-500 font-medium">Track your growth and monitor ROI performance.</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Platform
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Portfolio Doughnut -->
        <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200 shadow-sm p-8 flex flex-col items-center">
            <div class="w-full mb-6">
                <h3 class="text-lg font-bold text-slate-900">Capital Allocation</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Distribution of your wealth</p>
            </div>
            
            <div class="relative w-full h-[280px] flex items-center justify-center">
                <canvas id="investmentChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Total Equity</span>
                    <span class="text-2xl font-black text-slate-900">100%</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full mt-2 border border-emerald-100">+12.4% ROI</span>
                </div>
            </div>
            
            <div class="mt-8 space-y-4 w-full border-t border-slate-100 pt-6">
                @php $totalInvested = $portfolios->sum('balance'); @endphp
                @foreach($portfolios as $port)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $port->color }}"></div>
                        <span class="text-sm font-bold text-slate-600">{{ $port->name }}</span>
                    </div>
                    <span class="text-xs font-black text-slate-900">{{ $totalInvested > 0 ? number_format(($port->balance / $totalInvested) * 100, 1) : 0 }}% Weight</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Performance Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            
            <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Platform Status</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Real-time performance tracking per entity.</p>
                </div>
                <button class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-bold uppercase tracking-wider text-indigo-600 hover:bg-slate-50 transition-colors shadow-sm">
                    Detailed Ledger <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white">
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Instrument</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Total Invested</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">Current Value</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-right">ROI Rank</th>
                            <th class="px-6 py-4 border-b border-slate-100"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($portfolios as $port)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 border transition-transform group-hover:scale-105" 
                                         style="background: {{ $port->color }}10; border-color: {{ $port->color }}20;">
                                        <i data-lucide="{{ $port->icon ?: 'trending-up' }}" class="w-6 h-6" style="color: {{ $port->color }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800">{{ $port->name }}</h4>
                                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">{{ $port->currency }} Asset Class</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm font-bold text-slate-500">IDR {{ number_format($port->balance * 0.8, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm font-black text-slate-900">IDR {{ number_format($port->balance, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <i data-lucide="trending-up" class="w-3 h-3"></i> {{ number_format(abs($port->roi), 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <button class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Manage Platform">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>

@push('scripts')
<script>
    function initInvestCharts() {
        if (typeof Chart === 'undefined') {
            setTimeout(initInvestCharts, 100); return;
        }

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        const portData = @json($portfolios);
        const ctxInvest = document.getElementById('investmentChart').getContext('2d');
        
        new Chart(ctxInvest, {
            type: 'doughnut',
            data: {
                labels: portData.map(p => p.name),
                datasets: [{
                    data: portData.map(p => p.balance),
                    backgroundColor: portData.map(p => p.color),
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 8,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.95)',
                        titleColor: '#0f172a',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const val = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(context.parsed);
                                return ` ${context.label}: Rp ${val}`;
                            }
                        }
                    }
                }
            }
        });
    }

    document.addEventListener("DOMContentLoaded", initInvestCharts);
</script>
@endpush
@endsection
