<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak QR Massal</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; background: #f0f0f0; }
        .page {
            width: 210mm; /* A4 width */
            padding: 10mm;
            margin: 10mm auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .container { 
            text-align: center; 
            border: 1px dashed #ccc;
            padding: 10px;
            page-break-inside: avoid;
        }
        .qr-img { width: 120px; height: 120px; margin: 5px auto; }
        .asset-id { font-size: 14px; font-weight: bold; margin-bottom: 3px; }
        .asset-name { font-size: 11px; color: #333; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; display: block; }
        .asset-info { font-size: 9px; color: #666; line-height: 1.2; }
        .divider { border-top: 1px dashed #eee; margin: 5px 0; }
        
        .no-print { text-align: center; margin: 20px 0; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin: 0 5px; }
        .btn-print { background: #fecb00; color: #111; }
        .btn-close { background: #e0e0e0; color: #333; }

        @media print {
            body { margin: 0; padding: 0; background: none; }
            .page { margin: 0; box-shadow: none; padding: 0; width: auto; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Cetak {{ count($assets) }} QR Code
        </button>
        <button onclick="window.close()" class="btn btn-close">
            ✕ Tutup
        </button>
    </div>

    <div class="page">
        <div class="grid">
            @foreach($assets as $asset)
            <div class="container">
                @if($asset->qr_code_path)
                <img src="{{ asset('storage/' . $asset->qr_code_path) }}" alt="QR Code" class="qr-img">
                @else
                <div style="width: 120px; height: 120px; border: 2px dashed #eee; margin: 5px auto; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #999; font-size: 9px;">N/A</span>
                </div>
                @endif
        
                <div class="asset-id">{{ $asset->asset_id }}</div>
                <div class="asset-name" title="{{ $asset->asset_name }}">{{ $asset->asset_name }}</div>
                <div class="divider"></div>
                <div class="asset-info">
                    <div>{{ $asset->category->category_name ?? '-' }} | {{ $asset->brand ?? '-' }}</div>
                    <div>{{ $asset->store->store_name ?? '-' }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
