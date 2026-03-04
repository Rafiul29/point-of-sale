@extends('layouts.app')

@section('header', 'Top Products')

@section('content')
    <div class="flex flex-col gap-8 pb-20">


        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 relative overflow-hidden">
            <h2 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-3">
                <i class="fas fa-trophy text-amber-500"></i>
                Filter & Rank
            </h2>
            <form method="GET" action="{{ route('reports.top-products') }}"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="space-y-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                        class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-semibold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                        class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-semibold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Sort By</label>
                    <select name="sort"
                        class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-semibold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                        <option value="revenue" {{ $sortBy === 'revenue' ? 'selected' : '' }}>Revenue</option>
                        <option value="qty" {{ $sortBy === 'qty' ? 'selected' : '' }}>Quantity Sold</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Top N</label>
                    <select name="limit"
                        class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-semibold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                        @foreach ([5, 10, 20, 50] as $n)
                            <option value="{{ $n }}" {{ $limit == $n ? 'selected' : '' }}>Top {{ $n }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl px-4 py-3 text-sm font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-ranking-star mr-2"></i>Rank Now
                    </button>
                </div>
            </form>
            <i class="fas fa-medal absolute right-[-20px] top-[-20px] text-[10rem] opacity-[0.02]"></i>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-amber-500 rounded-3xl p-7 text-white shadow-xl shadow-amber-500/20">
                <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-2">Top {{ $limit }} Revenue
                </p>
                <h3 class="text-3xl font-black tracking-tighter">
                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($grandRevenue, 2) }}</h3>
                <p class="text-xs font-bold text-amber-100 mt-3"><i
                        class="fas fa-trophy mr-1"></i>{{ $startDate->format('M d') }} – {{ $endDate->format('M d, Y') }}
                </p>
            </div>
            <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Units Sold (Top
                    {{ $limit }})</p>
                <h3 class="text-3xl font-black tracking-tighter text-slate-900">{{ number_format($grandQty) }}</h3>
                <p class="text-xs font-bold text-slate-400 mt-3"><i class="fas fa-cubes mr-1"></i>Across
                    {{ $topProducts->count() }} products</p>
            </div>
            <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Avg Revenue / Product</p>
                <h3 class="text-3xl font-black tracking-tighter text-slate-900">
                    {{ $settings['currency_symbol'] ?? '$' }}{{ $topProducts->count() ? number_format($grandRevenue / $topProducts->count(), 2) : '0.00' }}
                </h3>
                <p class="text-xs font-bold text-slate-400 mt-3"><i class="fas fa-chart-pie mr-1"></i>Period average</p>
            </div>
        </div>


        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-10 py-7 border-b border-slate-50">
                <h2 class="text-lg font-black tracking-tight text-slate-900">
                    Product Leaderboard
                    <span class="ml-2 text-xs font-bold text-slate-400 normal-case">sorted by
                        {{ $sortBy === 'qty' ? 'quantity sold' : 'revenue' }}</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/60 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <th class="px-10 py-5 w-12">Rank</th>
                            <th class="px-6 py-5">Product</th>
                            <th class="px-6 py-5">Selling Price</th>
                            <th class="px-6 py-5">Qty Sold</th>
                            <th class="px-6 py-5">Revenue</th>
                            <th class="px-6 py-5">Revenue Share</th>
                            <th class="px-6 py-5">Stock Left</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($topProducts as $i => $product)
                            @php
                                $revenueShare =
                                    $grandRevenue > 0 ? round(($product->total_revenue / $grandRevenue) * 100, 1) : 0;
                                $rankColors = ['bg-amber-400', 'bg-slate-400', 'bg-orange-600'];
                                $rankColor = $rankColors[$i] ?? 'bg-indigo-100';
                                $rankText = $i < 3 ? 'text-white' : 'text-indigo-600';
                            @endphp
                            <tr class="hover:bg-slate-50/40 transition-all">
                                <td class="px-10 py-5">
                                    <div
                                        class="h-9 w-9 {{ $rankColor }} rounded-xl flex items-center justify-center text-sm font-black {{ $rankText }}">
                                        @if ($i < 3)
                                            <i class="fas fa-trophy text-xs"></i>
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-black text-slate-800">{{ $product->name }}</span>
                                    <span
                                        class="block text-[10px] font-mono text-slate-400 mt-0.5">{{ $product->barcode }}</span>
                                </td>
                                <td class="px-6 py-5 text-sm font-bold text-slate-600">
                                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($product->selling_price, 2) }}
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 rounded-xl px-3 py-1.5 text-sm font-black">
                                        {{ number_format($product->total_qty) }} <span
                                            class="text-[9px] font-bold text-indigo-300">units</span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-base font-black text-slate-900 tracking-tight">
                                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($product->total_revenue, 2) }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden min-w-[80px]">
                                            <div class="h-full {{ $i === 0 ? 'bg-amber-400' : 'bg-indigo-500' }} rounded-full transition-all"
                                                style="width: {{ $revenueShare }}%"></div>
                                        </div>
                                        <span
                                            class="text-xs font-black text-slate-500 w-10 text-right">{{ $revenueShare }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-black {{ $product->stock_quantity <= 0 ? 'bg-rose-50 text-rose-600' : ($product->stock_quantity < 10 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">
                                        <i
                                            class="fas fa-{{ $product->stock_quantity <= 0 ? 'ban' : ($product->stock_quantity < 10 ? 'exclamation-triangle' : 'check') }} text-[9px]"></i>
                                        {{ $product->stock_quantity }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center">
                                    <i class="fas fa-trophy text-5xl text-slate-100 mb-4 block"></i>
                                    <p class="text-sm font-black text-slate-300 uppercase tracking-widest">No sales data
                                        found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
