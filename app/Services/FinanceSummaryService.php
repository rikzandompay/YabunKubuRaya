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

            // 2. Ambil tanggal transaksi terbaru
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
                    ],
                    'donasi_bantuan' => [
                        'key' => 'donasi_bantuan',
                        'label' => 'DONASI BANTUAN',
                        'amount' => $donasiBantuan,
                        'percentage' => $pctBantuan,
                        'formatted' => 'Rp '.number_format($donasiBantuan, 0, ',', '.'),
                        'unit' => self::resolveUnit($donasiBantuan),
                        'description' => 'Total dana donasi bantuan yang diterima dan disalurkan.',
                    ],
                    'pembangunan_pondok_tahfidz' => [
                        'key' => 'pembangunan_pondok_tahfidz',
                        'label' => 'DONASI PEMBANGUNAN PONDOK TAHFIDZ',
                        'amount' => $pondokTahfidz,
                        'percentage' => $pctTahfidz,
                        'formatted' => 'Rp '.number_format($pondokTahfidz, 0, ',', '.'),
                        'unit' => self::resolveUnit($pondokTahfidz),
                        'description' => 'Total dana yang terkumpul untuk pembangunan pondok tahfidz.',
                    ],
                ],
                'grand_total' => $grandTotal,
                'grand_total_formatted' => 'Rp '.number_format($grandTotal, 0, ',', '.'),
                'grand_total_unit' => self::resolveUnit($grandTotal),
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
