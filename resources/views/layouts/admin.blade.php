<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard — Admin Bakti Umat Nusantara Cabang Kubu Raya')</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark Mode Init: Prevent flash of unstyled theme (FOUC) -->
    <script>
        (function() {
            try {
                const theme = localStorage.getItem('admin_theme');
                if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            touch-action: pan-y;
            -webkit-text-size-adjust: 100%;
            overflow-x: hidden !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        body {
            touch-action: pan-y;
            overflow-x: hidden !important;
            max-width: 100% !important;
            width: 100% !important;
            overscroll-behavior-x: none;
        }

        @media (prefers-reduced-motion: reduce) {
            .bar-transition {
                transition: none !important;
            }

            .sidebar-transition {
                transition: none !important;
            }
        }
    </style>
</head>

<body class="admin-theme bg-[#F8FAFC] dark:bg-[#0B132B] text-[#1E293B] dark:text-[#E2E8F0] antialiased font-sans overflow-x-hidden w-full max-w-full transition-colors duration-200"
    x-data="adminLayout()" @keydown.escape.window="closeMobileMenu()">

    {{-- Mobile sidebar overlay --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 dark:bg-black/70 z-40 xl:hidden" @click="closeMobileMenu()"
        x-cloak></div>

    {{-- Sidebar --}}
    <x-admin.sidebar />

    {{-- Main content area --}}
    <div class="xl:ml-[240px] min-h-screen flex flex-col min-w-0 overflow-x-hidden sidebar-transition"
        :class="sidebarCollapsed && 'xl:!ml-[64px]'">
        {{-- Topbar --}}
        <header
            class="sticky top-0 z-30 bg-white dark:bg-[#1E293B] border-b border-[#E2E8F0] dark:border-slate-800 px-4 sm:px-6 h-14 flex items-center justify-between gap-4 transition-colors duration-200 w-full max-w-full">
            <div class="flex items-center gap-3">
                {{-- Mobile hamburger --}}
                <button @click="toggleMobileMenu()"
                    class="xl:hidden p-1.5 rounded-lg hover:bg-[#F1F5F9] dark:hover:bg-slate-800 focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2"
                    :aria-expanded="mobileMenuOpen.toString()" aria-controls="admin-sidebar"
                    aria-label="Buka menu navigasi">
                    <svg class="w-5 h-5 text-[#64748B] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Grid icon + breadcrumb --}}
                <svg class="w-4 h-4 text-[#94A3B8] dark:text-slate-500 hidden sm:block" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                </svg>
                <nav aria-label="Breadcrumb" class="hidden sm:block">
                    <ol class="flex items-center gap-1.5 text-sm">
                        <li class="text-[#94A3B8] dark:text-slate-400">Admin</li>
                        <li class="text-[#94A3B8] dark:text-slate-400">/</li>
                        <li class="text-[#1E293B] dark:text-white font-medium">@yield('breadcrumb', 'Dashboard')</li>
                    </ol>
                </nav>
            </div>

            <div class="flex items-center gap-2">
                {{-- Theme toggle --}}
                <button
                    @click="toggleTheme()"
                    class="p-2 rounded-lg hover:bg-[#F1F5F9] dark:hover:bg-slate-800 text-[#64748B] dark:text-slate-300 focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2 transition-colors relative cursor-pointer"
                    :title="isDark ? 'Beralih ke Tema Terang' : 'Beralih ke Tema Gelap'"
                    :aria-label="isDark ? 'Beralih ke Tema Terang' : 'Beralih ke Tema Gelap'"
                >
                    <svg x-show="isDark" class="w-5 h-5 text-amber-400 transition-transform duration-300 hover:rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-1.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <svg x-show="!isDark" class="w-5 h-5 text-[#64748B] hover:text-[#1E293B] transition-transform duration-300 hover:-rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </button>

                {{-- Notification bell (placeholder) --}}
                <button
                    class="p-2 rounded-lg hover:bg-[#F1F5F9] dark:hover:bg-slate-800 text-[#64748B] dark:text-slate-300 focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2 relative transition-colors cursor-pointer"
                    title="Notifikasi" aria-label="Notifikasi">
                    <svg class="w-5 h-5 text-[#64748B] dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </button>

                {{-- Settings --}}
                <a href="{{ route('admin.setting.index') }}"
                    class="p-2 rounded-lg hover:bg-[#F1F5F9] dark:hover:bg-slate-800 focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2 transition-colors {{ request()->routeIs('admin.setting.*') ? 'text-[#065F46] bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-400' : 'text-[#64748B] dark:text-slate-300' }}"
                    title="Pengaturan" aria-label="Pengaturan">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-3 sm:p-6 min-w-0 overflow-x-hidden">
            @yield('content')
        </main>
    </div>

    <script>
        function adminLayout() {
            return {
                mobileMenuOpen: false,
                sidebarCollapsed: false,
                isDark: document.documentElement.classList.contains('dark'),
                focusableElements: [],

                toggleTheme() {
                    this.isDark = !this.isDark;
                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('admin_theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('admin_theme', 'light');
                    }
                },

                toggleMobileMenu() {
                    this.mobileMenuOpen = !this.mobileMenuOpen;
                    if (this.mobileMenuOpen) {
                        this.$nextTick(() => this.trapFocus());
                    }
                },

                closeMobileMenu() {
                    this.mobileMenuOpen = false;
                },

                toggleSidebar() {
                    this.sidebarCollapsed = !this.sidebarCollapsed;
                },

                trapFocus() {
                    const sidebar = document.getElementById('admin-sidebar');
                    if (!sidebar) return;
                    const focusable = sidebar.querySelectorAll('a, button, input, [tabindex]:not([tabindex="-1"])');
                    this.focusableElements = Array.from(focusable);
                    if (this.focusableElements.length > 0) {
                        this.focusableElements[0].focus();
                    }

                    sidebar.addEventListener('keydown', (e) => {
                        if (e.key !== 'Tab' || !this.mobileMenuOpen) return;
                        const first = this.focusableElements[0];
                        const last = this.focusableElements[this.focusableElements.length - 1];
                        if (e.shiftKey && document.activeElement === first) {
                            e.preventDefault();
                            last.focus();
                        } else if (!e.shiftKey && document.activeElement === last) {
                            e.preventDefault();
                            first.focus();
                        }
                    });
                }
            };
        }
    </script>

    @stack('scripts')
</body>

</html>
