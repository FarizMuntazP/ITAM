<x-layouts.app :title="'Daftar Karyawan'" :breadcrumbs="[['label' => 'Karyawan', 'url' => route('employees.index')]]">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Manajemen Karyawan</h1>
            <p class="text-sm text-[var(--color-text-muted)] mt-1">Daftar staff penanggung jawab aset IT</p>
        </div>
        <div>
            <a href="{{ route('employees.create') }}" class="btn btn-primary flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Karyawan
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-6">
        <form action="{{ route('employees.index') }}" method="GET" id="filter-form">
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            @if(request('direction'))
                <input type="hidden" name="direction" value="{{ request('direction') }}">
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                {{-- Search --}}
                <div class="sm:col-span-2 relative">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[var(--color-text-muted)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input form-input-with-leading-icon" placeholder="Cari nama, NIP, store...">
                </div>

                {{-- Store Filter --}}
                <div>
                    <select name="store_id" class="form-input form-select" onchange="document.getElementById('filter-form').submit()">
                        <option value="">Semua Store</option>
                        @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <button type="submit" class="btn btn-primary btn-sm flex items-center gap-1.5 font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm font-semibold">Reset</a>

                {{-- Per page --}}
                <div class="ml-auto flex items-center gap-2">
                    <span class="text-xs text-[var(--color-text-muted)]">Per halaman:</span>
                    <select name="per_page" onchange="document.getElementById('filter-form').submit()" class="form-input form-select py-1 px-2 text-xs w-20">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
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
                        <x-sortable-th label="NIP / Kode" column="employee_code" route="employees.index" />
                        <x-sortable-th label="Nama Lengkap" column="name" route="employees.index" />
                        <th>Email</th>
                        <x-sortable-th label="Store" column="store" route="employees.index" />
                        <th>No. Telp</th>
                        <x-sortable-th label="Aset Dipegang" column="assets_count" route="employees.index" />
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-[var(--color-brand-light)] text-[var(--color-brand)] font-mono text-xs font-bold">
                                {{ $employee->employee_code }}
                            </span>
                        </td>
                        <td class="font-medium text-white">{{ $employee->name }}</td>
                        <td class="text-[var(--color-text-secondary)] text-sm">{{ $employee->email ?? '-' }}</td>
                        <td class="text-[var(--color-text-secondary)] text-sm">{{ $employee->store->store_name ?? '-' }}</td>
                        <td class="text-[var(--color-text-secondary)] text-sm">{{ $employee->phone ?? '-' }}</td>
                        <td>
                            @if($employee->assets_count > 0)
                            <span class="badge badge-yellow">{{ $employee->assets_count }} aset</span>
                            @else
                            <span class="badge badge-gray">0 aset</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary btn-icon btn-sm" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus karyawan {{ $employee->name }}?')">
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
                        <td colspan="7" class="text-center py-8 text-[var(--color-text-muted)]">Belum ada karyawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
        <div class="px-6 py-4 border-t border-[var(--color-dark-border)]">
            {{ $employees->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>
