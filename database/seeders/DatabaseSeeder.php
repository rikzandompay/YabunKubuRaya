<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use App\Models\Program;
use App\Models\RekeningBank;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Default (Dummy / Percontohan)
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Administrator Simulasi',
                'kata_sandi' => Hash::make('password'),
                'peran' => 'superadmin',
                'email_diverifikasi_pada' => now(),
            ]
        );

        // 2. Program Utama Sesuai PRD
        $programs = [
            [
                'nama_program' => 'Jumat Berkah',
                'slug' => 'jumat-berkah',
                'deskripsi' => 'Program berbagi makanan dan paket sembako berkah setiap hari Jumat bagi masyarakat dhuafa.',
                'status_aktif' => true,
            ],
            [
                'nama_program' => 'Tahsin & Tahfiz',
                'slug' => 'tahsin-tahfiz',
                'deskripsi' => 'Program pembinaan baca tulis dan hafalan Al-Qur\'an bagi anak-anak dan santri binaan.',
                'status_aktif' => true,
            ],
            [
                'nama_program' => 'Santunan & Zakat',
                'slug' => 'santunan-zakat',
                'deskripsi' => 'Penyaluran zakat, infak, dan sedekah serta santunan pendidikan bagi anak yatim dan piatu.',
                'status_aktif' => true,
            ],
        ];

        foreach ($programs as $prog) {
            Program::firstOrCreate(
                ['slug' => $prog['slug']],
                $prog
            );
        }

        // 3. Rekening Bank Donasi Yayasan
        RekeningBank::firstOrCreate(
            ['nomor_rekening' => '7123456789'],
            [
                'nama_bank' => 'Bank Syariah Indonesia (BSI)',
                'atas_nama' => 'Yayasan Bakti Umat Nusantara',
                'status_aktif' => true,
            ]
        );

        RekeningBank::firstOrCreate(
            ['nomor_rekening' => '7719-01-013298-53-7'],
            [
                'nama_bank' => 'Bank BRI',
                'atas_nama' => 'Yayasan Bakti Umat Nusantara',
                'status_aktif' => true,
            ]
        );

        // 4. Pengaturan Umum Profil Yayasan (Dummy / Manipulasi Data)
        $pengaturan = [
            'nama_yayasan' => 'Yayasan Peduli Umat Nusantara (Demo)',
            'visi' => 'Menjadi wadah sosial kemanusiaan percontohan yang amanah, transparan, dan berdampak bagi masyarakat.',
            'misi' => "1. Menyelenggarakan kegiatan sosial dan dakwah percontohan.\n2. Memberikan bantuan dan santunan bagi yang membutuhkan.\n3. Menyajikan laporan donasi secara transparan dan akuntabel.",
            'kontak_wa' => '081200000000',
            'email' => 'kontak@example.test',
            'alamat' => 'Jl. Jenderal Sudirman No. 123 (Data Dummy), Kota Pontianak',
        ];

        foreach ($pengaturan as $kunci => $nilai) {
            Pengaturan::firstOrCreate(
                ['kunci' => $kunci],
                ['nilai' => $nilai, 'kelompok' => 'umum']
            );
        }

        // 5. Seeder Foto Galeri
        $this->call(GalleryPhotoSeeder::class);
    }
}
