<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 40px;
            color: #334155;
            background: #ffffff;
            line-height: 1.5;
        }

        .invoice-container {
            width: 100%;
        }

        /* Top Header Section */
        header {
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 30px;
            margin-bottom: 40px;
        }

        .shop-info {
            float: left;
            width: 60%;
        }

        .shop-name {
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .shop-details {
            font-size: 11px;
            color: #64748b;
            font-weight: bold;
            line-height: 1.6;
        }

        .invoice-meta {
            float: right;
            width: 35%;
            text-align: right;
        }

        .doc-title {
            font-size: 10px;
            font-weight: 900;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .invoice-id {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }

        /* Grid for Client & Payment */
        .details-grid {
            margin-bottom: 50px;
            width: 100%;
        }

        .grid-col {
            float: left;
            width: 50%;
        }

        .label {
            font-size: 9px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .data-box {
            font-size: 12px;
            color: #1e293b;
        }

        .client-name {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 5px;
        }

        /* Items Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th {
            background: #f8fafc;
            text-align: left;
            padding: 12px 15px;
            font-size: 10px;
            font-weight: 900;
            color: #64748b;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
            color: #334155;
            vertical-align: top;
        }

        .prod-name {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .prod-meta {
            font-size: 10px;
            color: #94a3b8;
            font-weight: bold;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Summary Section */
        .summary-wrapper {
            float: right;
            width: 280px;
            margin-top: 20px;
        }

        .summary-row {
            padding: 8px 0;
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
        }

        .amount-col {
            float: right;
            color: #0f172a;
        }

        .grand-total {
            margin-top: 15px;
            padding: 15px 0;
            border-top: 2px solid #0f172a;
        }

        .total-label {
            font-size: 12px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
        }

        .total-val {
            font-size: 22px;
            font-weight: 900;
            color: #4f46e5;
            float: right;
        }

        .due-badge {
            background: #fff1f2;
            color: #e11d48;
            font-size: 10px;
            font-weight: 900;
            padding: 10px;
            border-radius: 8px;
            margin-top: 15px;
            text-align: right;
        }

        /* Footer */
        footer {
            margin-top: 100px;
            border-top: 1px solid #f1f5f9;
            padding-top: 25px;
            text-align: center;
        }

        .footer-text {
            font-size: 9px;
            font-weight: bold;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .clearfix { clear: both; }

        /* Badge for methods/Status */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            background: #f1f5f9;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <header>
            <div class="shop-info">
                <div class="shop-name">{{ $settings['shop_name'] ?? 'VIGOR TERMINAL' }}</div>
                <div class="shop-details">
                    {{ $settings['shop_address'] ?? 'Central Business District, Dhaka' }}<br>
                    Contact: {{ $settings['shop_phone'] ?? '+880 1234 567890' }}
                </div>
            </div>
            <div class="invoice-meta">
                <div class="doc-title">Official Receipt</div>
                <div class="invoice-id">#TXN-{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="data-box" style="font-size: 10px; font-weight: bold; color: #94a3b8;">
                    DATED: {{ $sale->created_at->format('d M, Y') }}<br>
                    TIME: {{ $sale->created_at->format('h:i A') }}
                </div>
            </div>
            <div class="clearfix"></div>
        </header>

        <div class="details-grid">
            <div class="grid-col">
                <div class="label">Recipient Identity</div>
                <div class="data-box">
                    <div class="client-name">{{ $sale->customer->name ?? 'Walk-in Client' }}</div>
                    @if($sale->customer && $sale->customer->phone)
                        TEL: {{ $sale->customer->phone }}<br>
                    @endif
                    @if($sale->customer && $sale->customer->address)
                        LOC: {{ $sale->customer->address }}
                    @endif
                </div>
            </div>
            <div class="grid-col" style="text-align: right;">
                <div class="label">Settlement Logic</div>
                <div class="data-box">
                    METHOD: <span class="badge">{{ $sale->payment_method }}</span><br>
                    OPERATOR: {{ $sale->user->name }}<br>
                    STATUS: <span class="badge" style="background: #ecfdf5; color: #059669;">{{ strtoupper($sale->status) }}</span>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="50%">Product Specification</th>
                    <th class="text-center">Volume</th>
                    <th class="text-right">Unit Rate</th>
                    <th class="text-right">Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>
                            <div class="prod-name">{{ $item->product->name }}</div>
                            <div class="prod-meta">Ref Node: {{ $item->product->barcode }}</div>
                        </td>
                        <td class="text-center" style="font-weight: 800;">{{ $item->quantity }}</td>
                        <td class="text-right">&#2547; {{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right" style="font-weight: 800;">&#2547; {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-wrapper">
            <div class="summary-row">
                NET REVENUE
                <span class="amount-col">&#2547; {{ number_format($sale->total_amount, 2) }}</span>
                <div class="clearfix"></div>
            </div>
            <div class="summary-row">
                TAX COMPONENT ({{ $settings['tax_rate'] ?? 15 }}%)
                <span class="amount-col">&#2547; {{ number_format($sale->tax_amount, 2) }}</span>
                <div class="clearfix"></div>
            </div>
            
            <div class="grand-total">
                <span class="total-label">AGGREGATE SUM</span>
                <span class="total-val">&#2547; {{ number_format($sale->grand_total, 2) }}</span>
                <div class="clearfix"></div>
            </div>

            @if($sale->due_amount > 0)
                <div class="due-badge">
                    PENDING BALANCE: &#2547; {{ number_format($sale->due_amount, 2) }}
                </div>
            @endif
        </div>
        <div class="clearfix"></div>

        <footer>
            <div class="footer-text">Verified Electronic Node • Standard Compliant Receipt</div>
            <div class="footer-text" style="color: #e2e8f0; margin-top: 10px;">Auth Code: {{ md5($sale->id . $sale->created_at) }}</div>
        </footer>
    </div>
</body>
</html>