<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ITAM - Sistem Tracking Aset Inventory IT Multi Store">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — ITAM</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    {{-- Toast Notifications --}}
    @if(session('success'))
    <div class="toast toast-success" id="toast-success">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-[var(--color-success)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="toast toast-error" id="toast-error">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-[var(--color-danger)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-[var(--color-dark-card)] border-r border-[var(--color-dark-border)] flex flex-col fixed h-full z-40" id="sidebar">
            {{-- Logo --}}
            <div class="p-5 border-b border-[var(--color-dark-border)]">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[var(--color-brand)] rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#111111]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-[var(--color-brand)]">ITAM</h1>
                        <p class="text-[0.65rem] text-[var(--color-text-muted)] -mt-0.5">IT Asset Management</p>
                    </div>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <div class="pt-3 pb-1 px-3">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Data Master</p>
                </div>

                <a href="{{ route('assets.index') }}" class="sidebar-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    Aset
                </a>

                <a href="{{ route('employees.index') }}" class="sidebar-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Karyawan
                </a>

                <a href="{{ route('stores.index') }}" class="sidebar-link {{ request()->routeIs('stores.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Store
                </a>

                <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kategori
                </a>

                <div class="pt-3 pb-1 px-3">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[var(--color-text-muted)]">Audit</p>
                </div>

                <a href="{{ route('logs.index') }}" class="sidebar-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Log Aktivitas
                </a>
            </nav>

            {{-- User Info --}}
            <div class="p-4 border-t border-[var(--color-dark-border)]">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[var(--color-brand)] flex items-center justify-center text-[#111111] font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-[var(--color-text-muted)]">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-md hover:bg-[rgba(239,68,68,0.15)] text-[var(--color-text-muted)] hover:text-[var(--color-danger)] transition-colors" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 ml-64">
            {{-- Top Header --}}
            <header class="sticky top-0 z-30 bg-[var(--color-dark-bg)]/80 backdrop-blur-md border-b border-[var(--color-dark-border)]">
                <div class="px-6 py-3 flex items-center justify-between">
                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-2 text-sm">
                        <a href="{{ route('dashboard') }}" class="text-[var(--color-text-muted)] hover:text-[var(--color-brand)] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </a>
                        @if(isset($breadcrumbs))
                            @foreach($breadcrumbs as $crumb)
                                <svg class="w-3.5 h-3.5 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                @if($loop->last)
                                    <span class="text-[var(--color-text-primary)] font-medium">{{ $crumb['label'] }}</span>
                                @else
                                    <a href="{{ $crumb['url'] }}" class="text-[var(--color-text-muted)] hover:text-[var(--color-brand)] transition-colors">{{ $crumb['label'] }}</a>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    {{-- Right side --}}
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-[var(--color-text-muted)]">{{ now()->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Auto-hide toasts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 4000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
