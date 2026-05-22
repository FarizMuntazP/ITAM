<x-layouts.app :title="'Edit Kategori'" :breadcrumbs="[['label' => 'Kategori', 'url' => route('categories.index')], ['label' => 'Edit: ' . $category->category_code, 'url' => '#']]">

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Edit Kategori</h1>
                <p class="text-sm text-[var(--color-text-muted)] mt-0.5">{{ $category->category_code }} — {{ $category->category_name }}</p>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="category_code" class="form-label">Kode Kategori <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="category_code" name="category_code" value="{{ old('category_code', $category->category_code) }}" class="form-input uppercase" maxlength="10" required>
                        @error('category_code') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="category_name" class="form-label">Nama Kategori <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="category_name" name="category_name" value="{{ old('category_name', $category->category_name) }}" class="form-input" required>
                        @error('category_name') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" class="form-input">{{ old('description', $category->description) }}</textarea>
                    @error('description') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Kategori
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
