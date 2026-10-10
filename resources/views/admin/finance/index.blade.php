@extends('layouts.admin')

@section('title', 'Manajemen Keuangan & Transparansi Donasi — Admin YABUN Cabang Kubu Raya')
@section('breadcrumb', 'Manajemen Keuangan')

@section('content')
    <div class="space-y-8" x-data="{ modalOpen: false }" @open-modal-transaksi.window="modalOpen = true">

        <!-- Top Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 text-xs uppercase tracking-wider text-[#00843D] font-bold mb-1">
                    <span>Admin Panel</span>
                    <span>/</span>
                    <span>FR-ADM-07 Manajemen Keuangan</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                    Manajemen Keuangan & Transparansi Donasi
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Data transaksi yang dicatat di sini otomatis tersinkron dengan angka pada section Transparansi Donasi di
                    Landing Page.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.keuangan.pdf', request()->query()) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-[#00843D] text-white hover:bg-[#006B31] transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Unduh Laporan PDF</span>
                </a>
                <a href="{{ route('home') }}#transparansi-donasi"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-[#00843D]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Lihat di Landing Page</span>
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

        <!-- 3 Kartu Sinkronisasi Dashboard Admin (Live Sync dengan Section Transparansi) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @foreach ($summary['categories'] as $cat)
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm relative overflow-hidden">
                    <div class="text-xs font-bold uppercase tracking-wider text-[#00843D] mb-2">
                        {{ $cat['label'] }}
                    </div>
                    <div class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        {{ $cat['formatted'] }}
                    </div>
                    <div class="text-sm text-gray-500 font-medium mt-1">
                        {{ $cat['unit'] }}
                    </div>
                    <div class="text-xs text-gray-400 mt-4 pt-3 border-t border-gray-100">
                        {{ $cat['description'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Card Total Donasi Keseluruhan -->
            <div
                class="lg:col-span-1 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between h-fit">
                <div>
                    <!-- Badge Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-[#00843D] border border-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#00843D] animate-pulse"></span>
                            <span>Donasi Terkini</span>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                        Total Donasi Keseluruhan
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                        Akumulasi seluruh perolehan donasi masuk yang tercatat di sistem dari semua program aktif yayasan.
                    </p>

                    <!-- Nominal Utama -->
                    <div
                        class="mt-5 p-4 rounded-xl bg-gradient-to-br from-emerald-50/70 via-white to-emerald-50/40 border border-emerald-100">
                        <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                            Akumulasi Donasi Masuk
                        </div>
                        <div class="text-3xl font-extrabold text-gray-900 tracking-tight mt-1">
                            {{ $summary['grand_total_formatted'] ?? 'Rp 0' }}
                        </div>
                        <div class="text-xs font-semibold text-[#00843D] mt-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ $summary['grand_total_unit'] ?? 'Rupiah' }} &bull; Transparansi Publik Aktif</span>
                        </div>
                    </div>

                    <!-- Distribusi Persentase Program Bar -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 mb-2">
                            <span>Distribusi Program</span>
                            <span class="text-[11px] text-gray-400 font-normal">Proporsi Akumulasi</span>
                        </div>
                        <div class="h-2.5 w-full bg-gray-100 rounded-full overflow-hidden flex">
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
                    <div class="mt-4 space-y-2.5 divide-y divide-gray-100">
                        <div class="pt-2 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#00843D] shrink-0"></span>
                                <span class="font-medium text-gray-700">Jumat Berkah</span>
                            </div>
                            <div class="text-right font-semibold text-gray-900">
                                {{ $summary['categories']['jumat_berkah']['formatted'] }}
                                <span
                                    class="text-[11px] text-gray-400 font-normal ml-1">({{ $summary['categories']['jumat_berkah']['percentage'] }}%)</span>
                            </div>
                        </div>

                        <div class="pt-2.5 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                                <span class="font-medium text-gray-700">Donasi Bantuan</span>
                            </div>
                            <div class="text-right font-semibold text-gray-900">
                                {{ $summary['categories']['donasi_bantuan']['formatted'] }}
                                <span
                                    class="text-[11px] text-gray-400 font-normal ml-1">({{ $summary['categories']['donasi_bantuan']['percentage'] }}%)</span>
                            </div>
                        </div>

                        <div class="pt-2.5 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-teal-600 shrink-0"></span>
                                <span class="font-medium text-gray-700">Pembangunan Tahfidz</span>
                            </div>
                            <div class="text-right font-semibold text-gray-900">
                                {{ $summary['categories']['pembangunan_pondok_tahfidz']['formatted'] }}
                                <span
                                    class="text-[11px] text-gray-400 font-normal ml-1">({{ $summary['categories']['pembangunan_pondok_tahfidz']['percentage'] }}%)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mini Statistik: Total Donatur & Total Transaksi -->
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-5 border-t border-gray-100">
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-[11px] text-gray-500 font-medium">Donatur Terlibat</div>
                            <div class="text-base font-extrabold text-gray-900 mt-0.5">
                                {{ number_format($totalDonorsCount ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="text-[10px] text-gray-400">Donatur Terdaftar</div>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-[11px] text-gray-500 font-medium">Transaksi Masuk</div>
                            <div class="text-base font-extrabold text-gray-900 mt-0.5">
                                {{ number_format($totalTransactionsCount ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="text-[10px] text-gray-400">Total Transaksi</div>
                        </div>
                    </div>
                </div>

                <!-- Footer Card Action: Pembaruan Terakhir -->
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <p class="text-[11px] text-gray-400 text-center">
                        Pembaruan Terakhir: {{ $summary['latest_date_formatted'] ?? 'Belum ada transaksi' }}
                    </p>
                </div>
            </div>

            <!-- Tabel Riwayat Transaksi & Filter Kategori -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h2 class="text-lg font-bold text-gray-900">
                        Riwayat Transaksi Keuangan
                    </h2>

                    <!-- Filter Kategori Dropdown / Pill -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 font-medium">Filter:</span>
                        <a href="{{ route('admin.keuangan.index') }}"
                            class="px-3 py-1 text-xs rounded-full border transition-colors {{ empty($categoryFilter) ? 'bg-[#00843D] text-white border-[#00843D]' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }}">
                            Semua
                        </a>
                        <a href="{{ route('admin.keuangan.index', ['kategori' => 'jumat_berkah']) }}"
                            class="px-3 py-1 text-xs rounded-full border transition-colors {{ $categoryFilter === 'jumat_berkah' ? 'bg-[#00843D] text-white border-[#00843D]' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }}">
                            Jumat Berkah
                        </a>
                        <a href="{{ route('admin.keuangan.index', ['kategori' => 'donasi_bantuan']) }}"
                            class="px-3 py-1 text-xs rounded-full border transition-colors {{ $categoryFilter === 'donasi_bantuan' ? 'bg-[#00843D] text-white border-[#00843D]' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }}">
                            Donasi Bantuan
                        </a>
                        <a href="{{ route('admin.keuangan.index', ['kategori' => 'pembangunan_pondok_tahfidz']) }}"
                            class="px-3 py-1 text-xs rounded-full border transition-colors {{ $categoryFilter === 'pembangunan_pondok_tahfidz' ? 'bg-[#00843D] text-white border-[#00843D]' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }}">
                            Pembangunan
                        </a>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm min-w-[640px]">
                        <thead>
                            <tr
                                class="border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50/50">
                                <th class="py-3 px-3 whitespace-nowrap">Tanggal</th>
                                <th class="py-3 px-3 whitespace-nowrap">Kategori</th>
                                <th class="py-3 px-3 whitespace-nowrap">Donatur</th>
                                <th class="py-3 px-3 whitespace-nowrap">Jenis</th>
                                <th class="py-3 px-3 text-right whitespace-nowrap">Nominal</th>
                                <th class="py-3 px-3 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($transactions as $t)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-3 px-3 whitespace-nowrap text-gray-600 font-medium">
                                        {{ $t->transaction_date ? \Carbon\Carbon::parse($t->transaction_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @if ($t->category === 'jumat_berkah')
                                            <span
                                                class="inline-block px-2 py-0.5 text-xs font-semibold rounded bg-emerald-100 text-emerald-800">Jumat
                                                Berkah</span>
                                        @elseif ($t->category === 'donasi_bantuan')
                                            <span
                                                class="inline-block px-2 py-0.5 text-xs font-semibold rounded bg-amber-100 text-amber-800">Donasi
                                                Bantuan</span>
                                        @else
                                            <span
                                                class="inline-block px-2 py-0.5 text-xs font-semibold rounded bg-teal-100 text-teal-800">Pembangunan
                                                Tahfidz</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-gray-800 font-medium">
                                        {{ $t->donor?->nama_donatur ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @if ($t->type === 'pemasukan')
                                            <span class="text-xs font-bold text-[#00843D] uppercase">Pemasukan</span>
                                        @else
                                            <span class="text-xs font-bold text-red-600 uppercase">Pengeluaran</span>
                                        @endif
                                    </td>
                                    <td
                                        class="py-3 px-3 text-right font-bold text-gray-900 tabular-nums whitespace-nowrap">
                                        Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-3 text-center whitespace-nowrap">
                                        <form action="{{ route('admin.keuangan.destroy', $t->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus transaksi ini? Data statistik transparansi akan otomatis diperbarui.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-red-600 hover:text-red-800 font-semibold p-1 hover:bg-red-50 rounded transition-colors"
                                                title="Hapus transaksi">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-400">
                                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="font-medium text-gray-600">Belum ada transaksi tercatat</p>
                                        <p class="text-xs text-gray-400 mt-1">Semua angka pada section Transparansi Donasi
                                            landing page akan menampilkan Rp 0.</p>
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

        <!-- Modal Catat Transaksi Baru -->
        <div x-show="modalOpen" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
            @keydown.escape.window="modalOpen = false">
            <div @click.away="modalOpen = false"
                class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-200 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#00843D] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Catat Transaksi Baru</h3>
                            <p class="text-xs text-gray-500">Pencatatan mutasi kas donasi masuk & pengeluaran</p>
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

                <form id="form-catat-transaksi" action="{{ route('admin.keuangan.store') }}" method="POST"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label for="type"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Jenis Transaksi <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#00843D] focus:ring-[#00843D] text-sm py-2 px-3 border bg-white">
                            <option value="pemasukan" selected>Pemasukan (Donasi / Infaq Masuk)</option>
                            <option value="pengeluaran">Pengeluaran (Operasional / Penyaluran)</option>
                        </select>
                    </div>

                    <div>
                        <label for="category"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Kategori Program <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#00843D] focus:ring-[#00843D] text-sm py-2 px-3 border bg-white">
                            <option value="jumat_berkah">Jumat Berkah</option>
                            <option value="donasi_bantuan">Donasi Bantuan</option>
                            <option value="pembangunan_pondok_tahfidz">Donasi Pembangunan Pondok Tahfidz</option>
                        </select>
                        <p class="text-[11px] text-gray-500 mt-1">
                            Pemasukan dari 3 kategori ini otomatis terhitung ke statistik Transparansi Donasi.
                        </p>
                    </div>

                    <div>
                        <label for="amount"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Jumlah Nominal (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="1000" min="1000" id="amount" name="amount" required
                            placeholder="Contoh: 1500000"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#00843D] focus:ring-[#00843D] text-sm py-2 px-3 border">
                    </div>

                    <div>
                        <label for="transaction_date"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Tanggal Transaksi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="transaction_date" name="transaction_date" required
                            value="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#00843D] focus:ring-[#00843D] text-sm py-2 px-3 border">
                    </div>

                    <div>
                        <label for="donor_id"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Donatur <span class="text-red-500">*</span>
                        </label>
                        <select id="donor_id" name="donor_id" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#00843D] focus:ring-[#00843D] text-sm py-2 px-3 border bg-white">
                            <option value="" disabled selected>-- Pilih Donatur Terdaftar --</option>
                            @foreach ($donors as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_donatur }}
                                    ({{ ucfirst($d->tipe_donatur) }}{{ $d->nomor_hp ? ' - ' . $d->nomor_hp : '' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-gray-500 mt-1">
                            Setiap transaksi harus terhubung dengan data donatur terdaftar.
                        </p>
                    </div>

                    <div>
                        <label for="description"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">
                            Keterangan Transaksi
                        </label>
                        <textarea id="description" name="description" rows="3" placeholder="Uraian donasi atau peruntukan dana..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#00843D] focus:ring-[#00843D] text-sm py-2 px-3 border"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button" @click="modalOpen = false"
                            class="px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="py-2 px-4 bg-[#00843D] hover:bg-[#006B31] text-white font-semibold rounded-lg shadow-sm transition-colors text-xs flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan & Sinkronkan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
