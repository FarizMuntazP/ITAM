<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stock Opname — {{ $store->store_name }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: #111;
            background-color: #fff;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .header-title h1 {
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title p {
            margin: 0;
            color: #555;
            font-size: 11px;
        }
        .header-meta {
            text-align: right;
            font-size: 11px;
            color: #555;
        }
        .store-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        .detail-item {
            margin-bottom: 5px;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
            display: inline-block;
            width: 100px;
        }
        .detail-value {
            font-weight: 700;
        }
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }
        .print-table th, .print-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        .print-table th {
            background-color: #f2f2f2;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
        }
        .print-table tr:nth-child(even) {
            background-color: #fafafa;
        }
        .font-mono {
            font-family: monospace;
            font-size: 11px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid #ccc;
        }
        .signature-section {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            text-align: center;
        }
        .signature-title {
            font-weight: 600;
            margin-bottom: 70px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 70%;
            margin: 0 auto 5px auto;
        }
        .signature-name {
            font-weight: 700;
            font-size: 11px;
        }
        .signature-role {
            font-size: 10px;
            color: #555;
        }
        .no-print-btn {
            background-color: #fecb00;
            color: #111;
            border: none;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .no-print-btn:hover {
            background-color: #d4a900;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .store-details {
                background-color: transparent !important;
                border: 1px solid #333;
                display: block;
                overflow: hidden;
            }
            .store-details > div {
                float: left;
                width: 50%;
            }
            .print-table th {
                background-color: #eaeaea !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="display: flex; gap: 10px;">
        <button class="no-print-btn" onclick="window.print()">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Laporan
        </button>
        <button class="no-print-btn" style="background-color: #eaeaea; color: #333; border: 1px solid #ccc;" onclick="window.close()">
            Tutup Halaman
        </button>
    </div>

    <div class="header">
        <div class="header-title">
            <h1>Laporan Stock Opname Aset IT</h1>
            <p>Sistem Manajemen Aset Terintegrasi (ITAM)</p>
        </div>
        <div class="header-meta">
            Tanggal Cetak: <strong>{{ now()->translatedFormat('d F Y H:i') }}</strong><br>
            Dicetak Oleh: <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong>
        </div>
    </div>

    <div class="store-details">
        <div>
            <div class="detail-item">
                <span class="detail-label">Kode Store</span>: <span class="detail-value font-mono">{{ $store->store_code }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Nama Store</span>: <span class="detail-value">{{ $store->store_name }}</span>
            </div>
        </div>
        <div>
            <div class="detail-item">
                <span class="detail-label">Lokasi / Alamat</span>: <span class="detail-value">{{ $store->location }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Region</span>: <span class="detail-value">{{ $store->region ?? '-' }}</span>
            </div>
        </div>
    </div>

    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th style="width: 90px;">Asset ID</th>
                <th>Nama Aset</th>
                <th>Merek / Model</th>
                <th>Kategori</th>
                <th style="width: 50px; text-align:center;">Qty</th>
                <th style="width: 80px;">Kondisi</th>
                <th style="width: 80px;">Status</th>
                <th>Lokasi Detail</th>
                <th style="width: 130px; text-align: center;">Checklist Fisik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $index => $asset)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td class="font-mono" style="font-weight: 700;">{{ $asset->asset_id }}</td>
                <td style="font-weight: 600;">{{ $asset->asset_name }}</td>
                <td>{{ $asset->brand ?? '-' }} {{ $asset->model ?? '' }}</td>
                <td>{{ $asset->category->category_name ?? '-' }}</td>
                <td style="text-align:center; font-weight:700;">
                    {{ $asset->asset_type === 'bulk' ? $asset->quantity : 1 }}
                </td>
                <td>
                    <span class="badge" style="border-color: 
                        {{ $asset->condition === 'good' ? '#22c55e' : ($asset->condition === 'fair' ? '#fecb00' : ($asset->condition === 'poor' ? '#f97316' : '#ef4444')) }};
                        color: 
                        {{ $asset->condition === 'good' ? '#15803d' : ($asset->condition === 'fair' ? '#a16207' : ($asset->condition === 'poor' ? '#c2410c' : '#b91c1c')) }}">
                        {{ $asset->condition }}
                    </span>
                </td>
                <td>
                    <span class="badge" style="border-color: 
                        {{ $asset->status === 'active' ? '#22c55e' : ($asset->status === 'maintenance' ? '#3b82f6' : '#666') }};
                        color: 
                        {{ $asset->status === 'active' ? '#15803d' : ($asset->status === 'maintenance' ? '#1d4ed8' : '#333') }}">
                        {{ $asset->status }}
                    </span>
                </td>
                <td>{{ $asset->location_detail ?? '-' }}</td>
                <td style="white-space: nowrap; font-size: 9px;">
                    <div style="display: inline-block; margin-right: 6px;"><div style="width: 10px; height: 10px; border: 1px solid #333; display: inline-block; margin-right: 3px; vertical-align: -1px;"></div>Ada</div>
                    <div style="display: inline-block; margin-right: 6px;"><div style="width: 10px; height: 10px; border: 1px solid #333; display: inline-block; margin-right: 3px; vertical-align: -1px;"></div>Tdk</div>
                    <div style="display: inline-block;"><div style="width: 10px; height: 10px; border: 1px solid #333; display: inline-block; margin-right: 3px; vertical-align: -1px;"></div>Rsk</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px; color: #666;">
                    Tidak ada aset yang terdaftar di store ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-grid">
            <div>
                <div class="signature-title">Dibuat Oleh,</div>
                <div class="signature-line"></div>
                <div class="signature-name">....................................</div>
                <div class="signature-role">Staf IT Cabang</div>
            </div>
            <div>
                <div class="signature-title">Diverifikasi Oleh,</div>
                <div class="signature-line"></div>
                <div class="signature-name">....................................</div>
                <div class="signature-role">Kepala Cabang / Store Manager</div>
            </div>
            <div>
                <div class="signature-title">Disetujui Oleh,</div>
                <div class="signature-line"></div>
                <div class="signature-name">....................................</div>
                <div class="signature-role">Auditor IT / IT Manager</div>
            </div>
        </div>
    </div>
</body>
</html>
