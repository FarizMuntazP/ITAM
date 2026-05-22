<x-layouts.app :title="'Daftar Karyawan'" :breadcrumbs="[['label' => 'Karyawan', 'url' => route('employees.index')]]">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Manajemen Karyawan</h1>
            <p class="text-sm text-[var(--color-text-muted)] mt-1">Daftar staff penanggung jawab aset IT</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Search & Pagination Form --}}
            <form action="{{ route('employees.index') }}" method="GET" class="flex items-center gap-2">
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('direction'))
                    <input type="hidden" name="direction" value="{{ request('direction') }}">
                @endif

                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, dep..." class="form-input pr-10 w-64 h-10 py-1 text-sm bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-lg text-white placeholder-[var(--color-text-muted)] focus:outline-none focus:border-[var(--color-brand)] transition-colors">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--color-text-muted)] hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <select name="per_page" onchange="this.form.submit()" class="form-input form-select h-10 py-1 text-xs w-28 bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-lg text-white">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 / Page</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 / Page</option>
                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 / Page</option>
                </select>
            </form>
            <a href="{{ route('employees.create') }}" class="btn btn-primary h-10 flex items-center gap-1.5 px-4 rounded-lg text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Karyawan
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    @php
                        $currentSort = request('sort', 'name');
                        $currentDir = request('direction', 'asc');
                    @endphp
                    <tr>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'employee_code', 'direction' => $currentSort == 'employee_code' && $currentDir == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-[var(--color-brand)] transition-colors">
                                NIP / Kode
                                @if($currentSort == 'employee_code')
                                <svg class="w-3 h-3 text-[var(--color-brand)]" fill="currentColor" viewBox="0 0 20 20">
                                    @if($currentDir == 'asc')<path d="M5.293 9.707l4-4a1 1 0 011.414 0l4 4"/>@else<path d="M14.707 10.293l-4 4a1 1 0 01-1.414 0l-4-4"/>@endif
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => $currentSort == 'name' && $currentDir == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-[var(--color-brand)] transition-colors">
                                Nama Lengkap
                                @if($currentSort == 'name')
                                <svg class="w-3 h-3 text-[var(--color-brand)]" fill="currentColor" viewBox="0 0 20 20">
                                    @if($currentDir == 'asc')<path d="M5.293 9.707l4-4a1 1 0 011.414 0l4 4"/>@else<path d="M14.707 10.293l-4 4a1 1 0 01-1.414 0l-4-4"/>@endif
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th>Email</th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'department', 'direction' => $currentSort == 'department' && $currentDir == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-[var(--color-brand)] transition-colors">
                                Departemen
                                @if($currentSort == 'department')
                                <svg class="w-3 h-3 text-[var(--color-brand)]" fill="currentColor" viewBox="0 0 20 20">
                                    @if($currentDir == 'asc')<path d="M5.293 9.707l4-4a1 1 0 011.414 0l4 4"/>@else<path d="M14.707 10.293l-4 4a1 1 0 01-1.414 0l-4-4"/>@endif
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th>No. Telp</th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'assets_count', 'direction' => $currentSort == 'assets_count' && $currentDir == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-[var(--color-brand)] transition-colors">
                                Aset Dipegang
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
                    @forelse($employees as $employee)
                    <tr>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-[var(--color-brand-light)] text-[var(--color-brand)] font-mono text-xs font-bold">
                                {{ $employee->employee_code }}
                            </span>
                        </td>
                        <td class="font-medium text-white">{{ $employee->name }}</td>
                        <td class="text-[var(--color-text-secondary)] text-sm">{{ $employee->email ?? '-' }}</td>
                        <td class="text-[var(--color-text-secondary)] text-sm">{{ $employee->department ?? '-' }}</td>
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
