<?php

namespace Tests\Feature;

use App\Models\GalleryPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_gallery_section_and_view_all_button(): void
    {
        GalleryPhoto::create([
            'title' => 'Foto Kegiatan 1',
            'image_path' => 'https://example.com/photo1.jpg',
            'category' => 'jumat_berkah',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Galeri Kegiatan');
        $response->assertSee('Lihat Semua Galeri');
        $response->assertSee(route('galeri.index'));
    }

    public function test_gallery_index_page_renders_successfully(): void
    {
        GalleryPhoto::create([
            'title' => 'Penyaluran Donasi Santri',
            'image_path' => 'https://example.com/photo2.jpg',
            'category' => 'donasi',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertSee('Galeri Dokumentasi Kegiatan');
        $response->assertSee('Penyaluran Donasi Santri');
        $response->assertSee('Jumat Berkah');
        $response->assertSee('Donasi');
    }

    public function test_ajax_gallery_request_returns_json(): void
    {
        GalleryPhoto::create([
            'title' => 'Kajian Dzikir Bersama',
            'image_path' => 'https://example.com/photo3.jpg',
            'category' => 'dzikir',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $response = $this->getJson(route('galeri.index', ['kategori' => 'dzikir']));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'category' => 'dzikir',
            'total' => 1,
        ]);
        $response->assertJsonFragment([
            'title' => 'Kajian Dzikir Bersama',
        ]);
    }
}
