@extends('layouts.app')

@section('header', 'Inventory Report')

@section('content')
    <div class="flex flex-col gap-8 pb-20">


        @php
            $totalValue = $products->sum(fn($p) => $p->stock_quantity * $p->purchase_price);
            $lowStock = $products->filter(fn($p) => $p->stock_quantity <= $p->reorder_level)->count();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-indigo-600 rounded-3xl p-7 text-white shadow-xl shadow-indigo-600/20">
                <p class="text-[10px] font-black uppercase tracking-widest opacity-50 mb-2">Total SKUs</p>
                <h3 class="text-4xl font-black tracking-tighter">{{ $products->count() }}</h3>
                <p class="text-xs font-bold text-indigo-200 mt-3"><i class="fas fa-boxes mr-1"></i>Active products</p>
            </div>
            <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Stock Value</p>
                <h3 class="text-3xl font-black tracking-tighter text-slate-900">
                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($totalValue, 2) }}</h3>
                <p class="text-xs font-bold text-slate-400 mt-3"><i class="fas fa-warehouse mr-1"></i>At purchase price</p>
            </div>
            <div class="bg-white rounded-3xl p-7 border border-amber-100 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-widest text-amber-400 mb-2">Low Stock</p>
                <h3 class="text-3xl font-black tracking-tighter text-amber-500">{{ $lowStock }}</h3>

            </div>
            <div class="bg-slate-900 rounded-3xl p-7 text-white shadow-xl">
                <p class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-2">Total Units</p>
                <h3 class="text-3xl font-black tracking-tighter text-indigo-400">
                    {{ number_format($products->sum('stock_quantity')) }}</h3>
                <p class="text-xs font-bold text-slate-400 mt-3"><i class="fas fa-cubes mr-1"></i>In store</p>
            </div>
        </div>


        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-10 py-7 border-b border-slate-50 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-black tracking-tight text-slate-900">Complete Inventory</h2>
                    <p class="text-xs font-medium text-slate-400 mt-0.5">{{ $products->count() }} products tracked</p>
                </div>
                <a href="{{ route('products.export') }}"
                    class="bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-600 rounded-2xl px-5 py-2.5 text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Export
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/60 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <th class="px-10 py-5">Product</th>
                            <th class="px-6 py-5">Category</th>
                            <th class="px-6 py-5">Supplier</th>
                            <th class="px-6 py-5">Cost Price</th>
                            <th class="px-6 py-5">Sell Price</th>
                            <th class="px-6 py-5">Margin</th>
                            <th class="px-6 py-5">Qty</th>
                            <th class="px-6 py-5">Stock Value</th>
                            <th class="px-6 py-5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($products as $product)
                            @php
                                $margin =
                                    $product->selling_price > 0
                                        ? round(
                                            (($product->selling_price - $product->purchase_price) /
                                                $product->selling_price) *
                                                100,
                                            1,
                                        )
                                        : 0;
                                $stockValue = $product->stock_quantity * $product->purchase_price;
                                $isLow = $product->stock_quantity <= $product->reorder_level;
                                $isOut = $product->stock_quantity <= 0;
                            @endphp
                            <tr class="hover:bg-slate-50/40 transition-all">
                                <td class="px-10 py-5">
                                    <span class="text-sm font-black text-slate-800">{{ $product->name }}</span>
                                    <span
                                        class="block text-[10px] font-mono text-slate-400 mt-0.5">{{ $product->barcode }}</span>
                                </td>
                                <td class="px-6 py-5 text-sm font-semibold text-slate-600">
                                    {{ $product->category->name ?? '—' }}</td>
                                <td class="px-6 py-5 text-sm font-semibold text-slate-600">
                                    {{ $product->supplier->name ?? '—' }}</td>
                                <td class="px-6 py-5 text-sm font-bold text-slate-600">
                                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($product->purchase_price, 2) }}
                                </td>
                                <td class="px-6 py-5 text-sm font-bold text-slate-800">
                                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($product->selling_price, 2) }}
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="text-xs font-black {{ $margin >= 20 ? 'text-emerald-600' : ($margin >= 10 ? 'text-amber-600' : 'text-rose-500') }}">
                                        {{ $margin }}%
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-5 text-sm font-black {{ $isOut ? 'text-rose-500' : ($isLow ? 'text-amber-500' : 'text-slate-800') }}">
                                    {{ $product->stock_quantity }}
                                </td>
                                <td class="px-6 py-5 text-sm font-bold text-slate-700">
                                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($stockValue, 2) }}</td>
                                <td class="px-6 py-5">
                                    @if ($isOut)
                                        <span
                                            class="inline-flex rounded-xl bg-rose-50 border border-rose-200 px-2.5 py-1 text-[9px] font-black uppercase tracking-widest text-rose-600">Out
                                            of Stock</span>
                                    @elseif($isLow)
                                        <span
                                            class="inline-flex rounded-xl bg-amber-50 border border-amber-200 px-2.5 py-1 text-[9px] font-black uppercase tracking-widest text-amber-600">Low
                                            Stock</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-xl bg-emerald-50 border border-emerald-200 px-2.5 py-1 text-[9px] font-black uppercase tracking-widest text-emerald-600">In
                                            Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-20 text-center">
                                    <i class="fas fa-warehouse text-5xl text-slate-100 mb-4 block"></i>
                                    <p class="text-sm font-black text-slate-300 uppercase tracking-widest">No products in
                                        inventory</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
