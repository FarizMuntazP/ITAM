<x-layouts.app :title="'Edit Aset'" :breadcrumbs="[['label' => 'Aset', 'url' => route('assets.index')], ['label' => $asset->asset_id, 'url' => route('assets.show', $asset)], ['label' => 'Edit', 'url' => '#']]">

    <div class="max-w-4xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('assets.show', $asset) }}" class="btn btn-secondary btn-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Edit Aset</h1>
                <p class="text-sm text-[var(--color-text-muted)] mt-0.5">{{ $asset->asset_id }} — {{ $asset->asset_name }}</p>
            </div>
        </div>

        <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Asset ID (read-only) --}}
            <div class="card mb-4">
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)] mb-1">Asset ID</p>
                        <p class="text-xl font-bold font-mono text-[var(--color-brand)]">{{ $asset->asset_id }}</p>
                    </div>
                    <div class="ml-auto">
                        <span class="badge badge-{{ $asset->age_color }}">Umur: {{ $asset->age }}</span>
                    </div>
                </div>
            </div>

            {{-- Main Info --}}
            <div class="card mb-4">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-4">Informasi Utama</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="asset_name" class="form-label">Nama / Deskripsi Aset <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="asset_name" name="asset_name" value="{{ old('asset_name', $asset->asset_name) }}" class="form-input" required>
                        @error('asset_name') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="category_id" class="form-label">Kategori <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="category_id" name="category_id" class="form-input form-select" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $asset->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->category_code }} — {{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="store_id" class="form-label">Store / Cabang <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="store_id" name="store_id" class="form-input form-select" required>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ old('store_id', $asset->store_id) == $store->id ? 'selected' : '' }}>{{ $store->store_code }} — {{ $store->store_name }}</option>
                            @endforeach
                        </select>
                        @error('store_id') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Device Details --}}
            <div class="card mb-4">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-4">Detail Perangkat</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="brand" class="form-label">Merek</label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand', $asset->brand) }}" class="form-input">
                        @error('brand') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="model" class="form-label">Model / Tipe</label>
                        <input type="text" id="model" name="model" value="{{ old('model', $asset->model) }}" class="form-input">
                        @error('model') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" class="form-input">
                        @error('serial_number') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label for="specs" class="form-label">Spesifikasi Teknis</label>
                    <textarea id="specs" name="specs" rows="3" class="form-input">{{ old('specs', $asset->specs) }}</textarea>
                </div>
            </div>

            {{-- Status & Condition --}}
            <div class="card mb-4">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-4">Status & Kondisi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="condition" class="form-label">Kondisi <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="condition" name="condition" class="form-input form-select" required>
                            <option value="good" {{ old('condition', $asset->condition) == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('condition', $asset->condition) == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('condition', $asset->condition) == 'poor' ? 'selected' : '' }}>Poor</option>
                            <option value="damaged" {{ old('condition', $asset->condition) == 'damaged' ? 'selected' : '' }}>Damaged</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="form-label">Status <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="status" name="status" class="form-input form-select" required>
                            <option value="active" {{ old('status', $asset->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $asset->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="disposed" {{ old('status', $asset->status) == 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>
                    <div>
                        <label for="purchase_date" class="form-label">Tanggal Pembelian</label>
                        <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}" class="form-input">
                    </div>
                    <div>
                        <label for="warranty_until" class="form-label">Garansi Hingga</label>
                        <input type="date" id="warranty_until" name="warranty_until" value="{{ old('warranty_until', $asset->warranty_until?->format('Y-m-d')) }}" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="purchase_price" class="form-label">Harga Beli (Rp)</label>
                        <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $asset->purchase_price) }}" class="form-input" min="0" step="0.01">
                    </div>
                    <div>
                        <label for="location_detail" class="form-label">Lokasi Detail di Store</label>
                        <input type="text" id="location_detail" name="location_detail" value="{{ old('location_detail', $asset->location_detail) }}" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Photo & Notes --}}
            <div class="card mb-6">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-4">Foto & Catatan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="photo" class="form-label">Foto Aset</label>
                        <div class="border-2 border-dashed border-[var(--color-dark-border)] rounded-lg p-4 text-center hover:border-[var(--color-brand)] transition-colors cursor-pointer" onclick="document.getElementById('photo').click()">
                            <img id="photo-preview" src="{{ $asset->photo ? asset('storage/' . $asset->photo) : '' }}" alt="" class="{{ $asset->photo ? '' : 'hidden' }} max-h-40 mx-auto mb-3 rounded-lg" loading="lazy">
                            <div id="photo-placeholder" class="{{ $asset->photo ? 'hidden' : '' }}">
                                <svg class="w-10 h-10 mx-auto mb-2 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-[var(--color-text-muted)]">Klik untuk ganti foto</p>
                            </div>
                        </div>
                        <input type="file" id="photo" name="photo" class="hidden" accept="image/jpeg,image/png,image/webp" onchange="previewPhoto(this)">
                        @error('photo') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="notes" class="form-label">Catatan Tambahan</label>
                        <textarea id="notes" name="notes" rows="6" class="form-input">{{ old('notes', $asset->notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Aset
                </button>
                <a href="{{ route('assets.show', $asset) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photo-preview');
                    const placeholder = document.getElementById('photo-placeholder');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush

</x-layouts.app>
