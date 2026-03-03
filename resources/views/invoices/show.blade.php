@extends('layouts.app')

@section('header', 'Invoice #' . str_pad($sale->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto py-10 print:py-0 print:max-w-full">
    <!-- Actions -->
    <div class="mb-8 flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-200 print:hidden">
        <a href="{{ route('pos.index') }}" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Terminal
        </a>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition-all active:scale-95">
                <i class="fas fa-print mr-2"></i> Print Receipt
            </button>
            <a href="{{ route('invoice.download', $sale->id) }}" target="_blank" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-slate-900/20 hover:bg-slate-800 transition-all active:scale-95">
                <i class="fas fa-file-pdf mr-2"></i> PDF
            </a>
        </div>
    </div>

    <!-- Receipt Container -->
    <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden print:shadow-none print:border-none print:rounded-none">
        <!-- Receipt Header -->
        <div class="bg-slate-900 p-12 text-white relative overflow-hidden print:bg-white print:text-black print:p-8 print:border-b-2 print:border-slate-200">
            <div class="relative z-10 flex flex-col md:flex-row justify-between gap-8">
                <div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 shadow-xl shadow-indigo-600/30 mb-6 print:hidden">
                        <i class="fas fa-bolt text-white text-xl"></i>
                    </div>
                    <h1 class="text-3xl font-black tracking-tight print:text-2xl">{{ $settings['shop_name'] ?? 'Antigravity POS' }}</h1>
                    <p class="mt-2 text-sm font-medium text-slate-400 max-w-[250px] leading-relaxed print:text-slate-600 print:text-xs">
                        {{ $settings['shop_address'] ?? 'Silicon Valley, CA' }}<br>
                        {{ $settings['shop_phone'] ?? '+1 555 000 000' }}
                    </p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-indigo-400 mb-1 print:text-slate-500">Transaction Ref</p>
                    <h2 class="text-2xl font-black tracking-tight mb-6 print:text-xl print:mb-2">#TXN-{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</h2>
                    
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 print:text-slate-600">Issued On: <span class="text-white print:text-black">{{ $sale->created_at->format('M d, Y') }}</span></p>
                        <p class="text-xs font-bold text-slate-400 print:text-slate-600">Authorized By: <span class="text-white print:text-black">{{ $sale->user->name }}</span></p>
                    </div>
                </div>
            </div>
            <i class="fas fa-file-invoice-dollar absolute right-[-20px] bottom-[-20px] text-[15rem] opacity-5 -rotate-12 print:hidden"></i>
        </div>

        <!-- Billing Info -->
        <div class="p-12 border-b border-slate-50 print:p-8 print:border-slate-200">
            <div class="flex flex-col md:flex-row justify-between gap-8">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-4 print:text-slate-500 print:mb-2">Customer Identity</p>
                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($sale->customer->name ?? 'Walk-in') }}&background=6366f1&color=fff" class="h-10 w-10 rounded-xl print:hidden" alt="">
                        <div>
                            <p class="text-base font-black text-slate-900 print:text-sm">{{ $sale->customer->name ?? 'Walk-in Client' }}</p>
                            <p class="text-xs font-bold text-slate-400 print:text-slate-600">{{ $sale->customer->phone ?? 'Point of Sale Entry' }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-4 print:text-slate-500 print:mb-2">Settlement Details</p>
                    <div class="space-y-1 text-sm font-bold text-slate-600 print:text-xs">
                        <p>Method: <span class="text-indigo-600 print:text-black">{{ $sale->payment_method }}</span></p>
                        <p>Status: <span class="uppercase tracking-widest text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-lg print:border print:border-emerald-200 print:bg-white">{{ $sale->status }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Itemized List -->
        <div class="p-0">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-[10px] font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100 print:bg-slate-50 print:border-slate-200">
                        <th class="px-12 py-5 print:px-8 print:py-3 text-black">Product Description</th>
                        <th class="px-6 py-5 text-center print:px-4 print:py-3 text-black">Qty</th>
                        <th class="px-6 py-5 text-right w-32 print:px-4 print:py-3 text-black">Unit Price</th>
                        <th class="px-12 py-5 text-right w-32 print:px-8 print:py-3 text-black">Line Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 print:divide-slate-200">
                    @foreach($sale->items as $item)
                    <tr class="text-sm print:text-xs">
                        <td class="px-12 py-6 print:px-8 print:py-3">
                            <p class="font-bold text-slate-900">{{ $item->product->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter print:text-xs">SKU: {{ $item->product->barcode }}</p>
                        </td>
                        <td class="px-6 py-6 text-center font-bold text-slate-600 print:px-4 print:py-3">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-6 text-right font-bold text-slate-600 print:px-4 print:py-3">
                            {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->unit_price, 2) }}
                        </td>
                        <td class="px-12 py-6 text-right font-black text-slate-900 print:px-8 print:py-3">
                            {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="p-12 bg-slate-50/50 print:p-8 print:bg-white print:border-t-2 print:border-slate-200">
            <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                <div class="flex-1 italic text-slate-400 text-xs leading-relaxed print:text-slate-600 print:text-[10px]">
                    <p>Thank you for choosing {{ $settings['shop_name'] ?? 'Antigravity POS' }}. All digital transactions are verified per standard compliance protocols. Please retain this receipt for warranty claims.</p>
                </div>
                <div class="w-full md:w-64 space-y-4 print:w-48 print:space-y-2">
                    <div class="flex justify-between items-center text-sm font-bold text-slate-500 print:text-xs">
                        <span>Net Subtotal</span>
                        <span>{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-bold text-slate-500 print:text-xs">
                        <span>Associated Tax</span>
                        <span>{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->tax_amount, 2) }}</span>
                    </div>
                    <div class="pt-4 border-t border-slate-200 print:pt-2">
                        <div class="flex justify-between items-end">
                            <span class="text-sm font-black text-slate-900 uppercase print:text-xs">Paid Total</span>
                            <span class="text-3xl font-black text-indigo-600 tracking-tighter print:text-xl print:text-black">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->grand_total, 2) }}</span>
                        </div>
                    </div>

                    @if($sale->due_amount > 0)
                    <div class="mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-100 flex justify-between items-center print:mt-2 print:p-2 print:bg-white print:border-rose-200">
                        <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest print:text-[8px]">Balance Pending</span>
                        <span class="text-lg font-black text-rose-500 print:text-sm">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->due_amount, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer Seal -->
        <div class="bg-white p-8 flex flex-col items-center justify-center border-t border-slate-50 print:p-4 print:border-none">
            <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-300 mb-4 print:hidden">
                 <i class="fas fa-shield-alt"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.4em] print:text-slate-500 print:text-[8px]">Verified Transaction Node</p>
        </div>
    </div>
</div>

@push('css')
<style>
    @media print {
        @page { 
            margin: 0; 
            size: auto; 
        }
        body { 
            background: white !important; 
            margin: 0 !important; 
            padding: 0 !important; 
            -webkit-print-color-adjust: exact;
        }
        .ml-72 { margin-left: 0 !important; }
        main { 
            padding: 0 !important; 
            margin: 0 !important; 
            width: 100% !important;
        }
        .max-w-4xl { max-width: 100% !important; width: 100% !important; margin: 0 !important; }
        .rounded-\[3rem\] { border-radius: 0 !important; }
        .shadow-2xl { box-shadow: none !important; }
        
        /* Hide navbar and sidebar specifically if they leak */
        header, .print\:hidden { display: none !important; }
    }
</style>
@endpush
@endsection