@extends('layouts.app')

@section('header', 'Sales Analytics')

@section('content')
    <div class="space-y-4">
        <!-- Compact Filter & Summary Header -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <form action="{{ route('reports.sales') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold uppercase tracking-wider">From</span>
                        <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                            class="pl-14 pr-3 py-1.5 text-xs font-bold rounded-lg border-slate-100 bg-slate-50 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                    </div>
                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold uppercase tracking-wider">To</span>
                        <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                            class="pl-10 pr-3 py-1.5 text-xs font-bold rounded-lg border-slate-100 bg-slate-50 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2 shadow-sm">
                        <i class="fas fa-filter text-[10px]"></i> Apply Epoch
                    </button>
                    <!-- <a href="{{ route('reports.sales.export', request()->query()) }}"
                                                                                                        class="bg-slate-900 hover:bg-black text-white px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2 shadow-sm">
                                                                                                        <i class="fas fa-file-export text-[10px]"></i> Export PDF/XLS
                                                                                                    </a> -->
                </form>

                <div class="flex items-center gap-2 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest leading-none">Net
                            Period Margin</span>
                        <span class="text-sm font-black text-indigo-700 leading-none mt-1">
                            {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stats['total_profit'], 2) }}
                        </span>
                    </div>
                    <div class="h-6 w-[1px] bg-indigo-200 mx-2"></div>
                    <i class="fas fa-chart-line text-indigo-500 text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Metric Grid - High Density -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Total Revenue</p>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">
                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stats['total_revenue'], 2) }}
                </h3>
            </div>

            <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm border-l-4 border-l-indigo-600">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-1">Total Profits</p>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">
                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stats['total_profit'], 2) }}
                </h3>
            </div>

            <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Tax Liability</p>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">
                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stats['total_tax'], 2) }}
                </h3>
            </div>

            <div class="p-3.5 rounded-xl  shadow-lg overflow-hidden relative">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-1">Sales Count</p>
                <h3 class="text-xl font-black tracking-tight  flex items-baseline gap-1">
                    {{ $stats['sales_count'] }} <span class="text-[10px] opacity-50 uppercase">+</span>
                </h3>
                <i class="fas fa-receipt absolute right-[-10px] bottom-[-10px] text-4xl opacity-10"></i>
            </div>
        </div>

        <!-- Dense Data Ledger -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-50">
                <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Transaction History Log</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left table-fixed min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50/50 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <th class="px-5 py-3 w-48">Timestamp</th>
                            <th class="px-5 py-3">Client Information</th>
                            <th class="px-5 py-3 w-32">Channel</th>
                            <th class="px-5 py-3 w-32 text-right">Settlement</th>
                            <th class="px-5 py-3 w-20 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-black text-slate-900">{{ $sale->created_at->format('M d, Y') }}</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase">{{ $sale->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="h-7 w-7 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center overflow-hidden">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($sale->customer->name ?? 'Walk-in') }}&background=6366f1&color=fff&bold=true"
                                                class="h-full w-full object-cover">
                                        </div>
                                        <span
                                            class="text-xs font-bold text-slate-700">{{ $sale->customer->name ?? 'Walk-in Identity' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-md bg-slate-100 text-[9px] font-black text-slate-600 uppercase tracking-tighter border border-slate-200">
                                        {{ $sale->payment_method }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span
                                        class="text-sm font-black text-slate-900">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->grand_total, 2) }}</span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <a href="{{ route('invoice.show', $sale->id) }}"
                                        class="h-7 w-7 inline-flex items-center justify-center rounded-lg bg-slate-50 border border-slate-100 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 transition-all">
                                        <i class="fas fa-external-link-alt text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="text-slate-300 text-3xl mb-3">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No transaction nodes
                                        detected</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sales->hasPages())
                <div class="px-5 py-3 border-t border-slate-50 bg-slate-50/30">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection