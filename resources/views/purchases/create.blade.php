@extends('layouts.app')

@section('header', 'New Stock Acquisition')

@section('content')
<form action="{{ route('purchases.store') }}" method="POST" id="purchase-form">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50">
                    <h2 class="text-xl font-extrabold text-slate-900">Acquisition Details</h2>
                    <p class="text-xs font-medium text-slate-400 mt-1">Itemized list of products being received into inventory</p>
                </div>
                
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left" id="purchase-items-table">
                        <thead>
                            <tr class="bg-slate-50/50 text-[10px] font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100">
                                <th class="px-8 py-4">Item Selection</th>
                                <th class="px-6 py-4 w-32">Quantity</th>
                                <th class="px-6 py-4 w-40">Purchase Price ($)</th>
                                <th class="px-6 py-4">Subtotal</th>
                                <th class="px-8 py-4 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50" id="purchase-items-body">
                            <!-- Dynamic Rows -->
                        </tbody>
                    </table>
                </div>

                <div class="p-8 border-t border-slate-50 bg-slate-50/30">
                    <button type="button" id="add-item-btn" class="flex items-center gap-2 text-indigo-600 font-extrabold text-xs uppercase tracking-widest hover:text-indigo-700 transition-colors">
                        <i class="fas fa-plus-circle text-lg"></i> Append Line Item
                    </button>
                </div>
            </div>
        </div>

        <!-- Meta Data Panel -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10 space-y-6">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 block mb-3">Supplier Source</label>
                        <select name="supplier_id" class="w-full border-0 bg-white/10 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-2 focus:ring-indigo-400 text-white" required>
                            <option value="" disabled selected class="text-slate-900">Select Partner...</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" class="text-slate-900">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 block mb-3">Arrival Date</label>
                        <input type="date" name="purchase_date" class="w-full border-0 bg-white/10 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-2 focus:ring-indigo-400 text-white" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="pt-6 border-t border-white/10">
                        <div class="flex justify-between items-end mb-8">
                            <span class="text-sm font-bold opacity-40">Gross Valuation</span>
                            <span id="grand-total-display" class="text-4xl font-extrabold text-indigo-400 tracking-tighter">$0.00</span>
                        </div>
                        
                        <x-button class="w-full !py-5 !bg-indigo-600 hover:!bg-indigo-500 !text-white shadow-xl shadow-indigo-500/20 text-lg border-0">
                            Confirm Acquisition
                        </x-button>
                    </div>
                </div>
                <i class="fas fa-truck-loading absolute bottom-[-30px] right-[-30px] text-9xl opacity-5 rotate-12"></i>
            </div>
        </div>
    </div>
</form>

<template id="row-template">
    <tr class="item-row group hover:bg-slate-50/50 transition-colors border-0">
        <td class="px-8 py-5">
            <select name="items[INDEX][product_id]" class="w-full border-0 bg-slate-100/50 py-3 px-4 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-indigo-500 product-select" required>
                <option value="" disabled selected>Select SKU...</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}">
                        {{ $product->name }} (SKU: {{ $product->barcode }})
                    </option>
                @endforeach
            </select>
        </td>
        <td class="px-6 py-5 text-center">
            <input type="number" name="items[INDEX][quantity]" class="w-full border-0 bg-slate-100/50 py-3 px-4 text-sm font-bold rounded-xl focus:ring-2 focus:ring-indigo-500 qty-input" value="1" min="1" required>
        </td>
        <td class="px-6 py-5">
            <input type="number" name="items[INDEX][unit_price]" step="0.01" class="w-full border-0 bg-slate-100/50 py-3 px-4 text-sm font-bold rounded-xl focus:ring-2 focus:ring-indigo-500 price-input" required>
        </td>
        <td class="px-6 py-5">
            <span class="text-sm font-extrabold text-slate-900 row-subtotal">$0.00</span>
        </td>
        <td class="px-8 py-5 text-right">
            <button type="button" class="h-9 w-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white transition-all remove-row-btn">
                <i class="fas fa-trash-alt text-xs"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" } });
    let rowCount = 0;

    function addNewRow() {
        let template = $('#row-template').html();
        template = template.replace(/INDEX/g, rowCount);
        $('#purchase-items-body').append(template);
        rowCount++;
        updateGrandTotal();
    }

    // Initial Row
    addNewRow();

    $('#add-item-btn').on('click', addNewRow);

    $(document).on('click', '.remove-row-btn', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('tr').remove();
            updateGrandTotal();
        }
    });

    $(document).on('change', '.product-select', function() {
        let price = $(this).find(':selected').data('price');
        $(this).closest('tr').find('.price-input').val(price);
        updateRowSubtotal($(this).closest('tr'));
    });

    $(document).on('input', '.qty-input, .price-input', function() {
        updateRowSubtotal($(this).closest('tr'));
    });

    function updateRowSubtotal(row) {
        let qty = parseFloat(row.find('.qty-input').val()) || 0;
        let price = parseFloat(row.find('.price-input').val()) || 0;
        let subtotal = qty * price;
        row.find('.row-subtotal').text('$' + subtotal.toFixed(2));
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        $('.item-row').each(function() {
            let qty = parseFloat($(this).find('.qty-input').val()) || 0;
            let price = parseFloat($(this).find('.price-input').val()) || 0;
            grandTotal += qty * price;
        });
        $('#grand-total-display').text('$' + grandTotal.toFixed(2));
    }
});
</script>
@endpush
