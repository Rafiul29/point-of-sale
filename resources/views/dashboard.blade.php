@extends('layouts.app')

@section('header', 'Workstation Control Center')

@section('content')
<div class="flex flex-col gap-10">
    <!-- Premium Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Sales Velocity -->
        <div class="group relative overflow-hidden rounded-[3rem] bg-indigo-600 p-10 text-white shadow-2xl shadow-indigo-600/30 transition-all hover:scale-[1.02]">
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-60">Terminal Revenue Today</p>
                <h3 class="mt-4 text-5xl font-black tracking-tighter">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stats['revenue_today'], 2) }}</h3>
                <div class="mt-8 flex items-center gap-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-md">
                        <i class="fas fa-bolt text-indigo-200"></i>
                    </span>
                    <div class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest leading-tight">Live transactions<br>from active terminal</div>
                </div>
            </div>
            <i class="fas fa-chart-line absolute -bottom-10 -right-10 text-[12rem] opacity-10 transition-transform duration-1000 group-hover:rotate-12 group-hover:scale-110"></i>
        </div>

        <!-- Profit/Loss Metric -->
        <div class="group relative overflow-hidden rounded-[3rem] bg-white p-10 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:shadow-slate-200/50">
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Inventory Acquisitions</p>
                <h3 class="mt-4 text-5xl font-black tracking-tighter text-slate-900">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stats['purchase_month'], 2) }}</h3>
                <div class="mt-8 flex items-center gap-3 text-xs font-bold text-rose-500">
                    <div class="h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></div>
                    Outflow: Current Month
                </div>
            </div>
            <i class="fas fa-truck-loading absolute -bottom-10 -right-10 text-[12rem] text-slate-50 transition-transform duration-1000 group-hover:-rotate-6"></i>
        </div>

        <!-- Customer Base & Catalog -->
        <div class="grid grid-rows-2 gap-8">
            <div class="group relative flex items-center gap-6 rounded-[2rem] bg-slate-900 p-6 text-white transition-all hover:bg-slate-800">
                 <div class="h-16 w-16 flex items-center justify-center rounded-2xl bg-white/5 text-2xl text-indigo-400">
                    <i class="fas fa-users"></i>
                 </div>
                 <div>
                    <h4 class="text-3xl font-black">{{ $stats['total_customers'] }}</h4>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Verified Client Identities</p>
                 </div>
            </div>
            <div class="group relative flex items-center gap-6 rounded-[2rem] bg-white p-6 border border-slate-100 transition-all hover:bg-slate-50">
                 <div class="h-16 w-16 flex items-center justify-center rounded-2xl bg-indigo-50 text-2xl text-indigo-600">
                    <i class="fas fa-box-open"></i>
                 </div>
                 <div>
                    <h4 class="text-3xl font-black text-slate-900">{{ $stats['total_products'] }}</h4>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">SKU managed in catalog</p>
                 </div>
            </div>
        </div>
    </div>

    <!-- Main Insights Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Stock Criticality -->
        <div class="lg:col-span-8 flex flex-col gap-6">
            <div class="rounded-[3.5rem] bg-white p-12 shadow-sm border border-slate-100 flex-1">
                <div class="mb-10 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-black tracking-tight text-slate-900">Inventory Criticality</h2>
                        <p class="text-sm font-medium text-slate-400 mt-1">Real-time alerts for stock levels below reorder threshold</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-500">
                        <i class="fas fa-shield-virus text-xl"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($lowStockProducts as $product)
                    <div class="group p-6 rounded-[2.5rem] bg-slate-50/50 border border-slate-50 transition-all hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 hover:border-transparent">
                        <div class="flex items-center gap-5 mb-5">
                            <div class="h-14 w-14 flex items-center justify-center rounded-[1.5rem] bg-white shadow-sm transition-all group-hover:scale-110 group-hover:bg-rose-50">
                                <i class="fas fa-exclamation-triangle text-rose-500"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="truncate text-base font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $product->name }}</p>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $product->category->name }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                             <div class="relative h-2 w-full rounded-full bg-slate-200 overflow-hidden">
                                <div class="absolute inset-y-0 left-0 rounded-full bg-gradient-to-r from-rose-500 to-orange-400" style="width: {{ ($product->stock_quantity / max(1, $product->reorder_level)) * 100 }}%"></div>
                             </div>
                             <div class="flex justify-between text-[11px] font-bold">
                                <span class="text-rose-500 uppercase">{{ $product->stock_quantity }} units left</span>
                                <span class="text-slate-400">Target: {{ $product->reorder_level }}</span>
                             </div>
                        </div>
                    </div>
                    @empty
                    <div class="md:col-span-2 flex flex-col items-center justify-center py-20 text-slate-300">
                        <div class="h-24 w-24 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-300 mb-6 text-4xl">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <p class="text-sm font-bold opacity-60">Your catalog is healthy. All SKU levels are within safety buffers.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Terminal Feed -->
        <div class="lg:col-span-4 flex">
            <div class="flex-1 rounded-[3.5rem] bg-slate-900 p-10 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10 flex h-full flex-col">
                    <div class="mb-10">
                        <h2 class="text-2xl font-black tracking-tight">Recent Activity</h2>
                        <div class="h-1 w-12 bg-indigo-500 mt-3 rounded-full"></div>
                    </div>

                    <div class="space-y-8 flex-1">
                        @forelse($recentSales as $sale)
                        <div class="group flex items-start gap-4">
                            <div class="h-12 w-12 shrink-0 flex items-center justify-center rounded-2xl bg-white/5 text-indigo-400 border border-white/5 transition-all group-hover:bg-white/10">
                                <i class="fas fa-barcode text-sm"></i>
                            </div>
                            <div class="flex-1 overflow-hidden border-b border-white/5 pb-6 group-last:border-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="truncate text-sm font-bold text-slate-100">{{ $sale->customer->name ?? 'Walk-in' }}</p>
                                    <span class="text-[10px] font-bold text-slate-500 whitespace-nowrap">{{ $sale->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-black text-indigo-500">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->grand_total, 2) }}</span>
                                    <span class="h-1 w-1 rounded-full bg-slate-700"></span>
                                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">{{ $sale->payment_method }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-20 opacity-30">
                            <i class="fas fa-ghost text-5xl mb-4"></i>
                            <p class="text-xs font-bold uppercase tracking-widest">Feed silent</p>
                        </div>
                        @endforelse
                    </div>

                    <a href="{{ route('reports.sales') }}" class="mt-8 flex items-center justify-center gap-4 rounded-3xl bg-white/5 py-5 text-[10px] font-black uppercase tracking-[0.3em] transition-all hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-600/30">
                        Detailed Ledger
                        <i class="fas fa-external-link-alt text-[8px] opacity-40"></i>
                    </a>
                </div>
                <i class="fas fa-radiation absolute -bottom-10 -right-10 text-9xl opacity-[0.03] rotate-12"></i>
            </div>
        </div>
    </div>
</div>
@endsection
