<?php

namespace Database\Seeders;

use App\Models\Katalog;
use App\Models\Program;
use Illuminate\Database\Seeder;

class KatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            ['nama_program' => 'Jumat Berkah', 'slug' => 'jumat-berkah', 'deskripsi' => 'Program berbagi makanan dan sembako.'],
            ['nama_program' => 'Tahsin & Tahfiz', 'slug' => 'tahsin-tahfiz', 'deskripsi' => 'Program pembinaan hafalan Al-Qur\'an.'],
            ['nama_program' => 'Santunan Anak Yatim', 'slug' => 'santunan-anak-yatim', 'deskripsi' => 'Program santunan rutin anak yatim.'],
            ['nama_program' => 'Zakat Mal dan Fitrah', 'slug' => 'zakat-mal-fitrah', 'deskripsi' => 'Layanan penerimaan dan penyaluran zakat.'],
            ['nama_program' => 'Kurban', 'slug' => 'kurban', 'deskripsi' => 'Fasilitas ibadah kurban.'],
        ];

        foreach ($programs as $prog) {
            Program::firstOrCreate(
                ['slug' => $prog['slug']],
                ['nama_program' => $prog['nama_program'], 'deskripsi' => $prog['deskripsi'], 'status_aktif' => true]
            );
        }

        $items = [
            [
                'slug' => 'jumat-berkah',
                'program_slug' => 'jumat-berkah',
                'judul' => 'Jumat Berkah',
                'deskripsi' => 'Berbagi makanan dan kebaikan setiap Jumat untuk masyarakat yang membutuhkan.',
                'gambar' => 'images/santri-yabun.webp',
                'target_penerima' => 'Masyarakat Dhuafa',
            ],
            [
                'slug' => 'tahsin-tahfiz',
                'program_slug' => 'tahsin-tahfiz',
                'judul' => 'Tahsin & Tahfiz',
                'deskripsi' => 'Akses pembelajaran Al-Qur\'an, perbaikan bacaan, serta pembinaan hafalan santri secara gratis.',
                'gambar' => 'images/PembangunanRmgtahfidz.webp',
                'target_penerima' => 'Santri Binaan',
            ],
            [
                'slug' => 'santunan-anak-yatim',
                'program_slug' => 'santunan-anak-yatim',
                'judul' => 'Santunan Anak Yatim',
                'deskripsi' => 'Santunan rutin dan pendampingan kasih sayang untuk masa depan anak-anak yatim dhuafa.',
                'gambar' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=1200',
                'target_penerima' => 'Anak Yatim Dhuafa',
            ],
            [
                'slug' => 'zakat-mal-fitrah',
                'program_slug' => 'zakat-mal-fitrah',
                'judul' => 'Zakat Mal dan Fitrah',
                'deskripsi' => 'Layanan penerimaan dan penyaluran zakat secara amanah, transparan, dan tepat sasaran.',
                'gambar' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?auto=format&fit=crop&q=80&w=1200',
                'target_penerima' => '8 Asnaf Penerima Zakat',
            ],
            [
                'slug' => 'tebar-kurban',
                'program_slug' => 'kurban',
                'judul' => 'Tebar Kurban',
                'deskripsi' => 'Fasilitas ibadah kurban dengan distribusi menyasar warga pelosok dan pedalaman Kalbar.',
                'gambar' => 'https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?auto=format&fit=crop&q=80&w=1200',
                'target_penerima' => 'Warga Pelosok & Pedalaman',
            ],
        ];

        foreach ($items as $item) {
            $program = Program::where('slug', $item['program_slug'])->first();
            if ($program) {
                Katalog::firstOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'program_id' => $program->id,
                        'judul' => $item['judul'],
                        'deskripsi' => $item['deskripsi'],
                        'gambar' => $item['gambar'],
                        'target_penerima' => $item['target_penerima'],
                        'status' => 'aktif',
                    ]
                );
            }
        }
    }
}
