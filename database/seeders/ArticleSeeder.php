<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ProgramCategory;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keagamaan = ProgramCategory::where('slug', 'keagamaan')->first();
        $pendidikan = ProgramCategory::where('slug', 'pendidikan')->first();
        $sosial = ProgramCategory::where('slug', 'sosial')->first();

        $articlesData = [
            // Keagamaan
            [
                'program_category_id' => $keagamaan?->id,
                'title' => 'Majelis Dzikir Akbar & Doa Kebangsaan Sambut Keberkahan',
                'slug' => 'majelis-dzikir-akbar-dan-doa-kebangsaan-sambut-keberkahan',
                'excerpt' => 'Yayasan Demo Peduli sukses menggelar majelis dzikir akbar bersama para jamaah dan tokoh masyarakat setempat.',
                'content' => '<p>Yayasan Peduli Umat Nusantara (Demo) menyelenggarakan kegiatan Majelis Dzikir Akbar dan Doa Kebangsaan percontohan. Acara ini dihadiri ratusan jamaah, tokoh agama, serta pengurus yayasan untuk memohon keberkahan dan ketenteraman bagi masyarakat.</p><p>Kegiatan diawali dengan pembacaan Al-Qur\'an, dilanjutkan dzikir bersama, tausiyah singkat, dan diakhiri dengan doa keselamatan. Yayasan berkomitmen untuk terus menjadikan majelis ini sebagai sarana mempererat ukhuwah islamiyah.</p>',
                'header_image' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&q=80&w=800',
                'published_at' => now()->subDays(2),
                'is_published' => true,
            ],
            [
                'program_category_id' => $keagamaan?->id,
                'title' => 'Program Tahsin & Tahfiz Intensif untuk Generasi Muda Pontianak',
                'slug' => 'program-tahsin-dan-tahfiz-intensif-untuk-generasi-muda-pontianak',
                'excerpt' => 'Kelas bimbingan tajwid dan makhraj Al-Qur\'an diselenggarakan rutin setiap pekan bagi santri binaan yayasan.',
                'content' => '<p>Dalam upaya mencetak generasi pecinta Al-Qur\'an, Yayasan Demo Peduli memfasilitasi program Tahsin dan Tahfiz rutin. Program ini dibimbing langsung oleh ustadz yang kompeten di bidang tajwid dan makhrajul huruf.</p><p>Setiap santri mendapatkan bimbingan intensif agar mampu membaca Al-Qur\'an dengan tartil serta menambah hafalan secara bertahap.</p>',
                'header_image' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?auto=format&fit=crop&q=80&w=800',
                'published_at' => now()->subDays(5),
                'is_published' => true,
            ],
            [
                'program_category_id' => $keagamaan?->id,
                'title' => 'Keutamaan Berbagi Makanan di Hari Jumat Berkah',
                'slug' => 'keutamaan-berbagi-makanan-di-hari-jumat-berkah',
                'excerpt' => 'Menyusuri momen penuh rasa syukur dalam penyaluran paket hidangan berkah Jumat di wilayah pemukiman dhuafa.',
                'content' => '<p>Hari Jumat merupakan hari terpuji yang sarat akan pahala. Yayasan Demo Peduli secara konsisten menyalurkan ratusan porsi nasi kotak sehat kepada para pekerja harian, janda tua, dan anak-anak yatim.</p><p>Dukungan dari para donatur menjadi pendorong utama keberlanjutan aksi penuh keberkahan ini.</p>',
                'header_image' => 'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?auto=format&fit=crop&q=80&w=800',
                'published_at' => now()->subDays(9),
                'is_published' => true,
            ],

            // Pendidikan
            [
                'program_category_id' => $pendidikan?->id,
                'title' => 'Penyaluran Beasiswa Alat Tulis & Perlengkapan Santri Binaan',
                'slug' => 'penyaluran-beasiswa-alat-tulis-dan-perlengkapan-santri-binaan',
                'excerpt' => 'Yayasan menyerahkan bantuan tas, buku, dan seragam sekolah bagi anak-anak prasejahtera di Kota Pontianak.',
                'content' => '<p>Pendidikan adalah hak setiap anak. Yayasan Demo Peduli kembali menyalurkan bantuan berupa perlengkapan sekolah lengkap bagi anak-anak yatim dan keluarga kurang mampu.</p><p>Dengan bantuan ini, diharapkan semangat belajar adik-adik santri semakin terpacu untuk meraih masa depan yang lebih cerah.</p>',
                'header_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&q=80&w=800',
                'published_at' => now()->subDays(1),
                'is_published' => true,
            ],
            [
                'program_category_id' => $pendidikan?->id,
                'title' => 'Pelatihan Literasi Digital & Pengenalan Teknologi Bagi Anak Yatim',
                'slug' => 'pelatihan-literasi-digital-dan-pengenalan-teknologi-bagi-anak-yatim',
                'excerpt' => 'Mengenalkan penggunaan komputer dan perangkat digital secara bijak untuk meningkatkan kemampuan anak-anak.',
                'content' => '<p>Era digital menuntut kesiapan anak-anak sejak dini. Yayasan Demo Peduli mengadakan workshop pengenalan teknologi dasar dan etika bermedia sosial secara aman.</p><p>Para santri menyambut antusias kesempatan belajar komputer secara langsung dengan pembimbing berpengalaman.</p>',
                'header_image' => 'https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?auto=format&fit=crop&q=80&w=800',
                'published_at' => now()->subDays(7),
                'is_published' => true,
            ],
            [
                'program_category_id' => $pendidikan?->id,
                'title' => 'Peresmian Pojok Baca & Perpustakaan Mini Percontohan',
                'slug' => 'peresmian-pojok-baca-dan-perpustakaan-mini-percontohan',
                'excerpt' => 'Menyediakan beragam buku bacaan islami dan edukatif guna meningkatkan minat baca santri binaan.',
                'content' => '<p>Dalam rangka menumbuhkan budaya literasi, Pojok Baca resmi dibuka. Fasilitas ini menyediakan ratusan koleksi buku keislaman, ensiklopedia anak, dan kisah inspiratif.</p>',
                'header_image' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?auto=format&fit=crop&q=80&w=800',
                'published_at' => now()->subDays(12),
                'is_published' => true,
            ],

            // Sosial
            [
                'program_category_id' => $sosial?->id,
                'title' => 'Penyaluran Santunan Khusus Anak Yatim & Dhuafa Bulanan',
                'slug' => 'penyaluran-santunan-khusus-anak-yatim-dan-dhuafa-bulanan',
                'excerpt' => 'Program santunan uang tunai dan paket sembako diserahkan secara langsung kepada para penerima manfaat.',
                'content' => '<p>Komitmen kepedulian sosial terus diwujudkan melalui penyaluran santunan bulanan. Dana yang terhimpun disalurkan secara akuntabel.</p><p>Senyum dan kebahagiaan para penerima manfaat menjadi bukti nyata hangatnya kepedulian bersama.</p>',
                'header_image' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&q=80&w=800',
                'published_at' => now()->subHours(5),
                'is_published' => true,
            ],
            [
                'program_category_id' => $sosial?->id,
                'title' => 'Aksi Cepat Tanggap Bantuan Musim Hujan & Banjir Lokal',
                'slug' => 'aksi-cepat-tanggap-bantuan-musim-hujan-dan-banjir-lokal',
                'excerpt' => 'Tim relawan mendistribusikan selimut, pakaian hangat, dan makanan siap saji ke lokasi terdampak.',
                'content' => '<p>Menanggapi cuaca ekstrem dan luapan air, tim sigap tanggap bencana turun langsung ke lapangan membagikan bantuan darurat kepada warga yang membutuhkan.</p>',
                'header_image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=800',
                'published_at' => now()->subDays(4),
                'is_published' => true,
            ],
            [
                'program_category_id' => $sosial?->id,
                'title' => 'Transparansi Penyaluran Zakat Fitrah & Fidyah Simulasi',
                'slug' => 'transparansi-penyaluran-zakat-fitrah-dan-fidyah-simulasi',
                'excerpt' => 'Laporan rinci pelaksanaan amanah zakat, infak, dan sedekah masyarakat Pontianak.',
                'content' => '<p>Sebagai bentuk komitmen terhadap akuntabilitas publik, yayasan menerbitkan laporan berkala mengenai penghimpunan dan pendistribusian dana zakat secara transparan.</p>',
                'header_image' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?auto=format&fit=crop&q=80&w=800',
                'published_at' => now()->subDays(10),
                'is_published' => true,
            ],
        ];

        foreach ($articlesData as $data) {
            Article::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
