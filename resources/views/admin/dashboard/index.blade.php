@extends('layouts.admin')

@section('title', 'Dashboard — Admin Bakti Umat Nusantara Cabang Kubu Raya')
@section('breadcrumb', 'Dashboard')

@section('content')
    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div>
            <h1 class="text-lg sm:text-2xl font-bold text-[#1E293B] dark:text-white">
                Assalamu'alaikum, {{ auth()->user()->name ?? 'Admin' }}
            </h1>
            <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-[#64748B] dark:text-slate-400">
                Ringkasan donasi, donatur, artikel, dan kegiatan terbaru Yayasan Bakti Umat Nusantara Cabang Kubu Raya.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            {{-- Period dropdown --}}
            <x-admin.dropdown
                :options="$periodOptions"
                :selected="$currentPeriodLabel"
                :selected-key="$currentPeriodKey"
            >
                <x-slot:trigger>
                    <svg class="w-4 h-4 text-[#64748B] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </x-slot:trigger>
            </x-admin.dropdown>

            {{-- Kebab menu: Cetak & Ekspor --}}
            <div class="relative" x-data="{ menuOpen: false }">
                <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" :aria-expanded="menuOpen.toString()"
                    class="p-2 rounded-lg border border-[#E2E8F0] dark:border-slate-700 bg-white dark:bg-[#1E293B] hover:bg-[#F8FAFC] dark:hover:bg-slate-800 focus-visible:ring-2 focus-visible:ring-[#065F46] cursor-pointer transition-colors"
                    aria-label="Opsi halaman">
                    <svg class="w-4 h-4 text-[#64748B] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                    </svg>
                </button>
                <div x-show="menuOpen" x-transition
                    class="absolute right-0 mt-1 w-48 bg-white dark:bg-[#1E293B] border border-[#E2E8F0] dark:border-slate-700 rounded-lg shadow-lg py-1 z-20"
                    role="menu" x-cloak>
                    <a href="{{ route('admin.keuangan.pdf', ['periode' => $currentPeriodKey]) }}"
                        class="flex items-center gap-2.5 px-3.5 py-2 text-sm text-[#334155] dark:text-slate-200 hover:bg-[#F1F5F9] dark:hover:bg-slate-800 transition-colors"
                        role="menuitem">
                        <svg class="w-4 h-4 text-[#64748B] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Unduh Laporan (PDF)</span>
                    </a>
                    <a href="{{ route('admin.dashboard.export', ['periode' => $currentPeriodKey]) }}"
                        class="flex items-center gap-2.5 px-3.5 py-2 text-sm text-[#334155] dark:text-slate-200 hover:bg-[#F1F5F9] dark:hover:bg-slate-800 transition-colors"
                        role="menuitem">
                        <svg class="w-4 h-4 text-[#64748B] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Ekspor Data (CSV)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid: 4 KPI Cards (2 Kolom Kompak di Mobile, 4 Kolom di Desktop) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4 mb-4 sm:mb-6">
        @foreach ($stats as $stat)
            <x-admin.stat-card
                :title="$stat['title']"
                :icon="$stat['icon']"
                :value="$stat['value']"
                :delta="$stat['delta']"
                :positive="$stat['positive']"
                :is-neutral="$stat['is_neutral'] ?? false"
                :comparison="$stat['comparison'] ?? 'vs minggu lalu'"
                :sparkline="$stat['sparkline']"
                :sparkline-area="$stat['sparkline_area']"
            />
        @endforeach
    </div>

    {{-- Grid Row 2: Grafik Batang Donasi (Kiri 2/3) + Donut Chart Komposisi Kategori (Kanan 1/3) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6 items-stretch w-full max-w-full min-w-0">
        <div class="lg:col-span-2 min-w-0 w-full max-w-full">
            <x-admin.chart-bar :chart-data="$chartData" />
        </div>
        <div class="lg:col-span-1 min-w-0 w-full max-w-full">
            <x-admin.chart-donut :donut-data="$donutData" />
        </div>
    </div>

    {{-- Grid Row 3: Donatur Terbanyak (Kiri 1/3) + Monitoring Donasi Masuk (Kanan 2/3) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6 items-stretch w-full max-w-full min-w-0">
        <div class="lg:col-span-1 min-w-0 w-full max-w-full">
            <x-admin.donor-list :donors="$topDonors" :total-donors-count="$totalDonorsCount ?? null" />
        </div>
        <div class="lg:col-span-2 min-w-0 w-full max-w-full">
            <x-admin.donation-table :donations="$donations" />
        </div>
    </div>
@endsection
