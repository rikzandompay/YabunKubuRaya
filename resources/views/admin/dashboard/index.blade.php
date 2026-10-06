@extends('layouts.admin')

@section('title', 'Dashboard — Admin Bakti Umat Nusantara Pontianak')
@section('breadcrumb', 'Dashboard')

@section('content')
    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#1E293B]">
                Assalamu'alaikum, {{ auth()->user()->name ?? 'Admin' }}
            </h1>
            <p class="mt-1 text-sm text-[#64748B]">
                Ringkasan donasi, donatur, artikel, dan kegiatan terbaru Yayasan Bakti Umat Nusantara Pontianak.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            {{-- Period dropdown --}}
            <x-admin.dropdown :options="['Hari Ini', 'Minggu Ini', 'Minggu Lalu', 'Bulan Ini', 'Tahun Ini']" selected="Minggu Lalu">
                <x-slot:trigger>
                    <svg class="w-4 h-4 text-[#64748B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </x-slot:trigger>
            </x-admin.dropdown>

            {{-- Kebab --}}
            <div class="relative" x-data="{ menuOpen: false }">
                <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" :aria-expanded="menuOpen.toString()"
                    class="p-2 rounded-lg border border-[#E2E8F0] hover:bg-white focus-visible:ring-2 focus-visible:ring-[#065F46]"
                    aria-label="Opsi halaman">
                    <svg class="w-4 h-4 text-[#64748B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                    </svg>
                </button>
                <div x-show="menuOpen" x-transition
                    class="absolute right-0 mt-1 w-36 bg-white border border-[#E2E8F0] rounded-lg shadow-lg py-1 z-20"
                    role="menu" x-cloak>
                    {{-- TODO: implementasi cetak dan ekspor --}}
                    <button onclick="alert('Segera hadir')"
                        class="w-full text-left px-3 py-2 text-sm text-[#475569] hover:bg-[#F1F5F9]"
                        role="menuitem">Cetak</button>
                    <button onclick="alert('Segera hadir')"
                        class="w-full text-left px-3 py-2 text-sm text-[#475569] hover:bg-[#F1F5F9]"
                        role="menuitem">Ekspor</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid: 4 KPI Cards (Sejajar Halaman) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach ($stats as $stat)
            <x-admin.stat-card :title="$stat['title']" :icon="$stat['icon']" :value="$stat['value']" :delta="$stat['delta']" :positive="$stat['positive']"
                :sparkline="$stat['sparkline']" :sparkline-area="$stat['sparkline_area']" />
        @endforeach
    </div>

    {{-- Grid: Grafik Statistik Donasi (Kiri 2/3) + Donatur Terbanyak (Kanan 1/3) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 items-stretch">
        <div class="lg:col-span-2 min-w-0">
            <x-admin.chart-bar :chart-data="$chartData" />
        </div>
        <div class="lg:col-span-1 min-w-0">
            <x-admin.donor-list :donors="$topDonors" :total-donors-count="$totalDonorsCount ?? null" />
        </div>
    </div>

    {{-- Donation table (full width) --}}
    <div class="w-full min-w-0">
        <x-admin.donation-table :donations="$donations" />
    </div>
@endsection
