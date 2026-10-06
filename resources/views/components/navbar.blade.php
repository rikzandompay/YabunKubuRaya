<header id="navbar"
    x-data="{ mobileOpen: false, scrolled: false }"
    x-init="
        window.addEventListener('scroll', () => { scrolled = window.scrollY > 15 });
        $watch('mobileOpen', val => { document.body.style.overflow = val ? 'hidden' : '' });
    "
    :class="scrolled || mobileOpen ? 'shadow-sm bg-white' : 'bg-white/95 backdrop-blur-md'"
    class="fixed top-0 left-0 right-0 z-[100] transition-all duration-200 border-b border-gray-100">
    <nav class="mx-auto flex max-w-[1400px] items-center justify-between px-6 lg:px-12 h-[68px]">
        <!-- Brand Logo -->
        <div class="flex items-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 py-1">
                <img src="{{ asset('images/logo-bun.webp') }}" alt="Logo Yayasan Bakti Umat Nusantara"
                    class="h-10 md:h-11 w-auto object-contain" onerror="this.src='{{ asset('images/logo-bun.jpeg') }}'" />
            </a>
        </div>

        <!-- Desktop Navigation Links -->
        <ul class="hidden items-center gap-7 md:flex">
            <li>
                <a href="{{ url('/') }}"
                    class="text-[13.5px] font-medium text-[#374151] hover:text-[#00843D] transition-colors">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ request()->is('/') ? '#profile' : url('/#profile') }}"
                    class="text-[13.5px] font-medium text-[#374151] hover:text-[#00843D] transition-colors">
                    Profile Yabun
                </a>
            </li>
            <li>
                <a href="{{ request()->is('/') ? '#katalog' : url('/#katalog') }}"
                    class="text-[13.5px] font-medium text-[#374151] hover:text-[#00843D] transition-colors">
                    Katalog
                </a>
            </li>
            <li>
                <a href="{{ request()->is('/') ? '#donasi' : url('/#donasi') }}"
                    class="text-[13.5px] font-medium text-[#374151] hover:text-[#00843D] transition-colors">
                    Info Donasi
                </a>
            </li>
            <li>
                <a href="{{ request()->is('/') ? '#program-artikel' : route('artikel.index') }}"
                    class="text-[13.5px] font-medium text-[#374151] hover:text-[#00843D] transition-colors">
                    Artikel Program
                </a>
            </li>
            <li>
                <a href="{{ request()->is('/') ? '#galeri' : url('/#galeri') }}"
                    class="text-[13.5px] font-medium text-[#374151] hover:text-[#00843D] transition-colors">
                    Galeri
                </a>
            </li>
            <li>
                <a href="{{ request()->is('/') ? '#footer' : url('/#footer') }}"
                    class="text-[13.5px] font-medium text-[#374151] hover:text-[#00843D] transition-colors">
                    Footer
                </a>
            </li>
        </ul>

        <!-- Right Action CTA -->
        <div class="hidden md:flex items-center gap-3">
            <a href="https://wa.me/6285349836076?text=Assalamu%27alaikum%20Admin%20YABUN%20Pontianak"
                target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-2 bg-[#00843D] hover:bg-[#006B31] text-white px-5 py-2.5 rounded-md text-[13px] font-semibold transition-all shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>Hubungi Kami</span>
            </a>
        </div>

        <!-- Mobile Menu Toggle Button (Animated Burger/Close) -->
        <button type="button" id="mobile-menu-toggle"
            @click="mobileOpen = !mobileOpen"
            class="relative z-[110] flex h-10 w-10 flex-col items-center justify-center gap-1.5 md:hidden rounded-lg hover:bg-gray-100 transition-colors focus:outline-none cursor-pointer"
            aria-label="Toggle Menu"
            :aria-expanded="mobileOpen.toString()">
            <span class="h-0.5 w-6 bg-[#111827] transition-all duration-300 transform origin-center"
                :class="mobileOpen ? 'rotate-45 translate-y-2' : ''"></span>
            <span class="h-0.5 w-6 bg-[#111827] transition-all duration-300"
                :class="mobileOpen ? 'opacity-0 scale-x-0' : 'opacity-100'"></span>
            <span class="h-0.5 w-6 bg-[#111827] transition-all duration-300 transform origin-center"
                :class="mobileOpen ? '-rotate-45 -translate-y-2' : ''"></span>
        </button>
    </nav>

    <!-- Mobile Drawer Menu (Full Height, High Contrast, Solid Background) -->
    <div id="mobile-drawer"
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="fixed inset-x-0 top-[68px] z-[105] bg-white border-t border-gray-100 shadow-2xl md:hidden overflow-y-auto"
        style="height: calc(100vh - 68px); height: calc(100dvh - 68px);">
        
        <div class="flex flex-col justify-between min-h-full px-6 py-6 pb-12 bg-white">
            <!-- Navigation Links -->
            <ul class="flex flex-col divide-y divide-gray-100">
                <li>
                    <a href="{{ url('/') }}"
                        @click="mobileOpen = false"
                        class="flex items-center justify-between py-4 text-[16px] font-bold text-gray-900 hover:text-[#00843D] active:text-[#00843D] transition-colors">
                        <span>Home</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ request()->is('/') ? '#profile' : url('/#profile') }}"
                        @click="mobileOpen = false"
                        class="flex items-center justify-between py-4 text-[16px] font-bold text-gray-900 hover:text-[#00843D] active:text-[#00843D] transition-colors">
                        <span>Profile Yabun</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ request()->is('/') ? '#katalog' : url('/#katalog') }}"
                        @click="mobileOpen = false"
                        class="flex items-center justify-between py-4 text-[16px] font-bold text-gray-900 hover:text-[#00843D] active:text-[#00843D] transition-colors">
                        <span>Katalog</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ request()->is('/') ? '#donasi' : url('/#donasi') }}"
                        @click="mobileOpen = false"
                        class="flex items-center justify-between py-4 text-[16px] font-bold text-gray-900 hover:text-[#00843D] active:text-[#00843D] transition-colors">
                        <span>Info Donasi</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ request()->is('/') ? '#program-artikel' : route('artikel.index') }}"
                        @click="mobileOpen = false"
                        class="flex items-center justify-between py-4 text-[16px] font-bold text-gray-900 hover:text-[#00843D] active:text-[#00843D] transition-colors">
                        <span>Artikel Program</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ request()->is('/') ? '#galeri' : url('/#galeri') }}"
                        @click="mobileOpen = false"
                        class="flex items-center justify-between py-4 text-[16px] font-bold text-gray-900 hover:text-[#00843D] active:text-[#00843D] transition-colors">
                        <span>Galeri</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ request()->is('/') ? '#footer' : url('/#footer') }}"
                        @click="mobileOpen = false"
                        class="flex items-center justify-between py-4 text-[16px] font-bold text-gray-900 hover:text-[#00843D] active:text-[#00843D] transition-colors">
                        <span>Footer</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
            </ul>

            <!-- Bottom Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-100 space-y-3">
                <a href="{{ request()->is('/') ? '#donasi' : url('/#donasi') }}"
                    @click="mobileOpen = false"
                    class="flex w-full items-center justify-center gap-2 bg-[#00843D] hover:bg-[#006B31] text-white py-3.5 rounded-xl font-bold text-sm shadow-md active:scale-98 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Donasi Sekarang</span>
                </a>
                <a href="https://wa.me/6285349836076?text=Assalamu%27alaikum%20Admin%20YABUN%20Pontianak"
                    target="_blank" rel="noopener noreferrer"
                    class="flex w-full items-center justify-center gap-2 border border-gray-200 bg-gray-50 text-gray-800 py-3.5 rounded-xl font-bold text-sm hover:border-[#00843D] hover:text-[#00843D] active:scale-98 transition-all">
                    <svg class="w-4 h-4 text-[#00843D]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                    </svg>
                    <span>Hubungi via WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</header>
