<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan & Transparansi Donasi — {{ $settings['nama_yayasan'] ?? 'Yayasan Bakti Umat Nusantara Cabang Kubu Raya' }}</title>
    <style>
        @page {
            margin: 15mm 12mm 15mm 12mm;
            size: A4 portrait;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1E293B;
            font-size: 9.5pt;
            line-height: 1.35;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        /* KOP SURAT */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .kop-table td {
            vertical-align: middle;
            padding: 0;
        }
        .kop-logo-left {
            width: 80px;
            text-align: left;
        }
        .kop-logo-right {
            width: 80px;
            text-align: right;
        }
        .kop-logo-left img, .kop-logo-right img {
            max-height: 58px;
            max-width: 75px;
        }
        .kop-text {
            text-align: center;
            padding: 0 10px;
        }
        .kop-title {
            font-size: 13pt;
            font-weight: bold;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .kop-subtitle {
            font-size: 8.5pt;
            color: #334155;
            margin: 2px 0 0 0;
        }
        .kop-address {
            font-size: 7.5pt;
            color: #475569;
            margin: 3px 0 0 0;
            line-height: 1.25;
        }
        .kop-contact {
            font-size: 7.5pt;
            color: #475569;
            margin: 2px 0 0 0;
        }
        .border-kop-thick {
            border-bottom: 2.5px solid #0F172A;
            margin-top: 6px;
        }
        .border-kop-thin {
            border-bottom: 0.75px solid #0F172A;
            margin-top: 1.5px;
            margin-bottom: 12px;
        }

        /* JUDUL LAPORAN */
        .report-title-section {
            text-align: center;
            margin-bottom: 14px;
        }
        .report-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            color: #0F172A;
        }
        .report-subtitle {
            font-size: 8pt;
            color: #64748B;
            margin: 3px 0 0 0;
        }

        /* METADATA INFO */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 4px;
        }
        .meta-table td {
            padding: 6px 10px;
            font-size: 8pt;
            border: 1px solid #E2E8F0;
        }
        .meta-label {
            color: #64748B;
            display: block;
            font-size: 7.5pt;
        }
        .meta-val {
            font-weight: bold;
            color: #0F172A;
        }

        /* SECTION HEADER */
        .section-heading {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0F172A;
            margin: 0 0 6px 0;
            letter-spacing: 0.3px;
        }

        /* RINGKASAN PROGRAM */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin: 0 -6px 10px -6px;
        }
        .summary-cell {
            padding: 8px 10px;
            border: 1px solid #CBD5E1;
            background-color: #F8FAFC;
            vertical-align: top;
        }
        .summary-cell-title {
            font-size: 7.5pt;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            display: block;
            margin-bottom: 3px;
        }
        .summary-cell-amount {
            font-size: 11pt;
            font-weight: bold;
            color: #007A4D;
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
        }
        .summary-total-banner {
            width: 100%;
            border-collapse: collapse;
            background-color: #F1F5F9;
            border: 1.5px solid #94A3B8;
            margin-bottom: 14px;
        }
        .summary-total-banner td {
            padding: 7px 12px;
            font-weight: bold;
        }

        /* DATA TABLE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .data-table th {
            background-color: #F1F5F9;
            color: #0F172A;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5pt;
            padding: 6px 8px;
            border: 1px solid #CBD5E1;
            text-align: left;
        }
        .data-table td {
            padding: 5px 8px;
            font-size: 8pt;
            border: 1px solid #E2E8F0;
            vertical-align: middle;
        }
        .data-table tr.even {
            background-color: #F8FAFC;
        }
        .data-table tfoot td {
            background-color: #F1F5F9;
            border-top: 2px solid #0F172A;
            font-weight: bold;
            padding: 7px 8px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .badge-type {
            display: inline-block;
            padding: 1px 4px;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #94A3B8;
            background: #FFFFFF;
            border-radius: 2px;
        }

        /* AKUNTABILITAS & TANDA TANGAN */
        .disclaimer-box {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 6px 10px;
            font-size: 7.5pt;
            font-style: italic;
            color: #475569;
            margin-bottom: 18px;
            text-align: center;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 8pt;
            padding: 0 20px;
        }
        .signature-space {
            height: 55px;
        }
        .signature-line {
            border-bottom: 1px solid #475569;
            margin: 0 25px;
            padding-bottom: 2px;
            font-weight: bold;
            color: #0F172A;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT YAYASAN -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo-left">
                @if(!empty($logoLeft))
                    <img src="{{ $logoLeft }}" alt="Logo Yayasan">
                @endif
            </td>
            <td class="kop-text">
                <div class="kop-title">{{ $settings['nama_yayasan'] ?? 'Yayasan Bakti Umat Nusantara Cabang Kubu Raya' }}</div>
                <div class="kop-subtitle">Mewujudkan Kebaikan Bersama Melalui Program Sosial, Pendidikan, dan Dakwah Keumatan</div>
                <div class="kop-address">{{ $settings['alamat'] ?? 'Komplek Pondok Indah Lestari, Jln. Harmoni III Blok H4 No.2, Kec. Sungai Raya, Kab. Kubu Raya, Kalimantan Barat' }}</div>
                <div class="kop-contact">WhatsApp: <strong>{{ $settings['kontak_wa'] ?? '+62 819-3094-2890' }}</strong> &nbsp;|&nbsp; Email: <strong>{{ $settings['email'] ?? 'ybaktiumat@gmail.com' }}</strong></div>
            </td>
            <td class="kop-logo-right">
                @if(!empty($logoRight))
                    <img src="{{ $logoRight }}" alt="Logo YABUN">
                @endif
            </td>
        </tr>
    </table>
    <div class="border-kop-thick"></div>
    <div class="border-kop-thin"></div>

    <!-- JUDUL LAPORAN -->
    <div class="report-title-section">
        <div class="report-title">Laporan Transparansi Keuangan & Donasi</div>
        <div class="report-subtitle">Diterbitkan pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
    </div>

    <!-- METADATA PENCETAKAN -->
    <table class="meta-table">
        <tr>
            <td style="width: 34%;">
                <span class="meta-label">Kategori Program:</span>
                <span class="meta-val">
                    {{ $categoryFilter ? match($categoryFilter) {
                        'jumat_berkah' => 'Jumat Berkah',
                        'donasi_bantuan' => 'Donasi Bantuan',
                        'pembangunan_pondok_tahfidz' => 'Pembangunan Pondok Tahfidz',
                        default => ucwords(str_replace('_', ' ', (string) $categoryFilter))
                    } : 'Semua Program' }}
                </span>
            </td>
            <td style="width: 33%;">
                <span class="meta-label">Petugas Administrator:</span>
                <span class="meta-val">{{ $admin->nama ?? 'Administrator' }}</span>
            </td>
            <td style="width: 33%;">
                <span class="meta-label">Total Data Transaksi:</span>
                <span class="meta-val font-mono">{{ $transactions->count() }} Transaksi</span>
            </td>
        </tr>
    </table>

    <!-- RINGKASAN PEROLEHAN DONASI -->
    <div class="section-heading">I. Ringkasan Perolehan Donasi Berdasarkan Program</div>
    @php
        $displayedCategories = $summary['categories'] ?? [];
        if ($categoryFilter && isset($summary['categories'][$categoryFilter])) {
            $displayedCategories = [$categoryFilter => $summary['categories'][$categoryFilter]];
        }
        $totalNominal = $categoryFilter ? $transactions->sum('amount') : ($summary['grand_total'] ?? $transactions->sum('amount'));
        $cellWidth = count($displayedCategories) > 0 ? floor(100 / count($displayedCategories)) : 100;
    @endphp
    <table class="summary-table">
        <tr>
            @foreach ($displayedCategories as $cat)
                <td class="summary-cell" style="width: {{ $cellWidth }}%;">
                    <span class="summary-cell-title">{{ $cat['label'] }}</span>
                    <div class="summary-cell-amount">
                        {{ $cat['formatted'] ?? ('Rp ' . number_format($cat['amount'] ?? 0, 0, ',', '.')) }}
                    </div>
                </td>
            @endforeach
        </tr>
    </table>

    <table class="summary-total-banner">
        <tr>
            <td style="font-size: 8.5pt; text-transform: uppercase;">
                {{ $categoryFilter ? 'Total Donasi Program Ini:' : 'Total Akumulasi Donasi Masuk:' }}
            </td>
            <td class="text-right font-mono" style="font-size: 11pt; color: #007A4D;">
                Rp {{ number_format($totalNominal, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <!-- RINCIAN RIWAYAT TRANSAKSI -->
    <div class="section-heading">II. Rincian Riwayat Transaksi Donasi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 28px;" class="text-center">No</th>
                <th style="width: 70px;" class="text-center">Tanggal</th>
                <th>Nama Donatur</th>
                <th style="width: 120px;">Kategori Program</th>
                <th style="width: 55px;" class="text-center">Jenis</th>
                <th style="width: 95px;" class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @forelse ($transactions as $idx => $t)
                @php $subtotal += $t->amount; @endphp
                <tr class="{{ $idx % 2 === 1 ? 'even' : '' }}">
                    <td class="text-center font-mono">{{ $idx + 1 }}</td>
                    <td class="text-center font-mono">
                        {{ $t->transaction_date ? $t->transaction_date->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        <strong>{{ $t->donor->nama_donatur ?? 'Hamba Allah' }}</strong>
                    </td>
                    <td>
                        {{ $t->category_label ?: match ($t->category) {
                            'jumat_berkah' => 'Jumat Berkah',
                            'donasi_bantuan' => 'Donasi Bantuan',
                            'pembangunan_pondok_tahfidz' => 'Pembangunan Pondok Tahfidz',
                            default => ucwords(str_replace('_', ' ', (string) $t->category)),
                        } }}
                    </td>
                    <td class="text-center">
                        <span class="badge-type">{{ $t->type }}</span>
                    </td>
                    <td class="text-right font-mono">
                        <strong>{{ number_format($t->amount, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; font-style: italic; color: #64748B;">
                        Belum ada transaksi yang tercatat pada filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right" style="text-transform: uppercase; font-size: 8pt;">
                    Total Transaksi Tertera:
                </td>
                <td class="text-right font-mono" style="font-size: 9.5pt;">
                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- AKUNTABILITAS & TANDA TANGAN -->
    <div class="disclaimer-box">
        "Laporan ini diterbitkan secara transparan dan akuntabel oleh sistem manajemen donasi Yayasan Bakti Umat Nusantara Cabang Kubu Raya sebagai bentuk pertanggungjawaban amanah keumatan."
    </div>

    <table class="signature-table">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div style="font-weight: bold; margin-top: 2px;">Pimpinan Yayasan</div>
                <div class="signature-space"></div>
                <div class="signature-line">( ........................................ )</div>
            </td>
            <td>
                <div>Kubu Raya, {{ now()->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold; margin-top: 2px;">Bendahara / Administrasi</div>
                <div class="signature-space"></div>
                <div class="signature-line">{{ $admin->nama ?? 'Administrator' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
