@extends('layouts.admin')

@section('title', 'Manajemen Keuangan & Transparansi Donasi — Admin YABUN Cabang Kubu Raya')
@section('breadcrumb', 'Manajemen Keuangan')

@section('content')
    <div class="space-y-8" x-data="{ modalOpen: false, displayAmount: '' }" @open-modal-transaksi.window="modalOpen = true">

        <!-- Top Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 text-xs uppercase tracking-wider text-[#00843D] font-bold mb-1">
                    <span>Admin Panel</span>
                    <span>/</span>
                    <span>FR-ADM-07 Manajemen Keuangan</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Manajemen Keuangan & Transparansi Donasi
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Kelola pencatatan pemasukan donasi dan pengeluaran program yayasan secara transparan dan akuntabel.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0 self-start md:self-auto">
                <button type="button" @click="modalOpen = true; displayAmount = ''"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-lg bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-sm whitespace-nowrap">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Catat Pengeluaran</span>
                </button>
                <a href="{{ route('admin.keuangan.pdf', request()->query()) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors shadow-sm whitespace-nowrap">
                    <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Unduh PDF</span>
                </a>
            </div>
        </div>

        <!-- Alert Notification -->
        @if (session('success'))
            <div
                class="mb-8 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-3 text-emerald-900">
                <svg class="w-5 h-5 text-[#00843D] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-medium">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-200 text-red-900">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 3 Kartu Sinkronisasi Dashboard Admin (Live Sync per Program: Masuk, Keluar, Saldo) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @foreach ($summary['categories'] as $cat)
                <div
                    class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-200 dark:border-slate-700/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#00843D] dark:text-emerald-400">
                                {{ $cat['label'] }}
                            </span>
                            <span class="text-[11px] font-semibold text-gray-400 dark:text-slate-400">
                                {{ $cat['percentage'] }}% dari total
                            </span>
                        </div>
                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ $cat['formatted'] }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-0.5">
                            Total Donasi Masuk
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-slate-800 space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500 dark:text-slate-400 font-medium">Pengeluaran:</span>
                            <span class="font-bold text-rose-600 dark:text-rose-400 font-mono">{{ $cat['expense_formatted'] ?? 'Rp 0' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500 dark:text-slate-400 font-medium">Sisa Saldo Kas:</span>
                            <span
                                class="font-bold text-[#00843D] dark:text-emerald-400 font-mono">{{ $cat['balance_formatted'] ?? $cat['formatted'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Card Total Donasi & Saldo Keseluruhan -->
            <div
                class="lg:col-span-1 bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-200 dark:border-slate-700/80 shadow-sm flex flex-col justify-between h-fit">
                <div>
                    <!-- Badge Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/60 text-[#00843D] dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#00843D] dark:bg-emerald-400 animate-pulse"></span>
                            <span>Saldo Donasi Yayasan</span>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Ringkasan Kas & Saldo
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Akumulasi perolehan donasi masuk, pengeluaran program, dan sisa saldo kas aktif yayasan.
                    </p>

                    <!-- Sisa Saldo Kas Bersih -->
                    <div
                        class="mt-5 p-4 rounded-xl bg-gradient-to-br from-emerald-50/70 via-white to-emerald-50/40 dark:from-slate-900/90 dark:via-slate-800 dark:to-emerald-950/40 border border-emerald-100 dark:border-emerald-800/40 shadow-xs">
                        <div class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Sisa Saldo Kas Bersih
                        </div>
                        <div class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight mt-1">
                            {{ $summary['balance_formatted'] ?? 'Rp 0' }}
                        </div>
                        <div class="text-xs font-semibold text-[#00843D] dark:text-emerald-400 mt-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ $summary['balance_unit'] ?? 'Rupiah' }} &bull; Saldo Kas Siap Pakai</span>
                        </div>
                    </div>

                    <!-- Rekapitulasi Masuk vs Keluar -->
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/50 border border-emerald-100 dark:border-emerald-800/50">
                            <div class="text-[11px] font-bold text-emerald-800 dark:text-emerald-300 uppercase">Donasi Masuk</div>
                            <div class="text-base font-extrabold text-[#00843D] dark:text-emerald-400 mt-0.5">
                                {{ $summary['grand_total_formatted'] ?? 'Rp 0' }}
                            </div>
                            <div class="text-[10px] text-gray-500 dark:text-slate-400 mt-0.5">Akumulasi donasi</div>
                        </div>
                        <div class="p-3 rounded-xl bg-rose-50/50 dark:bg-rose-950/50 border border-rose-100 dark:border-rose-800/50">
                            <div class="text-[11px] font-bold text-rose-800 dark:text-rose-300 uppercase">Dana Tersalurkan</div>
                            <div class="text-base font-extrabold text-rose-600 dark:text-rose-400 mt-0.5">
                                {{ $summary['total_expense_formatted'] ?? 'Rp 0' }}
                            </div>
                            <div class="text-[10px] text-gray-500 dark:text-slate-400 mt-0.5">Pengeluaran program</div>
                        </div>
                    </div>

                    <!-- Distribusi Persentase Program Bar -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-slate-300 mb-2">
                            <span>Distribusi Pemasukan Program</span>
                            <span class="text-[11px] text-gray-400 dark:text-slate-400 font-normal">Proporsi Akumulasi</span>
                        </div>
                        <div class="h-2.5 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden flex">
                            <div style="width: {{ $summary['categories']['jumat_berkah']['percentage'] ?? 0 }}%"
                                class="bg-[#00843D] transition-all duration-300"
                                title="Jumat Berkah: {{ $summary['categories']['jumat_berkah']['percentage'] ?? 0 }}%">
                            </div>
                            <div style="width: {{ $summary['categories']['donasi_bantuan']['percentage'] ?? 0 }}%"
                                class="bg-amber-500 transition-all duration-300"
                                title="Donasi Bantuan: {{ $summary['categories']['donasi_bantuan']['percentage'] ?? 0 }}%">
                            </div>
                            <div style="width: {{ $summary['categories']['pembangunan_pondok_tahfidz']['percentage'] ?? 0 }}%"
                                class="bg-teal-600 transition-all duration-300"
                                title="Pembangunan: {{ $summary['categories']['pembangunan_pondok_tahfidz']['percentage'] ?? 0 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Nominal Tiap Kategori -->
                    <div class="mt-4 space-y-2.5 divide-y divide-gray-100 dark:divide-slate-800">
                        <div class="pt-2 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#00843D] shrink-0"></span>
                                <span class="font-medium text-gray-700 dark:text-slate-300">Jumat Berkah</span>
                            </div>
                            <div class="text-right font-semibold text-gray-900 dark:text-white">
                                {{ $summary['categories']['jumat_berkah']['formatted'] }}
                                <span
                                    class="text-[11px] text-gray-400 dark:text-slate-400 font-normal ml-1">({{ $summary['categories']['jumat_berkah']['percentage'] }}%)</span>
                            </div>
                        </div>

                        <div class="pt-2.5 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                                <span class="font-medium text-gray-700 dark:text-slate-300">Donasi Bantuan</span>
                            </div>
                            <div class="text-right font-semibold text-gray-900 dark:text-white">
                                {{ $summary['categories']['donasi_bantuan']['formatted'] }}
                                <span
                                    class="text-[11px] text-gray-400 dark:text-slate-400 font-normal ml-1">({{ $summary['categories']['donasi_bantuan']['percentage'] }}%)</span>
                            </div>
                        </div>

                        <div class="pt-2.5 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-teal-600 shrink-0"></span>
                                <span class="font-medium text-gray-700 dark:text-slate-300">Pembangunan Tahfidz</span>
                            </div>
                            <div class="text-right font-semibold text-gray-900 dark:text-white">
                                {{ $summary['categories']['pembangunan_pondok_tahfidz']['formatted'] }}
                                <span
                                    class="text-[11px] text-gray-400 dark:text-slate-400 font-normal ml-1">({{ $summary['categories']['pembangunan_pondok_tahfidz']['percentage'] }}%)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mini Statistik: 4 Kolom -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-6 pt-5 border-t border-gray-100 dark:border-slate-800">
                        <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-slate-900/60 border border-gray-100 dark:border-slate-800">
                            <div class="text-[10px] text-gray-500 dark:text-slate-400 font-medium">Donatur</div>
                            <div class="text-sm font-extrabold text-gray-900 dark:text-white mt-0.5">
                                {{ number_format($totalDonorsCount ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-slate-900/60 border border-gray-100 dark:border-slate-800">
                            <div class="text-[10px] text-gray-500 dark:text-slate-400 font-medium">Semua Tx</div>
                            <div class="text-sm font-extrabold text-gray-900 dark:text-white mt-0.5">
                                {{ number_format($totalTransactionsCount ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-emerald-50/60 dark:bg-emerald-950/50 border border-emerald-100 dark:border-emerald-800/50">
                            <div class="text-[10px] text-[#00843D] dark:text-emerald-400 font-medium">Masuk</div>
                            <div class="text-sm font-extrabold text-[#00843D] dark:text-emerald-400 mt-0.5">
                                {{ number_format($totalIncomeCount ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-rose-50/60 dark:bg-rose-950/50 border border-rose-100 dark:border-rose-800/50">
                            <div class="text-[10px] text-rose-700 dark:text-rose-400 font-medium">Keluar</div>
                            <div class="text-sm font-extrabold text-rose-700 dark:text-rose-400 mt-0.5">
                                {{ number_format($totalExpenseCount ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Card Action: Pembaruan Terakhir -->
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800">
                    <p class="text-[11px] text-gray-400 dark:text-slate-500 text-center">
                        Pembaruan Terakhir: {{ $summary['latest_date_formatted'] ?? 'Belum ada transaksi' }}
                    </p>
                </div>
            </div>

            <!-- Tabel Riwayat Transaksi & Filter Kategori & Jenis -->
            <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-200 dark:border-slate-700/80 shadow-sm">
                <div class="flex flex-col gap-4 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            Riwayat Transaksi Keuangan
                        </h2>

                        <!-- Filter Jenis: Semua, Pemasukan, Pengeluaran -->
                        <div class="flex items-center gap-1.5 bg-gray-100 dark:bg-slate-900/80 p-1 rounded-lg text-xs font-semibold">
                            <a href="{{ request()->fullUrlWithQuery(['jenis' => null]) }}"
                                class="px-3 py-1 rounded-md transition-colors {{ empty($typeFilter) ? 'bg-white dark:bg-slate-800 text-gray-900 dark:text-white shadow-xs' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white' }}">
                                Semua ({{ $totalTransactionsCount }})
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['jenis' => 'pemasukan']) }}"
                                class="px-3 py-1 rounded-md transition-colors {{ $typeFilter === 'pemasukan' ? 'bg-[#00843D] text-white shadow-xs' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white' }}">
                                Pemasukan ({{ $totalIncomeCount }})
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['jenis' => 'pengeluaran']) }}"
                                class="px-3 py-1 rounded-md transition-colors {{ $typeFilter === 'pengeluaran' ? 'bg-rose-600 text-white shadow-xs' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white' }}">
                                Pengeluaran ({{ $totalExpenseCount }})
                            </a>
                        </div>
                    </div>

                    <!-- Filter Kategori Dropdown / Pill -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs text-gray-500 dark:text-slate-400 font-medium">Program:</span>
                        <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}"
                            class="px-3 py-1 text-xs rounded-full border transition-colors {{ empty($categoryFilter) ? 'bg-[#00843D] text-white border-[#00843D]' : 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-700 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                            Semua Program
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['kategori' => 'jumat_berkah']) }}"
                            class="px-3 py-1 text-xs rounded-full border transition-colors {{ $categoryFilter === 'jumat_berkah' ? 'bg-[#00843D] text-white border-[#00843D]' : 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-700 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                            Jumat Berkah
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['kategori' => 'donasi_bantuan']) }}"
                            class="px-3 py-1 text-xs rounded-full border transition-colors {{ $categoryFilter === 'donasi_bantuan' ? 'bg-[#00843D] text-white border-[#00843D]' : 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-700 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                            Donasi Bantuan
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['kategori' => 'pembangunan_pondok_tahfidz']) }}"
                            class="px-3 py-1 text-xs rounded-full border transition-colors {{ $categoryFilter === 'pembangunan_pondok_tahfidz' ? 'bg-[#00843D] text-white border-[#00843D]' : 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-700 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                            Pembangunan Tahfidz
                        </a>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm min-w-[640px]">
                        <thead>
                            <tr
                                class="border-b border-gray-200 dark:border-slate-800 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider bg-gray-50/50 dark:bg-slate-900/60">
                                <th class="py-3 px-3 whitespace-nowrap">Tanggal</th>
                                <th class="py-3 px-3 whitespace-nowrap">Kategori</th>
                                <th class="py-3 px-3 whitespace-nowrap">Pihak / Donatur</th>
                                <th class="py-3 px-3 whitespace-nowrap">Jenis</th>
                                <th class="py-3 px-3 text-right whitespace-nowrap">Nominal</th>
                                <th class="py-3 px-3 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                            @forelse ($transactions as $t)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-3 px-3 whitespace-nowrap text-gray-600 dark:text-slate-300 font-medium">
                                        {{ $t->transaction_date ? \Carbon\Carbon::parse($t->transaction_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @if ($t->category === 'jumat_berkah')
                                            <span
                                                class="inline-block px-2 py-0.5 text-xs font-semibold rounded bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-transparent dark:border-emerald-800/50">Jumat
                                                Berkah</span>
                                        @elseif ($t->category === 'donasi_bantuan')
                                            <span
                                                class="inline-block px-2 py-0.5 text-xs font-semibold rounded bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-transparent dark:border-amber-800/50">Donasi
                                                Bantuan</span>
                                        @else
                                            <span
                                                class="inline-block px-2 py-0.5 text-xs font-semibold rounded bg-teal-100 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-transparent dark:border-teal-800/50">Pembangunan
                                                Tahfidz</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3">
                                        @if ($t->type === 'pemasukan')
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $t->donor?->nama_donatur ?? 'Donatur Valid' }}
                                            </div>
                                            @if ($t->description)
                                                <div class="text-[11px] text-gray-500 dark:text-slate-400 line-clamp-1 mt-0.5">
                                                    {{ $t->description }}
                                                </div>
                                            @endif
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 border border-transparent dark:border-slate-700">
                                                Internal Yayasan
                                            </span>
                                            <div class="text-xs text-gray-600 dark:text-slate-300 mt-0.5 line-clamp-2"
                                                title="{{ $t->description }}">
                                                {{ $t->description ?: 'Pengeluaran keperluan program' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @if ($t->type === 'pemasukan')
                                            <span
                                                class="inline-flex items-center gap-1 text-xs font-bold text-[#00843D] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded border border-emerald-100 dark:border-emerald-800/60 uppercase">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                                Masuk
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 text-xs font-bold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 px-2 py-0.5 rounded border border-rose-100 dark:border-rose-800/60 uppercase">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                </svg>
                                                Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="py-3 px-3 text-right font-bold tabular-nums whitespace-nowrap {{ $t->type === 'pemasukan' ? 'text-[#00843D] dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $t->type === 'pemasukan' ? '+ ' : '- ' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-3 text-center whitespace-nowrap">
                                        <form action="{{ route('admin.keuangan.destroy', $t->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus transaksi ini? Data statistik transparansi dan saldo kas akan otomatis disinkronkan kembali.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-red-600 dark:text-rose-400 hover:text-red-800 dark:hover:text-rose-300 font-semibold p-1 hover:bg-red-50 dark:hover:bg-rose-950/40 rounded transition-colors"
                                                title="Hapus transaksi">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-400 dark:text-slate-500">
                                        <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-slate-600 mb-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="font-medium text-gray-600 dark:text-slate-300">Belum ada transaksi pada filter ini</p>
                                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Gunakan tombol "Catat Transaksi Baru" untuk
                                            menambah mutasi kas masuk atau keluar.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>
        <!-- Modal Catat Pengeluaran Program (Tanpa Donatur, Pemisah Koma Otomatis) -->
        <div x-show="modalOpen" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
            @keydown.escape.window="modalOpen = false">
            <div @click.away="modalOpen = false"
                class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-200 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center bg-rose-50 text-rose-600 border border-rose-100 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 leading-tight">Catat Pengeluaran Program</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Pencatatan realisasi dana keluar internal yayasan</p>
                        </div>
                    </div>
                    <button @click="modalOpen = false" type="button"
                        class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors"
                        aria-label="Tutup modal">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Info Banner Alur Bisnis -->
                <div
                    class="p-3 bg-amber-50/80 border border-amber-200/90 rounded-xl text-xs text-amber-900 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="leading-relaxed">
                        <span class="font-bold">Alur Keuangan:</span> Donatur <strong>tidak terlibat</strong> dalam
                        pengeluaran kas internal yayasan. Donasi masuk otomatis tercatat lewat menu <strong>Manajemen
                            Donatur</strong>.
                    </div>
                </div>

                <form id="form-catat-pengeluaran" action="{{ route('admin.keuangan.store') }}" method="POST"
                    class="space-y-4">
                    @csrf
                    <!-- Transaksi Khusus Pengeluaran -->
                    <input type="hidden" name="type" value="pengeluaran">

                    <div>
                        <label for="category"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Kategori Program <span class="text-rose-500">*</span>
                        </label>
                        <select id="category" name="category" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm py-2 px-3 border bg-white font-medium">
                            <option value="jumat_berkah">Jumat Berkah</option>
                            <option value="donasi_bantuan">Donasi Bantuan</option>
                            <option value="pembangunan_pondok_tahfidz">Donasi Pembangunan Pondok Tahfidz</option>
                        </select>
                        <p class="text-[11px] text-gray-500 mt-1">
                            Pengeluaran dialokasikan ke pos program terkait untuk memotong saldo kas program.
                        </p>
                    </div>

                    <div>
                        <label for="amount"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Jumlah Nominal Pengeluaran <span class="text-rose-600">*</span>
                        </label>
                        <div
                            class="flex rounded-lg shadow-sm border border-gray-300 focus-within:border-rose-500 focus-within:ring-1 focus-within:ring-rose-500 overflow-hidden bg-white">
                            <span
                                class="inline-flex items-center px-3.5 bg-gray-50 text-gray-600 font-bold text-sm border-r border-gray-200 select-none">
                                Rp
                            </span>
                            <input type="text" id="amount" name="amount" x-model="displayAmount"
                                @input="
                                    let clean = $event.target.value.replace(/[^0-9]/g, '');
                                    displayAmount = clean ? new Intl.NumberFormat('en-US').format(clean) : '';
                                "
                                placeholder="6,000,000" required autocomplete="off"
                                class="block w-full border-0 py-2.5 px-3 text-base font-bold text-gray-900 placeholder-gray-400 focus:ring-0 font-mono">
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1 flex items-center justify-between">
                            <span>Format tanda koma otomatis saat mengetik nominal.</span>
                            <span x-show="displayAmount" class="font-bold text-rose-600 font-mono text-xs"
                                x-text="'Rp ' + displayAmount"></span>
                        </p>
                    </div>

                    <div>
                        <label for="transaction_date"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Tanggal Transaksi Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="transaction_date" name="transaction_date" required
                            value="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm py-2 px-3 border">
                    </div>

                    <!-- Rincian Keperluan Pengeluaran (Wajib) -->
                    <div>
                        <label for="description"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Rincian Keperluan Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="3" required
                            placeholder="Contoh: Belanja 200 box nasi berkah, pembelian 50 sak semen pondok, santunan tunai anak yatim..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm py-2 px-3 border"></textarea>
                        <p class="text-[11px] text-gray-500 mt-1">
                            Donatur tidak terlibat. Mohon tuliskan rincian kebutuhan belanja atau penyaluran yayasan di
                            sini.
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="modalOpen = false"
                            class="px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="py-2.5 px-5 text-white font-bold rounded-lg shadow-sm transition-colors text-xs sm:text-sm flex items-center justify-center gap-1.5 bg-rose-600 hover:bg-rose-700">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Pengeluaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
