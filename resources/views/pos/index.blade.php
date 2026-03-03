@extends('layouts.app')

@section('header', 'POS Workstation')

@section('content')
@php
    $currency = $settings['currency_symbol'] ?? '$';
    $tax_rate = ($settings['tax_percentage'] ?? 5);
@endphp

<div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
    <!-- Left: Cart -->
    <div class="lg:col-span-8">
        <div class="flex flex-col rounded-[2rem] bg-white shadow-sm border border-slate-100 overflow-hidden min-h-[700px]">
            <!-- Barcode Input Header -->
            <div class="flex items-center gap-4 border-b border-slate-50 px-8 py-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <i class="fas fa-barcode text-xl"></i>
                </div>
                <div class="relative flex-1 max-w-lg">
                    <input type="text" id="barcode-input"
                        class="w-full border-0 bg-slate-100 py-3.5 pl-5 pr-16 text-sm font-semibold rounded-2xl focus:ring-2 focus:ring-indigo-500 placeholder:text-slate-400"
                        placeholder="Scan barcode or enter SKU then press Enter..." autofocus>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 rounded-lg bg-white px-2 py-1 text-[10px] font-bold text-slate-400 shadow-sm">Alt+S</span>
                </div>
            </div>

            <!-- Cart Table -->
            <div class="flex-1 overflow-y-auto">
                <table class="w-full text-left">
                    <thead class="sticky top-0 z-10 bg-white border-b border-slate-50">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Item</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Price</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Qty</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Total</th>
                            <th class="px-8 py-4 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Del</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items" class="divide-y divide-slate-50">
                        <tr id="empty-cart-row">
                            <td colspan="5" class="py-24 text-center text-slate-300">
                                <i class="fas fa-shopping-basket text-5xl mb-4 opacity-20 block"></i>
                                <p class="text-sm font-bold">Terminal ready — scan a product</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right: Summary Panel -->
    <div class="lg:col-span-4">
        <div class="space-y-6 sticky top-24">
            <!-- Customer -->
            <div class="rounded-[2rem] bg-white p-6 shadow-sm border border-slate-100">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Customer Profile</label>
                <select id="customer-select" class="w-full border-0 bg-slate-100 py-3.5 px-4 text-sm font-semibold rounded-2xl focus:ring-2 focus:ring-indigo-500">
                    <option value="">Walk-in Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Totals -->
            <div class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10 space-y-4">
                    <div class="flex justify-between items-center text-sm opacity-60">
                        <span class="font-semibold">Subtotal</span>
                        <span id="summary-subtotal" class="font-bold">{{ $currency }}0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-indigo-400">
                        <span class="font-semibold">Tax ({{ $tax_rate }}%)</span>
                        <span id="summary-tax" class="font-bold">{{ $currency }}0.00</span>
                    </div>
                    <div class="border-t border-white/5 my-4"></div>
                    <div class="flex justify-between items-end">
                        <span class="text-xs font-bold opacity-40 uppercase tracking-widest">Total Payable</span>
                        <span id="summary-total" class="text-4xl font-extrabold text-indigo-400 tracking-tighter">{{ $currency }}0.00</span>
                    </div>
                </div>

                <div class="mt-8 space-y-3 relative z-10">
                    <button type="button" id="finalize-btn"
                        class="w-full rounded-[1.5rem] py-5 text-lg font-extrabold text-white flex items-center justify-center gap-3
                               bg-gradient-to-r from-indigo-500 to-purple-600 shadow-2xl shadow-indigo-600/30
                               transition-all opacity-40 cursor-not-allowed">
                        <i class="fas fa-rocket mr-2 opacity-70"></i> Finalize Sale
                    </button>
                    <button type="button" id="clear-cart-btn"
                        class="w-full py-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-rose-400 flex items-center justify-center gap-2 transition-colors">
                        <i class="fas fa-undo-alt"></i> Clear Workstation
                    </button>
                </div>
                <i class="fas fa-cash-register absolute -bottom-10 -right-10 text-[10rem] opacity-[0.03] rotate-12"></i>
            </div>
        </div>
    </div>
</div>

<!-- ═════════════════════ MODAL ═════════════════════ -->
<div id="finalizeModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md">
    <div class="relative w-full max-w-4xl max-h-[90vh] overflow-hidden bg-white rounded-[3.5rem] shadow-2xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 h-full">
            <!-- Left: Payment Options -->
            <div class="p-12 overflow-y-auto">
                <div class="flex justify-between items-start mb-10">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900">Checkout</h2>
                        <p class="text-sm font-medium text-slate-400 mt-1">Select payment and reconcile amount.</p>
                    </div>
                    <button id="close-modal-btn" class="h-12 w-12 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <div class="space-y-10">
                    <!-- Payment Method -->
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 block">Settlement Channel</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="Cash" class="peer sr-only" checked>
                                <div class="flex flex-col items-center gap-3 p-5 rounded-3xl border-2 border-slate-50 transition-all peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 text-slate-300 peer-checked:text-indigo-600 hover:border-slate-100 italic">
                                    <i class="fas fa-wallet text-2xl"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Cash</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="Card" class="peer sr-only">
                                <div class="flex flex-col items-center gap-3 p-5 rounded-3xl border-2 border-slate-50 transition-all peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 text-slate-300 peer-checked:text-indigo-600 hover:border-slate-100">
                                    <i class="fas fa-credit-card text-2xl"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Card</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="Credit" class="peer sr-only">
                                <div class="flex flex-col items-center gap-3 p-5 rounded-3xl border-2 border-slate-50 transition-all peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 text-slate-300 peer-checked:text-indigo-600 hover:border-slate-100">
                                    <i class="fas fa-handshake text-2xl"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Credit</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Tender Tool -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Tendered Amount</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-2xl font-black text-slate-300">{{ $currency }}</span>
                            <input type="number" id="paid_amount_input" step="0.01" min="0"
                                class="w-full border-0 bg-slate-50 py-7 pl-14 pr-7 text-4xl font-black rounded-3xl focus:ring-4 focus:ring-indigo-600/5 placeholder:text-slate-200"
                                value="0.00">
                        </div>
                        <div class="flex justify-between items-center p-6 rounded-3xl bg-slate-900 text-white shadow-xl shadow-slate-900/20">
                            <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Reconciliation</span>
                            <span id="due-amount-display" class="text-2xl font-black text-emerald-400">{{ $currency }}0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Matrix -->
            <div class="bg-indigo-600 p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40 mb-8 italic">Order Matrix</p>
                    <div id="modal-items-list" class="space-y-4 mb-10 max-h-64 overflow-y-auto pr-4 scroll-smooth"></div>
                    
                    <div class="space-y-4 border-t border-white/10 pt-8">
                        <div class="flex justify-between text-xs font-bold opacity-60">
                            <span>Net Subtotal</span>
                            <span id="modal-subtotal-display">{{ $currency }}0.00</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-indigo-200">
                            <span>Computed Tax</span>
                            <span id="modal-tax-display">{{ $currency }}0.00</span>
                        </div>
                        <div class="pt-6">
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-2">Final Invoice Total</p>
                            <h2 id="modal-total-display" class="text-6xl font-black tracking-tighter text-white">{{ $currency }}0.00</h2>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-10">
                    <button id="confirm-sale-btn"
                        class="w-full py-6 rounded-[2rem] text-xl font-black text-indigo-600 bg-white shadow-2xl transition-all hover:scale-[1.03] active:scale-[0.97] flex items-center justify-center gap-4">
                        Commit Transaction
                        <i class="fas fa-check-circle text-lg"></i>
                    </button>
                    <p class="text-center text-[10px] font-bold opacity-30 mt-6 uppercase tracking-widest">Verified by active node system</p>
                </div>
                
                <i class="fas fa-shield-check absolute -bottom-10 -right-10 text-[15rem] opacity-5 -rotate-12 transition-transform duration-1000"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function () {
    let cart = [];
    const TAX_RATE = {{ ($settings['tax_percentage'] ?? 5) / 100 }};
    const CURRENCY = '{{ $currency }}';

    /* ──────────────── NETWORK ──────────────── */
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    /* ──────────────── INPUT LAYER ──────────────── */
    $(document).on('keydown', function (e) {
        if (e.altKey && e.which === 83) { $('#barcode-input').focus(); }
    });

    $('#barcode-input').on('keydown', function (e) {
        if (e.which === 13) {
            let val = $(this).val().trim();
            if (val) { searchProduct(val); $(this).val(''); }
        }
    });

    function searchProduct(barcode) {
        $.get('{{ route('pos.search') }}', { barcode: barcode }, function (res) {
            if (res.success) { addToCart(res.product); } 
            else { alert('SKU not recognized: ' + barcode); }
        }).fail(function () { alert('Network error. Check node connection.'); });
    }

    /* ──────────────── CORE CART ──────────────── */
    function addToCart(product) {
        let existing = cart.find(i => i.id === product.id);
        if (existing) { existing.quantity += 1; } 
        else {
            cart.push({ 
                id: product.id, 
                name: product.name, 
                barcode: product.barcode, 
                price: parseFloat(product.selling_price), 
                quantity: 1 
            });
        }
        renderCart();
    }

    function renderCart() {
        let $tbody = $('#cart-items');
        $tbody.empty();

        if (cart.length === 0) {
            $tbody.append('<tr id="empty-cart-row"><td colspan="5" class="py-24 text-center text-slate-300"><i class="fas fa-shopping-basket text-5xl mb-4 opacity-20 block"></i><p class="text-sm font-bold opacity-60 italic">Terminal ready — scan or enter SKU</p></td></tr>');
            setFinalizeEnabled(false);
            updateTotals(0);
            return;
        }

        let subtotal = 0;
        cart.forEach(function (item, index) {
            let lineTotal = item.price * item.quantity;
            subtotal += lineTotal;
            $tbody.append(`
                <tr class="group hover:bg-slate-50/50 transition-all border-b border-transparent hover:border-slate-100">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-5">
                            <div class="h-12 w-12 flex items-center justify-center rounded-2xl bg-white shadow-sm border border-slate-50 text-indigo-500 shrink-0 group-hover:scale-110 transition-transform"><i class="fas fa-cube text-sm"></i></div>
                            <div>
                                <p class="text-sm font-black text-slate-900">${item.name}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">${item.barcode}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-6 text-sm font-bold text-slate-600">${CURRENCY}${item.price.toFixed(2)}</td>
                    <td class="px-6 py-6">
                        <div class="flex items-center gap-2 bg-slate-100 rounded-2xl px-3 py-1.5 w-fit border border-slate-200/50">
                            <button class="h-8 w-8 flex items-center justify-center rounded-xl bg-white text-slate-400 hover:text-indigo-600 shadow-sm decrease-qty transition-all active:scale-90" data-index="${index}"><i class="fas fa-minus text-[10px]"></i></button>
                            <span class="w-8 text-center text-xs font-black text-slate-900">${item.quantity}</span>
                            <button class="h-8 w-8 flex items-center justify-center rounded-xl bg-white text-slate-400 hover:text-indigo-600 shadow-sm increase-qty transition-all active:scale-90" data-index="${index}"><i class="fas fa-plus text-[10px]"></i></button>
                        </div>
                    </td>
                    <td class="px-6 py-6 text-sm font-black text-indigo-600">${CURRENCY}${lineTotal.toFixed(2)}</td>
                    <td class="px-8 py-6 text-right">
                        <button class="h-10 w-10 flex items-center justify-center rounded-xl text-slate-200 hover:text-rose-500 hover:bg-rose-50 transition-all remove-item" data-index="${index}"><i class="fas fa-trash-alt text-xs"></i></button>
                    </td>
                </tr>
            `);
        });

        updateTotals(subtotal);
        setFinalizeEnabled(true);
    }

    function updateTotals(subtotal) {
        let tax = subtotal * TAX_RATE;
        let grand = subtotal + tax;
        $('#summary-subtotal').text(CURRENCY + subtotal.toFixed(2));
        $('#summary-tax').text(CURRENCY + tax.toFixed(2));
        $('#summary-total').text(CURRENCY + grand.toFixed(2));
    }

    function setFinalizeEnabled(enabled) {
        let $btn = $('#finalize-btn');
        if (enabled) {
            $btn.removeClass('opacity-40 cursor-not-allowed').addClass('hover:scale-[1.02] active:scale-[0.98] cursor-pointer shadow-indigo-500/40');
        } else {
            $btn.addClass('opacity-40 cursor-not-allowed').removeClass('hover:scale-[1.02] active:scale-[0.98] cursor-pointer shadow-indigo-500/40');
        }
        $btn.data('enabled', enabled);
    }

    /* ──────────────── EVENTS ──────────────── */
    $(document).on('click', '.increase-qty', function () {
        let i = $(this).data('index'); cart[i].quantity += 1; renderCart();
    });

    $(document).on('click', '.decrease-qty', function () {
        let i = $(this).data('index');
        if (cart[i].quantity > 1) { cart[i].quantity -= 1; renderCart(); }
    });

    $(document).on('click', '.remove-item', function () {
        if(confirm('Purge this line item?')) { cart.splice($(this).data('index'), 1); renderCart(); }
    });

    $('#clear-cart-btn').on('click', function () {
        if (confirm('Verify: Purge entire workstation session?')) { cart = []; renderCart(); }
    });

    /* ──────────────── MODAL ENGINE ──────────────── */
    function openModal() {
        let subtotal = 0;
        cart.forEach(i => subtotal += i.price * i.quantity);
        let tax = subtotal * TAX_RATE;
        let grand = subtotal + tax;

        let itemsHtml = cart.map(i =>
            `<div class="flex justify-between items-center group"><div class="flex flex-col"><span class="text-xs font-black text-white group-hover:text-indigo-200 transition-colors">${i.name}</span><span class="text-[10px] font-bold text-white/40 italic">×${i.quantity} units</span></div><span class="font-black text-sm text-white">${CURRENCY}${(i.price * i.quantity).toFixed(2)}</span></div>`
        ).join('');
        $('#modal-items-list').html(itemsHtml);

        $('#modal-subtotal-display').text(CURRENCY + subtotal.toFixed(2));
        $('#modal-tax-display').text(CURRENCY + tax.toFixed(2));
        $('#modal-total-display').text(CURRENCY + grand.toFixed(2));
        $('#paid_amount_input').val(grand.toFixed(2)).trigger('input');

        $('#finalizeModal').fadeIn(300);
        setTimeout(() => $('#paid_amount_input').focus().select(), 100);
    }

    function closeModal() { $('#finalizeModal').fadeOut(200); }

    $('#finalize-btn').on('click', function () {
        if (!$(this).data('enabled')) return;
        if (cart.length === 0) return;
        openModal();
    });

    $('#close-modal-btn').on('click', closeModal);
    $('#finalizeModal').on('click', function (e) { if ($(e.target).is('#finalizeModal')) closeModal(); });

    /* ──────────────── TENDER RECONCILIATION ──────────────── */
    $('#paid_amount_input').on('input', function () {
        let totalStr = $('#modal-total-display').text().replace(CURRENCY, '').replace(/,/g, '');
        let total = parseFloat(totalStr) || 0;
        let paid = parseFloat($(this).val()) || 0;
        let reconciliation = paid - total;
        
        let $display = $('#due-amount-display');
        if (reconciliation >= 0) {
            $display.text(CURRENCY + reconciliation.toFixed(2) + ' change').removeClass('text-rose-400').addClass('text-emerald-400');
        } else {
            $display.text(CURRENCY + Math.abs(reconciliation).toFixed(2) + ' owed').removeClass('text-emerald-400').addClass('text-rose-400');
        }
    });

    /* ──────────────── COMMIT ──────────────── */
    $('#confirm-sale-btn').on('click', function () {
        let $btn = $(this);
        let subtotal = 0; cart.forEach(i => subtotal += i.price * i.quantity);
        let tax = subtotal * TAX_RATE;
        let grand = subtotal + tax;
        let paid = parseFloat($('#paid_amount_input').val()) || 0;
        let due = Math.max(0, grand - paid);

        let payload = {
            customer_id: $('#customer-select').val() || null,
            total_amount: parseFloat(subtotal.toFixed(2)),
            tax_amount: parseFloat(tax.toFixed(2)),
            grand_total: parseFloat(grand.toFixed(2)),
            paid_amount: parseFloat(paid.toFixed(2)),
            due_amount: parseFloat(due.toFixed(2)),
            payment_method: $('input[name="payment_method"]:checked').val() || 'Cash',
            items: cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity,
                unit_price: parseFloat(item.price.toFixed(2)),
                subtotal: parseFloat((item.price * item.quantity).toFixed(2))
            }))
        };

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-3"></i> Synchronizing Node...');

        $.ajax({
            url: '{{ route('pos.store') }}',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function (res) {
                if (res.success) {
                    cart = []; renderCart(); closeModal();
                    window.location.href = '{{ url('/invoice') }}/' + res.sale_id;
                } else {
                    alert('Node Error: ' + res.message);
                    $btn.prop('disabled', false).text('Commit Transaction');
                }
            },
            error: function (xhr) {
                let msg = 'Terminal failure.';
                try { msg = JSON.parse(xhr.responseText).message || xhr.responseText; } catch(e){}
                alert('CRITICAL ERROR [' + xhr.status + ']: ' + msg);
                $btn.prop('disabled', false).text('Commit Transaction');
            }
        });
    });
});
</script>
@endpush
