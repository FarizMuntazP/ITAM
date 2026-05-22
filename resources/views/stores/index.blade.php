<x-layouts.app :title="'Daftar Store'" :breadcrumbs="[['label' => 'Store', 'url' => route('stores.index')]]">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Manajemen Store</h1>
            <p class="text-sm text-[var(--color-text-muted)] mt-1">Kelola daftar store / cabang</p>
        </div>
        <a href="{{ route('stores.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Store
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Store --}}
        <a href="{{ route('stores.index', array_merge(request()->query(), ['filter' => ''])) }}" class="stat-card {{ !request('filter') ? 'active' : '' }}">
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
        </a>

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

        {{-- Store Aktif (Punya Aset) --}}
        <a href="{{ route('stores.index', array_merge(request()->query(), ['filter' => 'active'])) }}" class="stat-card {{ request('filter') == 'active' ? 'active' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-muted)] mb-1">Store Aktif</p>
                    <p class="text-3xl font-bold text-[var(--color-success)]">{{ number_format($storesWithAssets) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[rgba(34,197,94,0.15)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </a>

        {{-- Store Kosong --}}
        <a href="{{ route('stores.index', array_merge(request()->query(), ['filter' => 'empty'])) }}" class="stat-card {{ request('filter') == 'empty' ? 'active' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-muted)] mb-1">Store Kosong</p>
                    <p class="text-3xl font-bold text-[var(--color-warning)]">{{ number_format($storesEmpty) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[rgba(254,203,0,0.15)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-warning)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="card mb-6">
        <form action="{{ route('stores.index') }}" method="GET" id="store-filter-form">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Search --}}
                <div class="sm:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Cari kode store, nama, lokasi...">
                </div>

                {{-- Sort --}}
                <div>
                    <select name="sort" class="form-input form-select">
                        <option value="store_code" {{ request('sort', 'store_code') == 'store_code' ? 'selected' : '' }}>Urutkan: Kode Store</option>
                        <option value="store_name" {{ request('sort') == 'store_name' ? 'selected' : '' }}>Urutkan: Nama Store</option>
                        <option value="assets_count" {{ request('sort') == 'assets_count' ? 'selected' : '' }}>Urutkan: Jumlah Aset</option>
                    </select>
                </div>

                {{-- Direction --}}
                <div>
                    <select name="direction" class="form-input form-select">
                        <option value="asc" {{ request('direction', 'asc') == 'asc' ? 'selected' : '' }}>A → Z / Kecil → Besar</option>
                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Z → A / Besar → Kecil</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('stores.index') }}" class="btn btn-secondary btn-sm">Reset</a>

                {{-- Per page --}}
                <div class="ml-auto flex items-center gap-2">
                    <span class="text-xs text-[var(--color-text-muted)]">Per halaman:</span>
                    <select name="per_page" onchange="document.getElementById('store-filter-form').submit()" class="form-input form-select py-1 px-2 text-xs w-20">
                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        @php
                            $currentSort = request('sort', 'store_code');
                            $currentDir = request('direction', 'asc');
                        @endphp
                        <th>
                            <a href="{{ route('stores.index', array_merge(request()->query(), ['sort' => 'store_code', 'direction' => $currentSort == 'store_code' && $currentDir == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1">
                                Kode Store
                                @if($currentSort == 'store_code')
                                <svg class="w-3 h-3 text-[var(--color-brand)]" fill="currentColor" viewBox="0 0 20 20">
                                    @if($currentDir == 'asc')<path d="M5.293 9.707l4-4a1 1 0 011.414 0l4 4"/>@else<path d="M14.707 10.293l-4 4a1 1 0 01-1.414 0l-4-4"/>@endif
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('stores.index', array_merge(request()->query(), ['sort' => 'store_name', 'direction' => $currentSort == 'store_name' && $currentDir == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1">
                                Nama Store
                                @if($currentSort == 'store_name')
                                <svg class="w-3 h-3 text-[var(--color-brand)]" fill="currentColor" viewBox="0 0 20 20">
                                    @if($currentDir == 'asc')<path d="M5.293 9.707l4-4a1 1 0 011.414 0l4 4"/>@else<path d="M14.707 10.293l-4 4a1 1 0 01-1.414 0l-4-4"/>@endif
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th>Lokasi</th>
                        <th>Region</th>
                        <th>
                            <a href="{{ route('stores.index', array_merge(request()->query(), ['sort' => 'assets_count', 'direction' => $currentSort == 'assets_count' && $currentDir == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1">
                                Jumlah Aset
                                @if($currentSort == 'assets_count')
                                <svg class="w-3 h-3 text-[var(--color-brand)]" fill="currentColor" viewBox="0 0 20 20">
                                    @if($currentDir == 'asc')<path d="M5.293 9.707l4-4a1 1 0 011.414 0l4 4"/>@else<path d="M14.707 10.293l-4 4a1 1 0 01-1.414 0l-4-4"/>@endif
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                    <tr>
                        <td class="font-mono text-sm text-[var(--color-brand)]">{{ $store->store_code }}</td>
                        <td class="font-medium">{{ $store->store_name }}</td>
                        <td class="text-[var(--color-text-secondary)]">{{ $store->location }}</td>
                        <td class="text-[var(--color-text-secondary)]">{{ $store->region ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $store->assets_count > 0 ? 'badge-blue' : 'badge-gray' }}">{{ $store->assets_count }} aset</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('stores.edit', $store) }}" class="btn btn-secondary btn-icon btn-sm" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('stores.destroy', $store) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus store {{ $store->store_name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Hapus">
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
                        <td colspan="6" class="text-center py-12 text-[var(--color-text-muted)]">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-sm mb-2">Tidak ada store ditemukan.</p>
                            @if(request('search'))
                            <a href="{{ route('stores.index') }}" class="text-[var(--color-brand)] hover:underline text-sm">Reset filter</a>
                            @else
                            <a href="{{ route('stores.create') }}" class="text-[var(--color-brand)] hover:underline text-sm">+ Tambah store baru</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($stores->hasPages())
        <div class="px-6 py-4 border-t border-[var(--color-dark-border)]">
            {{ $stores->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>

