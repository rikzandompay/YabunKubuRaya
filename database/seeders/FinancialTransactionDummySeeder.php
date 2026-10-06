<?php

namespace Database\Seeders;

use App\Models\Donatur;
use App\Models\FinancialTransaction;
use Illuminate\Database\Seeder;

class FinancialTransactionDummySeeder extends Seeder
{
    /**
     * Jalankan seeder dummy data transaksi keuangan (opsional).
     * Penggunaan: php artisan db:seed --class=FinancialTransactionDummySeeder
     */
    public function run(): void
    {
        // Bersihkan data transaksi dummy sebelumnya agar bersih dan sinkron
        FinancialTransaction::truncate();

        $donorsData = [
            [
                'nama_donatur' => 'Donatur Simulasi 1',
                'nomor_hp' => '081200000001',
                'tipe_donatur' => 'individu',
                'transactions' => [
                    ['category' => 'pembangunan_pondok_tahfidz', 'amount' => 5000000.00, 'desc' => 'Simulasi Wakaf Asrama Santri', 'days_ago' => 5],
                    ['category' => 'donasi_bantuan', 'amount' => 4500000.00, 'desc' => 'Simulasi Infaq Paket Pendidikan', 'days_ago' => 12],
                    ['category' => 'jumat_berkah', 'amount' => 3000000.00, 'desc' => 'Simulasi Sedekah Makanan Jumat Berkah', 'days_ago' => 19],
                ],
            ],
            [
                'nama_donatur' => 'PT Mitra Kebaikan (Dummy)',
                'nomor_hp' => '081200000002',
                'tipe_donatur' => 'lembaga',
                'transactions' => [
                    ['category' => 'pembangunan_pondok_tahfidz', 'amount' => 10000000.00, 'desc' => 'Simulasi CSR Ruang Belajar', 'days_ago' => 8],
                ],
            ],
            [
                'nama_donatur' => 'Hamba Allah (Simulasi)',
                'nomor_hp' => '081200000003',
                'tipe_donatur' => 'anonim',
                'transactions' => [
                    ['category' => 'jumat_berkah', 'amount' => 3000000.00, 'desc' => 'Simulasi Sedekah Jumat Berkah', 'days_ago' => 2],
                    ['category' => 'donasi_bantuan', 'amount' => 2500000.00, 'desc' => 'Simulasi Bantuan Santri', 'days_ago' => 9],
                    ['category' => 'pembangunan_pondok_tahfidz', 'amount' => 1750000.00, 'desc' => 'Simulasi Infaq Fasilitas', 'days_ago' => 16],
                ],
            ],
            [
                'nama_donatur' => 'Donatur Simulasi 2',
                'nomor_hp' => '081200000004',
                'tipe_donatur' => 'individu',
                'transactions' => [
                    ['category' => 'donasi_bantuan', 'amount' => 3000000.00, 'desc' => 'Simulasi Sembako Santunan', 'days_ago' => 4],
                    ['category' => 'jumat_berkah', 'amount' => 2400000.00, 'desc' => 'Simulasi Beras Jumat Berkah', 'days_ago' => 14],
                ],
            ],
            [
                'nama_donatur' => 'Donatur Simulasi 3',
                'nomor_hp' => '081200000005',
                'tipe_donatur' => 'individu',
                'transactions' => [
                    ['category' => 'jumat_berkah', 'amount' => 2000000.00, 'desc' => 'Simulasi Paket Makanan', 'days_ago' => 7],
                    ['category' => 'donasi_bantuan', 'amount' => 1800000.00, 'desc' => 'Simulasi Santunan Kebutuhan', 'days_ago' => 21],
                ],
            ],
            [
                'nama_donatur' => 'Donatur Simulasi 4',
                'nomor_hp' => '081200000006',
                'tipe_donatur' => 'individu',
                'transactions' => [
                    ['category' => 'pembangunan_pondok_tahfidz', 'amount' => 2950000.00, 'desc' => 'Simulasi Wakaf Bangunan', 'days_ago' => 10],
                ],
            ],
            [
                'nama_donatur' => 'Komunitas Sosial Mitra (Dummy)',
                'nomor_hp' => '081200000007',
                'tipe_donatur' => 'lembaga',
                'transactions' => [
                    ['category' => 'jumat_berkah', 'amount' => 2000000.00, 'desc' => 'Simulasi Donasi Gabungan', 'days_ago' => 1],
                ],
            ],
        ];

        foreach ($donorsData as $d) {
            $donatur = Donatur::firstOrCreate(
                ['nomor_hp' => $d['nomor_hp']],
                [
                    'nama_donatur' => $d['nama_donatur'],
                    'tipe_donatur' => $d['tipe_donatur'],
                ]
            );

            // Perbarui nama dan tipe jika sebelumnya sudah ada
            $donatur->update([
                'nama_donatur' => $d['nama_donatur'],
                'tipe_donatur' => $d['tipe_donatur'],
            ]);

            foreach ($d['transactions'] as $tx) {
                FinancialTransaction::create([
                    'type' => 'pemasukan',
                    'category' => $tx['category'],
                    'amount' => $tx['amount'],
                    'donor_id' => $donatur->id,
                    'description' => $tx['desc'],
                    'transaction_date' => now()->subDays($tx['days_ago'])->toDateString(),
                ]);
            }
        }
    }
}
