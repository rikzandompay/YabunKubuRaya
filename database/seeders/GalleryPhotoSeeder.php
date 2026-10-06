<?php

namespace Database\Seeders;

use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

class GalleryPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyData = [
            // Kategori: Jumat Berkah (5 Foto)
            [
                'title' => 'Pembagian Nasi Kotak Jumat Berkah Pontianak',
                'image_path' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&q=80&w=1200',
                'category' => 'jumat_berkah',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Penyaluran Paket Sembako Dhuafa',
                'image_path' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&q=80&w=800',
                'category' => 'jumat_berkah',
                'is_featured' => false,
                'sort_order' => 2,
            ],
            [
                'title' => 'Senyum Kebahagiaan Penerima Manfaat',
                'image_path' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&q=80&w=800',
                'category' => 'jumat_berkah',
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'title' => 'Kegiatan Berbagi Takjil & Makanan Suka Cita',
                'image_path' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?auto=format&fit=crop&q=80&w=800',
                'category' => 'jumat_berkah',
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'title' => 'Relawan YABUN Persiapan Jumat Berkah',
                'image_path' => 'https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&q=80&w=800',
                'category' => 'jumat_berkah',
                'is_featured' => false,
                'sort_order' => 5,
            ],

            // Kategori: Donasi (5 Foto)
            [
                'title' => 'Penyaluran Santunan Anak Yatim Piatu',
                'image_path' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=1200',
                'category' => 'donasi',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Bantuan Sarana Fasilitas Ibadah',
                'image_path' => 'https://images.unsplash.com/photo-1578357078586-491adf1aa5ba?auto=format&fit=crop&q=80&w=800',
                'category' => 'donasi',
                'is_featured' => false,
                'sort_order' => 2,
            ],
            [
                'title' => 'Penyerahan Beasiswa Perlengkapan Sekolah',
                'image_path' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&q=80&w=800',
                'category' => 'donasi',
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'title' => 'Aksi Tanggap Darurat Bencana Sosial',
                'image_path' => 'https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?auto=format&fit=crop&q=80&w=800',
                'category' => 'donasi',
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'title' => 'Serah Terima Donasi Rekening Resmi Yayasan',
                'image_path' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?auto=format&fit=crop&q=80&w=800',
                'category' => 'donasi',
                'is_featured' => false,
                'sort_order' => 5,
            ],

            // Kategori: Dzikir (5 Foto)
            [
                'title' => 'Pengajian Dzikir Bersama & Doa Kebangsaan',
                'image_path' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&q=80&w=1200',
                'category' => 'dzikir',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Kajian Rutin Santri Tahfidz Al-Qur\'an',
                'image_path' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?auto=format&fit=crop&q=80&w=800',
                'category' => 'dzikir',
                'is_featured' => false,
                'sort_order' => 2,
            ],
            [
                'title' => 'Pembacaan Sholawat & Tahlil Jamaah',
                'image_path' => 'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?auto=format&fit=crop&q=80&w=800',
                'category' => 'dzikir',
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'title' => 'Suasana Kekhusyukan Majelis Dzikir YABUN',
                'image_path' => 'https://images.unsplash.com/photo-1543731068-7e0f5beff43a?auto=format&fit=crop&q=80&w=800',
                'category' => 'dzikir',
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'title' => 'Silaturahmi Pengurus & Tokoh Agama Pontianak',
                'image_path' => 'https://images.unsplash.com/photo-1519817650390-64a93db51149?auto=format&fit=crop&q=80&w=800',
                'category' => 'dzikir',
                'is_featured' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($dummyData as $data) {
            GalleryPhoto::updateOrCreate(
                [
                    'title' => $data['title'],
                    'category' => $data['category'],
                ],
                $data
            );
        }
    }
}
