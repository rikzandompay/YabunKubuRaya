<?php

namespace Tests\Feature;

use App\Filament\Resources\Articles\ArticleResource\Pages\CreateArticle;
use App\Models\Article;
use App\Models\Donatur;
use App\Models\FinancialTransaction;
use App\Models\ProgramCategory;
use App\Models\RekeningBank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
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
        $this->assertStringContainsString('src="foto.jpg"', $article->content);
    }

    public function test_article_content_blocks_obfuscated_javascript_links_on_every_write_path(): void
    {
        $category = ProgramCategory::create(['name' => 'Sosial', 'slug' => 'sosial']);

        // Article::create langsung = jalur yang dipakai panel Filament.
        $article = Article::create([
            'program_category_id' => $category->id,
            'title' => 'Uji Bypass Sanitasi',
            'slug' => 'uji-bypass-sanitasi',
            'content' => '<p>Aman</p><a href="java&#x09;script:alert(1)">a</a><a href=" javascript:alert(2)">b</a><img/src=x/onerror=alert(3)>',
        ]);

        $stored = $article->getAttributes()['content'];
        $this->assertStringNotContainsString('href', $stored);
        $this->assertStringNotContainsString('onerror=', $stored);
        $this->assertStringContainsString('<p>Aman</p>', $stored);
    }

    public function test_legacy_unsanitized_article_content_is_sanitized_when_rendered(): void
    {
        $category = ProgramCategory::create(['name' => 'Dakwah', 'slug' => 'dakwah']);

        DB::table('articles')->insert([
            'program_category_id' => $category->id,
            'title' => 'Artikel Lama',
            'slug' => 'artikel-lama',
            'content' => '<p>Lama</p><script>alert("lama")</script>',
            'is_published' => true,
            'published_at' => now()->subDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('artikel.show', 'artikel-lama'));

        $response->assertOk();
        $response->assertSee('<p>Lama</p>', false);
        $response->assertDontSee('alert("lama")', false);
    }

    public function test_article_title_cannot_break_out_of_json_ld_script(): void
    {
        $category = ProgramCategory::create(['name' => 'Pendidikan', 'slug' => 'pendidikan']);

        Article::create([
            'program_category_id' => $category->id,
            'title' => '</script><script>alert(1)</script>',
            'slug' => 'judul-xss',
            'content' => '<p>Isi</p>',
            'is_published' => true,
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get(route('artikel.show', 'judul-xss'));

        $response->assertOk();
        $response->assertDontSee('<script>alert(1)</script>', false);
        $response->assertSee('</script>', false);
    }

    public function test_bank_name_cannot_break_out_of_copy_button_onclick(): void
    {
        RekeningBank::create([
            'nama_bank' => "BSI');alert(1);//",
            'nomor_rekening' => '7123456789',
            'atas_nama' => 'Yayasan',
            'status_aktif' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee("copyToClipboard('7123456789', 'BSI\\u0027);alert(1);\\/\\/')", false);
        $response->assertDontSee("'BSI&#039;);alert(1);//'", false);
    }

    public function test_donor_name_cannot_break_out_of_dashboard_alpine_expressions(): void
    {
        $donor = Donatur::create([
            'nama_donatur' => "Budi');alert(1);//",
            'tipe_donatur' => 'individu',
        ]);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 50000,
            'donor_id' => $donor->id,
            'transaction_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs(User::factory()->create(['peran' => 'admin']))
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee("'Budi\\u0027);alert(1);\\/\\/'", false);
        $response->assertSee("'budi\\u0027);alert(1);\\/\\/'", false);
        $response->assertDontSee("Budi&#039;);alert(1);//'", false);
        $response->assertDontSee("budi&#039;);alert(1);//'", false);
    }

    public function test_filament_article_upload_rejects_svg_files(): void
    {
        $this->actingAs(User::factory()->create(['peran' => 'admin']));
        $category = ProgramCategory::create(['name' => 'Media', 'slug' => 'media']);

        $svg = UploadedFile::fake()->createWithContent(
            'evil.svg',
            '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'
        );

        Livewire::test(CreateArticle::class)
            ->fillForm([
                'program_category_id' => $category->id,
                'title' => 'Artikel SVG',
                'content' => '<p>Isi</p>',
                'header_image' => $svg,
            ])
            ->call('create')
            ->assertHasFormErrors(['header_image']);

        $this->assertDatabaseMissing('articles', ['title' => 'Artikel SVG']);
    }

    public function test_filament_rich_editor_attachment_rejects_svg_files(): void
    {
        $this->actingAs(User::factory()->create(['peran' => 'admin']));

        $svg = UploadedFile::fake()->createWithContent(
            'evil.svg',
            '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'
        );

        Livewire::test(CreateArticle::class)
            ->set('componentFileAttachments.data.content', $svg)
            ->call('getFormComponentFileAttachmentUrl', 'data.content')
            ->assertHasErrors(['data.content']);
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
