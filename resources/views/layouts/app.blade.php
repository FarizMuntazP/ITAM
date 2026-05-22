<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ITAM - Sistem Tracking Aset Inventory IT Multi Store">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — ITAM</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://unpkg.com/html5-qrcode" defer></script>
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

    <!-- Sidebar Backdrop for Mobile -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-30 hidden md:hidden transition-all duration-300"></div>

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-[var(--color-dark-card)] border-r border-[var(--color-dark-border)] flex flex-col h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out" id="sidebar">
            {{-- Logo --}}
            <div class="p-5 border-b border-[var(--color-dark-border)] flex items-center justify-between">
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
                <button id="close-sidebar-btn" class="p-1 rounded-md text-[var(--color-text-muted)] hover:text-white md:hidden transition-colors cursor-pointer" title="Close Sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
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
        <main class="flex-1 ml-0 md:ml-64 transition-all duration-300">
            {{-- Top Header --}}
            <header class="sticky top-0 z-30 bg-[var(--color-dark-bg)]/80 backdrop-blur-md border-b border-[var(--color-dark-border)]">
                <div class="px-4 md:px-6 py-3 flex items-center justify-between">
                    {{-- Hamburger Menu & Breadcrumbs --}}
                    <div class="flex items-center gap-3">
                        <button id="sidebar-toggle" class="p-1.5 rounded-md hover:bg-white/10 text-[var(--color-text-muted)] hover:text-white md:hidden transition-colors cursor-pointer" title="Toggle Sidebar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <div class="hidden md:flex items-center gap-2 text-sm">
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

                        {{-- Mobile Page Title --}}
                        <div class="md:hidden font-semibold text-[var(--color-brand)] text-sm truncate max-w-[150px]">
                            {{ $title ?? 'ITAM' }}
                        </div>
                    </div>

                    {{-- Right side --}}
                    <div class="flex items-center gap-2 md:gap-3">
                        <button id="scan-qr-btn" class="flex items-center gap-1.5 px-2.5 py-1.5 md:px-3 md:py-1.5 bg-[var(--color-brand)] text-[#111111] hover:bg-[var(--color-brand-hover)] rounded-lg text-[10px] md:text-xs font-semibold transition-all shadow-md shadow-[rgba(254,203,0,0.15)] cursor-pointer" title="Scan QR Code Aset">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2m-2-12h-2a2 2 0 00-2 2v2m-6 12H6a2 2 0 01-2-2v-2m2-10H6a2 2 0 00-2 2v2m12 4a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Scan QR</span>
                        </button>
                        <span class="text-xs text-[var(--color-text-muted)] hidden sm:inline">{{ now()->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- QR Scanner Modal --}}
    <div id="qr-scanner-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-[var(--color-dark-card)] border border-[var(--color-dark-border)] rounded-xl w-full max-w-md overflow-hidden shadow-2xl">
            <div class="p-4 border-b border-[var(--color-dark-border)] flex items-center justify-between">
                <h3 class="font-bold text-[var(--color-brand)] flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2m-2-12h-2a2 2 0 00-2 2v2m-6 12H6a2 2 0 01-2-2v-2m2-10H6a2 2 0 00-2 2v2m12 4a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Scan QR Code Aset
                </h3>
                <button id="close-scanner-btn" class="p-1 rounded-md text-[var(--color-text-muted)] hover:text-white hover:bg-white/10 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 flex flex-col items-center">
                <p class="text-xs text-[var(--color-text-muted)] mb-4 text-center">Arahkan kamera ke QR Code label aset ITAM</p>
                <div class="w-full bg-black/40 border border-[var(--color-dark-border)] rounded-lg overflow-hidden relative" style="aspect-ratio: 1/1;">
                    <div id="qr-reader" class="w-full h-full"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #qr-reader video {
            border-radius: 8px !important;
            object-fit: cover !important;
        }
    </style>

    {{-- Auto-hide toasts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 4000);
            });

            // QR Scanner logic
            const scanBtn = document.getElementById('scan-qr-btn');
            const modal = document.getElementById('qr-scanner-modal');
            const closeBtn = document.getElementById('close-scanner-btn');
            let html5QrCode = null;

            if (scanBtn && modal && closeBtn) {
                scanBtn.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    
                    // Start QR Scanner
                    html5QrCode = new Html5Qrcode("qr-reader");
                    const config = { fps: 10, qrbox: { width: 220, height: 220 } };
                    
                    html5QrCode.start(
                        { facingMode: "environment" },
                        config,
                        onScanSuccess,
                        onScanFailure
                    ).catch(err => {
                        console.error("Gagal memulai kamera: ", err);
                        alert("Gagal mengakses kamera. Pastikan izin kamera telah diberikan.");
                        closeModal();
                    });
                });

                function closeModal() {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    if (html5QrCode) {
                        html5QrCode.stop().then(() => {
                            html5QrCode.clear();
                            html5QrCode = null;
                        }).catch(err => {
                            console.error("Gagal menghentikan kamera: ", err);
                            html5QrCode = null;
                        });
                    }
                }

                closeBtn.addEventListener('click', closeModal);

                function onScanSuccess(decodedText, decodedResult) {
                    console.log(`Scan success: ${decodedText}`);
                    try {
                        // Check if the decodedText is a JSON
                        const data = JSON.parse(decodedText);
                        if (data && data.asset_id) {
                            window.location.href = `/assets/lookup/${data.asset_id}`;
                        } else {
                            alert("QR Code tidak mengandung data Aset ITAM yang valid.");
                        }
                    } catch (e) {
                        // If not JSON, check if it contains ITAM-
                        if (decodedText.includes("ITAM-")) {
                            const match = decodedText.match(/ITAM-[A-Z0-9]+-[0-9]+/i);
                            if (match) {
                                window.location.href = `/assets/lookup/${match[0]}`;
                                return;
                            }
                        }
                        
                        // Fallback: is it a valid URL?
                        if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                            window.location.href = decodedText;
                        } else {
                            alert("Format QR Code tidak dikenali.");
                        }
                    }
                }

                function onScanFailure(error) {
                    // Failures occur frequently when scanner doesn't detect a code in the frame, ignore them
                }
            }

            // Mobile sidebar toggle logic
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');

            if (sidebarToggle && sidebar && sidebarBackdrop) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarBackdrop.classList.remove('hidden');
                });

                const closeSidebar = () => {
                    sidebar.classList.add('-translate-x-full');
                    sidebarBackdrop.classList.add('hidden');
                };

                sidebarBackdrop.addEventListener('click', closeSidebar);
                if (closeSidebarBtn) {
                    closeSidebarBtn.addEventListener('click', closeSidebar);
                }

                // Close sidebar on click of navigation links on mobile
                const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 768) {
                            closeSidebar();
                        }
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
