@extends('layouts.app')

@section('header', 'Sales Analytics')

@section('content')
<div class="flex flex-col gap-10 pb-20">
    <!-- Premium Filter Engine -->
    <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-slate-100 relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                <i class="fas fa-calendar-alt text-indigo-600"></i>
                Temporal Filters
            </h2>
            <form action="{{ route('reports.sales') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ms-1">Start Horizon</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-full border-0 bg-slate-50 py-4 px-6 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-indigo-600/5 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ms-1">End Horizon</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-full border-0 bg-slate-50 py-4 px-6 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-indigo-600/5 transition-all">
                </div>
                <div class="flex items-end">
                    <x-button class="w-full !py-4 shadow-xl shadow-indigo-600/10">
                        <i class="fas fa-sync-alt mr-3 opacity-70"></i> Recompute Metrics
                    </x-button>
                </div>
            </form>
        </div>
        <i class="fas fa-search-dollar absolute right-[-20px] top-[-20px] text-[10rem] opacity-[0.02] -rotate-12"></i>
    </div>

    <!-- Metrics Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Revenue -->
        <div class="group relative overflow-hidden bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-200/50">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-3">Gross Liquidity</p>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stats['total_revenue'], 2) }}</h3>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-emerald-500 bg-emerald-50 w-fit px-3 py-1 rounded-full">
                <i class="fas fa-arrow-up text-[8px]"></i> Total Inflow
            </div>
            <i class="fas fa-hand-holding-usd absolute right-[-10px] bottom-[-10px] text-7xl opacity-[0.03] transition-transform duration-700 group-hover:rotate-12 group-hover:scale-110"></i>
        </div>

        <!-- Profit -->
        <div class="group relative overflow-hidden bg-indigo-600 p-8 rounded-[2.5rem] shadow-2xl shadow-indigo-600/20 text-white transition-all hover:scale-[1.02]">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40 mb-3">Net Yield</p>
            <h3 class="text-3xl font-black tracking-tighter">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stats['total_profit'], 2) }}</h3>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-indigo-200 bg-white/10 w-fit px-3 py-1 rounded-full">
                <i class="fas fa-gem text-[8px]"></i> Operational Margin
            </div>
            <i class="fas fa-money-check-alt absolute right-[-10px] bottom-[-10px] text-7xl opacity-10 transition-transform duration-700 group-hover:-rotate-12"></i>
        </div>

        <!-- Tax -->
        <div class="group relative overflow-hidden bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-200/50">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-3">Compliant Tax</p>
            <h3 class="text-3xl font-black text-indigo-500 tracking-tighter">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stats['total_tax'], 2) }}</h3>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-indigo-400 bg-indigo-50 w-fit px-3 py-1 rounded-full">
                <i class="fas fa-file-invoice text-[8px]"></i> Regional Protocol
            </div>
        </div>

        <!-- Count -->
        <div class="group relative overflow-hidden bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-xl transition-all hover:scale-[1.02]">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40 mb-3">Node Operations</p>
            <h3 class="text-3xl font-black tracking-tighter text-indigo-400">{{ $stats['sales_count'] }} <span class="text-lg opacity-40">TXN</span></h3>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-slate-400 bg-white/5 w-fit px-3 py-1 rounded-full">
                <i class="fas fa-microchip text-[8px]"></i> Transaction Load
            </div>
        </div>
    </div>

    <!-- Data Ledger -->
    <div class="bg-white rounded-[3.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-12 py-10 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Historical Transaction Ledger</h2>
                <p class="text-sm font-medium text-slate-400 mt-1 italic">Detailed audit log of all validated client operations</p>
            </div>
            <div class="flex gap-4">
                 <button class="h-12 px-6 rounded-2xl bg-slate-100 text-slate-600 font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all flex items-center gap-3">
                    <i class="fas fa-file-excel text-sm"></i> Export CSV
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                        <th class="px-12 py-6">Transaction Node</th>
                        <th class="px-8 py-6">Customer Profile</th>
                        <th class="px-8 py-6">Settlement</th>
                        <th class="px-8 py-6">Total Amount</th>
                        <th class="px-12 py-6 text-right">Audit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($sales as $sale)
                    <tr class="group hover:bg-slate-50/30 transition-all">
                        <td class="px-12 py-8">
                            <div class="flex items-center gap-5">
                                <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-slate-100 text-indigo-500 font-black text-xs">
                                    {{ ($sales->currentPage() - 1) * $sales->perPage() + $loop->iteration }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-900 tracking-tight">{{ $sale->created_at->format('M d, Y') }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $sale->created_at->format('h:i:s A') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-8">
                             <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($sale->customer->name ?? 'Walk-in') }}&background=6366f110&color=6366f1&bold=true" class="h-8 w-8 rounded-lg" alt="">
                                <span class="text-sm font-black text-slate-700 tracking-tight">{{ $sale->customer->name ?? 'Walk-in Identity' }}</span>
                             </div>
                        </td>
                        <td class="px-8 py-8">
                            <span class="inline-flex items-center rounded-xl bg-indigo-50 px-4 py-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-[0.2em] border border-indigo-100/50 shadow-sm shadow-indigo-100/20">
                                {{ $sale->payment_method }}
                            </span>
                        </td>
                        <td class="px-8 py-8">
                            <span class="text-base font-black text-slate-900 tracking-tighter">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->grand_total, 2) }}</span>
                        </td>
                        <td class="px-12 py-8 text-right">
                            <a href="{{ route('invoice.show', $sale->id) }}" class="h-12 w-12 inline-flex items-center justify-center rounded-[1.25rem] bg-white text-slate-300 shadow-sm border border-slate-100 hover:bg-indigo-600 hover:text-white hover:border-transparent hover:shadow-xl hover:shadow-indigo-600/20 transition-all hover:scale-110 active:scale-95">
                                <i class="fas fa-file-invoice-dollar text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-32 text-center">
                            <div class="h-24 w-24 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-6 text-4xl">
                                <i class="fas fa-satellite-dish animate-pulse"></i>
                            </div>
                            <p class="text-sm font-black text-slate-300 uppercase tracking-widest">No node data detected for selected epoch</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sales->hasPages())
        <div class="p-10 border-t border-slate-50">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
