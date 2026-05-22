<x-layouts.app :title="'Log Aktivitas'" :breadcrumbs="[['label' => 'Log Aktivitas', 'url' => route('logs.index')]]">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Log Aktivitas Aset</h1>
            <p class="text-sm text-[var(--color-text-muted)] mt-1">{{ $activities->total() }} log aktivitas tercatat</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-6">
        <form action="{{ route('logs.index') }}" method="GET" id="filter-form">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                {{-- Search --}}
                <div class="sm:col-span-2 lg:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Cari deskripsi, asset ID, nama aset, nama admin...">
                </div>

                {{-- Action --}}
                <div>
                    <select name="action" class="form-input form-select">
                        <option value="">Semua Aksi</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                    </select>
                </div>

                {{-- Date range --}}
                <div class="sm:col-span-2 lg:col-span-2">
                    <div class="flex items-center gap-2 w-full">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input text-xs py-1 min-w-0 w-full" title="Dari Tanggal">
                        <span class="text-xs text-[var(--color-text-muted)] shrink-0">s/d</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input text-xs py-1 min-w-0 w-full" title="Sampai Tanggal">
                    </div>
                </div>

                {{-- Per Page --}}
                <div>
                    <select name="per_page" class="form-input form-select" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 50) == '10' ? 'selected' : '' }}>10 Per Halaman</option>
                        <option value="25" {{ request('per_page', 50) == '25' ? 'selected' : '' }}>25 Per Halaman</option>
                        <option value="50" {{ request('per_page', 50) == '50' ? 'selected' : '' }}>50 Per Halaman</option>
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
                <a href="{{ route('logs.index') }}" class="btn btn-secondary btn-sm">Reset</a>

                <a href="{{ route('logs.export', request()->query()) }}" class="btn btn-secondary btn-sm ml-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export Excel
                </a>
            </div>
        </form>
    </div>

    {{-- Logs Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="cursor: default;">Waktu</th>
                        <th style="cursor: default;">Aset</th>
                        <th style="cursor: default;">Aksi</th>
                        <th style="cursor: default;">Deskripsi Perubahan</th>
                        <th style="cursor: default;">Aktor (Admin)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td class="text-xs text-[var(--color-text-muted)] whitespace-nowrap">
                            {{ $activity->created_at ? $activity->created_at->format('d M Y H:i:s') : '-' }}
                            <span class="block text-[10px] opacity-75 mt-0.5">{{ $activity->created_at ? $activity->created_at->diffForHumans() : '' }}</span>
                        </td>
                        <td>
                            @if($activity->asset)
                            <a href="{{ route('assets.show', $activity->asset) }}" class="font-mono text-xs text-[var(--color-brand)] hover:underline block">
                                {{ $activity->asset->asset_id }}
                            </a>
                            <span class="text-xs text-[var(--color-text-secondary)] block truncate max-w-[150px]">
                                {{ $activity->asset->asset_name }}
                            </span>
                            @else
                            <span class="text-xs text-[var(--color-text-muted)] font-mono">Aset Terhapus</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeColor = match($activity->action) {
                                    'created' => 'green',
                                    'updated' => 'blue',
                                    default => 'gray'
                                };
                            @endphp
                            <span class="badge badge-{{ $badgeColor }}">{{ ucfirst($activity->action) }}</span>
                        </td>
                        <td class="text-sm text-[var(--color-text-primary)] max-w-md break-words whitespace-normal py-3">
                            {{ $activity->description }}
                        </td>
                        <td class="text-xs text-[var(--color-text-secondary)] font-medium">
                            {{ $activity->user->name ?? 'System / Seeder' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-[var(--color-text-muted)]">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm mb-2">Tidak ada log aktivitas ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($activities->hasPages())
        <div class="px-6 py-4 border-t border-[var(--color-dark-border)]">
            {{ $activities->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>
