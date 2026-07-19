<x-layouts.app :title="'Dashboard'" :breadcrumbs="[['label' => 'Dashboard', 'url' => '#']]">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Total Aset --}}
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-muted)] mb-1">Total Aset</p>
                    <p class="text-3xl font-bold text-[var(--color-brand)]">{{ number_format($totalAssets) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[var(--color-brand-light)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-brand)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Store --}}
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-muted)] mb-1">Total Store</p>
                    <p class="text-3xl font-bold text-[var(--color-info)]">{{ number_format($totalStores) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[rgba(59,130,246,0.15)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-info)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Kategori --}}
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-muted)] mb-1">Total Kategori</p>
                    <p class="text-3xl font-bold text-[var(--color-success)]">{{ number_format($totalCategories) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[rgba(34,197,94,0.15)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Aset Damaged --}}
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-muted)] mb-1">Aset Rusak</p>
                    <p class="text-3xl font-bold text-[var(--color-danger)]">{{ number_format($damagedAssets) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[rgba(239,68,68,0.15)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-danger)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Storage Monitoring --}}
    <div class="card mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold">Storage File</h2>
                <p class="text-sm text-[var(--color-text-muted)] mt-1">
                    {{ $storageStats['total_files'] }} file tersimpan di public storage
                </p>
            </div>
            <div class="text-left lg:text-right">
                <p class="text-3xl font-bold text-[var(--color-brand)]">{{ $storageStats['human_total'] }}</p>
                <p class="text-xs text-[var(--color-text-muted)]">Total penggunaan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-5">
            <div class="rounded-lg border border-[var(--color-dark-border)] bg-[var(--color-dark-bg)] p-4">
                <p class="text-xs text-[var(--color-text-muted)] mb-1">Foto Aset</p>
                <p class="font-semibold text-white">{{ $storageStats['groups']['photos']['human_size'] }}</p>
                <p class="text-xs text-[var(--color-text-muted)] mt-1">{{ $storageStats['groups']['photos']['files'] }} file</p>
            </div>
            <div class="rounded-lg border border-[var(--color-dark-border)] bg-[var(--color-dark-bg)] p-4">
                <p class="text-xs text-[var(--color-text-muted)] mb-1">Thumbnail</p>
                <p class="font-semibold text-white">{{ $storageStats['groups']['thumbnails']['human_size'] }}</p>
                <p class="text-xs text-[var(--color-text-muted)] mt-1">{{ $storageStats['groups']['thumbnails']['files'] }} file</p>
            </div>
            <div class="rounded-lg border border-[var(--color-dark-border)] bg-[var(--color-dark-bg)] p-4">
                <p class="text-xs text-[var(--color-text-muted)] mb-1">QR Code</p>
                <p class="font-semibold text-white">{{ $storageStats['groups']['qrcodes']['human_size'] }}</p>
                <p class="text-xs text-[var(--color-text-muted)] mt-1">{{ $storageStats['groups']['qrcodes']['files'] }} file</p>
            </div>
        </div>
    </div>

    {{-- Action Needed / Perhatian Khusus --}}
    @if($warrantyExpiringAssets->count() > 0 || $oldAssets->count() > 0 || $longMaintenanceAssets->count() > 0)
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4 text-white flex items-center gap-2">
            <span class="text-yellow-400">⚠️</span> Perhatian Khusus (Action Needed)
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            {{-- Warranty Expiring --}}
            <div class="card p-0 overflow-hidden border border-yellow-500/30">
                <div class="bg-yellow-500/10 p-3 border-b border-yellow-500/20">
                    <h3 class="text-sm font-semibold text-yellow-500">Garansi Habis (< 90 Hari)</h3>
                </div>
                <div class="p-3 max-h-60 overflow-y-auto">
                    @forelse($warrantyExpiringAssets as $asset)
                        @php
                            $days = now()->diffInDays($asset->warranty_until, false);
                            $daysText = $days < 0 ? 'Sudah habis' : ($days == 0 ? 'Hari ini' : $days . ' hari lagi');
                        @endphp
                        <a href="{{ route('assets.show', $asset) }}" class="block mb-2 last:mb-0 p-2 rounded bg-[var(--color-dark-bg)] hover:bg-yellow-500/10 transition-colors">
                            <div class="flex justify-between items-start">
                                <span class="text-xs font-mono text-[var(--color-brand)]">{{ $asset->asset_id }}</span>
                                <span class="text-[10px] bg-yellow-500/20 text-yellow-500 px-1.5 py-0.5 rounded">{{ $daysText }}</span>
                            </div>
                            <p class="text-xs font-medium text-white truncate mt-1">{{ $asset->asset_name }}</p>
                            <p class="text-[10px] text-[var(--color-text-muted)] mt-0.5">Berakhir: {{ $asset->warranty_until->format('d M Y') }}</p>
                        </a>
                    @empty
                        <p class="text-xs text-[var(--color-text-muted)] text-center py-4">Tidak ada aset mendekati batas garansi.</p>
                    @endforelse
                </div>
            </div>

            {{-- Old Assets (> 4 Years) --}}
            <div class="card p-0 overflow-hidden border border-orange-500/30">
                <div class="bg-orange-500/10 p-3 border-b border-orange-500/20">
                    <h3 class="text-sm font-semibold text-orange-500">Aset Usang (> 4 Tahun)</h3>
                </div>
                <div class="p-3 max-h-60 overflow-y-auto">
                    @forelse($oldAssets as $asset)
                        @php
                            $baseDate = $asset->purchase_date ?? $asset->added_at;
                            $ageYears = $baseDate ? number_format(now()->diffInDays($baseDate) / 365.25, 1) : '?';
                        @endphp
                        <a href="{{ route('assets.show', $asset) }}" class="block mb-2 last:mb-0 p-2 rounded bg-[var(--color-dark-bg)] hover:bg-orange-500/10 transition-colors">
                            <div class="flex justify-between items-start">
                                <span class="text-xs font-mono text-[var(--color-brand)]">{{ $asset->asset_id }}</span>
                                <span class="text-[10px] bg-orange-500/20 text-orange-500 px-1.5 py-0.5 rounded">{{ $ageYears }} Thn</span>
                            </div>
                            <p class="text-xs font-medium text-white truncate mt-1">{{ $asset->asset_name }}</p>
                            <p class="text-[10px] text-[var(--color-text-muted)] mt-0.5">Sejak: {{ $baseDate ? $baseDate->format('d M Y') : '-' }}</p>
                        </a>
                    @empty
                        <p class="text-xs text-[var(--color-text-muted)] text-center py-4">Tidak ada aset berusia > 4 tahun.</p>
                    @endforelse
                </div>
            </div>

            {{-- Long Maintenance --}}
            <div class="card p-0 overflow-hidden border border-red-500/30">
                <div class="bg-red-500/10 p-3 border-b border-red-500/20">
                    <h3 class="text-sm font-semibold text-red-500">Maintenance > 30 Hari</h3>
                </div>
                <div class="p-3 max-h-60 overflow-y-auto">
                    @forelse($longMaintenanceAssets as $asset)
                        @php
                            $maintDays = now()->diffInDays($asset->updated_at);
                        @endphp
                        <a href="{{ route('assets.show', $asset) }}" class="block mb-2 last:mb-0 p-2 rounded bg-[var(--color-dark-bg)] hover:bg-red-500/10 transition-colors">
                            <div class="flex justify-between items-start">
                                <span class="text-xs font-mono text-[var(--color-brand)]">{{ $asset->asset_id }}</span>
                                <span class="text-[10px] bg-red-500/20 text-red-500 px-1.5 py-0.5 rounded">{{ $maintDays }} Hari</span>
                            </div>
                            <p class="text-xs font-medium text-white truncate mt-1">{{ $asset->asset_name }}</p>
                            <p class="text-[10px] text-[var(--color-text-muted)] mt-0.5">Store: {{ $asset->store->store_name ?? '-' }}</p>
                        </a>
                    @empty
                        <p class="text-xs text-[var(--color-text-muted)] text-center py-4">Tidak ada aset menunggak maintenance.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
    @endif

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Kondisi Aset --}}
        <div class="card flex flex-col justify-between">
            <h3 class="text-sm font-semibold mb-4 text-[var(--color-text-secondary)]">Kondisi Aset</h3>
            <div class="relative w-full h-[260px] flex items-center justify-center">
                <canvas id="conditionChart"></canvas>
            </div>
        </div>

        {{-- Status Aset --}}
        <div class="card flex flex-col justify-between">
            <h3 class="text-sm font-semibold mb-4 text-[var(--color-text-secondary)]">Status Aset</h3>
            <div class="relative w-full h-[260px] flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        {{-- Top 5 Kategori --}}
        <div class="card flex flex-col justify-between">
            <h3 class="text-sm font-semibold mb-4 text-[var(--color-text-secondary)]">Top 5 Kategori</h3>
            <div class="relative w-full h-[260px]">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        {{-- Top 5 Store --}}
        <div class="card flex flex-col justify-between">
            <h3 class="text-sm font-semibold mb-4 text-[var(--color-text-secondary)]">Top 5 Store Terbanyak Aset</h3>
            <div class="relative w-full h-[260px]">
                <canvas id="storeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tables Section --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Latest Assets (2/3 width) --}}
        <div class="xl:col-span-2">
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">
                        <span class="text-[var(--color-brand)]">10</span> Aset Terbaru
                    </h2>
                    <a href="{{ route('assets.index') }}" class="text-sm text-[var(--color-text-muted)] hover:text-[var(--color-brand)] transition-colors">
                        Lihat Semua →
                    </a>
                </div>

                <div class="overflow-x-auto -mx-6 -mb-6">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Asset ID</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Store</th>
                                <th>Kondisi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestAssets as $asset)
                            <tr>
                                <td class="font-mono text-xs text-[var(--color-brand)]">{{ $asset->asset_id }}</td>
                                <td class="font-medium">{{ Str::limit($asset->asset_name, 30) }}</td>
                                <td class="text-[var(--color-text-secondary)]">{{ $asset->category->category_name ?? '-' }}</td>
                                <td class="text-[var(--color-text-secondary)]">{{ $asset->store->store_name ?? '-' }}</td>
                                <td><span class="badge badge-{{ $asset->condition_color }}">{{ ucfirst($asset->condition) }}</span></td>
                                <td><span class="badge badge-{{ $asset->status_color }}">{{ ucfirst($asset->status) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-[var(--color-text-muted)]">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-sm">Belum ada aset. <a href="{{ route('assets.create') }}" class="text-[var(--color-brand)] hover:underline">Tambah aset pertama</a></p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Worst Condition (1/3 width) --}}
        <div>
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">
                        Kondisi <span class="text-[var(--color-danger)]">Buruk</span>
                    </h2>
                </div>

                <div class="space-y-3">
                    @forelse($worstAssets as $asset)
                    <a href="{{ route('assets.show', $asset) }}" class="block p-3 rounded-lg bg-[var(--color-dark-bg)] border border-[var(--color-dark-border)] hover:border-[var(--color-brand-glow)] transition-all">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-mono text-xs text-[var(--color-brand)]">{{ $asset->asset_id }}</span>
                            <span class="badge badge-{{ $asset->condition_color }}">{{ ucfirst($asset->condition) }}</span>
                        </div>
                        <p class="text-sm font-medium truncate">{{ $asset->asset_name }}</p>
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">{{ $asset->store->store_name ?? '-' }}</p>
                    </a>
                    @empty
                    <div class="text-center py-8 text-[var(--color-text-muted)]">
                        <svg class="w-10 h-10 mx-auto mb-2 text-[var(--color-success)] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm">Semua aset dalam kondisi baik! 👍</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Global configuration for dark mode
        Chart.defaults.color = '#a0a0a0'; // var(--color-text-secondary)
        Chart.defaults.font.family = "'Inter', ui-sans-serif, system-ui, sans-serif";
        Chart.defaults.borderColor = '#2e2e2e'; // var(--color-dark-border)

        // 1. Condition Chart (Doughnut)
        const conditionCtx = document.getElementById('conditionChart').getContext('2d');
        new Chart(conditionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Baik', 'Cukup', 'Kurang', 'Rusak'],
                datasets: [{
                    data: [
                        {{ $conditionsData['good'] }},
                        {{ $conditionsData['fair'] }},
                        {{ $conditionsData['poor'] }},
                        {{ $conditionsData['damaged'] }}
                    ],
                    backgroundColor: ['#22c55e', '#fecb00', '#f97316', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#1e1e1e' // var(--color-dark-card)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: '#1e1e1e',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#2e2e2e',
                        borderWidth: 1
                    }
                },
                cutout: '70%'
            },
            plugins: [{
                id: 'centerText',
                beforeDraw(chart) {
                    const { ctx, data } = chart;
                    ctx.save();
                    const datasetMeta = chart.getDatasetMeta(0);
                    if (datasetMeta && datasetMeta.data && datasetMeta.data.length > 0) {
                        const x = datasetMeta.data[0].x;
                        const y = datasetMeta.data[0].y;
                        let total = 0;
                        for (let i = 0; i < data.datasets[0].data.length; i++) {
                            if (chart.getDataVisibility(i)) {
                                total += data.datasets[0].data[i];
                            }
                        }
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillStyle = '#ffffff';
                        ctx.font = 'bold 24px Inter';
                        ctx.fillText(total.toLocaleString('id-ID'), x, y - 8);
                        ctx.fillStyle = '#a0a0a0';
                        ctx.font = '500 12px Inter';
                        ctx.fillText('Aset', x, y + 14);
                    }
                    ctx.restore();
                }
            }]
        });

        // 2. Status Chart (Doughnut)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Tidak Aktif', 'Pemeliharaan', 'Dihapuskan'],
                datasets: [{
                    data: [
                        {{ $statusesData['active'] }},
                        {{ $statusesData['inactive'] }},
                        {{ $statusesData['maintenance'] }},
                        {{ $statusesData['disposed'] }}
                    ],
                    backgroundColor: ['#22c55e', '#666666', '#fecb00', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#1e1e1e'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: '#1e1e1e',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#2e2e2e',
                        borderWidth: 1
                    }
                },
                cutout: '70%'
            },
            plugins: [{
                id: 'centerText',
                beforeDraw(chart) {
                    const { ctx, data } = chart;
                    ctx.save();
                    const datasetMeta = chart.getDatasetMeta(0);
                    if (datasetMeta && datasetMeta.data && datasetMeta.data.length > 0) {
                        const x = datasetMeta.data[0].x;
                        const y = datasetMeta.data[0].y;
                        let total = 0;
                        for (let i = 0; i < data.datasets[0].data.length; i++) {
                            if (chart.getDataVisibility(i)) {
                                total += data.datasets[0].data[i];
                            }
                        }
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillStyle = '#ffffff';
                        ctx.font = 'bold 24px Inter';
                        ctx.fillText(total.toLocaleString('id-ID'), x, y - 8);
                        ctx.fillStyle = '#a0a0a0';
                        ctx.font = '500 12px Inter';
                        ctx.fillText('Aset', x, y + 14);
                    }
                    ctx.restore();
                }
            }]
        });


        // Helper to check if data exists
        const categoryData = {!! json_encode($topCategories) !!};
        const storeData = {!! json_encode($topStores) !!};

        // 3. Category Chart (Bar)
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: categoryData.map(item => item.name),
                datasets: [{
                    label: 'Jumlah Aset',
                    data: categoryData.map(item => item.count),
                    backgroundColor: 'rgba(254, 203, 0, 0.85)',
                    hoverBackgroundColor: '#fecb00',
                    borderRadius: 6,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: '#1e1e1e',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#2e2e2e',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // 4. Store Chart (Horizontal Bar)
        const storeCtx = document.getElementById('storeChart').getContext('2d');
        new Chart(storeCtx, {
            type: 'bar',
            data: {
                labels: storeData.map(item => item.name),
                datasets: [{
                    label: 'Jumlah Aset',
                    data: storeData.map(item => item.count),
                    backgroundColor: 'rgba(59, 130, 246, 0.85)',
                    hoverBackgroundColor: '#3b82f6',
                    borderRadius: 6,
                    borderWidth: 0
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: '#1e1e1e',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#2e2e2e',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

</x-layouts.app>
