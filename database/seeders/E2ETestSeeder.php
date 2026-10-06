<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Donatur;
use App\Models\FinancialTransaction;
use App\Models\GalleryPhoto;
use App\Models\Katalog;
use App\Models\Pengaturan;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\RekeningBank;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class E2ETestSeeder extends Seeder
{
    /**
     * Jalankan seeder khusus E2E Playwright dengan data deterministik.
     */
    public function run(): void
    {
        // 1. Admin Akun Test E2E (Dummy)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Admin E2E Playwright',
                'kata_sandi' => Hash::make('password'),
                'peran' => 'superadmin',
                'email_diverifikasi_pada' => now(),
            ]
        );

        // 2. Program Utama
        $program = Program::firstOrCreate(
            ['slug' => 'jumat-berkah'],
            [
                'nama_program' => 'Jumat Berkah',
                'deskripsi' => 'Program makanan dan sembako berkah untuk masyarakat dhuafa.',
                'status_aktif' => true,
            ]
        );

        // 3. Rekening Bank (Dummy)
        RekeningBank::firstOrCreate(
            ['nomor_rekening' => '000012345678'],
            [
                'nama_bank' => 'Bank Syariah Demo (BSI)',
                'atas_nama' => 'Yayasan Contoh Amanah',
                'status_aktif' => true,
            ]
        );

        // 4. Pengaturan Umum (Dummy / Simulasi)
        $settings = [
            'nama_yayasan' => 'Yayasan Peduli Umat Nusantara (Demo)',
            'visi' => 'Menjadi wadah sosial kemanusiaan percontohan yang amanah, transparan, dan berdampak bagi masyarakat.',
            'misi' => 'Menyelenggarakan program dakwah dan santunan sosial simulasi.',
            'kontak_wa' => '081200000000',
            'email' => 'kontak@example.test',
            'alamat' => 'Jl. Jenderal Sudirman No. 123 (Data Dummy), Kota Pontianak',
        ];
        foreach ($settings as $key => $val) {
            Pengaturan::updateOrCreate(
                ['kunci' => $key],
                ['nilai' => $val, 'kelompok' => 'umum']
            );
        }

        // 5. Kategori Program & Artikel Test
        $category = ProgramCategory::firstOrCreate(
            ['slug' => 'keagamaan'],
            ['name' => 'Keagamaan', 'description' => 'Program dakwah dan keagamaan']
        );

        Article::firstOrCreate(
            ['slug' => 'kegiatan-santunan-akbar-dhuafa'],
            [
                'program_category_id' => $category->id,
                'title' => 'Kegiatan Santunan Akbar Dhuafa',
                'excerpt' => 'Laporan penyaluran santunan bagi dhuafa di Pontianak.',
                'content' => '<p>Alhamdulillah telah terlaksana santunan akbar dhuafa dengan lancar.</p>',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        // 6. Katalog Test
        Katalog::firstOrCreate(
            ['judul' => 'Katalog Sedekah Pangan Santri'],
            [
                'program_id' => $program->id,
                'slug' => 'katalog-sedekah-pangan-santri',
                'deskripsi' => 'Program bantuan pangan harian santri penghafal Quran.',
                'status' => 'aktif',
                'target_penerima' => '100 Santri',
            ]
        );

        // 7. Galeri Test
        GalleryPhoto::firstOrCreate(
            ['title' => 'Dokumentasi Santunan Santri'],
            [
                'image_path' => 'photos/santri-yabun.jpeg',
                'category' => 'jumat_berkah',
                'is_featured' => true,
                'sort_order' => 1,
                'activity_date' => now()->format('Y-m-d'),
            ]
        );

        // 8. Donatur Test (Dummy)
        $donor = Donatur::firstOrCreate(
            ['nama_donatur' => 'Donatur Simulasi E2E'],
            [
                'nomor_hp' => '081200001234',
                'tipe_donatur' => 'individu',
            ]
        );

        // 9. Transaksi Keuangan Test
        FinancialTransaction::firstOrCreate(
            ['description' => 'Infaq Operasional E2E Test'],
            [
                'type' => 'pemasukan',
                'category' => 'jumat_berkah',
                'amount' => 1500000,
                'donor_id' => $donor->id,
                'transaction_date' => now()->format('Y-m-d'),
            ]
        );
    }
}
