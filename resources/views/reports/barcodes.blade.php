<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Product Barcode Labels</title>
    <style>
        @page { margin: 0; }
        /* Reduced body padding to allow labels to span wider */
        body { 
            font-family: 'Helvetica', sans-serif; 
            margin: 0; 
            padding: 0.5cm; 
            background: #fff; 
        }

        .label-container {
            width: 100%;
        }

        .label {
            /* 31.5% fits 3 columns comfortably on A4 */
            width: 30.5%; 
            display: inline-block;
            border: 0.1mm solid #e2e8f0;
            margin: 0.1cm 0.2cm;
            /* REMOVED PADDING */
            padding: 0 !important; 
            vertical-align: top;
            text-align: center;
            box-sizing: border-box;
            overflow: hidden;
        }

        .name { 
            font-size: 8pt; 
            font-weight: bold; 
            color: #1e293b;
            /* Added small margin instead of padding to keep text off edges */
            margin: 1px;
            
            line-height: 12pt;
            overflow: hidden;
        }

        .barcode { 
            margin: 0; 
            padding: 0;
            width: 100%;
        }

        .barcode img { 
            width: 90%; /* Scale slightly so it doesn't touch side borders */
            height: 30px; 
            display: block;
            margin: 0 auto;
        }

        .code { 
            font-size: 7pt; 
            font-family: monospace; 
            letter-spacing: 1px; 
            color: #64748b;
            margin-bottom: 2px;
        }

        .price { 
            font-size: 11pt; 
            font-weight: 900; 
            color: #fff; 
            background: #000; /* Dark background looks great with no padding */
            width: 90%; /* Scale slightly so it doesn't touch side borders */
            height: 20px; 
            display: block;
            margin: 5px  auto;
            
        }

        .page-break { page-break-after: always; clear: both; }
    </style>
</head>
<body>
    <div class="label-container">
        @foreach($barcodeData as $data)
            <div class="label">
                <div class="name">{{ $data['name'] }}</div>
                
                <div class="barcode">
                    <img src="data:image/png;base64,{{ $data['image'] }}">
                </div>
                
                <div class="code">{{ $data['barcode'] }}</div>
                
                <div class="price">
                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($data['price'], 2) }}
                </div>
            </div>

            {{-- 3 columns * 8 rows = 24 labels per page --}}
            @if($loop->iteration % 24 == 0)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
</body>
</html>