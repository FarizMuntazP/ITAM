<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print QR — {{ $asset->asset_id }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; padding: 20mm; }
        .container { text-align: center; max-width: 300px; margin: 0 auto; }
        .qr-img { width: 200px; height: 200px; margin: 10px auto; }
        .asset-id { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .asset-name { font-size: 14px; color: #333; margin-bottom: 3px; }
        .asset-info { font-size: 11px; color: #666; }
        .divider { border-top: 1px dashed #ccc; margin: 10px 0; }
        @media print {
            body { padding: 5mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #fecb00; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🖨️ Print
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #eee; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Tutup
        </button>
    </div>

    <div class="container">
        @if($asset->qr_code_path)
        <img src="{{ asset('storage/' . $asset->qr_code_path) }}" alt="QR Code" class="qr-img">
        @else
        <div style="width: 200px; height: 200px; border: 2px dashed #ccc; margin: 10px auto; display: flex; align-items: center; justify-content: center;">
            <span style="color: #999;">QR tidak tersedia</span>
        </div>
        @endif

        <div class="asset-id">{{ $asset->asset_id }}</div>
        <div class="asset-name">{{ $asset->asset_name }}</div>
        <div class="divider"></div>
        <div class="asset-info">
            <p>{{ $asset->category->category_name ?? '-' }} | {{ $asset->brand ?? '-' }} {{ $asset->model ?? '' }}</p>
            <p>{{ $asset->store->store_name ?? '-' }}</p>
            <p>S/N: {{ $asset->serial_number ?? '-' }}</p>
        </div>
    </div>
</body>
</html>
