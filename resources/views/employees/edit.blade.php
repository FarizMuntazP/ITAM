<x-layouts.app :title="'Edit Karyawan'" :breadcrumbs="[['label' => 'Karyawan', 'url' => route('employees.index')], ['label' => 'Edit', 'url' => '#']]">

    <div class="{{ ($employee->assets->isNotEmpty() || $employee->loans->isNotEmpty()) ? 'grid grid-cols-1 lg:grid-cols-3 gap-6' : 'max-w-2xl' }}">
        
        {{-- Form Column --}}
        <div class="{{ $employee->assets->isNotEmpty() ? 'lg:col-span-2' : ($employee->loans->isNotEmpty() ? 'lg:col-span-3' : '') }}">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-icon">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold">Edit Karyawan</h1>
                    <p class="text-sm text-[var(--color-text-muted)] mt-0.5">Ubah detail data karyawan {{ $employee->employee_code }}</p>
                </div>
            </div>

            <div class="card">
                <form action="{{ route('employees.update', $employee) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-[var(--color-danger)]">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $employee->name) }}" class="form-input" placeholder="Budi Santoso" required>
                        @error('name') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}" class="form-input" placeholder="budi.santoso@company.com">
                        @error('email') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="department" class="form-label">Departemen / Divisi</label>
                            <input type="text" id="department" name="department" value="{{ old('department', $employee->department) }}" class="form-input" placeholder="Information Technology">
                            @error('department') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="form-label">No. Telepon / WhatsApp</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" class="form-input" placeholder="0812XXXXXXXX">
                            @error('phone') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>

            {{-- Riwayat Penugasan Aset --}}
            @if($employee->loans->isNotEmpty())
            <div class="card mt-6">
                <h3 class="text-sm font-semibold text-[var(--color-brand)] uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Riwayat Penugasan Aset
                </h3>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Aset</th>
                                <th>Kategori</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Admin</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->loans as $loan)
                            @php
                                $durationDays = $loan->loan_date ? (int) $loan->loan_date->diffInDays($loan->return_date ?? now()) : null;
                            @endphp
                            <tr>
                                <td>
                                    @if($loan->asset)
                                        <a href="{{ route('assets.show', $loan->asset) }}" class="font-medium text-white hover:text-[var(--color-brand)] transition-colors">
                                            {{ $loan->asset->asset_name }}
                                        </a>
                                        <div class="text-xs text-[var(--color-text-muted)] font-mono mt-0.5">{{ $loan->asset->asset_id }}</div>
                                    @else
                                        <span class="text-[var(--color-text-muted)]">Aset Terhapus</span>
                                    @endif
                                </td>
                                <td class="text-sm text-[var(--color-text-secondary)]">
                                    {{ $loan->asset->category->category_name ?? '-' }}
                                </td>
                                <td class="text-sm text-white">{{ $loan->loan_date ? $loan->loan_date->format('d M Y') : '-' }}</td>
                                <td class="text-sm text-white">
                                    {{ $loan->return_date ? $loan->return_date->format('d M Y') : '-' }}
                                </td>
                                <td class="text-sm text-white whitespace-nowrap">
                                    @if($durationDays !== null)
                                        {{ $loan->status === 'active' ? 'Berjalan ' : '' }}{{ $durationDays }} hari
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($loan->status === 'active')
                                        <span class="badge badge-yellow">Dipegang</span>
                                    @else
                                        <span class="badge badge-green">Kembali</span>
                                    @endif
                                </td>
                                <td class="text-xs text-[var(--color-text-secondary)]">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="whitespace-nowrap">Out: {{ $loan->loanedBy->name ?? '-' }}</span>
                                        @if($loan->status === 'returned')
                                            <span class="whitespace-nowrap">In: {{ $loan->returnedBy->name ?? '-' }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-sm text-[var(--color-text-secondary)] max-w-xs truncate" title="{{ $loan->notes }}">
                                    {{ $loan->notes ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- Assets Column (Only shows if employee is holding assets) --}}
        @if($employee->assets->isNotEmpty())
        <div class="space-y-6">
            <div class="card border border-[rgba(254,203,0,0.12)] bg-[rgba(254,203,0,0.015)]">
                <h3 class="text-sm font-semibold text-[var(--color-brand)] uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    Aset Dipinjam / Dipegang
                </h3>
                <div class="space-y-4">
                    @foreach($employee->assets as $asset)
                    @php
                        $activeLoan = $asset->loans->where('employee_id', $employee->id)->where('status', 'active')->first();
                    @endphp
                    <div class="p-4 bg-[var(--color-dark-bg)] border border-[var(--color-dark-border)] rounded-xl space-y-3.5">
                        <div class="flex justify-between items-start gap-2">
                            <div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-[rgba(254,203,0,0.1)] text-[var(--color-brand)] border border-[rgba(254,203,0,0.2)]">
                                    {{ $asset->asset_id }}
                                </span>
                            </div>
                            <span class="badge badge-{{ $asset->condition_color }} text-[10px] py-0.5 px-2">
                                {{ ucfirst($asset->condition) }}
                            </span>
                        </div>

                        <div>
                            <h4 class="font-bold text-white text-sm leading-snug">{{ $asset->asset_name }}</h4>
                            <p class="text-[11px] text-[var(--color-text-muted)] mt-0.5">{{ $asset->category->category_name ?? 'Laptop' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs border-t border-[var(--color-dark-border)] pt-3">
                            <div>
                                <span class="text-[var(--color-text-muted)] block text-[10px] uppercase tracking-wider mb-0.5">Merek</span>
                                <span class="text-white font-medium">{{ $asset->brand ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[var(--color-text-muted)] block text-[10px] uppercase tracking-wider mb-0.5">Model / Tipe</span>
                                <span class="text-white font-medium">{{ $asset->model ?? '-' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[var(--color-text-muted)] block text-[10px] uppercase tracking-wider mb-0.5">Serial Number (S/N)</span>
                                <span class="font-mono text-white text-[11px] font-bold tracking-wide">{{ $asset->serial_number ?? '-' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[var(--color-text-muted)] block text-[10px] uppercase tracking-wider mb-0.5">Tanggal Mulai Pinjam</span>
                                <span class="text-white font-medium">
                                    @if($activeLoan && $activeLoan->loan_date)
                                        {{ $activeLoan->loan_date->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-[var(--color-dark-border)] pt-3">
                            <span class="text-[var(--color-text-muted)] block text-[10px] uppercase tracking-wider mb-1.5">Spesifikasi Teknis</span>
                            @if($asset->specs)
                                <p class="text-xs text-[var(--color-text-secondary)] whitespace-pre-wrap bg-[rgba(255,255,255,0.015)] border border-[var(--color-dark-border)] rounded-lg p-2.5 font-mono leading-relaxed">{{ $asset->specs }}</p>
                            @else
                                <p class="text-xs text-[var(--color-text-muted)] italic">Tidak ada detail spesifikasi teknis.</p>
                            @endif
                        </div>
                        
                        <div class="pt-2">
                            <a href="{{ route('assets.show', $asset) }}" class="btn btn-secondary btn-sm w-full py-2 text-xs flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail Aset
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
    </div>

</x-layouts.app>
