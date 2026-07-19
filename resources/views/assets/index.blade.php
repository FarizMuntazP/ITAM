<x-layouts.app :title="'Daftar Aset'" :breadcrumbs="[['label' => 'Aset', 'url' => route('assets.index')]]">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Inventory Aset IT</h1>
            <p class="text-sm text-[var(--color-text-muted)] mt-1">{{ $assets->total() }} aset ditemukan</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('assets.template') }}" class="btn btn-secondary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Template
            </a>
            <a href="{{ route('assets.import.form') }}" class="btn btn-secondary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import
            </a>
            <a href="{{ route('assets.export', request()->query()) }}" class="btn btn-secondary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export
            </a>
            <a href="{{ route('assets.create') }}" class="btn btn-primary btn-sm md:btn-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Aset
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-6">
        <form action="{{ route('assets.index') }}" method="GET" id="filter-form">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-3">
                {{-- Search --}}
                <div class="xl:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Cari asset ID, nama, brand, model, SN...">
                </div>

                {{-- Store --}}
                <div>
                    <select name="store_id" class="form-input form-select">
                        <option value="">Semua Store</option>
                        @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Category --}}
                <div>
                    <select name="category_id" class="form-input form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                        @endforeach
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
            </div>

            <div class="flex items-center gap-2 mt-3">
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('assets.index') }}" class="btn btn-secondary btn-sm">Reset</a>

                {{-- Per page --}}
                <div class="ml-auto flex items-center gap-2">
                    <span class="text-xs text-[var(--color-text-muted)]">Per halaman:</span>
                    <select name="per_page" onchange="document.getElementById('filter-form').submit()" class="form-input form-select py-1 px-2 text-xs w-20">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    {{-- Bulk Action Bar --}}
    <div id="bulk-action-bar" class="hidden card mb-4 p-3 bg-[rgba(255,255,255,0.03)] border border-[var(--color-brand)]/50 flex flex-wrap items-center justify-between gap-4 transition-all">
        <div class="flex items-center gap-2">
            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-[var(--color-brand)] text-white text-xs font-bold" id="selected-count">0</span>
            <span class="text-sm font-medium text-white">Aset dipilih</span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="openBulkModal('store')" class="btn btn-secondary btn-sm bg-[var(--color-dark-bg)] hover:bg-[var(--color-dark-border)] border border-[var(--color-dark-border)] text-white">
                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Ubah Store
            </button>
            <button type="button" onclick="openBulkModal('status')" class="btn btn-secondary btn-sm bg-[var(--color-dark-bg)] hover:bg-[var(--color-dark-border)] border border-[var(--color-dark-border)] text-white">
                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Ubah Status
            </button>
            <button type="button" onclick="submitBulkAction('{{ route('assets.bulk.export') }}', 'POST')" class="btn btn-secondary btn-sm bg-[var(--color-dark-bg)] hover:bg-[var(--color-dark-border)] border border-[var(--color-dark-border)] text-white">
                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Terpilih
            </button>
            <button type="button" onclick="submitBulkAction('{{ route('assets.bulk.qr.print') }}', 'POST', '_blank')" class="btn btn-primary btn-sm">
                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M4 8h4m0 0h.01M4 16h4m0 0h.01M4 20h4m0 0h.01m8-16h.01M16 16h.01M12 8h.01"/></svg>
                Cetak QR Terpilih
            </button>
        </div>
    </div>

    {{-- Assets Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-10 text-center"><input type="checkbox" id="selectAll" class="rounded border-[var(--color-dark-border)] text-[var(--color-brand)] bg-[var(--color-dark-bg)] focus:ring-[var(--color-brand)] focus:ring-offset-[#111111] cursor-pointer"></th>
                        <x-sortable-th label="Asset ID" column="asset_id" route="assets.index" />
                        <th>Foto</th>
                        <x-sortable-th label="Nama Aset" column="asset_name" route="assets.index" />
                        <x-sortable-th label="Kategori" column="category" route="assets.index" />
                        <x-sortable-th label="Store" column="store" route="assets.index" />
                        <x-sortable-th label="Kondisi" column="condition" route="assets.index" />
                        <x-sortable-th label="Status" column="status" route="assets.index" />
                        <th class="text-center">Qty</th>
                        <x-sortable-th label="Umur" column="added_at" route="assets.index" />
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td class="text-center"><input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}" class="asset-checkbox rounded border-[var(--color-dark-border)] text-[var(--color-brand)] bg-[var(--color-dark-bg)] focus:ring-[var(--color-brand)] focus:ring-offset-[#111111] cursor-pointer" onclick="updateBulkActionBar()"></td>
                        <td class="font-mono text-xs text-[var(--color-brand)]">{{ $asset->asset_id }}</td>
                        <td>
                            @if($asset->photo)
                            <img src="{{ asset('storage/' . ($asset->photo_thumbnail ?: $asset->photo)) }}" alt="{{ $asset->asset_name }}" class="w-10 h-10 rounded-lg object-cover border border-[var(--color-dark-border)]" loading="lazy">
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
                        <td class="text-[var(--color-text-secondary)] text-sm">{{ $asset->store->store_name ?? '-' }}</td>
                        <td><span class="badge badge-{{ $asset->condition_color }}">{{ ucfirst($asset->condition) }}</span></td>
                        <td><span class="badge badge-{{ $asset->status_color }}">{{ ucfirst($asset->status) }}</span></td>
                        <td class="text-center">
                            @if($asset->asset_type === 'bulk')
                                <span class="font-bold text-[var(--color-brand)]">{{ $asset->quantity }}</span>
                                <span class="text-xs text-[var(--color-text-muted)]"> pcs</span>
                            @else
                                <span class="text-[var(--color-text-muted)]">—</span>
                            @endif
                        </td>
                        <td><span class="badge badge-{{ $asset->age_color }}">{{ $asset->age }}</span></td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="showQrModal('{{ $asset->asset_id }}', '{{ $asset->asset_name }}', '{{ $asset->qr_code_path ? asset('storage/' . $asset->qr_code_path) : '' }}', '{{ route('assets.qr.download', $asset) }}', '{{ route('assets.qr.print', $asset) }}')" class="btn btn-secondary btn-icon btn-sm" title="QR Code">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M4 8h4m0 0h.01M4 16h4m0 0h.01M4 20h4m0 0h.01m8-16h.01M16 16h.01M12 8h.01"/>
                                    </svg>
                                </button>
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
                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset {{ $asset->asset_id }}?')">
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
                        <td colspan="11" class="text-center py-12 text-[var(--color-text-muted)]">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-sm mb-2">Tidak ada aset ditemukan.</p>
                            <a href="{{ route('assets.create') }}" class="text-[var(--color-brand)] hover:underline text-sm">+ Tambah aset baru</a>
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

    {{-- QR Modal --}}
    <div id="qr-modal" class="modal-overlay hidden" style="z-index: 100;">
        <div class="modal-content max-w-sm text-center">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-[var(--color-dark-border)]">
                <h3 class="text-lg font-semibold" id="qr-modal-title">QR Code Aset</h3>
                <button type="button" onclick="hideQrModal()" class="text-[var(--color-text-muted)] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex flex-col items-center justify-center p-4">
                <div class="bg-white p-3 rounded-xl mb-4 shadow-inner">
                    <img id="qr-modal-img" src="" alt="QR Code" class="w-48 h-48 object-contain" loading="lazy">
                </div>
                <div id="qr-modal-id" class="font-mono text-base text-[var(--color-brand)] font-bold mb-1"></div>
                <div id="qr-modal-name" class="text-sm text-[var(--color-text-secondary)] mb-6 px-4"></div>
                
                <div class="flex gap-2 w-full">
                    <a id="qr-modal-download" href="#" class="btn btn-primary flex-1 py-2.5">
                        Download
                    </a>
                    <a id="qr-modal-print" href="#" target="_blank" class="btn btn-secondary flex-1 py-2.5">
                        Print
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Action Form (Hidden) --}}
    <form id="bulk-action-form" method="POST" class="hidden">
        @csrf
        <div id="bulk-inputs-container"></div>
        <input type="hidden" name="store_id" id="bulk_store_id">
        <input type="hidden" name="status" id="bulk_status">
    </form>

    {{-- Bulk Store Modal --}}
    <div id="bulk-store-modal" class="modal-overlay hidden" style="z-index: 100;">
        <div class="modal-content max-w-md">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-[var(--color-dark-border)]">
                <h3 class="text-lg font-semibold text-white">Ubah Store Massal</h3>
                <button type="button" onclick="closeBulkModal('store')" class="text-[var(--color-text-muted)] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p class="text-sm text-[var(--color-text-muted)] mb-4">Pilih store baru untuk <strong id="bulk-store-count" class="text-white">0</strong> aset yang dipilih.</p>
            <div class="mb-5">
                <label class="form-label">Store Tujuan</label>
                <select id="modal_store_id" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none">
                    <option value="">-- Pilih Store --</option>
                    @foreach($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeBulkModal('store')" class="btn btn-secondary py-2 px-4 rounded-lg">Batal</button>
                <button type="button" onclick="executeBulkAction('store')" class="btn btn-primary py-2 px-4 rounded-lg">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    {{-- Bulk Status Modal --}}
    <div id="bulk-status-modal" class="modal-overlay hidden" style="z-index: 100;">
        <div class="modal-content max-w-md">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-[var(--color-dark-border)]">
                <h3 class="text-lg font-semibold text-white">Ubah Status Massal</h3>
                <button type="button" onclick="closeBulkModal('status')" class="text-[var(--color-text-muted)] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p class="text-sm text-[var(--color-text-muted)] mb-4">Pilih status baru untuk <strong id="bulk-status-count" class="text-white">0</strong> aset yang dipilih.</p>
            <div class="mb-5">
                <label class="form-label">Status Baru</label>
                <select id="modal_status" class="form-input bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white w-full rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none">
                    <option value="">-- Pilih Status --</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="disposed">Disposed</option>
                </select>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeBulkModal('status')" class="btn btn-secondary py-2 px-4 rounded-lg">Batal</button>
                <button type="button" onclick="executeBulkAction('status')" class="btn btn-primary py-2 px-4 rounded-lg">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    <script>
        function showQrModal(id, name, qrUrl, downloadUrl, printUrl) {
            const modal = document.getElementById('qr-modal');
            const img = document.getElementById('qr-modal-img');
            const idText = document.getElementById('qr-modal-id');
            const nameText = document.getElementById('qr-modal-name');
            const downloadBtn = document.getElementById('qr-modal-download');
            const printBtn = document.getElementById('qr-modal-print');

            if (!qrUrl) {
                alert('QR Code belum di-generate untuk aset ini.');
                return;
            }

            img.src = qrUrl;
            idText.textContent = id;
            nameText.textContent = name;
            downloadBtn.href = downloadUrl;
            printBtn.href = printUrl;

            modal.classList.remove('hidden');
        }

        function hideQrModal() {
            document.getElementById('qr-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('qr-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideQrModal();
            }
        });

        // Bulk Action Logic
        const selectAllCheckbox = document.getElementById('selectAll');
        const assetCheckboxes = document.querySelectorAll('.asset-checkbox');
        const bulkActionBar = document.getElementById('bulk-action-bar');
        const selectedCountSpan = document.getElementById('selected-count');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                assetCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateBulkActionBar();
            });
        }

        function updateBulkActionBar() {
            const checkedCount = document.querySelectorAll('.asset-checkbox:checked').length;
            selectedCountSpan.textContent = checkedCount;
            
            if (checkedCount > 0) {
                bulkActionBar.classList.remove('hidden');
            } else {
                bulkActionBar.classList.add('hidden');
                if (selectAllCheckbox) selectAllCheckbox.checked = false;
            }
        }

        function getSelectedIds() {
            return Array.from(document.querySelectorAll('.asset-checkbox:checked')).map(cb => cb.value);
        }

        function openBulkModal(type) {
            const count = getSelectedIds().length;
            if (count === 0) return;
            
            document.getElementById(`bulk-${type}-count`).textContent = count;
            document.getElementById(`bulk-${type}-modal`).classList.remove('hidden');
            document.getElementById(`modal_${type === 'store' ? 'store_id' : 'status'}`).value = '';
        }

        function closeBulkModal(type) {
            document.getElementById(`bulk-${type}-modal`).classList.add('hidden');
        }

        function submitBulkAction(url, method = 'POST', target = '_self') {
            const ids = getSelectedIds();
            if (ids.length === 0) return;
            
            const form = document.getElementById('bulk-action-form');
            form.action = url;
            form.method = method === 'GET' ? 'GET' : 'POST';
            form.target = target;
            
            // Clear previous inputs
            const container = document.getElementById('bulk-inputs-container');
            container.innerHTML = '';
            
            // Add method spoofing if needed
            if (method !== 'GET' && method !== 'POST') {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = method;
                container.appendChild(methodInput);
            }
            
            // Add selected IDs
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'asset_ids[]';
                input.value = id;
                container.appendChild(input);
            });
            
            form.submit();
        }

        function executeBulkAction(type) {
            const value = document.getElementById(`modal_${type === 'store' ? 'store_id' : 'status'}`).value;
            if (!value) {
                alert('Silakan pilih opsi yang valid.');
                return;
            }
            
            document.getElementById(`bulk_${type === 'store' ? 'store_id' : 'status'}`).value = value;
            
            const url = type === 'store' ? '{{ route('assets.bulk.update-store') }}' : '{{ route('assets.bulk.update-status') }}';
            submitBulkAction(url, 'POST');
        }
    </script>
</x-layouts.app>
