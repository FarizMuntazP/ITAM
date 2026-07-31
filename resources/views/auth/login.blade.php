<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ITAM — Sistem Tracking Aset Inventory IT Multi Store">
    <title>Login — ITAM</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-[#111111] relative overflow-hidden">

    {{-- Barcode texture bg — faint vertical lines evoking inventory labels --}}
    <div class="fixed inset-0 pointer-events-none opacity-[0.015]"
         style="background-image: repeating-linear-gradient(
            90deg,
            transparent,
            transparent 3px,
            #fecb00 3px,
            #fecb00 4px
         );">
    </div>

    {{-- Scan line accent glow --}}
    <div class="fixed top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[var(--color-brand)]/40 to-transparent pointer-events-none"></div>

    <div class="relative w-full max-w-sm">

        {{-- Wordmark --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-baseline gap-1.5 select-none">
                <span class="text-5xl md:text-6xl font-extrabold tracking-[0.12em] text-[var(--color-brand)]"
                      style="font-family: 'JetBrains Mono', monospace;">
                    ITAM
                </span>
            </div>
            <p class="text-xs tracking-[0.2em] text-[var(--color-text-muted)] uppercase mt-2 font-medium">
                IT Asset Management
            </p>
        </div>

        {{-- Asset-tag card: border-left mimics industrial label stickers --}}
        <div class="bg-[#1a1a1a] border border-[var(--color-dark-border)] rounded-sm"
             style="border-left: 4px solid var(--color-brand); padding: 2rem 1.75rem 1.75rem;">

            {{-- Error Messages --}}
            @if($errors->any())
            <div class="mb-5 p-3 bg-[rgba(239,68,68,0.08)] border-l-2 border-[var(--color-danger)]">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[var(--color-danger)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-[var(--color-danger)]">{{ $errors->first() }}</p>
                </div>
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" id="login-form">
                @csrf

                {{-- Username --}}
                <div class="mb-4">
                    <label for="username" class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5 tracking-wide">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            class="w-full bg-[#111111] border border-[var(--color-dark-border)] px-3 py-2.5 pl-9 text-sm text-[var(--color-text-primary)] placeholder:text-[var(--color-text-muted)] transition-all duration-200 focus:outline-none focus:border-[var(--color-brand)] focus:shadow-[0_0_0_1px_rgba(254,203,0,0.15)] rounded-none"
                            placeholder="admin"
                            required
                            autofocus
                            autocomplete="username"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <label for="password" class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5 tracking-wide">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full bg-[#111111] border border-[var(--color-dark-border)] px-3 py-2.5 pl-9 pr-9 text-sm text-[var(--color-text-primary)] placeholder:text-[var(--color-text-muted)] transition-all duration-200 focus:outline-none focus:border-[var(--color-brand)] focus:shadow-[0_0_0_1px_rgba(254,203,0,0.15)] rounded-none"
                            placeholder="••••••"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[var(--color-text-muted)] hover:text-[var(--color-brand)] transition-colors cursor-pointer">
                            <svg id="eye-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" id="login-btn"
                    class="w-full py-2.5 text-sm font-bold bg-[var(--color-brand)] text-[#111111] hover:bg-[var(--color-brand-hover)] transition-all duration-200 rounded-none tracking-wide flex items-center justify-center gap-2 cursor-pointer">
                    <span id="btn-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg></span>
                    <span id="btn-text">Masuk</span>
                    <svg id="btn-spinner" class="w-4 h-4 hidden animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-[0.65rem] text-[var(--color-text-muted)] mt-6 tracking-wider font-mono">
            ITAM v1.0 &middot; IT Asset Management
        </p>
    </div>

    {{-- Scan-line overlay — shown on form submit --}}
    <div id="scan-overlay" class="fixed inset-0 z-50 hidden flex-col items-center justify-center bg-[#111111]">
        <div class="relative w-full max-w-md px-6 text-center">
            {{-- Animated scan bar --}}
            <div class="relative h-px mb-8 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[var(--color-brand)] to-transparent animate-scan-line"></div>
            </div>
            {{-- Text --}}
            <p class="text-xs tracking-[0.25em] text-[var(--color-text-muted)] uppercase font-mono animate-pulse">Memverifikasi</p>
        </div>
    </div>

    <style>
        @keyframes scan-line {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-scan-line {
            animation: scan-line 1.5s ease-in-out infinite;
        }
    </style>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            var btn = document.getElementById('login-btn');
            var icon = document.getElementById('btn-icon');
            var text = document.getElementById('btn-text');
            var spinner = document.getElementById('btn-spinner');

            icon.classList.add('hidden');
            text.classList.add('hidden');
            spinner.classList.remove('hidden');
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-wait');

            document.getElementById('scan-overlay').classList.remove('hidden');
            document.getElementById('scan-overlay').classList.add('flex');
        });

        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.879L21 21"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</body>
</html>
