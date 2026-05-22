<x-layouts.app :title="'Detail Aset'" :breadcrumbs="[['label' => 'Aset', 'url' => route('assets.index')], ['label' => $asset->asset_id, 'url' => '#']]">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('assets.index') }}" class="btn btn-secondary btn-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">{{ $asset->asset_name }}</h1>
                <p class="text-sm font-mono text-[var(--color-brand)] mt-0.5">{{ $asset->asset_id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($asset->qr_code_path)
            <a href="{{ route('assets.qr.download', $asset) }}" class="btn btn-secondary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download QR
            </a>
            <a href="{{ route('assets.qr.print', $asset) }}" target="_blank" class="btn btn-secondary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print QR
            </a>
            @endif
            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column (2/3) --}}
        <div class="lg:col-span-2 flex h-full flex-col gap-6">
            {{-- Main Info --}}
            <div class="card">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between mb-4">
                    <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider">Informasi Utama</h3>
                    <div class="flex flex-wrap gap-2 md:justify-end">
                        <span class="badge badge-{{ $asset->condition_color }}">Kondisi: {{ ucfirst($asset->condition) }}</span>
                        <span class="badge badge-{{ $asset->status_color }}">Status: {{ ucfirst($asset->status) }}</span>
                        <span class="badge badge-{{ $asset->age_color }}">Umur: {{ $asset->age }}</span>
                        @if($asset->warranty_until)
                            @if($asset->warranty_until->isFuture())
                            <span class="badge badge-green">Garansi Aktif</span>
                            @else
                            <span class="badge badge-red">Garansi Habis</span>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Kategori</p>
                        <p class="font-medium">{{ $asset->category->category_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Store</p>
                        <p class="font-medium">{{ $asset->store->store_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Merek</p>
                        <p class="font-medium">{{ $asset->brand ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Model</p>
                        <p class="font-medium">{{ $asset->model ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Serial Number</p>
                        <p class="font-medium font-mono text-sm">{{ $asset->serial_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Lokasi Detail</p>
                        <p class="font-medium">{{ $asset->location_detail ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Specs --}}
            @if($asset->specs)
            <div class="card">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-3">Spesifikasi</h3>
                <p class="text-sm whitespace-pre-wrap text-[var(--color-text-secondary)]">{{ $asset->specs }}</p>
            </div>
            @endif

            {{-- Purchase Info --}}
            <div class="card">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-4">Info Pembelian</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Harga Beli</p>
                        <p class="font-medium">{{ $asset->purchase_price ? 'Rp ' . number_format($asset->purchase_price, 0, ',', '.') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Tanggal Beli</p>
                        <p class="font-medium">{{ $asset->purchase_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Garansi Hingga</p>
                        <p class="font-medium">{{ $asset->warranty_until?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)]">Ditambahkan</p>
                        <p class="font-medium">{{ $asset->added_at?->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($asset->notes)
            <div class="card flex-1">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-3">Catatan</h3>
                <p class="text-sm whitespace-pre-wrap text-[var(--color-text-secondary)]">{{ $asset->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Right Column (1/3) --}}
        <div class="space-y-6">
            {{-- Penugasan Aset (Check-out/Check-in) --}}
            <div class="card bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl p-5">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-4">Penugasan Aset</h3>
                
                @if($asset->currentEmployee)
                    <div class="flex items-start gap-3 p-4 bg-[rgba(254,203,0,0.04)] border border-[rgba(254,203,0,0.12)] rounded-lg mb-4">
                        <div class="w-10 h-10 rounded-full bg-[var(--color-brand)] flex items-center justify-center text-[#111111] font-bold text-sm shrink-0">
                            {{ strtoupper(substr($asset->currentEmployee->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-white truncate">{{ $asset->currentEmployee->name }}</p>
                            <p class="text-xs text-[var(--color-brand)] font-mono mt-0.5">{{ $asset->currentEmployee->employee_code }}</p>
                            <p class="text-xs text-[var(--color-text-muted)] mt-1.5">Divisi: {{ $asset->currentEmployee->department ?? '-' }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="openModal('checkinModal')" class="btn btn-secondary w-full flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
                        </svg>
                        Kembalikan Aset (Check-in)
                    </button>
                @else
                    <div class="text-center py-5 bg-[rgba(255,255,255,0.01)] border border-[var(--color-dark-border)] rounded-lg mb-4">
                        <svg class="w-8 h-8 text-[var(--color-text-muted)] opacity-40 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5"/>
                        </svg>
                        <p class="text-sm text-[var(--color-text-muted)]">Aset di Gudang (Belum ditugaskan)</p>
                    </div>

                    @if($asset->status === 'active')
                        <button type="button" onclick="openModal('checkoutModal')" class="btn btn-primary w-full flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-sm font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tugaskan Aset (Check-out)
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary w-full opacity-50 cursor-not-allowed text-sm py-2.5 rounded-lg" disabled>
                            Tugaskan Aset (Check-out)
                        </button>
                        <p class="text-[0.7rem] text-[var(--color-danger)] text-center mt-2 font-medium">Hanya aset aktif yang dapat ditugaskan.</p>
                    @endif
                @endif
            </div>

            {{-- Media --}}
            <div class="card">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-3">Media Aset</h3>
                @if($asset->photo)
                <img src="{{ asset('storage/' . $asset->photo) }}" alt="{{ $asset->asset_name }}" class="w-full max-h-72 object-cover rounded-lg border border-[var(--color-dark-border)]" loading="lazy">
                @else
                <div class="w-full aspect-[4/3] rounded-lg bg-[var(--color-dark-bg)] border border-[var(--color-dark-border)] flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-[var(--color-text-muted)] opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    <p class="text-xs text-[var(--color-text-muted)] mt-2">Tidak ada foto</p>
                </div>
                @endif

                @if($asset->qr_code_path)
                <div class="mt-4 pt-4 border-t border-[var(--color-dark-border)]">
                    <div class="flex items-center gap-4 rounded-lg bg-[rgba(255,255,255,0.015)] border border-[var(--color-dark-border)] p-3">
                        <img src="{{ asset('storage/' . $asset->qr_code_path) }}" alt="QR Code {{ $asset->asset_id }}" class="w-24 h-24 bg-white p-1.5 rounded-md shrink-0" loading="lazy">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-white">QR Code</p>
                            <p class="text-xs font-mono text-[var(--color-brand)] mt-1 truncate">{{ $asset->asset_id }}</p>
                            <p class="text-xs text-[var(--color-text-muted)] mt-1.5">Scan untuk melihat data aset</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabs section at the bottom --}}
    <div class="mt-8 card p-0 overflow-hidden">
        {{-- Tabs Header --}}
        <div class="flex border-b border-[var(--color-dark-border)] bg-[rgba(255,255,255,0.01)]">
            <button onclick="switchTab('peminjaman')" id="tab-peminjaman" class="px-6 py-4 font-semibold text-sm border-b-2 border-[var(--color-brand)] text-white hover:text-white transition-colors focus:outline-none">
                Riwayat Peminjaman ({{ $asset->loans->count() }})
            </button>
            <button onclick="switchTab('maintenance')" id="tab-maintenance" class="px-6 py-4 font-semibold text-sm border-b-2 border-transparent text-[var(--color-text-muted)] hover:text-white transition-colors focus:outline-none">
                Riwayat Maintenance ({{ $asset->maintenances->count() }})
            </button>
            <button onclick="switchTab('aktivitas')" id="tab-aktivitas" class="px-6 py-4 font-semibold text-sm border-b-2 border-transparent text-[var(--color-text-muted)] hover:text-white transition-colors focus:outline-none">
                Log Aktivitas / Audit Trail ({{ $asset->activities->count() }})
            </button>
        </div>

        {{-- Tab Content: Peminjaman --}}
        <div id="content-peminjaman" class="p-6">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Admin Check-out</th>
                            <th>Admin Check-in</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asset->loans as $loan)
                        @php
                            $durationDays = $loan->loan_date ? (int) $loan->loan_date->diffInDays($loan->return_date ?? now()) : null;
                        @endphp
                        <tr>
                            <td>
                                @if($loan->employee)
                                    <div class="font-medium text-white">{{ $loan->employee->name }}</div>
                                    <div class="text-xs text-[var(--color-text-muted)] font-mono">{{ $loan->employee->employee_code }}</div>
                                @else
                                    <span class="text-[var(--color-text-muted)]">Karyawan Terhapus</span>
                                @endif
                            </td>
                            <td class="text-sm text-white">{{ $loan->loan_date ? $loan->loan_date->format('d M Y') : '-' }}</td>
                            <td class="text-sm text-white">
                                {{ $loan->return_date ? $loan->return_date->format('d M Y') : '-' }}
                            </td>
                            <td class="text-sm text-white whitespace-nowrap">
                                @if($durationDays !== null)
                                    {{ $loan->status === 'active' ? 'Berjalan ' : '' }}{{ $durationDays }} hari
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($loan->status === 'active')
                                    <span class="badge badge-yellow">Dipegang</span>
                                @else
                                    <span class="badge badge-green">Kembali</span>
                                @endif
                            </td>
                            <td class="text-sm text-[var(--color-text-secondary)]">{{ $loan->loanedBy->name ?? '-' }}</td>
                            <td class="text-sm text-[var(--color-text-secondary)]">{{ $loan->returnedBy->name ?? '-' }}</td>
                            <td class="text-sm text-[var(--color-text-secondary)] max-w-xs truncate" title="{{ $loan->notes }}">
                                {{ $loan->notes ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-[var(--color-text-muted)] text-sm">Belum ada riwayat peminjaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab Content: Aktivitas --}}
        <div id="content-aktivitas" class="p-6 hidden">
            <div class="space-y-6 relative before:absolute before:inset-0 before:left-4 before:w-0.5 before:bg-[var(--color-dark-border)]">
                @forelse($asset->activities as $activity)
                <div class="relative pl-10">
                    {{-- Icon / Bullet --}}
                    <div class="absolute left-1 top-1.5 w-6.5 h-6.5 rounded-full bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] flex items-center justify-center text-[var(--color-brand)] z-10">
                        @if($activity->action === 'created')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        @elseif($activity->action === 'deleted')
                            <svg class="w-3.5 h-3.5 text-[var(--color-danger)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        @elseif($activity->action === 'assigned')
                            <svg class="w-3.5 h-3.5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        @elseif($activity->action === 'returned')
                            <svg class="w-3.5 h-3.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                        @else
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[var(--color-text-muted)]">{{ $activity->created_at->format('d M Y, H:i') }}</span>
                            <span class="text-xs px-2 py-0.5 rounded bg-[var(--color-dark-border)] text-white font-mono uppercase">{{ $activity->action }}</span>
                        </div>
                        <p class="text-sm font-medium text-white mt-1">{{ $activity->description }}</p>
                        
                        @if($activity->properties && is_array($activity->properties) && count($activity->properties) > 0)
                            <div class="mt-2 text-xs bg-[rgba(255,255,255,0.015)] border border-[var(--color-dark-border)] rounded-md p-3 max-w-lg">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="border-b border-[var(--color-dark-border)] text-[var(--color-text-muted)]">
                                            <th class="pb-1 font-semibold">Atribut</th>
                                            @if(isset($activity->properties['old']))
                                            <th class="pb-1 font-semibold">Sebelum</th>
                                            @endif
                                            <th class="pb-1 font-semibold">Sesudah</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-[var(--color-text-secondary)]">
                                        @if(isset($activity->properties['old']) && isset($activity->properties['new']))
                                            @foreach($activity->properties['new'] as $key => $newVal)
                                                @php $oldVal = $activity->properties['old'][$key] ?? '-'; @endphp
                                                @if($oldVal !== $newVal)
                                                <tr>
                                                    <td class="py-1 font-mono text-[var(--color-brand)]">{{ $key }}</td>
                                                    <td class="py-1 line-through opacity-60">{{ is_string($oldVal) ? $oldVal : json_encode($oldVal) }}</td>
                                                    <td class="py-1 text-white font-semibold">{{ is_string($newVal) ? $newVal : json_encode($newVal) }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @elseif(isset($activity->properties['new']))
                                            @foreach($activity->properties['new'] as $key => $newVal)
                                                @if(!empty($newVal))
                                                <tr>
                                                    <td class="py-1 font-mono text-[var(--color-brand)]">{{ $key }}</td>
                                                    <td class="py-1 text-white font-semibold">{{ is_string($newVal) ? $newVal : json_encode($newVal) }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <p class="text-xs text-[var(--color-text-muted)] mt-1.5">Oleh: {{ $activity->user->name ?? 'System' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-[var(--color-text-muted)] text-sm pl-10">Belum ada log aktivitas.</div>
                @endforelse
            </div>
        </div>

        {{-- Tab Content: Maintenance --}}
        <div id="content-maintenance" class="p-6 hidden">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-base font-semibold text-white">Log Perbaikan / Pemeliharaan</h4>
                <button type="button" onclick="openModal('addMaintenanceModal')" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Log Maintenance
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Kendala / Kerusakan</th>
                            <th>Teknisi / Vendor</th>
                            <th>Biaya</th>
                            <th>Status</th>
                            <th>Solusi</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asset->maintenances as $maintenance)
                        <tr>
                            <td class="text-sm text-white">{{ $maintenance->start_date ? $maintenance->start_date->format('d M Y') : '-' }}</td>
                            <td class="text-sm text-white">{{ $maintenance->end_date ? $maintenance->end_date->format('d M Y') : '-' }}</td>
                            <td class="text-sm text-[var(--color-text-secondary)] max-w-xs truncate" title="{{ $maintenance->issue }}">
                                {{ $maintenance->issue }}
                            </td>
                            <td class="text-sm text-[var(--color-text-secondary)]">{{ $maintenance->performed_by ?? '-' }}</td>
                            <td class="text-sm text-white">
                                {{ $maintenance->cost ? 'Rp ' . number_format($maintenance->cost, 0, ',', '.') : '-' }}
                            </td>
                            <td>
                                @if($maintenance->status === 'scheduled')
                                    <span class="badge badge-blue">Scheduled</span>
                                @elseif($maintenance->status === 'in_progress')
                                    <span class="badge badge-orange">In Progress</span>
                                @elseif($maintenance->status === 'completed')
                                    <span class="badge badge-green">Completed</span>
                                @else
                                    <span class="badge badge-gray">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-sm text-[var(--color-text-secondary)] max-w-xs truncate" title="{{ $maintenance->solution }}">
                                {{ $maintenance->solution ?? '-' }}
                            </td>
                            <td class="text-right">
                                <div class="inline-flex gap-2 justify-end">
                                    <button type="button" onclick="openEditMaintenanceModal({{ json_encode($maintenance) }})" class="btn btn-secondary btn-sm btn-icon" title="Edit / Selesaikan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('assets.maintenances.destroy', $maintenance) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log maintenance ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-[var(--color-text-muted)] text-sm">Belum ada riwayat maintenance.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Checkout Modal --}}
    <div id="checkoutModal" class="modal-overlay hidden" style="z-index: 100;">
        <div class="modal-content max-w-md">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-[var(--color-dark-border)]">
                <h3 class="text-lg font-semibold text-white">Tugaskan Aset (Check-out)</h3>
                <button type="button" onclick="closeModal('checkoutModal')" class="text-[var(--color-text-muted)] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('assets.checkout', $asset) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="employee_id" class="form-label text-left">Pilih Karyawan / Staff <span class="text-[var(--color-danger)]">*</span></label>
                    <select id="employee_id" name="employee_id" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" required>
                        <option value="">-- Pilih Staff --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->employee_code }} - {{ $employee->name }} ({{ $employee->department ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="checkout_notes" class="form-label text-left">Catatan Penugasan / Kondisi Awal</label>
                    <textarea id="checkout_notes" name="notes" rows="3" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" placeholder="Tulis catatan serah terima aset..."></textarea>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeModal('checkoutModal')" class="btn btn-secondary py-2 px-4 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary py-2 px-4 rounded-lg">
                        Tugaskan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Checkin Modal --}}
    <div id="checkinModal" class="modal-overlay hidden" style="z-index: 100;">
        <div class="modal-content max-w-md">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-[var(--color-dark-border)]">
                <h3 class="text-lg font-semibold text-white">Kembalikan Aset (Check-in)</h3>
                <button type="button" onclick="closeModal('checkinModal')" class="text-[var(--color-text-muted)] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('assets.checkin', $asset) }}" method="POST">
                @csrf
                <p class="text-sm text-[var(--color-text-secondary)] mb-4 text-left">
                    Apakah Anda yakin ingin mengembalikan aset <strong>{{ $asset->asset_name }} ({{ $asset->asset_id }})</strong> dari karyawan <strong>{{ $asset->currentEmployee->name ?? '' }}</strong> ke gudang?
                </p>
                <div class="mb-5">
                    <label for="checkin_notes" class="form-label text-left font-medium">Catatan Pengembalian / Kondisi Akhir</label>
                    <textarea id="checkin_notes" name="notes" rows="3" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" placeholder="Tulis catatan pengembalian (opsional)..."></textarea>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeModal('checkinModal')" class="btn btn-secondary py-2 px-4 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary py-2 px-4 rounded-lg">
                        Kembalikan ke Gudang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Maintenance Modal --}}
    <div id="addMaintenanceModal" class="modal-overlay hidden" style="z-index: 100;">
        <div class="modal-content max-w-md">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-[var(--color-dark-border)]">
                <h3 class="text-lg font-semibold text-white">Tambah Log Maintenance</h3>
                <button type="button" onclick="closeModal('addMaintenanceModal')" class="text-[var(--color-text-muted)] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('assets.maintenances.store', $asset) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="issue" class="form-label text-left font-medium">Kendala / Kerusakan <span class="text-[var(--color-danger)]">*</span></label>
                    <textarea id="issue" name="issue" rows="3" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" placeholder="Tuliskan kendala atau jenis kerusakan aset..." required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_date" class="form-label text-left font-medium">Tanggal Mulai <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="date" id="start_date" name="start_date" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label for="status" class="form-label text-left font-medium">Status Awal <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="status" name="status" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" required>
                            <option value="in_progress">In Progress</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="performed_by" class="form-label text-left font-medium">Teknisi / Vendor</label>
                        <input type="text" id="performed_by" name="performed_by" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" placeholder="cth: IT Support / Lenovo Center">
                    </div>
                    <div>
                        <label for="cost" class="form-label text-left font-medium">Biaya Perkiraan (Rp)</label>
                        <input type="number" id="cost" name="cost" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" placeholder="cth: 500000">
                    </div>
                </div>
                <div class="mb-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="change_asset_status" value="true" checked class="rounded border-[var(--color-dark-border)] text-[var(--color-brand)] bg-[var(--color-dark-bg)] focus:ring-[var(--color-brand)] focus:ring-offset-[#111111]">
                        <span class="text-sm text-[var(--color-text-secondary)] select-none">Ubah status aset menjadi <strong>Maintenance</strong></span>
                    </label>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeModal('addMaintenanceModal')" class="btn btn-secondary py-2 px-4 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary py-2 px-4 rounded-lg">
                        Simpan Log
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit / Complete Maintenance Modal --}}
    <div id="editMaintenanceModal" class="modal-overlay hidden" style="z-index: 100;">
        <div class="modal-content max-w-md">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-[var(--color-dark-border)]">
                <h3 class="text-lg font-semibold text-white">Edit / Selesaikan Maintenance</h3>
                <button type="button" onclick="closeModal('editMaintenanceModal')" class="text-[var(--color-text-muted)] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editMaintenanceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_issue" class="form-label text-left font-medium">Kendala / Kerusakan <span class="text-[var(--color-danger)]">*</span></label>
                    <textarea id="edit_issue" name="issue" rows="3" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="edit_start_date" class="form-label text-left font-medium">Tanggal Mulai <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="date" id="edit_start_date" name="start_date" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" required>
                    </div>
                    <div>
                        <label for="edit_status" class="form-label text-left font-medium">Status <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="edit_status" name="status" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" required onchange="toggleEditSolutionField()">
                            <option value="in_progress">In Progress</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="edit_end_date" class="form-label text-left font-medium">Tanggal Selesai</label>
                        <input type="date" id="edit_end_date" name="end_date" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none">
                    </div>
                    <div>
                        <label for="edit_cost" class="form-label text-left font-medium">Biaya Perbaikan (Rp)</label>
                        <input type="number" id="edit_cost" name="cost" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="edit_performed_by" class="form-label text-left font-medium">Teknisi / Vendor</label>
                    <input type="text" id="edit_performed_by" name="performed_by" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none">
                </div>
                <div class="mb-4" id="edit_solution_container">
                    <label for="edit_solution" class="form-label text-left font-medium">Solusi Perbaikan</label>
                    <textarea id="edit_solution" name="solution" rows="3" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none" placeholder="Tuliskan tindakan/solusi perbaikan..."></textarea>
                </div>
                <div class="mb-5" id="restore_asset_status_container">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="restore_asset_status" value="true" checked class="rounded border-[var(--color-dark-border)] text-[var(--color-brand)] bg-[var(--color-dark-bg)] focus:ring-[var(--color-brand)] focus:ring-offset-[#111111]">
                        <span class="text-sm text-[var(--color-text-secondary)] select-none">Kembalikan status aset menjadi <strong>Active</strong></span>
                    </label>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeModal('editMaintenanceModal')" class="btn btn-secondary py-2 px-4 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary py-2 px-4 rounded-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Close on overlay click
        const modals = ['checkoutModal', 'checkinModal', 'addMaintenanceModal', 'editMaintenanceModal'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) closeModal(id);
                });
            }
        });

        // Tab Switching
        function switchTab(tab) {
            const tabs = ['peminjaman', 'maintenance', 'aktivitas'];
            
            tabs.forEach(t => {
                const tabEl = document.getElementById(`tab-${t}`);
                const contentEl = document.getElementById(`content-${t}`);
                if (!tabEl || !contentEl) return;
                
                if (t === tab) {
                    tabEl.classList.add('border-[var(--color-brand)]', 'text-white');
                    tabEl.classList.remove('border-transparent', 'text-[var(--color-text-muted)]');
                    contentEl.classList.remove('hidden');
                } else {
                    tabEl.classList.add('border-transparent', 'text-[var(--color-text-muted)]');
                    tabEl.classList.remove('border-[var(--color-brand)]', 'text-white');
                    contentEl.classList.add('hidden');
                }
            });
        }

        // Maintenance specific handlers
        function openEditMaintenanceModal(maintenance) {
            const modal = document.getElementById('editMaintenanceModal');
            const form = document.getElementById('editMaintenanceForm');
            
            // Set action URL
            form.action = `/assets/maintenances/${maintenance.id}`;
            
            // Fill fields
            document.getElementById('edit_issue').value = maintenance.issue;
            document.getElementById('edit_start_date').value = maintenance.start_date ? maintenance.start_date.substring(0, 10) : '';
            document.getElementById('edit_end_date').value = maintenance.end_date ? maintenance.end_date.substring(0, 10) : '';
            document.getElementById('edit_performed_by').value = maintenance.performed_by || '';
            document.getElementById('edit_cost').value = maintenance.cost ? parseFloat(maintenance.cost) : '';
            document.getElementById('edit_status').value = maintenance.status;
            document.getElementById('edit_solution').value = maintenance.solution || '';
            
            toggleEditSolutionField();
            openModal('editMaintenanceModal');
        }

        function toggleEditSolutionField() {
            const status = document.getElementById('edit_status').value;
            const restoreContainer = document.getElementById('restore_asset_status_container');
            const end_date = document.getElementById('edit_end_date');
            
            if (status === 'completed') {
                restoreContainer.classList.remove('hidden');
                // Auto fill end_date with today's date if empty
                if (!end_date.value) {
                    const today = new Date().toISOString().substring(0, 10);
                    end_date.value = today;
                }
            } else {
                restoreContainer.classList.add('hidden');
            }
        }
    </script>
    @endpush

</x-layouts.app>
