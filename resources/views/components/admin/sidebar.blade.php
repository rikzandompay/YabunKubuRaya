<nav id="admin-sidebar" aria-label="Sidebar"
    class="fixed top-0 left-0 z-50 h-full bg-[#F8FAFC] border-r border-[#E2E8F0] flex flex-col sidebar-transition overflow-y-auto overflow-x-hidden"
    :class="[
        mobileMenuOpen ? 'translate-x-0' : '-translate-x-full xl:translate-x-0',
        sidebarCollapsed ? 'w-[64px]' : 'w-[240px]'
    ]"
    x-data="sidebarNav()">
    {{-- Header --}}
    <div class="flex items-center gap-3 px-4 h-14 border-b border-[#E2E8F0] shrink-0">
        <div class="w-8 h-8 bg-[#1E293B] rounded-lg flex items-center justify-center shrink-0">
            <img src="{{ asset('images/logoyabun.webp') }}" alt="Logo">
        </div>
        <span class="text-sm font-semibold text-[#1E293B] truncate" x-show="!sidebarCollapsed" x-cloak>Yabun
            Pontianak</span>
        <button
            @click="$root.closest('[x-data]') && sidebarCollapsed != undefined ? (sidebarCollapsed = !sidebarCollapsed) : $dispatch('toggle-sidebar')"
            @click="sidebarCollapsed = !sidebarCollapsed"
            class="ml-auto p-1 rounded hover:bg-[#E2E8F0] focus-visible:ring-2 focus-visible:ring-[#065F46] hidden xl:flex shrink-0"
            aria-label="Toggle sidebar">
            <svg class="w-4 h-4 text-[#94A3B8] transition-transform" :class="sidebarCollapsed && 'rotate-180'"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>

    {{-- Search --}}
    <div class="px-3 py-3 shrink-0" x-show="!sidebarCollapsed">
        <div class="relative">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#94A3B8]" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Cari menu…" x-model="searchQuery"
                class="w-full pl-8 pr-10 py-1.5 text-sm bg-white border border-[#E2E8F0] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none placeholder-[#94A3B8]"
                aria-label="Cari menu navigasi">
            <kbd
                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-[#94A3B8] bg-[#F1F5F9] border border-[#E2E8F0] rounded px-1 py-0.5 font-sans hidden sm:inline">⌘K</kbd>
        </div>
    </div>

    {{-- Menu utama --}}
    <div class="flex-1 px-2 pb-4 overflow-y-auto" x-show="!sidebarCollapsed">
        <p class="px-2 pt-3 pb-2 text-[10px] tracking-widest text-[#94A3B8] font-medium uppercase">Menu Utama</p>

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-white border border-[#E2E8F0] shadow-sm text-[#065F46] font-semibold' : 'text-[#475569] hover:bg-white hover:shadow-sm hover:border hover:border-[#E2E8F0]' }} transition-colors mb-0.5"
            aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}"
            x-show="matchesSearch('Dashboard')">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-[#065F46]' : 'text-[#64748B]' }} shrink-0"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- Kegiatan Foto --}}
        <x-admin.nav-item label="Kegiatan Foto"
            icon='<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/></svg>'
            badge="{{ \App\Models\GalleryPhoto::count() }}" :items="[
                ['label' => 'Semua Foto', 'href' => route('admin.galeri.index')],
                ['label' => 'Jumat Berkah', 'href' => route('admin.galeri.index', ['kategori' => 'jumat_berkah'])],
                ['label' => 'Donasi', 'href' => route('admin.galeri.index', ['kategori' => 'donasi'])],
                ['label' => 'Dzikir', 'href' => route('admin.galeri.index', ['kategori' => 'dzikir'])],
            ]" />

        {{-- Katalog --}}
        <x-admin.nav-item label="Katalog"
            icon='<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>'
            badge="{{ \App\Models\Katalog::count() }}" :items="[
                ['label' => 'Jumat Berkah', 'href' => route('admin.katalog.index', ['program' => 'jumat-berkah'])],
                ['label' => 'Tahsin & Tahfiz', 'href' => route('admin.katalog.index', ['program' => 'tahsin-tahfiz'])],
                [
                    'label' => 'Santunan Anak Yatim',
                    'href' => route('admin.katalog.index', ['program' => 'santunan-anak-yatim']),
                ],
                [
                    'label' => 'Zakat Mal dan Fitrah',
                    'href' => route('admin.katalog.index', ['program' => 'Zakat Mal Dan Fitrah']),
                ],
                ['label' => 'Kurban', 'href' => route('admin.katalog.index', ['program' => 'kurban'])],
            ]" />

        {{-- Artikel Program --}}
        <a href="{{ route('admin.artikel.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.artikel.*') ? 'bg-white border border-[#E2E8F0] shadow-sm text-[#065F46] font-semibold' : 'text-[#475569] hover:bg-white hover:shadow-sm hover:border hover:border-[#E2E8F0]' }} transition-colors mb-0.5"
            x-show="matchesSearch('Artikel Program')">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.artikel.*') ? 'text-[#065F46]' : 'text-[#64748B]' }} shrink-0"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
            </svg>
            <span>Artikel Program</span>
            <span
                class="ml-auto bg-[#F1F5F9] text-[#64748B] text-[11px] font-medium px-1.5 py-0.5 rounded-full">{{ \App\Models\Article::count() }}</span>
        </a>

        {{-- Donatur --}}
        <a href="{{ route('admin.donatur.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.donatur.*') ? 'bg-white border border-[#E2E8F0] shadow-sm text-[#065F46] font-semibold' : 'text-[#475569] hover:bg-white hover:shadow-sm hover:border hover:border-[#E2E8F0]' }} transition-colors mb-0.5"
            x-show="matchesSearch('Donatur')">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.donatur.*') ? 'text-[#065F46]' : 'text-[#64748B]' }} shrink-0"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span>Donatur</span>
        </a>

        {{-- Manajemen Keuangan --}}
        <a href="{{ route('admin.keuangan.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors mb-0.5 {{ request()->routeIs('admin.keuangan.*') ? 'bg-white text-[#065F46] font-semibold shadow-sm border border-[#E2E8F0]' : 'text-[#475569] hover:bg-white hover:shadow-sm hover:border hover:border-[#E2E8F0]' }}"
            x-show="matchesSearch('Manajemen Keuangan')">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.keuangan.*') ? 'text-[#065F46]' : 'text-[#64748B]' }} shrink-0"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
            <span>Manajemen Keuangan</span>
        </a>

        {{-- Sistem group --}}
        <p class="px-2 pt-5 pb-2 text-[10px] tracking-widest text-[#94A3B8] font-medium uppercase">Sistem</p>

        {{-- Setting --}}
        <a href="{{ route('admin.setting.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors mb-0.5 {{ request()->routeIs('admin.setting.*') ? 'bg-white text-[#065F46] font-semibold shadow-sm border border-[#E2E8F0]' : 'text-[#475569] hover:bg-white hover:shadow-sm hover:border hover:border-[#E2E8F0]' }}"
            x-show="matchesSearch('Setting')">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.setting.*') ? 'text-[#065F46]' : 'text-[#64748B]' }} shrink-0"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Setting</span>
        </a>

        {{-- Lihat Website --}}
        <a href="{{ route('home') }}" target="_blank" rel="noopener"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-[#475569] hover:bg-white hover:shadow-sm hover:border hover:border-[#E2E8F0] transition-colors mb-0.5"
            x-show="matchesSearch('Lihat Website')">
            <svg class="w-4 h-4 text-[#64748B] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            <span>Lihat Website</span>
        </a>
    </div>

    {{-- Collapsed icons --}}
    <div class="flex-1 px-2 pb-4 flex flex-col items-center gap-1.5 pt-3" x-show="sidebarCollapsed" x-cloak>
        <a href="{{ route('admin.dashboard') }}"
            class="p-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white border border-[#E2E8F0] shadow-sm text-[#065F46]' : 'text-[#64748B] hover:bg-white hover:shadow-sm' }}"
            title="Dashboard" aria-label="Dashboard">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
            </svg>
        </a>
        <a href="{{ route('admin.galeri.index') }}"
            class="p-2 rounded-lg {{ request()->routeIs('admin.galeri.*') ? 'bg-white border border-[#E2E8F0] shadow-sm text-[#065F46]' : 'text-[#64748B] hover:bg-white hover:shadow-sm' }}"
            title="Kegiatan Foto" aria-label="Kegiatan Foto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
            </svg>
        </a>
        <a href="{{ route('admin.katalog.index') }}"
            class="p-2 rounded-lg {{ request()->routeIs('admin.katalog.*') ? 'bg-white border border-[#E2E8F0] shadow-sm text-[#065F46]' : 'text-[#64748B] hover:bg-white hover:shadow-sm' }}"
            title="Katalog Program" aria-label="Katalog Program">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </a>
        <a href="{{ route('admin.artikel.index') }}"
            class="p-2 rounded-lg {{ request()->routeIs('admin.artikel.*') ? 'bg-white border border-[#E2E8F0] shadow-sm text-[#065F46]' : 'text-[#64748B] hover:bg-white hover:shadow-sm' }}"
            title="Artikel Program" aria-label="Artikel Program">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
            </svg>
        </a>
        <a href="{{ route('admin.donatur.index') }}"
            class="p-2 rounded-lg {{ request()->routeIs('admin.donatur.*') ? 'bg-white border border-[#E2E8F0] shadow-sm text-[#065F46]' : 'text-[#64748B] hover:bg-white hover:shadow-sm' }}"
            title="Donatur" aria-label="Donatur">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
        </a>
        <a href="{{ route('admin.keuangan.index') }}"
            class="p-2 rounded-lg {{ request()->routeIs('admin.keuangan.*') ? 'bg-white border border-[#E2E8F0] shadow-sm text-[#065F46]' : 'text-[#64748B] hover:bg-white hover:shadow-sm' }}"
            title="Manajemen Keuangan" aria-label="Manajemen Keuangan">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
        </a>
    </div>

    {{-- Profile footer --}}
    <div class="border-t border-[#E2E8F0] px-3 py-3 shrink-0" x-data="{ profileOpen: false }">
        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false"
            :aria-expanded="profileOpen.toString()"
            class="w-full flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-white hover:shadow-sm transition-colors focus-visible:ring-2 focus-visible:ring-[#065F46]">
            <div class="relative shrink-0">
                <div class="w-9 h-9 bg-[#065F46] rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                </div>
                <span
                    class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 ring-2 ring-[#F8FAFC] rounded-full"
                    aria-label="Online"></span>
            </div>
            <div class="flex-1 text-left min-w-0" x-show="!sidebarCollapsed">
                <p class="text-sm font-medium text-[#1E293B] truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-[11px] text-[#94A3B8] truncate">{{ auth()->user()->email ?? 'admin@bum.or.id' }}</p>
            </div>
            <svg class="w-4 h-4 text-[#94A3B8] shrink-0" x-show="!sidebarCollapsed" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
            </svg>
        </button>

        {{-- Profile dropdown --}}
        <div x-show="profileOpen" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute bottom-16 left-3 right-3 bg-white rounded-lg border border-[#E2E8F0] shadow-lg py-1 z-10"
            role="menu" x-cloak>
            {{-- TODO: buat route admin.setting --}}
            <a href="#" class="block px-3 py-2 text-sm text-[#475569] hover:bg-[#F1F5F9]"
                role="menuitem">Setting</a>
            <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50"
                    role="menuitem">Keluar</button>
            </form>
        </div>
    </div>
</nav>

<script>
    function sidebarNav() {
        return {
            searchQuery: '',
            matchesSearch(label) {
                if (!this.searchQuery) return true;
                return label.toLowerCase().includes(this.searchQuery.toLowerCase());
            }
        };
    }
</script>
