<x-layouts.app :title="'Daftar Kategori'" :breadcrumbs="[['label' => 'Kategori', 'url' => route('categories.index')]]">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Manajemen Kategori</h1>
            <p class="text-sm text-[var(--color-text-muted)] mt-1">Kelola kategori aset IT</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Table --}}
    <div class="border border-[var(--color-dark-border)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[var(--color-dark-border)]">
                        <x-sortable-th label="Kode" column="category_code" route="categories.index" />
                        <x-sortable-th label="Nama Kategori" column="category_name" route="categories.index" />
                        <th class="p-3 text-left text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Deskripsi</th>
                        <x-sortable-th label="Aset" column="assets_count" route="categories.index" />
                        <th class="p-3 text-right text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr class="group border-b border-[var(--color-dark-border)] hover:bg-white/[0.015] transition-colors duration-150">
                        <td class="p-3">
                            <span class="inline-block px-2 py-0.5 text-[0.65rem] font-mono font-bold tracking-wider text-[var(--color-brand)] bg-[var(--color-brand)]/5 border border-[var(--color-brand)]/20">{{ $category->category_code }}</span>
                        </td>
                        <td class="p-3 font-medium text-white">{{ $category->category_name }}</td>
                        <td class="p-3 text-[var(--color-text-secondary)] text-[0.8rem] max-w-sm truncate">{{ Str::limit($category->description, 50) ?? '—' }}</td>
                        <td class="p-3">
                            <span class="inline-block px-2 py-0.5 text-[0.65rem] font-semibold tracking-wider {{ $category->assets_count > 0 ? 'bg-[rgba(59,130,246,0.12)] text-[#3b82f6] border border-[rgba(59,130,246,0.25)]' : 'bg-[rgba(160,160,160,0.12)] text-[#a0a0a0] border border-[rgba(160,160,160,0.25)]' }}">{{ $category->assets_count }} aset</span>
                        </td>
                        <td class="p-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('categories.edit', $category) }}" class="p-1.5 rounded hover:bg-white/5 text-[var(--color-text-muted)] hover:text-white transition-colors" title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->category_name }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded hover:bg-[rgba(239,68,68,0.15)] text-[var(--color-text-muted)] hover:text-[var(--color-danger)] transition-colors cursor-pointer" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center">
                            <div class="max-w-xs mx-auto">
                                <svg class="w-16 h-16 mx-auto mb-4 text-[var(--color-text-muted)] opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                                <p class="text-sm text-[var(--color-text-muted)] mb-1">Belum ada kategori</p>
                                @if(request('search'))
                                <a href="{{ route('categories.index') }}" class="text-xs font-semibold text-[var(--color-brand)] hover:text-[var(--color-brand-hover)] transition-colors">Reset filter</a>
                                @else
                                <p class="text-[0.7rem] text-[var(--color-text-muted)]/60 mb-4">Kategori membantu mengelompokkan aset</p>
                                <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--color-brand)] hover:text-[var(--color-brand-hover)] transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Kategori Baru
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="px-6 py-4 border-t border-[var(--color-dark-border)]">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>
