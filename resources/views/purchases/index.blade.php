@extends('layouts.app')

@section('header', 'Purchase History')

@section('content')
<div class="flex flex-col gap-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Inventory Acquisitions</h2>
            <p class="text-sm font-medium text-slate-400 mt-1">Track all stock arrivals and supplier invoices</p>
        </div>
        <a href="{{ route('purchases.create') }}" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-extrabold shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 transition-all flex items-center gap-3 active:scale-95">
            <i class="fas fa-plus-circle"></i> New Stock Arrival
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        <th class="px-10 py-5">Date / Invoice</th>
                        <th class="px-8 py-5">Supplier Partner</th>
                        <th class="px-8 py-5">Total Valuation</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-10 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($purchases as $purchase)
                    <tr class="group hover:bg-slate-50/30 transition-colors text-sm">
                        <td class="px-10 py-6">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}</span>
                                <span class="text-[10px] font-extrabold text-indigo-400 uppercase tracking-wider">{{ $purchase->invoice_no }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i class="fas fa-truck text-xs"></i>
                                </div>
                                <span class="font-bold text-slate-700">{{ $purchase->supplier->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-extrabold text-slate-900">${{ number_format($purchase->total_amount, 2) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold text-emerald-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                {{ $purchase->status }}
                            </span>
                        </td>
                        <td class="px-10 py-6 text-right">
                            <a href="{{ route('purchases.show', $purchase) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-900 hover:text-white transition-all">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="opacity-10 mb-4">
                                <i class="fas fa-file-invoice-dollar text-6xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-400">No purchase records found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
