@extends('layouts.app')

@section('title', 'Investments')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-700">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-0 gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Investment Hub</h1>
            <p class="text-gray-400 mt-1">Track your growth and monitor your ROI performance.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="flex items-center px-5 py-2.5 gradient-bg rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i data-lucide="plus-square" class="w-4 h-4 mr-2"></i> Add Platform
            </button>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Portfolio Doughnut -->
        <div class="lg:col-span-1 glass-card p-10 flex flex-col justify-center items-center h-full hover:border-blue-500/20 transition-all">
            <h3 class="text-xl font-bold mb-4">Capital Allocation</h3>
            <div class="relative w-full h-80 flex items-center justify-center">
                <canvas id="investmentChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none scale-105">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1">Total Net Equity</span>
                    <span class="text-2xl font-black">100%</span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-green-500 mt-1">+12.4% Annualized</span>
                </div>
            </div>
            
            <div class="mt-8 space-y-4 w-full">
                @php $totalInvested = $portfolios->sum('balance'); @endphp
                @foreach($portfolios as $port)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3 border border-white/5 transition-transform group-hover:scale-110" style="background-color: {{ $port->color }}"></div>
                        <span class="text-sm font-bold text-gray-400 group-hover:text-white transition-colors">{{ $port->name }}</span>
                    </div>
                    <span class="text-xs font-black">{{ $totalInvested > 0 ? number_format(($port->balance / $totalInvested) * 100, 1) : 0 }}% Equity</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Performance Table -->
        <div class="lg:col-span-2 glass-card flex flex-col overflow-hidden h-full">
            <div class="p-8 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Platform Status</h3>
                    <p class="text-xs text-gray-500 mt-1">Real-time performance tracking.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="flex items-center px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-colors">
                        Detailed Ledger <i data-lucide="arrow-right" class="w-3 h-3 ml-2"></i>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-[10px] text-gray-500 uppercase font-black tracking-widest border-b border-white/5 bg-white/[0.02]">
                        <tr>
                            <th class="px-8 py-6">Instrument</th>
                            <th class="px-8 py-6">Total Invested</th>
                            <th class="px-8 py-6">Current Value</th>
                            <th class="px-8 py-6 text-right">ROI Rank</th>
                            <th class="px-8 py-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($portfolios as $port)
                        <tr class="hover:bg-white/5 transition-all group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform" style="background: {{ $port->color }}15; border: 1px solid {{ $port->color }}30">
                                        <i data-lucide="{{ $port->icon ?: 'trending-up' }}" class="w-6 h-6" style="color: {{ $port->color }}"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold">{{ $port->name }}</div>
                                        <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">{{ $port->currency }} Asset Class</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-sm font-black text-gray-400">
                                IDR {{ number_format($port->balance * 0.8, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-black">IDR {{ number_format($port->balance, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="inline-flex items-center px-3 py-1.5 rounded-xl border border-green-500/20 bg-green-500/10 text-green-500 text-[10px] font-black uppercase tracking-widest">
                                    <i data-lucide="trending-up" class="w-3 h-3 mr-2"></i> {{ number_format(abs($port->roi), 1) }}%
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <button class="p-2.5 bg-white/5 rounded-xl hover:bg-white/10 transition-colors text-gray-500">
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

<script>
    const portData = @json($portfolios);
    const ctxInvest = document.getElementById('investmentChart').getContext('2d');
    new Chart(ctxInvest, {
        type: 'doughnut',
        data: {
            labels: portData.map(p => p.name),
            datasets: [{
                data: portData.map(p => p.balance),
                backgroundColor: portData.map(p => p.color),
                borderWidth: 5,
                borderColor: '#0a0c10',
                hoverOffset: 20,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '84%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(13, 17, 23, 0.95)',
                    padding: 15,
                    cornerRadius: 12,
                    callbacks: {
                        label: function(context) {
                            const val = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed);
                            return ` ${context.label}: ${val}`;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
