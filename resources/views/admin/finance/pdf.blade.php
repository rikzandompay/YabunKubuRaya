<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan & Transparansi Donasi — {{ $settings['nama_yayasan'] ?? 'Yayasan Bakti Umat Nusantara Cabang Pontianak' }}</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/logo-bun.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: #000 !important;
                font-size: 11pt;
            }
            .page-break {
                page-break-after: always;
            }
            .print-table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-card {
                border: 1px solid #cbd5e1 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased min-h-screen">

    <!-- Top Action Bar (Hanya tampil di layar, tersembunyi saat dicetak) -->
    <div class="no-print sticky top-0 z-50 bg-[#0B192C] text-white py-3.5 px-6 shadow-md">
        <div class="max-w-4xl mx-auto flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-600/30 text-emerald-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-bold">Pratinjau Dokumen Laporan Keuangan (PDF)</h2>
                    <p class="text-xs text-gray-300">Format siap cetak A4. Pilih "Save as PDF" di menu cetak browser untuk menyimpan file PDF.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#00843D] hover:bg-[#006B31] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak / Simpan PDF</span>
                </button>
                <a href="{{ route('admin.keuangan.index') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors">
                    <span>Tutup & Kembali</span>
                </a>
            </div>
        </div>

        <!-- Filter Bar Terintegrasi Langsung di Header Pratinjau PDF -->
        <div class="max-w-4xl mx-auto mt-2.5 pt-2.5 border-t border-white/10 flex flex-wrap items-center gap-2 text-xs">
            <span class="text-white/70 font-medium">Filter Kategori Laporan:</span>
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('admin.keuangan.pdf') }}"
                    class="px-2.5 py-1 rounded-md transition-colors {{ empty($categoryFilter) ? 'bg-white text-gray-900 font-bold shadow-xs' : 'bg-white/10 text-gray-200 hover:bg-white/20' }}">
                    Semua Program
                </a>
                <a href="{{ route('admin.keuangan.pdf', ['kategori' => 'jumat_berkah']) }}"
                    class="px-2.5 py-1 rounded-md transition-colors {{ $categoryFilter === 'jumat_berkah' ? 'bg-white text-gray-900 font-bold shadow-xs' : 'bg-white/10 text-gray-200 hover:bg-white/20' }}">
                    Jumat Berkah
                </a>
                <a href="{{ route('admin.keuangan.pdf', ['kategori' => 'donasi_bantuan']) }}"
                    class="px-2.5 py-1 rounded-md transition-colors {{ $categoryFilter === 'donasi_bantuan' ? 'bg-white text-gray-900 font-bold shadow-xs' : 'bg-white/10 text-gray-200 hover:bg-white/20' }}">
                    Donasi Bantuan
                </a>
                <a href="{{ route('admin.keuangan.pdf', ['kategori' => 'pembangunan_pondok_tahfidz']) }}"
                    class="px-2.5 py-1 rounded-md transition-colors {{ $categoryFilter === 'pembangunan_pondok_tahfidz' ? 'bg-white text-gray-900 font-bold shadow-xs' : 'bg-white/10 text-gray-200 hover:bg-white/20' }}">
                    Pembangunan Tahfidz
                </a>
            </div>
        </div>
    </div>

    <!-- Halaman Dokumen A4 -->
    <div class="max-w-4xl mx-auto my-6 md:my-10 p-8 md:p-12 bg-white shadow-lg rounded-xl print:shadow-none print:m-0 print:p-0 print:rounded-none">

        <!-- KOP SURAT YAYASAN RESMI -->
        <div class="flex items-center justify-between gap-6 pb-4 border-b-2 border-black">
            <div class="shrink-0">
                <img src="{{ asset('images/logo-bun.webp') }}" alt="Logo Yayasan" class="h-16 md:h-20 w-auto object-contain"
                    onerror="this.src='{{ asset('images/logoyabun.webp') }}'">
            </div>
            <div class="text-center flex-1">
                <h1 class="text-lg md:text-xl font-extrabold uppercase tracking-wide text-black leading-tight">
                    {{ $settings['nama_yayasan'] ?? 'Yayasan Bakti Umat Nusantara Cabang Pontianak' }}
                </h1>
                <p class="text-xs md:text-sm font-medium text-gray-700 mt-0.5">
                    Mewujudkan Kebaikan Bersama Melalui Program Sosial, Pendidikan, dan Dakwah Keumatan
                </p>
                <p class="text-[11px] text-gray-600 mt-1 leading-snug">
                    {{ $settings['alamat'] ?? 'Komplek Pondok Indah Lestari, Jln. Harmoni III Blok H4 No.2, Kec. Sungai Raya, Kab. Kubu Raya, Kalimantan Barat' }}
                </p>
                <p class="text-[11px] text-gray-600 mt-0.5">
                    WhatsApp: <span class="font-semibold text-gray-800">{{ $settings['kontak_wa'] ?? '+62 819-3094-2890' }}</span> | Email: <span class="font-semibold text-gray-800">{{ $settings['email'] ?? 'ybaktiumat@gmail.com' }}</span>
                </p>
            </div>
            <div class="shrink-0 w-16 md:w-20 hidden sm:block">
                <img src="{{ asset('images/logoyabun.webp') }}" alt="Logo YABUN" class="h-14 w-auto object-contain mx-auto"
                    onerror="this.style.display='none'">
            </div>
        </div>
        <!-- Garis Tipis Tambahan Kop Surat -->
        <div class="border-b border-black mt-1 mb-6"></div>

        <!-- JUDUL & INFORMASI LAPORAN -->
        <div class="text-center mb-6">
            <h2 class="text-base md:text-lg font-extrabold uppercase tracking-wider text-black underline underline-offset-4">
                Laporan Transparansi Keuangan & Donasi
            </h2>
            <p class="text-xs text-gray-600 mt-1">
                Periode data: Per {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>

        <!-- META DATA PENCETAKAN -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6 p-4 rounded-lg bg-gray-50 border border-gray-300 text-xs print-card">
            <div>
                <span class="text-gray-600 block">Kategori Filter:</span>
                <span class="font-bold text-black uppercase">
                    {{ $categoryFilter ? match($categoryFilter) {
                        'jumat_berkah' => 'Jumat Berkah',
                        'donasi_bantuan' => 'Donasi Bantuan',
                        'pembangunan_pondok_tahfidz' => 'Pembangunan Pondok Tahfidz',
                        default => ucwords(str_replace('_', ' ', (string) $categoryFilter))
                    } : 'Semua Program' }}
                </span>
            </div>
            <div>
                <span class="text-gray-600 block">Petugas Administrator:</span>
                <span class="font-bold text-black">{{ $admin->nama ?? 'Administrator' }}</span>
            </div>
            <div>
                <span class="text-gray-600 block">Total Data Transaksi:</span>
                <span class="font-bold text-black font-mono">{{ $transactions->count() }} Transaksi</span>
            </div>
        </div>

        <!-- RINGKASAN REKAPITULASI DANA -->
        <div class="mb-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-black mb-2">
                I. Ringkasan Perolehan Donasi Berdasarkan Program
            </h3>
            @php
                $displayedCategories = $summary['categories'];
                if ($categoryFilter && isset($summary['categories'][$categoryFilter])) {
                    $displayedCategories = [$categoryFilter => $summary['categories'][$categoryFilter]];
                }
                $gridColsClass = count($displayedCategories) === 1 ? 'grid-cols-1' : (count($displayedCategories) === 2 ? 'grid-cols-2' : 'grid-cols-3');
                $totalNominal = $categoryFilter ? $transactions->sum('amount') : $summary['grand_total'];
            @endphp
            <div class="grid {{ $gridColsClass }} gap-3">
                @foreach ($displayedCategories as $cat)
                    <div class="p-3.5 rounded-lg border border-gray-300 bg-gray-50 print-card">
                        <span class="text-[11px] font-bold text-gray-700 uppercase block mb-1">{{ $cat['label'] }}</span>
                        <p class="text-base font-extrabold font-mono text-black">
                            {{ $cat['formatted'] ?? ('Rp ' . number_format($cat['amount'] ?? 0, 0, ',', '.')) }}
                        </p>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 p-3.5 bg-gray-100 border border-gray-400 rounded-lg flex items-center justify-between print-card">
                <span class="text-xs font-bold text-black uppercase">
                    {{ $categoryFilter ? 'Total Donasi Program Ini:' : 'Total Akumulasi Donasi Masuk:' }}
                </span>
                <span class="text-base md:text-lg font-black font-mono text-black">
                    Rp {{ number_format($totalNominal, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- DAFTAR TRANSAKSI DONASI -->
        <div class="mb-8">
            <h3 class="text-xs font-bold uppercase tracking-wider text-black mb-2">
                II. Rincian Riwayat Transaksi Donasi
            </h3>
            <div class="border border-black rounded-lg overflow-hidden">
                <table class="w-full text-left text-xs border-collapse print-table">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-black text-black font-extrabold uppercase text-[10px] tracking-wider">
                            <th class="py-3 px-3 text-center w-12 border-r border-gray-300">No</th>
                            <th class="py-3 px-3 w-28 text-center border-r border-gray-300">Tanggal</th>
                            <th class="py-3 px-4 border-r border-gray-300">Nama Donatur</th>
                            <th class="py-3 px-3 border-r border-gray-300">Kategori Program</th>
                            <th class="py-3 px-3 text-center w-24 border-r border-gray-300">Jenis</th>
                            <th class="py-3 px-4 text-right">Nominal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @php $subtotal = 0; @endphp
                        @forelse ($transactions as $idx => $t)
                            @php $subtotal += $t->amount; @endphp
                            <tr class="even:bg-gray-50/70 hover:bg-gray-100/50 transition-colors">
                                <td class="py-2.5 px-3 text-center text-gray-700 font-mono border-r border-gray-200">{{ $idx + 1 }}</td>
                                <td class="py-2.5 px-3 text-center font-mono text-black whitespace-nowrap border-r border-gray-200">
                                    {{ $t->transaction_date ? $t->transaction_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-2.5 px-4 font-bold text-black border-r border-gray-200">
                                    {{ $t->donor->nama_donatur ?? 'Hamba Allah' }}
                                </td>
                                <td class="py-2.5 px-3 text-black font-semibold border-r border-gray-200">
                                    {{ $t->category_label ?: match ($t->category) {
                                        'jumat_berkah' => 'Jumat Berkah',
                                        'donasi_bantuan' => 'Donasi Bantuan',
                                        'pembangunan_pondok_tahfidz' => 'Pembangunan Pondok Tahfidz',
                                        default => ucwords(str_replace('_', ' ', (string) $t->category)),
                                    } }}
                                </td>
                                <td class="py-2.5 px-3 text-center border-r border-gray-200">
                                    <span class="inline-block px-2 py-0.5 font-bold text-[10px] text-black bg-gray-100 rounded border border-gray-400 uppercase tracking-tight">
                                        {{ $t->type }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-right font-mono font-bold text-black whitespace-nowrap">
                                    {{ number_format($t->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-500 italic">
                                    Belum ada transaksi yang tercatat pada filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 border-t-2 border-black font-bold text-black">
                            <td colspan="5" class="py-3 px-4 text-right uppercase text-[11px] tracking-wide border-r border-gray-300">
                                Total Transaksi Tertera:
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-sm font-black text-black whitespace-nowrap">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- PERNYATAAN AKUNTABILITAS & PENGESAHAN -->
        <div class="mt-10 pt-4 text-xs text-gray-700">
            <div class="mb-6 p-3 bg-gray-50 border border-gray-200 rounded-lg text-[11px] leading-relaxed italic text-gray-600 print-card">
                "Laporan ini diterbitkan secara transparan dan akuntabel oleh sistem manajemen donasi Yayasan Bakti Umat Nusantara Cabang Pontianak sebagai bentuk pertanggungjawaban amanah keumatan."
            </div>

            <div class="flex items-start justify-between gap-8 pt-4">
                <div class="text-center w-52">
                    <p class="text-gray-600 mb-16">
                        Mengetahui,<br>
                        <strong>Pimpinan Yayasan</strong>
                    </p>
                    <p class="border-t border-gray-400 pt-1 font-bold text-gray-900">
                        ( ........................................ )
                    </p>
                </div>

                <div class="text-center w-52">
                    <p class="text-gray-600 mb-16">
                        Pontianak, {{ now()->translatedFormat('d F Y') }}<br>
                        <strong>Bendahara / Administrasi</strong>
                    </p>
                    <p class="border-t border-gray-400 pt-1 font-bold text-gray-900">
                        {{ $admin->nama ?? 'Administrator' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
