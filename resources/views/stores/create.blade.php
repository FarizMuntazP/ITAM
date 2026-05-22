<x-layouts.app :title="'Tambah Store'" :breadcrumbs="[['label' => 'Store', 'url' => route('stores.index')], ['label' => 'Tambah', 'url' => '#']]">

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('stores.index') }}" class="btn btn-secondary btn-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Tambah Store Baru</h1>
                <p class="text-sm text-[var(--color-text-muted)] mt-0.5">Isi data store / cabang baru</p>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('stores.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="store_code" class="form-label">Kode Store <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="store_code" name="store_code" value="{{ old('store_code') }}" class="form-input" placeholder="STR-001" required>
                        @error('store_code') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="store_name" class="form-label">Nama Store <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="store_name" name="store_name" value="{{ old('store_name') }}" class="form-input" placeholder="Store Jakarta Pusat" required>
                        @error('store_name') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="location" class="form-label">Lokasi / Alamat <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}" class="form-input" placeholder="Jakarta Pusat" required>
                        @error('location') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="region" class="form-label">Region / Wilayah</label>
                        <input type="text" id="region" name="region" value="{{ old('region') }}" class="form-input" placeholder="DKI Jakarta">
                        @error('region') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Store
                    </button>
                    <a href="{{ route('stores.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
