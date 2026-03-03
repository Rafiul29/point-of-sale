@extends('layouts.app')

@section('header', 'Acquisition Audit')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Back Button -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('purchases.index') }}" class="flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-slate-900 transition-colors">
            <i class="fas fa-arrow-left"></i> Acquisition History
        </a>
        <button onclick="window.print()" class="bg-slate-900 text-white px-8 py-3 rounded-2xl font-extrabold shadow-xl hover:bg-slate-800 transition-all flex items-center gap-3">
            <i class="fas fa-print opacity-70"></i> Print Audit
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Audit Details -->
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Itemized Receipt</h2>
                        <p class="text-xs font-medium text-slate-400 mt-1">Detailed list of stock received and registered</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 text-[10px] font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100">
                                <th class="px-10 py-5">SKU / Product Name</th>
                                <th class="px-6 py-5 text-center">Qty Recv.</th>
                                <th class="px-6 py-5 text-right w-32">Unit Cost</th>
                                <th class="px-10 py-5 text-right w-32">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($purchase->items as $item)
                            <tr class="text-sm">
                                <td class="px-10 py-6">
                                    <p class="font-bold text-slate-900">{{ $item->product->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">SKU: {{ $item->product->barcode }}</p>
                                </td>
                                <td class="px-6 py-6 text-center font-bold text-slate-700">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-6 text-right font-bold text-slate-500">
                                    ${{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="px-10 py-6 text-right font-extrabold text-slate-900">
                                    ${{ number_format($item->subtotal, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-10 bg-slate-50/30 border-t border-slate-50 text-right">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Acquisition Valuation</p>
                    <h3 class="text-4xl font-extrabold text-indigo-600 tracking-tighter">${{ number_format($purchase->total_amount, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Sidebar Meta -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10 space-y-8">
                     <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Audit ID</p>
                        <h3 class="text-xl font-black">{{ $purchase->invoice_no }}</h3>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">Supplier Partner</p>
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-white/5 text-indigo-400">
                                <i class="fas fa-truck text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-100">{{ $purchase->supplier->name }}</p>
                                <p class="text-xs font-medium text-slate-400">{{ $purchase->supplier->email ?? 'no-email@partner' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Status History</p>
                        <div class="flex items-center gap-2">
                             <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                             <span class="text-sm font-bold text-emerald-400 uppercase tracking-widest">{{ $purchase->status }}</span>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-white/5">
                        <p class="text-xs font-medium text-slate-500 italic">This acquisition record has been verified and stock levels have been automatically adjusted in the primary database node.</p>
                    </div>
                </div>
                <i class="fas fa-shield-check absolute -bottom-10 -right-10 text-[12rem] opacity-5 -rotate-12"></i>
            </div>
        </div>
    </div>
</div>
@endsection
