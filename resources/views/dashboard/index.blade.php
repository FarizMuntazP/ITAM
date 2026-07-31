<x-layouts.app :title="'Dashboard'" :breadcrumbs="[['label' => 'Dashboard', 'url' => '#']]">

    <div class="animate-dashboard-fade">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Total Aset --}}
        <div class="relative bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-5 overflow-hidden hover:translate-y-[-2px] hover:shadow-lg hover:border-[var(--color-brand)] transition-all duration-300">
            <div class="absolute top-0 left-0 w-1 h-full bg-[var(--color-brand)]"></div>
            <div class="pl-4">
                <span class="inline-block px-1.5 py-0.5 text-[0.6rem] font-mono font-bold tracking-wider text-[var(--color-brand)] bg-[var(--color-brand)]/10 border border-[var(--color-brand)]/20 mb-2">TOTAL</span>
                <p class="text-xs text-[var(--color-text-muted)] mb-0.5">Aset</p>
                <p class="text-3xl font-bold text-[var(--color-brand)]">{{ number_format($totalAssets) }}</p>
            </div>
        </div>

        {{-- Total Store --}}
        <div class="relative bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-5 overflow-hidden hover:translate-y-[-2px] hover:shadow-lg hover:border-[var(--color-info)] transition-all duration-300">
            <div class="absolute top-0 left-0 w-1 h-full bg-[var(--color-info)]"></div>
            <div class="pl-4">
                <span class="inline-block px-1.5 py-0.5 text-[0.6rem] font-mono font-bold tracking-wider text-[var(--color-info)] bg-[var(--color-info)]/10 border border-[var(--color-info)]/20 mb-2">TOTAL</span>
                <p class="text-xs text-[var(--color-text-muted)] mb-0.5">Store</p>
                <p class="text-3xl font-bold text-[var(--color-info)]">{{ number_format($totalStores) }}</p>
            </div>
        </div>

        {{-- Total Kategori --}}
        <div class="relative bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-5 overflow-hidden hover:translate-y-[-2px] hover:shadow-lg hover:border-[var(--color-success)] transition-all duration-300">
            <div class="absolute top-0 left-0 w-1 h-full bg-[var(--color-success)]"></div>
            <div class="pl-4">
                <span class="inline-block px-1.5 py-0.5 text-[0.6rem] font-mono font-bold tracking-wider text-[var(--color-success)] bg-[var(--color-success)]/10 border border-[var(--color-success)]/20 mb-2">TOTAL</span>
                <p class="text-xs text-[var(--color-text-muted)] mb-0.5">Kategori</p>
                <p class="text-3xl font-bold text-[var(--color-success)]">{{ number_format($totalCategories) }}</p>
            </div>
        </div>

        {{-- Aset Rusak --}}
        <div class="relative bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-5 overflow-hidden hover:translate-y-[-2px] hover:shadow-lg hover:border-[var(--color-danger)] transition-all duration-300">
            <div class="absolute top-0 left-0 w-1 h-full bg-[var(--color-danger)]"></div>
            <div class="pl-4">
                <span class="inline-block px-1.5 py-0.5 text-[0.6rem] font-mono font-bold tracking-wider text-[var(--color-danger)] bg-[var(--color-danger)]/10 border border-[var(--color-danger)]/20 mb-2">TOTAL</span>
                <p class="text-xs text-[var(--color-text-muted)] mb-0.5">Aset Rusak</p>
                <p class="text-3xl font-bold text-[var(--color-danger)]">{{ number_format($damagedAssets) }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="flex flex-wrap items-center gap-3 mb-8">
        <span class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)] mr-1">Aksi Cepat</span>
        <a href="{{ route('assets.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[var(--color-brand)] bg-[var(--color-brand)]/5 border border-[var(--color-brand)]/20 hover:bg-[var(--color-brand)]/10 hover:border-[var(--color-brand)]/40 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Aset
        </a>
        <a href="{{ route('assets.import.form') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[var(--color-info)] bg-[var(--color-info)]/5 border border-[var(--color-info)]/20 hover:bg-[var(--color-info)]/10 hover:border-[var(--color-info)]/40 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
            Import Excel
        </a>
        <a href="{{ route('assets.export') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[var(--color-success)] bg-[var(--color-success)]/5 border border-[var(--color-success)]/20 hover:bg-[var(--color-success)]/10 hover:border-[var(--color-success)]/40 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Data
        </a>
        <a href="{{ route('assets.index') }}?per_page=50" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[var(--color-text-muted)] border border-[var(--color-dark-border)] hover:text-white hover:bg-white/5 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Lihat Semua Aset
        </a>
    </div>

    {{-- Storage Monitoring --}}
    <div class="bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-6 mb-8 hover:border-[var(--color-brand-glow)] transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-semibold uppercase tracking-wider text-[var(--color-text-secondary)]">Storage File</h2>
                <p class="text-xs text-[var(--color-text-muted)] mt-1">
                    {{ $storageStats['total_files'] }} file tersimpan di public storage
                </p>
            </div>
            <div class="text-left lg:text-right">
                <p class="text-3xl font-bold text-[var(--color-brand)]">{{ $storageStats['human_total'] }}</p>
                <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Total penggunaan</p>
            </div>
        </div>

        @php
            $photoBytes = $storageStats['groups']['photos']['bytes'] ?? 0;
            $thumbBytes = $storageStats['groups']['thumbnails']['bytes'] ?? 0;
            $qrBytes = $storageStats['groups']['qrcodes']['bytes'] ?? 0;
            $totalBytes = $photoBytes + $thumbBytes + $qrBytes;
            $photoPct = $totalBytes > 0 ? round($photoBytes / $totalBytes * 100) : 0;
            $thumbPct = $totalBytes > 0 ? round($thumbBytes / $totalBytes * 100) : 0;
            $qrPct = $totalBytes > 0 ? round($qrBytes / $totalBytes * 100) : 0;
        @endphp

        <div class="mt-5">
            <div class="w-full h-2 bg-[var(--color-dark-bg)] rounded-full overflow-hidden flex">
                <div class="h-full rounded-full transition-all" style="width: {{ $photoPct }}%; background: linear-gradient(90deg, rgba(254,203,0,0.6), rgba(254,203,0,0.9))"></div>
                <div class="h-full rounded-full transition-all" style="width: {{ $thumbPct }}%; background: linear-gradient(90deg, rgba(59,130,246,0.4), rgba(59,130,246,0.8))"></div>
                <div class="h-full rounded-full transition-all" style="width: {{ $qrPct }}%; background: linear-gradient(90deg, rgba(34,197,94,0.4), rgba(34,197,94,0.8))"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4">
                <div class="flex items-center gap-3 p-3 rounded-lg border border-[var(--color-dark-border)] bg-[var(--color-dark-bg)]">
                    <span class="w-2.5 h-2.5 rounded-full bg-[var(--color-brand)] opacity-80"></span>
                    <div>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Foto Aset</p>
                        <p class="text-sm font-semibold text-white">{{ $storageStats['groups']['photos']['human_size'] }}</p>
                        <p class="text-[0.6rem] text-[var(--color-text-muted)]">{{ $storageStats['groups']['photos']['files'] }} file ({{ $photoPct }}%)</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg border border-[var(--color-dark-border)] bg-[var(--color-dark-bg)]">
                    <span class="w-2.5 h-2.5 rounded-full bg-[var(--color-info)] opacity-80"></span>
                    <div>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Thumbnail</p>
                        <p class="text-sm font-semibold text-white">{{ $storageStats['groups']['thumbnails']['human_size'] }}</p>
                        <p class="text-[0.6rem] text-[var(--color-text-muted)]">{{ $storageStats['groups']['thumbnails']['files'] }} file ({{ $thumbPct }}%)</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg border border-[var(--color-dark-border)] bg-[var(--color-dark-bg)]">
                    <span class="w-2.5 h-2.5 rounded-full bg-[var(--color-success)] opacity-80"></span>
                    <div>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">QR Code</p>
                        <p class="text-sm font-semibold text-white">{{ $storageStats['groups']['qrcodes']['human_size'] }}</p>
                        <p class="text-[0.6rem] text-[var(--color-text-muted)]">{{ $storageStats['groups']['qrcodes']['files'] }} file ({{ $qrPct }}%)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Needed / Perhatian Khusus --}}
    @if($warrantyExpiringAssets->count() > 0 || $oldAssets->count() > 0 || $longMaintenanceAssets->count() > 0)
    <div class="mb-8">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-[var(--color-text-muted)] mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Perhatian Khusus
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            {{-- Warranty Expiring --}}
            <div class="bg-[var(--color-dark-card)] border border-yellow-500/30 rounded-xl overflow-hidden">
                <div class="bg-yellow-500/10 px-4 py-3 border-b border-yellow-500/20">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-yellow-500">Garansi Habis (&lt; 90 Hari)</h3>
                </div>
                <div class="p-3 max-h-60 overflow-y-auto">
                    @forelse($warrantyExpiringAssets as $asset)
                        @php
                            $days = (int) now()->startOfDay()->diffInDays($asset->warranty_until, false);
                            $daysText = $days < 0 ? 'Sudah habis' : ($days === 0 ? 'Hari ini' : $days . ' hari lagi');
                        @endphp
                        <a href="{{ route('assets.show', $asset) }}" class="block mb-2 last:mb-0 p-2 rounded bg-[var(--color-dark-bg)] border-l-2 border-yellow-500 hover:bg-yellow-500/5 transition-colors">
                            <div class="flex justify-between items-start">
                                <span class="text-[0.6rem] font-mono font-bold text-[var(--color-brand)]">{{ $asset->asset_id }}</span>
                                <span class="text-[0.55rem] bg-yellow-500/20 text-yellow-500 px-1.5 py-0.5">{{ $daysText }}</span>
                            </div>
                            <p class="text-xs font-medium text-white truncate mt-1">{{ $asset->asset_name }}</p>
                            <p class="text-[0.55rem] text-[var(--color-text-muted)] mt-0.5">Berakhir: {{ $asset->warranty_until->format('d M Y') }}</p>
                        </a>
                    @empty
                        <p class="text-xs text-[var(--color-text-muted)] text-center py-4">Tidak ada aset mendekati batas garansi.</p>
                    @endforelse
                </div>
            </div>

            {{-- Old Assets (> 4 Years) --}}
            <div class="bg-[var(--color-dark-card)] border border-orange-500/30 rounded-xl overflow-hidden">
                <div class="bg-orange-500/10 px-4 py-3 border-b border-orange-500/20">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-orange-500">Aset Usang (&gt; 4 Tahun)</h3>
                </div>
                <div class="p-3 max-h-60 overflow-y-auto">
                    @forelse($oldAssets as $asset)
                        @php
                            $baseDate = $asset->purchase_date ?? $asset->added_at;
                            $ageYears = $baseDate ? number_format(now()->diffInDays($baseDate, true) / 365.25, 1) : '?';
                        @endphp
                        <a href="{{ route('assets.show', $asset) }}" class="block mb-2 last:mb-0 p-2 rounded bg-[var(--color-dark-bg)] border-l-2 border-orange-500 hover:bg-orange-500/5 transition-colors">
                            <div class="flex justify-between items-start">
                                <span class="text-[0.6rem] font-mono font-bold text-[var(--color-brand)]">{{ $asset->asset_id }}</span>
                                <span class="text-[0.55rem] bg-orange-500/20 text-orange-500 px-1.5 py-0.5">{{ $ageYears }} Thn</span>
                            </div>
                            <p class="text-xs font-medium text-white truncate mt-1">{{ $asset->asset_name }}</p>
                            <p class="text-[0.55rem] text-[var(--color-text-muted)] mt-0.5">Sejak: {{ $baseDate ? $baseDate->format('d M Y') : '-' }}</p>
                        </a>
                    @empty
                        <p class="text-xs text-[var(--color-text-muted)] text-center py-4">Tidak ada aset berusia &gt; 4 tahun.</p>
                    @endforelse
                </div>
            </div>

            {{-- Long Maintenance --}}
            <div class="bg-[var(--color-dark-card)] border border-red-500/30 rounded-xl overflow-hidden">
                <div class="bg-red-500/10 px-4 py-3 border-b border-red-500/20">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-red-500">Maintenance &gt; 30 Hari</h3>
                </div>
                <div class="p-3 max-h-60 overflow-y-auto">
                    @forelse($longMaintenanceAssets as $asset)
                        @php
                            $maintDays = (int) now()->diffInDays($asset->updated_at, true);
                        @endphp
                        <a href="{{ route('assets.show', $asset) }}" class="block mb-2 last:mb-0 p-2 rounded bg-[var(--color-dark-bg)] border-l-2 border-red-500 hover:bg-red-500/5 transition-colors">
                            <div class="flex justify-between items-start">
                                <span class="text-[0.6rem] font-mono font-bold text-[var(--color-brand)]">{{ $asset->asset_id }}</span>
                                <span class="text-[0.55rem] bg-red-500/20 text-red-500 px-1.5 py-0.5">{{ $maintDays }} Hari</span>
                            </div>
                            <p class="text-xs font-medium text-white truncate mt-1">{{ $asset->asset_name }}</p>
                            <p class="text-[0.55rem] text-[var(--color-text-muted)] mt-0.5">Store: {{ $asset->store->store_name ?? '-' }}</p>
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
        <div class="bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-6 flex flex-col justify-between">
            <h3 class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)] mb-4">Kondisi Aset</h3>
            <div class="relative w-full h-[260px] flex items-center justify-center">
                <canvas id="conditionChart"></canvas>
            </div>
        </div>

        {{-- Status Aset --}}
        <div class="bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-6 flex flex-col justify-between">
            <h3 class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)] mb-4">Status Aset</h3>
            <div class="relative w-full h-[260px] flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        {{-- Top 5 Kategori --}}
        <div class="bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-6 flex flex-col justify-between">
            <h3 class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)] mb-4">Top 5 Kategori</h3>
            <div class="relative w-full h-[260px]">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        {{-- Top 5 Store --}}
        <div class="bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-6 flex flex-col justify-between">
            <h3 class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)] mb-4">Top 5 Store Terbanyak Aset</h3>
            <div class="relative w-full h-[260px]">
                <canvas id="storeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tables Section --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Latest Assets (2/3 width) --}}
        <div class="xl:col-span-2">
            <div class="bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold">
                        <span class="text-[var(--color-brand)]">10</span> Aset Terbaru
                    </h2>
                    <a href="{{ route('assets.index') }}" class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)] hover:text-[var(--color-brand)] transition-colors">
                        Lihat Semua →
                    </a>
                </div>

                <div class="overflow-x-auto -mx-6 -mb-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[var(--color-dark-border)]">
                                <th class="p-3 text-left text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Asset ID</th>
                                <th class="p-3 text-left text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Nama</th>
                                <th class="p-3 text-left text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Kategori</th>
                                <th class="p-3 text-left text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Store</th>
                                <th class="p-3 text-left text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Kondisi</th>
                                <th class="p-3 text-left text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestAssets as $asset)
                            <tr class="group border-b border-[var(--color-dark-border)] hover:bg-white/[0.015] transition-colors duration-150">
                                <td class="p-3">
                                    <span class="text-[0.6rem] font-mono font-bold text-[var(--color-brand)]">{{ $asset->asset_id }}</span>
                                </td>
                                <td class="p-3 font-medium text-white text-[0.8rem]">{{ Str::limit($asset->asset_name, 30) }}</td>
                                <td class="p-3 text-[var(--color-text-secondary)] text-[0.8rem]">{{ $asset->category->category_name ?? '-' }}</td>
                                <td class="p-3 text-[var(--color-text-secondary)] text-[0.8rem]">{{ $asset->store->store_name ?? '-' }}</td>
                                <td class="p-3">
                                    <span class="inline-block px-1.5 py-0.5 text-[0.6rem] font-semibold tracking-wider {{ $asset->condition_color === 'green' ? 'bg-[rgba(34,197,94,0.12)] text-[#22c55e] border border-[rgba(34,197,94,0.25)]' : ($asset->condition_color === 'yellow' ? 'bg-[rgba(254,203,0,0.12)] text-[#fecb00] border border-[rgba(254,203,0,0.25)]' : ($asset->condition_color === 'orange' ? 'bg-[rgba(249,115,22,0.12)] text-[#f97316] border border-[rgba(249,115,22,0.25)]' : 'bg-[rgba(239,68,68,0.12)] text-[#ef4444] border border-[rgba(239,68,68,0.25)]')) }}">{{ ucfirst($asset->condition) }}</span>
                                </td>
                                <td class="p-3">
                                    <span class="inline-block px-1.5 py-0.5 text-[0.6rem] font-semibold tracking-wider {{ $asset->status === 'active' ? 'bg-[rgba(34,197,94,0.12)] text-[#22c55e] border border-[rgba(34,197,94,0.25)]' : ($asset->status === 'disposed' ? 'bg-[rgba(239,68,68,0.12)] text-[#ef4444] border border-[rgba(239,68,68,0.25)]' : ($asset->status === 'maintenance' ? 'bg-[rgba(254,203,0,0.12)] text-[#fecb00] border border-[rgba(254,203,0,0.25)]' : 'bg-[rgba(160,160,160,0.12)] text-[#a0a0a0] border border-[rgba(160,160,160,0.25)]')) }}">{{ ucfirst($asset->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-[var(--color-text-muted)] opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-sm text-[var(--color-text-muted)]">Belum ada aset. <a href="{{ route('assets.create') }}" class="text-[var(--color-brand)] hover:underline">Tambah aset pertama</a></p>
                                    </div>
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
            <div class="bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold">
                        Kondisi <span class="text-[var(--color-danger)]">Buruk</span>
                    </h2>
                </div>

                <div class="space-y-3">
                    @forelse($worstAssets as $asset)
                    <a href="{{ route('assets.show', $asset) }}" class="block p-3 rounded-lg bg-[var(--color-dark-bg)] border border-[var(--color-dark-border)] hover:border-[var(--color-brand-glow)] transition-all">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-8 rounded-full {{ $asset->condition_color === 'red' ? 'bg-[var(--color-danger)]' : ($asset->condition_color === 'orange' ? 'bg-orange-500' : ($asset->condition_color === 'yellow' ? 'bg-yellow-500' : 'bg-green-500')) }}"></span>
                                <span class="text-[0.6rem] font-mono font-bold text-[var(--color-brand)]">{{ $asset->asset_id }}</span>
                            </div>
                            <span class="inline-block px-1.5 py-0.5 text-[0.55rem] font-semibold tracking-wider {{ $asset->condition_color === 'green' ? 'bg-[rgba(34,197,94,0.12)] text-[#22c55e] border border-[rgba(34,197,94,0.25)]' : ($asset->condition_color === 'yellow' ? 'bg-[rgba(254,203,0,0.12)] text-[#fecb00] border border-[rgba(254,203,0,0.25)]' : ($asset->condition_color === 'orange' ? 'bg-[rgba(249,115,22,0.12)] text-[#f97316] border border-[rgba(249,115,22,0.25)]' : 'bg-[rgba(239,68,68,0.12)] text-[#ef4444] border border-[rgba(239,68,68,0.25)]')) }}">{{ ucfirst($asset->condition) }}</span>
                        </div>
                        <p class="text-sm font-medium text-white truncate pl-6">{{ $asset->asset_name }}</p>
                        <p class="text-xs text-[var(--color-text-muted)] mt-1 pl-6">{{ $asset->store->store_name ?? '-' }}</p>
                    </a>
                    @empty
                    <div class="text-center py-8 text-[var(--color-text-muted)]">
                        <svg class="w-10 h-10 mx-auto mb-2 text-[var(--color-success)] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm">Semua aset dalam kondisi baik!</p>
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
        Chart.defaults.color = '#a0a0a0';
        Chart.defaults.font.family = "'Inter', ui-sans-serif, system-ui, sans-serif";
        Chart.defaults.borderColor = '#2e2e2e';

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

        const categoryData = {!! json_encode($topCategories) !!};
        const storeData = {!! json_encode($topStores) !!};

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
                    legend: { display: false },
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
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
                }
            }
        });

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
                    legend: { display: false },
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
                    x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
                    y: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endpush

</div>

<style>
@keyframes dashboard-fade {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.animate-dashboard-fade {
    animation: dashboard-fade 0.5s ease-out;
}
</style>

</x-layouts.app>
