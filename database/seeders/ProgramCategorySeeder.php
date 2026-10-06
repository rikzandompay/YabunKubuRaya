<?php

namespace Database\Seeders;

use App\Models\ProgramCategory;
use Illuminate\Database\Seeder;

class ProgramCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Keagamaan',
                'slug' => 'keagamaan',
                'description' => 'Kumpulan artikel kegiatan keagamaan seperti Jumat Berkah, Tahsin & Tahfiz, dan dzikir bersama yang kami selenggarakan.',
                'cover_image' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&q=80&w=800',
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Ikuti kisah dan kabar program pendidikan yayasan, mulai dari pembinaan Al-Qur\'an hingga dukungan belajar bagi generasi penerus.',
                'cover_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&q=80&w=800',
            ],
            [
                'name' => 'Sosial',
                'slug' => 'sosial',
                'description' => 'Telusuri berita santunan, zakat, dan aksi peduli sesama yang menunjukkan komitmen kami terhadap kemaslahatan umat.',
                'cover_image' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&q=80&w=800',
            ],
        ];

        foreach ($categories as $cat) {
            ProgramCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
