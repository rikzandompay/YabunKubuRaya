<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ProgramCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_all_admin_routes(): void
    {
        $routes = [
            route('admin.dashboard'),
            route('admin.galeri.index'),
            route('admin.katalog.index'),
            route('admin.artikel.index'),
            route('admin.donatur.index'),
            route('admin.keuangan.index'),
            route('admin.setting.index'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertRedirect(route('login'));
        }
    }

    public function test_pengurus_cannot_access_admin_panel(): void
    {
        $pengurus = User::factory()->create(['peran' => 'pengurus']);

        $response = $this->actingAs($pengurus)->get(route('admin.dashboard'));
        $response->assertStatus(403);

        $response = $this->actingAs($pengurus)->get(route('admin.keuangan.index'));
        $response->assertStatus(403);
    }

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_article_content_is_sanitized_against_xss(): void
    {
        $admin = User::factory()->create(['peran' => 'admin']);
        $category = ProgramCategory::create([
            'name' => 'Pendidikan',
            'slug' => 'pendidikan',
        ]);

        $xssPayload = '<p>Paragraf normal</p><script>alert("hacked")</script><iframe src="https://evil.com"></iframe>';

        $response = $this->actingAs($admin)->post(route('admin.artikel.store'), [
            'program_category_id' => $category->id,
            'title' => 'Uji Keamanan XSS',
            'content' => $xssPayload,
        ]);

        $response->assertRedirect(route('admin.artikel.index'));

        $article = Article::where('title', 'Uji Keamanan XSS')->first();
        $this->assertNotNull($article);
        $this->assertStringNotContainsString('<script>', $article->content);
        $this->assertStringNotContainsString('<iframe>', $article->content);
        $this->assertStringContainsString('<p>Paragraf normal</p>', $article->content);
    }

    public function test_article_content_strips_malicious_xss_attributes(): void
    {
        $admin = User::factory()->create(['peran' => 'admin']);
        $category = ProgramCategory::create([
            'name' => 'Kesehatan',
            'slug' => 'kesehatan',
        ]);

        $xssPayload = '<p>Aman</p><img src="foto.jpg" onerror="alert(1)"><a href="javascript:alert(2)">Klik</a>';

        $this->actingAs($admin)->post(route('admin.artikel.store'), [
            'program_category_id' => $category->id,
            'title' => 'Uji Atribut XSS',
            'content' => $xssPayload,
        ]);

        $article = Article::where('title', 'Uji Atribut XSS')->first();
        $this->assertNotNull($article);
        $this->assertStringNotContainsString('onerror', $article->content);
        $this->assertStringNotContainsString('javascript:', $article->content);
        $this->assertStringContainsString('<img src="foto.jpg">', $article->content);
    }

    public function test_pengurus_cannot_perform_mutating_actions(): void
    {
        $pengurus = User::factory()->create(['peran' => 'pengurus']);

        // Finance
        $response = $this->actingAs($pengurus)->post(route('admin.keuangan.store'), [
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 50000,
            'donor_id' => 1,
            'transaction_date' => now()->toDateString(),
        ]);
        $response->assertStatus(403);

        // Donatur
        $response = $this->actingAs($pengurus)->post(route('admin.donatur.store'), [
            'nama_donatur' => 'Tester',
            'tipe_donatur' => 'individu',
            'kategori' => 'jumat_berkah',
            'total_donasi' => 50000,
            'tanggal_donasi' => now()->toDateString(),
        ]);
        $response->assertStatus(403);

        // Bank settings
        $response = $this->actingAs($pengurus)->post(route('admin.setting.bank.store'), [
            'nama_bank' => 'BSI',
            'nomor_rekening' => '9999999999',
            'atas_nama' => 'Yayasan',
        ]);
        $response->assertStatus(403);

        // General settings
        $response = $this->actingAs($pengurus)->put(route('admin.setting.general.update'), [
            'nama_yayasan' => 'Yayasan Mod',
            'email' => 'mod@example.com',
            'kontak_wa' => '0812345678',
            'alamat' => 'Pontianak',
        ]);
        $response->assertStatus(403);
    }
}
