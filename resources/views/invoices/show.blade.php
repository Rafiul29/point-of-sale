@extends('layouts.app')

@section('header', 'Invoice #' . str_pad($sale->id, 6, '0', STR_PAD_LEFT))

@section('content')
    <div class="max-w-4xl mx-auto py-12 print:py-0 print:max-w-full">
        <!-- Actions Panel -->
        <div
            class="mb-10 flex flex-col sm:flex-row justify-between items-center bg-white p-6 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 print:hidden gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('pos.index') }}"
                    class="h-11 px-5 flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-2xl transition-all active:scale-95">
                    <i class="fas fa-arrow-left text-xs opacity-50"></i> Return to Terminal
                </a>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <button onclick="window.print()"
                    class="flex-1 sm:flex-none h-11 px-8 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center gap-2 active:scale-95 shadow-sm">
                    <i class="fas fa-print opacity-50"></i> Local Print
                </button>
                <a href="{{ route('invoice.download', $sale->id) }}" target="_blank"
                    class="flex-1 sm:flex-none h-11 px-8 rounded-2xl bg-indigo-600 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 active:translate-y-0 active:scale-95">
                    <i class="fas fa-file-pdf"></i> Fetch PDF Node
                </a>
            </div>
        </div>

        <!-- Master Document Container -->
        <div
            class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/40 border border-slate-100 overflow-hidden print:shadow-none print:border-none print:rounded-none">
            <!-- Header: High Contrast -->
            <div
                class="bg-slate-900 p-12 text-white relative overflow-hidden print:bg-white print:text-black print:p-8 print:border-b-2 print:border-slate-200">
                <div class="relative z-10 flex flex-col lg:flex-row justify-between gap-12">
                    <div class="flex-1">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 shadow-xl shadow-indigo-600/40 mb-8 print:hidden">
                            <i class="fas fa-terminal text-white text-xl"></i>
                        </div>
                        <h1 class="text-4xl font-black tracking-tight leading-none print:text-3xl">
                            {{ $settings['shop_name'] ?? 'TERMINAL V' }}</h1>
                        <p
                            class="mt-4 text-sm font-bold text-slate-400 max-w-sm leading-relaxed print:text-slate-600 print:text-xs uppercase tracking-widest opacity-80">
                            {{ $settings['shop_address'] ?? 'Central Business Node, Dhaka IT District' }}<br>
                            Comm: {{ $settings['shop_phone'] ?? '+880 1234 567 890' }}
                        </p>
                    </div>

                    <div class="text-left lg:text-right shrink-0">
                        <div class="inline-block bg-white/5 border border-white/10 px-4 py-1.5 rounded-full mb-6 print:hidden">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400">Secure Protocol
                                Enabled</span>
                        </div>
                        <h2 class="text-5xl font-black tracking-tighter mb-4 print:text-3xl print:mb-2">
                            #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</h2>

                        <div class="space-y-1 mt-6">
                            <p class="text-xs font-bold text-slate-400 print:text-slate-600 uppercase tracking-widest">
                                Timestamp: <span
                                    class="text-white print:text-black ml-2">{{ $sale->created_at->format('d M, Y • h:i A') }}</span>
                            </p>
                            <p class="text-xs font-bold text-slate-400 print:text-slate-600 uppercase tracking-widest">
                                Operator ID: <span
                                    class="text-white print:text-black ml-2">{{ strtoupper($sale->user->name) }}</span></p>
                        </div>
                    </div>
                </div>
                <!-- Artistic Background Decor -->
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-indigo-600/10 rounded-full blur-[120px] -mr-40 -mt-40 print:hidden"></div>
                <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-600/5 rounded-full blur-[100px] -ml-20 -mb-20 print:hidden"></div>
            </div>

            <!-- Content Body: Refined Data Blocks -->
            <div class="p-12 print:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">
                    <!-- Client Section -->
                    <div class="space-y-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 print:text-slate-500">
                                Recipient Identity</p>
                            <div class="flex items-start gap-5">
                                <div class="w-14 h-14 rounded-2x; bg-indigo-50 flex items-center justify-center text-indigo-600 print:hidden shrink-0 shadow-sm border border-indigo-100">
                                    <i class="fas fa-user-tie text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-black text-slate-900 leading-none mb-2 print:text-base">
                                        {{ $sale->customer->name ?? 'Walk-in Client' }}</p>
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold text-slate-400 print:text-slate-600 flex items-center gap-2">
                                            <i class="fas fa-phone-alt opacity-30 text-[10px]"></i>
                                            {{ $sale->customer->phone ?? 'Unlisted Mobile Index' }}
                                        </p>
                                        @if($sale->customer && $sale->customer->email)
                                            <p class="text-xs font-bold text-slate-400 print:text-slate-600 flex items-center gap-2">
                                                <i class="fas fa-envelope opacity-30 text-[10px]"></i>
                                                {{ $sale->customer->email }}
                                            </p>
                                        @endif
                                        @if($sale->customer && $sale->customer->address)
                                            <p class="text-xs font-bold text-slate-400 print:text-slate-600 flex items-center gap-2">
                                                <i class="fas fa-map-marker-alt opacity-30 text-[10px]"></i>
                                                {{ $sale->customer->address }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settlement Section -->
                    <div class="md:text-right space-y-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 print:text-slate-500">
                                Settlement Logic</p>
                            <div class="space-y-3">
                                <div class="flex md:justify-end items-center gap-3">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Method</span>
                                    <span class="px-4 py-1.5 rounded-xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-slate-900/10">
                                        {{ $sale->payment_method }} Node
                                    </span>
                                </div>
                                <div class="flex md:justify-end items-center gap-3">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Status</span>
                                    <span class="px-4 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black uppercase tracking-widest">
                                        {{ strtoupper($sale->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ledger: Itemized -->
                <div class="overflow-x-auto -mx-12 px-12 md:mx-0 md:px-0 mb-16">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-y border-slate-100 print:bg-slate-50 print:border-slate-200">
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Node Description</th>
                                <th class="px-6 py-5 text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Qty</th>
                                <th class="px-6 py-5 text-right w-40 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Unit Rate</th>
                                <th class="px-8 py-5 text-right w-40 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 border-b border-slate-100">
                            @foreach($sale->items as $item)
                                <tr class="group hover:bg-slate-50/50 transition-all">
                                    <td class="px-8 py-6">
                                        <p class="text-base font-black text-slate-900 mb-1">{{ $item->product->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID:
                                            {{ $item->product->barcode }}</p>
                                    </td>
                                    <td class="px-6 py-6 text-center font-black text-slate-700">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-6 text-right font-bold text-slate-500">
                                        {{ $settings['currency_symbol'] ?? '৳' }} {{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-8 py-6 text-right font-black text-indigo-600 text-lg">
                                        {{ $settings['currency_symbol'] ?? '৳' }} {{ number_format($item->subtotal, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Reconciliation Summary -->
                <div class="flex flex-col md:flex-row justify-between items-start gap-12">
                    <div class="flex-1">
                        <div class="bg-indigo-50/50 rounded-3xl p-8 border border-indigo-100/50 print:hidden max-w-md">
                            <h4 class="text-sm font-black text-indigo-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle text-indigo-400"></i> Terminal Memo
                            </h4>
                            <p class="text-xs font-bold text-indigo-700/60 italic leading-relaxed">
                                Thank you for your settlement with {{ $settings['shop_name'] ?? 'TERMINAL V' }}. This document serves as a verified legal acknowledgment of transaction completion. Digital records have been synchronized with the master node.
                            </p>
                        </div>
                    </div>

                    <div class="w-full md:w-80 space-y-4">
                        <div class="flex justify-between items-center text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Gross Revenue</span>
                            <span class="text-slate-900">{{ $settings['currency_symbol'] ?? '৳' }} {{ number_format($sale->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Tax Contribution</span>
                            <span class="text-slate-900">{{ $settings['currency_symbol'] ?? '৳' }} {{ number_format($sale->tax_amount, 2) }}</span>
                        </div>
                        <div class="pt-6 border-t-2 border-slate-900/5 mt-4">
                            <div class="flex justify-between items-end">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-1">Aggregate Sum</span>
                                    <span class="text-sm font-black text-slate-900 uppercase">Paid Total</span>
                                </div>
                                <span class="text-4xl font-black text-slate-900 tracking-tighter leading-none">
                                    {{ $settings['currency_symbol'] ?? '৳' }} {{ number_format($sale->grand_total, 2) }}
                                </span>
                            </div>
                        </div>

                        @if($sale->due_amount > 0)
                            <div class="mt-8 p-6 rounded-3xl bg-rose-50 border border-rose-100 flex flex-col gap-2">
                                <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Balance Pending Reconciliation</span>
                                <span class="text-3xl font-black text-rose-600 tracking-tight">
                                    {{ $settings['currency_symbol'] ?? '৳' }} {{ number_format($sale->due_amount, 2) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Verified Seal Footer -->
            <div class="bg-white p-10 flex flex-col items-center justify-center border-t border-slate-50 print:p-6 print:border-none">
                <div class="h-12 w-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-200 mb-4 print:hidden border border-slate-100 shadow-inner">
                    <i class="fas fa-shield-halved text-lg"></i>
                </div>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] mb-1">Authenticated Terminal Payload</p>
                <div class="flex items-center gap-2 mt-4 opacity-10 grayscale print:hidden">
                    <div class="h-1 w-8 bg-slate-400 rounded-full"></div>
                    <div class="h-1 w-32 bg-slate-400 rounded-full"></div>
                    <div class="h-1 w-8 bg-slate-400 rounded-full"></div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            @media print {
                @page { margin: 0; size: auto; }
                body { background: white !important; -webkit-print-color-adjust: exact; }
                main { padding: 0 !important; width: 100% !important; margin: 0 !important; }
                .rounded-\[3rem\], .rounded-3xl { border-radius: 0 !important; }
                .shadow-2xl, .shadow-xl { box-shadow: none !important; }
                header, nav, .print\:hidden { display: none !important; }
                .max-w-4xl { max-width: 100% !important; }
            }
        </style>
    @endpush
@endsection