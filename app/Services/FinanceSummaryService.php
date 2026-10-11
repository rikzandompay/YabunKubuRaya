<?php

namespace App\Services;

use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class FinanceSummaryService
{
    public const CACHE_KEY = 'finance_transparency_summary';

    public const CACHE_TTL_SECONDS = 300; // 5 menit

    /**
     * Ambil total donasi masuk per kategori dan tanggal pembaruan terakhir.
     * Menggunakan cache 5 menit yang di-invalidate saat ada mutasi transaksi.
     *
     * @return array{
     *     categories: array<string, array{
     *         key: string,
     *         label: string,
     *         amount: float,
     *         formatted: string,
     *         unit: string,
     *         description: string
     *     }>,
     *     latest_transaction_date: ?string,
     *     latest_date_formatted: ?string
     * }
     */
    public static function totalsByCategory(bool $fresh = false): array
    {
        if ($fresh) {
            self::clearCache();
        }

        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            // 1. Hitung total pemasukan per kategori
            $jumatBerkah = (float) FinancialTransaction::pemasukan()
                ->kategori('jumat_berkah')
                ->sum('amount');

            $donasiBantuan = (float) FinancialTransaction::pemasukan()
                ->kategori('donasi_bantuan')
                ->sum('amount');

            $pondokTahfidz = (float) FinancialTransaction::pemasukan()
                ->kategori('pembangunan_pondok_tahfidz')
                ->sum('amount');

            $grandTotal = $jumatBerkah + $donasiBantuan + $pondokTahfidz;

            // 2. Hitung total pengeluaran per kategori
            $pengeluaranJumat = (float) FinancialTransaction::pengeluaran()
                ->kategori('jumat_berkah')
                ->sum('amount');

            $pengeluaranBantuan = (float) FinancialTransaction::pengeluaran()
                ->kategori('donasi_bantuan')
                ->sum('amount');

            $pengeluaranTahfidz = (float) FinancialTransaction::pengeluaran()
                ->kategori('pembangunan_pondok_tahfidz')
                ->sum('amount');

            $totalPengeluaran = $pengeluaranJumat + $pengeluaranBantuan + $pengeluaranTahfidz;
            $saldoKas = $grandTotal - $totalPengeluaran;

            // 3. Ambil tanggal transaksi terbaru
            $latest = FinancialTransaction::orderByDesc('transaction_date')
                ->orderByDesc('id')
                ->first();

            $latestDate = $latest?->transaction_date;
            $formattedLatestDate = null;

            if ($latestDate) {
                $carbonDate = Carbon::parse($latestDate);
                $indonesianMonths = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ];
                $formattedLatestDate = $carbonDate->day.' '.($indonesianMonths[$carbonDate->month] ?? $carbonDate->format('F')).' '.$carbonDate->year;
            }

            $pctJumat = $grandTotal > 0 ? round(($jumatBerkah / $grandTotal) * 100, 1) : 0;
            $pctBantuan = $grandTotal > 0 ? round(($donasiBantuan / $grandTotal) * 100, 1) : 0;
            $pctTahfidz = $grandTotal > 0 ? round(($pondokTahfidz / $grandTotal) * 100, 1) : 0;

            return [
                'categories' => [
                    'jumat_berkah' => [
                        'key' => 'jumat_berkah',
                        'label' => 'JUMAT BERKAH',
                        'amount' => $jumatBerkah,
                        'percentage' => $pctJumat,
                        'formatted' => 'Rp '.number_format($jumatBerkah, 0, ',', '.'),
                        'unit' => self::resolveUnit($jumatBerkah),
                        'description' => 'Total dana yang terkumpul dari program Jumat Berkah.',
                        'expense_amount' => $pengeluaranJumat,
                        'expense_formatted' => 'Rp '.number_format($pengeluaranJumat, 0, ',', '.'),
                        'balance' => $jumatBerkah - $pengeluaranJumat,
                        'balance_formatted' => 'Rp '.number_format($jumatBerkah - $pengeluaranJumat, 0, ',', '.'),
                    ],
                    'donasi_bantuan' => [
                        'key' => 'donasi_bantuan',
                        'label' => 'DONASI BANTUAN',
                        'amount' => $donasiBantuan,
                        'percentage' => $pctBantuan,
                        'formatted' => 'Rp '.number_format($donasiBantuan, 0, ',', '.'),
                        'unit' => self::resolveUnit($donasiBantuan),
                        'description' => 'Total dana donasi bantuan yang diterima dan disalurkan.',
                        'expense_amount' => $pengeluaranBantuan,
                        'expense_formatted' => 'Rp '.number_format($pengeluaranBantuan, 0, ',', '.'),
                        'balance' => $donasiBantuan - $pengeluaranBantuan,
                        'balance_formatted' => 'Rp '.number_format($donasiBantuan - $pengeluaranBantuan, 0, ',', '.'),
                    ],
                    'pembangunan_pondok_tahfidz' => [
                        'key' => 'pembangunan_pondok_tahfidz',
                        'label' => 'DONASI PEMBANGUNAN PONDOK TAHFIDZ',
                        'amount' => $pondokTahfidz,
                        'percentage' => $pctTahfidz,
                        'formatted' => 'Rp '.number_format($pondokTahfidz, 0, ',', '.'),
                        'unit' => self::resolveUnit($pondokTahfidz),
                        'description' => 'Total dana yang terkumpul untuk pembangunan pondok tahfidz.',
                        'expense_amount' => $pengeluaranTahfidz,
                        'expense_formatted' => 'Rp '.number_format($pengeluaranTahfidz, 0, ',', '.'),
                        'balance' => $pondokTahfidz - $pengeluaranTahfidz,
                        'balance_formatted' => 'Rp '.number_format($pondokTahfidz - $pengeluaranTahfidz, 0, ',', '.'),
                    ],
                ],
                'grand_total' => $grandTotal,
                'grand_total_formatted' => 'Rp '.number_format($grandTotal, 0, ',', '.'),
                'grand_total_unit' => self::resolveUnit($grandTotal),
                'total_expense' => $totalPengeluaran,
                'total_expense_formatted' => 'Rp '.number_format($totalPengeluaran, 0, ',', '.'),
                'balance' => $saldoKas,
                'balance_formatted' => 'Rp '.number_format($saldoKas, 0, ',', '.'),
                'balance_unit' => self::resolveUnit(abs($saldoKas)),
                'latest_transaction_date' => $latestDate?->toDateString(),
                'latest_date_formatted' => $formattedLatestDate,
            ];
        });
    }

    /**
     * Tentukan satuan nominal (Rupiah, atau otomatis menyesuaikan Juta/Miliar).
     */
    public static function resolveUnit(float $amount): string
    {
        if ($amount >= 1_000_000_000) {
            return 'Miliar Rupiah';
        }

        if ($amount >= 1_000_000) {
            return 'Juta Rupiah';
        }

        return 'Rupiah';
    }

    /**
     * Hapus cache ringkasan transparansi donasi.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
