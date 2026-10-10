<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Donatur;
use App\Models\FinancialTransaction;
use App\Models\GalleryPhoto;
use App\Models\Katalog;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModulesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'nama' => 'Admin Pengurus',
            'peran' => 'admin',
        ]);
    }

    public function test_admin_can_access_gallery_module(): void
    {
        GalleryPhoto::create([
            'title' => 'Foto Kegiatan Santri',
            'category' => 'jumat_berkah',
            'image_path' => 'gallery/test.webp',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.galeri.index', ['kategori' => 'jumat_berkah']));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Kegiatan Foto');
        $response->assertSee('Foto Kegiatan Santri');
    }

    public function test_admin_can_access_katalog_module(): void
    {
        $program = Program::create([
            'nama_program' => 'Jumat Berkah',
            'slug' => 'jumat-berkah',
            'status_aktif' => true,
        ]);

        Katalog::create([
            'program_id' => $program->id,
            'judul' => 'Paket Nasi Berkah',
            'slug' => 'paket-nasi-berkah',
            'deskripsi' => 'Deskripsi paket',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.katalog.index', ['program' => 'jumat-berkah']));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Katalog Program');
        $response->assertSee('Paket Nasi Berkah');
    }

    public function test_admin_can_access_articles_module(): void
    {
        $category = ProgramCategory::create([
            'name' => 'Dakwah & Quran',
            'slug' => 'dakwah-quran',
        ]);

        Article::create([
            'program_category_id' => $category->id,
            'title' => 'Kajian Rutin Santri',
            'slug' => 'kajian-rutin-santri',
            'content' => 'Isi materi kajian santri mingguan.',
            'published_at' => now(),
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.artikel.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Artikel Program');
        $response->assertSee('Kajian Rutin Santri');
    }

    public function test_admin_can_access_donors_module(): void
    {
        Donatur::create([
            'nama_donatur' => 'H. Sulaiman',
            'nomor_hp' => '08123456789',
            'tipe_donatur' => 'individu',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.donatur.index', ['q' => 'Sulaiman']));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Donatur');
        $response->assertSee('H. Sulaiman');
    }

    public function test_admin_can_store_donor_with_required_donation(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.donatur.store'), [
            'nama_donatur' => 'Donatur Uji Coba',
            'tipe_donatur' => 'individu',
            'nomor_hp' => '08987654321',
            'kategori' => 'jumat_berkah',
            'total_donasi' => 'Rp 50.000',
            'tanggal_donasi' => '2026-10-03',
        ]);

        $response->assertRedirect(route('admin.donatur.index'));
        $this->assertDatabaseHas('donatur', [
            'nama_donatur' => 'Donatur Uji Coba',
            'nomor_hp' => '08987654321',
        ]);
        $this->assertDatabaseHas('financial_transactions', [
            'category' => 'jumat_berkah',
            'amount' => 50000,
        ]);
    }

    public function test_admin_cannot_store_donor_without_total_donation(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.donatur.store'), [
            'nama_donatur' => 'Donatur Kosong',
            'tipe_donatur' => 'individu',
            'nomor_hp' => '08987654321',
            'kategori' => 'jumat_berkah',
            'total_donasi' => '',
            'tanggal_donasi' => '2026-10-03',
        ]);

        $response->assertSessionHasErrors(['total_donasi']);
    }

    public function test_admin_can_update_donor_and_its_donation(): void
    {
        $donor = Donatur::create([
            'nama_donatur' => 'Donatur Lama',
            'tipe_donatur' => 'individu',
            'nomor_hp' => '0811111111',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.donatur.update', $donor->id), [
            'nama_donatur' => 'Donatur Baru Terupdate',
            'tipe_donatur' => 'lembaga',
            'nomor_hp' => '0822222222',
            'kategori' => 'pembangunan_pondok_tahfidz',
            'total_donasi' => 'Rp 1.500.000',
            'tanggal_donasi' => '2026-10-05',
        ]);

        $response->assertRedirect(route('admin.donatur.index'));
        $this->assertDatabaseHas('donatur', [
            'id' => $donor->id,
            'nama_donatur' => 'Donatur Baru Terupdate',
            'tipe_donatur' => 'lembaga',
        ]);
        $this->assertDatabaseHas('financial_transactions', [
            'donor_id' => $donor->id,
            'category' => 'pembangunan_pondok_tahfidz',
            'amount' => 1500000,
        ]);
    }

    public function test_admin_can_filter_donors_by_category_and_type(): void
    {
        $donor1 = Donatur::create([
            'nama_donatur' => 'Donatur Berkah',
            'tipe_donatur' => 'lembaga',
            'nomor_hp' => '08123456781',
        ]);
        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 500000,
            'donor_id' => $donor1->id,
            'transaction_date' => '2026-10-01',
        ]);

        $donor2 = Donatur::create([
            'nama_donatur' => 'Donatur Tahfidz',
            'tipe_donatur' => 'individu',
            'nomor_hp' => '08123456782',
        ]);
        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'pembangunan_pondok_tahfidz',
            'amount' => 1000000,
            'donor_id' => $donor2->id,
            'transaction_date' => '2026-10-02',
        ]);

        // Filter by kategori jumat_berkah
        $response = $this->actingAs($this->admin)->get(route('admin.donatur.index', ['kategori' => 'jumat_berkah']));
        $response->assertStatus(200);
        $response->assertSee('Donatur Berkah');
        $response->assertDontSee('Donatur Tahfidz');

        // Filter by tipe individu
        $responseType = $this->actingAs($this->admin)->get(route('admin.donatur.index', ['tipe' => 'individu']));
        $responseType->assertStatus(200);
        $responseType->assertSee('Donatur Tahfidz');
        $responseType->assertDontSee('Donatur Berkah');
    }

    public function test_financial_transactions_only_display_when_donor_exists(): void
    {
        $donor = Donatur::create([
            'nama_donatur' => 'Donatur Valid',
            'tipe_donatur' => 'individu',
            'nomor_hp' => '08199999999',
        ]);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 750000,
            'donor_id' => $donor->id,
            'transaction_date' => '2026-10-01',
        ]);

        // Orphan transaction without donor
        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 300000,
            'donor_id' => null,
            'transaction_date' => '2026-10-01',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.keuangan.index'));
        $response->assertStatus(200);
        $response->assertSee('Donatur Valid');
        $response->assertSee('Rp 750.000');
        $response->assertDontSee('Rp 300.000');
    }

    public function test_admin_can_export_finance_pdf(): void
    {
        $donor = Donatur::create([
            'nama_donatur' => 'Donatur Test PDF',
            'tipe_donatur' => 'individu',
            'nomor_hp' => '08123456789',
        ]);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 500000,
            'donor_id' => $donor->id,
            'transaction_date' => '2026-10-06',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.keuangan.pdf'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('attachment; filename=', $response->headers->get('content-disposition') ?? '');
        $this->assertStringStartsWith('%PDF-', $response->getContent());

        // Test with category filter
        $filteredResponse = $this->actingAs($this->admin)->get(route('admin.keuangan.pdf', ['kategori' => 'jumat_berkah']));
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('laporan-keuangan-yabun-kubu-raya-jumat_berkah-', $filteredResponse->headers->get('content-disposition') ?? '');
    }

    public function test_deleting_donor_deletes_its_financial_transactions(): void
    {
        $donor = Donatur::create([
            'nama_donatur' => 'Donatur Mau Dihapus',
            'tipe_donatur' => 'individu',
            'nomor_hp' => '08177777777',
        ]);

        $tx = FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'donasi_bantuan',
            'amount' => 850000,
            'donor_id' => $donor->id,
            'transaction_date' => '2026-10-01',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.donatur.destroy', $donor->id));
        $response->assertRedirect();

        $this->assertDatabaseMissing('donatur', ['id' => $donor->id]);
        $this->assertDatabaseMissing('financial_transactions', ['id' => $tx->id]);
    }

    public function test_admin_sidebar_contains_correct_admin_urls(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('admin.galeri.index'));
        $response->assertSee(route('admin.katalog.index'));
        $response->assertSee(route('admin.artikel.index'));
        $response->assertSee(route('admin.donatur.index'));
        $response->assertSee(route('admin.keuangan.index'));
    }

    public function test_admin_can_store_update_and_destroy_article(): void
    {
        $category = ProgramCategory::firstOrCreate(
            ['slug' => 'sosial-test'],
            ['name' => 'Sosial Test', 'description' => 'Deskripsi sosial test']
        );

        // 1. Store
        $storeResponse = $this->actingAs($this->admin)->post(route('admin.artikel.store'), [
            'program_category_id' => $category->id,
            'title' => 'Judul Artikel Baru Test',
            'excerpt' => 'Ringkasan artikel test',
            'content' => '<p>Konten artikel test lengkap.</p>',
            'is_published' => '1',
        ]);

        $storeResponse->assertRedirect(route('admin.artikel.index'));
        $this->assertDatabaseHas('articles', [
            'title' => 'Judul Artikel Baru Test',
            'is_published' => true,
        ]);

        $article = Article::where('title', 'Judul Artikel Baru Test')->first();
        $this->assertNotNull($article);

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.artikel.update', $article->id), [
            'program_category_id' => $category->id,
            'title' => 'Judul Artikel Terupdate Test',
            'excerpt' => 'Ringkasan artikel terupdate',
            'content' => '<p>Konten terupdate.</p>',
            'is_published' => '1',
        ]);

        $updateResponse->assertRedirect(route('admin.artikel.index'));
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Judul Artikel Terupdate Test',
        ]);

        // 3. Destroy
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.artikel.destroy', $article->id));
        $deleteResponse->assertRedirect(route('admin.artikel.index'));
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
