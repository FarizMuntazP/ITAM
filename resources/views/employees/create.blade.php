<x-layouts.app :title="'Tambah Karyawan'" :breadcrumbs="[['label' => 'Karyawan', 'url' => route('employees.index')], ['label' => 'Tambah', 'url' => '#']]">

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Tambah Karyawan Baru</h1>
                <p class="text-sm text-[var(--color-text-muted)] mt-0.5">Daftarkan staff baru untuk alokasi penugasan aset</p>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label">Nama Lengkap <span class="text-[var(--color-danger)]">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input" placeholder="Budi Santoso" required>
                    @error('name') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="budi.santoso@company.com">
                    @error('email') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="store_id" class="form-label">Store / Cabang</label>
                        <select id="store_id" name="store_id" class="form-select w-full bg-[var(--color-dark-bg)] border-[var(--color-dark-border)] text-white rounded-md p-2 focus:border-[var(--color-brand)] focus:outline-none">
                            <option value="">-- Pilih Store --</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                    {{ $store->store_name }} ({{ $store->store_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('store_id') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="form-label">No. Telepon / WhatsApp</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="0812XXXXXXXX">
                        @error('phone') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Karyawan
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
