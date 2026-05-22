<x-layouts.app :title="'Edit Store — ' . $store->store_name" :breadcrumbs="[
    ['label' => 'Store', 'url' => route('stores.index')],
    ['label' => 'Edit: ' . $store->store_code, 'url' => '#'],
]">

    <div class="max-w-2xl mb-8">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('stores.index') }}" class="btn btn-secondary btn-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Edit Store</h1>
                <p class="text-sm text-[var(--color-text-muted)] mt-0.5">{{ $store->store_code }} — {{ $store->store_name }}</p>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('stores.update', $store) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="store_code" class="form-label">Kode Store <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="store_code" name="store_code" value="{{ old('store_code', $store->store_code) }}" class="form-input" required>
                        @error('store_code') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="store_name" class="form-label">Nama Store <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="store_name" name="store_name" value="{{ old('store_name', $store->store_name) }}" class="form-input" required>
                        @error('store_name') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="location" class="form-label">Lokasi / Alamat <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location', $store->location) }}" class="form-input" required>
                        @error('location') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="region" class="form-label">Region / Wilayah</label>
                        <input type="text" id="region" name="region" value="{{ old('region', $store->region) }}" class="form-input">
                        @error('region') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Store
                    </button>
                    <a href="{{ route('stores.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Asset Checker Section --}}
    <div class="border-t border-[var(--color-dark-border)] pt-8 mt-8" id="assets-list">
        <div class="mb-6">
            <h2 class="text-xl font-bold mb-1">Aset di Store Ini</h2>
            <p class="text-sm text-[var(--color-text-muted)]">Cek, cari, dan filter aset yang terdaftar di store ini.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            {{-- Total Aset --}}
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)] mb-1">Total Aset</p>
                        <p class="text-2xl font-bold text-[var(--color-brand)]">{{ $totalAssets }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-lg bg-[var(--color-brand-light)] flex items-center justify-center">
                        <svg class="w-5 h-5 text-[var(--color-brand)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Kondisi: Good --}}
            <div class="stat-card cursor-pointer hover:border-[var(--color-success)] transition-colors" onclick="filterCondition('good')">
                <p class="text-xs text-[var(--color-text-muted)] mb-1">Good</p>
                <p class="text-2xl font-bold text-[var(--color-success)]">{{ $conditionStats['good'] }}</p>
            </div>

            {{-- Kondisi: Fair --}}
            <div class="stat-card cursor-pointer hover:border-[var(--color-warning)] transition-colors" onclick="filterCondition('fair')">
                <p class="text-xs text-[var(--color-text-muted)] mb-1">Fair</p>
                <p class="text-2xl font-bold text-[var(--color-warning)]">{{ $conditionStats['fair'] }}</p>
            </div>

            {{-- Kondisi: Poor --}}
            <div class="stat-card cursor-pointer hover:border-[#f97316] transition-colors" onclick="filterCondition('poor')">
                <p class="text-xs text-[var(--color-text-muted)] mb-1">Poor</p>
                <p class="text-2xl font-bold text-[#f97316]">{{ $conditionStats['poor'] }}</p>
            </div>

            {{-- Kondisi: Damaged --}}
            <div class="stat-card cursor-pointer hover:border-[var(--color-danger)] transition-colors" onclick="filterCondition('damaged')">
                <p class="text-xs text-[var(--color-text-muted)] mb-1">Damaged</p>
                <p class="text-2xl font-bold text-[var(--color-danger)]">{{ $conditionStats['damaged'] }}</p>
            </div>

            {{-- Status: Active --}}
            <div class="stat-card cursor-pointer hover:border-[var(--color-success)] transition-colors" onclick="filterStatus('active')">
                <p class="text-xs text-[var(--color-text-muted)] mb-1">Active</p>
                <p class="text-2xl font-bold text-[var(--color-success)]">{{ $statusStats['active'] }}</p>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="card mb-6">
            <form action="{{ route('stores.edit', $store) }}#assets-list" method="GET" id="asset-filter-form">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Search --}}
                    <div class="sm:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Cari asset ID, nama, brand, model, SN...">
                    </div>

                    {{-- Condition --}}
                    <div>
                        <select name="condition" class="form-input form-select">
                            <option value="">Semua Kondisi</option>
                            <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ request('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                            <option value="damaged" {{ request('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <select name="status" class="form-input form-select">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('stores.edit', $store) }}#assets-list" class="btn btn-secondary btn-sm">Reset</a>

                    @if($assets->total() > 0)
                        <a href="{{ route('assets.export', ['store_id' => $store->id, 'search' => request('search'), 'condition' => request('condition'), 'status' => request('status')]) }}" class="btn btn-secondary btn-sm flex items-center gap-1.5" title="Export ke Excel">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export Excel
                        </a>
                        <a href="{{ route('stores.print', ['store' => $store->id, 'search' => request('search'), 'condition' => request('condition'), 'status' => request('status')]) }}" target="_blank" class="btn btn-secondary btn-sm flex items-center gap-1.5" title="Cetak Stock Opname">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Cetak Laporan (PDF)
                        </a>
                    @endif

                    <div class="ml-auto text-xs text-[var(--color-text-muted)]">
                        Menampilkan {{ $assets->total() }} aset
                    </div>
                </div>
            </form>
        </div>

        {{-- Assets Table --}}
        <div class="card p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Asset ID</th>
                            <th>Foto</th>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th>Umur</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                        <tr>
                            <td class="font-mono text-xs text-[var(--color-brand)]">{{ $asset->asset_id }}</td>
                            <td>
                                @if($asset->photo)
                                <img src="{{ asset('storage/' . $asset->photo) }}" alt="{{ $asset->asset_name }}" class="w-10 h-10 rounded-lg object-cover border border-[var(--color-dark-border)]" loading="lazy">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-[var(--color-dark-bg)] border border-[var(--color-dark-border)] flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                    </svg>
                                </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('assets.show', $asset) }}" class="font-medium hover:text-[var(--color-brand)] transition-colors">
                                    {{ Str::limit($asset->asset_name, 35) }}
                                </a>
                                @if($asset->brand || $asset->model)
                                <p class="text-xs text-[var(--color-text-muted)]">{{ $asset->brand }} {{ $asset->model }}</p>
                                @endif
                            </td>
                            <td class="text-[var(--color-text-secondary)] text-sm">{{ $asset->category->category_name ?? '-' }}</td>
                            <td><span class="badge badge-{{ $asset->condition_color }}">{{ ucfirst($asset->condition) }}</span></td>
                            <td><span class="badge badge-{{ $asset->status_color }}">{{ ucfirst($asset->status) }}</span></td>
                            <td><span class="badge badge-{{ $asset->age_color }}">{{ $asset->age }}</span></td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('assets.show', $asset) }}" class="btn btn-secondary btn-icon btn-sm" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-secondary btn-icon btn-sm" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-[var(--color-text-muted)]">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                @if(request('search') || request('condition') || request('status'))
                                <p class="text-sm mb-2">Tidak ada aset yang cocok dengan filter.</p>
                                <a href="{{ route('stores.edit', $store) }}#assets-list" class="text-[var(--color-brand)] hover:underline text-sm">Reset filter</a>
                                @else
                                <p class="text-sm mb-2">Belum ada aset di store ini.</p>
                                <a href="{{ route('assets.create') }}" class="text-[var(--color-brand)] hover:underline text-sm">+ Tambah aset baru</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($assets->hasPages())
            <div class="px-6 py-4 border-t border-[var(--color-dark-border)]">
                {{ $assets->links() }}
            </div>
            @endif
        </div>
    </div>

    <script>
        function filterCondition(value) {
            const form = document.getElementById('asset-filter-form');
            const select = form.querySelector('select[name="condition"]');
            select.value = value;
            form.submit();
        }

        function filterStatus(value) {
            const form = document.getElementById('asset-filter-form');
            const select = form.querySelector('select[name="status"]');
            select.value = value;
            form.submit();
        }
    </script>

</x-layouts.app>
