<x-layouts.app :title="'Tambah Aset'" :breadcrumbs="[['label' => 'Aset', 'url' => route('assets.index')], ['label' => 'Tambah', 'url' => '#']]">

    <div class="max-w-4xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('assets.index') }}" class="btn btn-secondary btn-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Tambah Aset Baru</h1>
                <p class="text-sm text-[var(--color-text-muted)] mt-0.5">Isi data aset IT yang akan ditambahkan</p>
            </div>
        </div>

        <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Asset ID Preview --}}
            <div class="card mb-4">
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-xs text-[var(--color-text-muted)] mb-1">Asset ID (Auto-generated)</p>
                        <p class="text-xl font-bold font-mono text-[var(--color-brand)]" id="asset-id-preview">ITAM-???-0000</p>
                    </div>
                    <div class="ml-auto">
                        <span class="badge badge-yellow">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Otomatis
                        </span>
                    </div>
                </div>
            </div>

            {{-- Main Info --}}
            <div class="card mb-4">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-4">Informasi Utama</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="asset_name" class="form-label">Nama / Deskripsi Aset <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="asset_name" name="asset_name" value="{{ old('asset_name') }}" class="form-input" placeholder="Laptop Dell Latitude 5420" required>
                        @error('asset_name') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="form-label">Kategori <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="category_id" name="category_id" class="form-input form-select" required onchange="fetchAssetId()">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->category_code }} — {{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="store_id" class="form-label">Store / Cabang <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="store_id" name="store_id" class="form-input form-select" required>
                            <option value="">Pilih Store</option>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->store_code }} — {{ $store->store_name }}</option>
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
                        <input type="text" id="brand" name="brand" value="{{ old('brand') }}" class="form-input" placeholder="Dell, HP, Lenovo...">
                        @error('brand') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="model" class="form-label">Model / Tipe</label>
                        <input type="text" id="model" name="model" value="{{ old('model') }}" class="form-input" placeholder="Latitude 5420">
                        @error('model') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" class="form-input" placeholder="SN123456789">
                        @error('serial_number') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="specs" class="form-label">Spesifikasi Teknis</label>
                    <textarea id="specs" name="specs" rows="3" class="form-input" placeholder="Intel i5-1135G7, RAM 16GB, SSD 512GB...">{{ old('specs') }}</textarea>
                    @error('specs') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Status & Condition --}}
            <div class="card mb-4">
                <h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-4">Status & Kondisi</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="condition" class="form-label">Kondisi <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="condition" name="condition" class="form-input form-select" required>
                            <option value="good" {{ old('condition', 'good') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                            <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="form-label">Status <span class="text-[var(--color-danger)]">*</span></label>
                        <select id="status" name="status" class="form-input form-select" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="disposed" {{ old('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>
                    <div>
                        <label for="purchase_date" class="form-label">Tanggal Pembelian</label>
                        <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}" class="form-input">
                    </div>
                    <div>
                        <label for="warranty_until" class="form-label">Garansi Hingga</label>
                        <input type="date" id="warranty_until" name="warranty_until" value="{{ old('warranty_until') }}" class="form-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="purchase_price" class="form-label">Harga Beli (Rp)</label>
                        <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" class="form-input" placeholder="0" min="0" step="0.01">
                    </div>
                    <div>
                        <label for="location_detail" class="form-label">Lokasi Detail di Store</label>
                        <input type="text" id="location_detail" name="location_detail" value="{{ old('location_detail') }}" class="form-input" placeholder="Lantai 2, Ruang Server">
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
                            <img id="photo-preview" src="" alt="" class="hidden max-h-40 mx-auto mb-3 rounded-lg" loading="lazy">
                            <div id="photo-placeholder">
                                <svg class="w-10 h-10 mx-auto mb-2 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-[var(--color-text-muted)]">Klik untuk upload foto</p>
                                <p class="text-xs text-[var(--color-text-muted)]">JPG, PNG, WEBP (Maks 2MB)</p>
                            </div>
                        </div>
                        <input type="file" id="photo" name="photo" class="hidden" accept="image/jpeg,image/png,image/webp" onchange="previewPhoto(this)">
                        @error('photo') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="notes" class="form-label">Catatan Tambahan</label>
                        <textarea id="notes" name="notes" rows="6" class="form-input" placeholder="Catatan tambahan tentang aset ini...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Aset
                </button>
                <a href="{{ route('assets.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function fetchAssetId() {
            const categoryId = document.getElementById('category_id').value;
            const preview = document.getElementById('asset-id-preview');

            if (!categoryId) {
                preview.textContent = 'ITAM-???-0000';
                return;
            }

            fetch(`{{ route('assets.generate-id') }}?category_id=${categoryId}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                preview.textContent = data.asset_id || 'ITAM-???-0000';
            })
            .catch(() => {
                preview.textContent = 'ITAM-???-0000';
            });
        }

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

        // Trigger on page load if category was pre-selected (old values)
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('category_id').value) {
                fetchAssetId();
            }
        });
    </script>
    @endpush

</x-layouts.app>
