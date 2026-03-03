<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; margin: 0; padding: 40px; color: #1e293b; line-height: 1.5; }
        .header { margin-bottom: 40px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; }
        .shop-name { font-size: 24px; font-weight: bold; color: #000; margin-bottom: 5px; }
        .shop-info { font-size: 12px; color: #64748b; }
        .invoice-meta { float: right; text-align: right; margin-top: -65px; }
        .txn-id { font-size: 18px; font-weight: bold; color: #4f46e5; }
        .meta-line { font-size: 11px; color: #64748b; margin-top: 5px; }
        
        .client-info { margin-bottom: 40px; }
        .section-title { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 10px; }
        .client-name { font-size: 16px; font-weight: bold; }
        .client-detail { font-size: 12px; color: #64748b; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 12px 10px; text-align: left; font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b; }
        td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        .product-name { font-weight: bold; color: #0f172a; }
        .product-sku { font-size: 10px; color: #94a3b8; margin-top: 2px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .totals { float: right; width: 250px; }
        .total-row { padding: 8px 0; font-size: 13px; font-weight: 500; }
        .grand-total { border-top: 1px solid #e2e8f0; margin-top: 10px; padding-top: 15px; font-size: 20px; font-weight: bold; color: #4f46e5; }
        
        .footer { margin-top: 100px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 20px; font-size: 10px; color: #94a3b8; }
        .clearfix { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <div class="shop-name">{{ $settings['shop_name'] ?? 'Antigravity POS' }}</div>
        <div class="shop-info">
            {{ $settings['shop_address'] ?? 'Silicon Valley, CA' }}<br>
            {{ $settings['shop_phone'] ?? '+1 555 000 000' }}
        </div>
        <div class="invoice-meta">
            <div class="txn-id">INVOICE #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="meta-line">Date: {{ $sale->created_at->format('M d, Y') }}</div>
            <div class="meta-line">Operator: {{ $sale->user->name }}</div>
        </div>
    </div>

    <div class="client-info">
        <div class="section-title">Bill To</div>
        <div class="client-name">{{ $sale->customer->name ?? 'Walk-in Client' }}</div>
        <div class="client-detail">{{ $sale->customer->phone ?? 'POS Entry' }}</div>
        <div class="client-detail" style="margin-top: 15px;">
            Payment: {{ $sale->payment_method }} | Status: {{ strtoupper($sale->status) }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>
                    <div class="product-name">{{ $item->product->name }}</div>
                    <div class="product-sku">SKU: {{ $item->product->barcode }}</div>
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            Subtotal: <span style="float: right;">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->total_amount, 2) }}</span>
            <div class="clearfix"></div>
        </div>
        <div class="total-row">
            Tax (15%): <span style="float: right;">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->tax_amount, 2) }}</span>
            <div class="clearfix"></div>
        </div>
        <div class="grand-total">
            Total: <span style="float: right;">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->grand_total, 2) }}</span>
            <div class="clearfix"></div>
        </div>
        
        @if($sale->due_amount > 0)
        <div class="total-row" style="color: #ef4444; margin-top: 10px;">
            Due: <span style="float: right;">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->due_amount, 2) }}</span>
            <div class="clearfix"></div>
        </div>
        @endif
    </div>

    <div class="clearfix"></div>

    <div class="footer">
        Thank you for choosing {{ $settings['shop_name'] ?? 'Antigravity POS' }}.<br>
        Standard compliant digital receipt generated on {{ date('Y-m-d H:i:s') }}
    </div>
</body>
</html>
