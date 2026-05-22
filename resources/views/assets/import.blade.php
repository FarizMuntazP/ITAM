<x-layouts.app :title="'Import Excel'" :breadcrumbs="[['label' => 'Aset', 'url' => route('assets.index')], ['label' => 'Import Excel', 'url' => '#']]">

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('assets.index') }}" class="btn btn-secondary btn-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Import Aset dari Excel</h1>
                <p class="text-sm text-[var(--color-text-muted)] mt-0.5">Upload file Excel untuk menambah banyak aset sekaligus</p>
            </div>
        </div>

        {{-- Download Template --}}
        <div class="card mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold mb-1">Download Template</h3>
                    <p class="text-sm text-[var(--color-text-muted)]">Gunakan template yang disediakan untuk memastikan format data sesuai.</p>
                </div>
                <a href="{{ route('assets.template') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Template
                </a>
            </div>
        </div>

        @if(session('import_errors'))
        <div class="card mb-4 border-[var(--color-danger)]/30 bg-[var(--color-danger)]/5">
            <h3 class="font-semibold text-[var(--color-danger)] mb-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Detail Error Import
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-[var(--color-dark-border)] text-[var(--color-text-secondary)] font-semibold">
                            <th class="py-2 px-3">Baris</th>
                            <th class="py-2 px-3">Nama Aset</th>
                            <th class="py-2 px-3 text-[var(--color-danger)]">Deskripsi Error</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-dark-border)]">
                        @foreach(session('import_errors') as $errorItem)
                        <tr>
                            <td class="py-2.5 px-3 font-mono text-[var(--color-brand)]">Row {{ $errorItem['row'] }}</td>
                            <td class="py-2.5 px-3 font-medium text-white">{{ $errorItem['asset_name'] }}</td>
                            <td class="py-2.5 px-3">
                                <ul class="list-disc list-inside text-xs text-[var(--color-text-secondary)] space-y-1">
                                    @foreach($errorItem['errors'] as $errorMsg)
                                    <li>{{ $errorMsg }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Upload Form --}}
        <div class="card">
            <h3 class="font-semibold mb-4">Upload File Excel</h3>

            <form action="{{ route('assets.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <div class="border-2 border-dashed border-[var(--color-dark-border)] rounded-lg p-8 text-center hover:border-[var(--color-brand)] transition-colors cursor-pointer" onclick="document.getElementById('excel-file').click()">
                        <svg class="w-12 h-12 mx-auto mb-3 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm text-[var(--color-text-secondary)] mb-1" id="file-label">Klik untuk pilih file atau drag & drop</p>
                        <p class="text-xs text-[var(--color-text-muted)]">Format: .xlsx, .xls (Maks 10MB)</p>
                    </div>
                    <input type="file" id="excel-file" name="file" class="hidden" accept=".xlsx,.xls" onchange="document.getElementById('file-label').textContent = this.files[0]?.name || 'Klik untuk pilih file'" required>
                    @error('file') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Proses Import
                </button>
            </form>
        </div>
    </div>

</x-layouts.app>
