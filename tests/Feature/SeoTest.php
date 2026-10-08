<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ProgramCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_returns_success_and_includes_pages_and_published_articles(): void
    {
        $category = ProgramCategory::create([
            'name' => 'Sosial',
            'slug' => 'sosial',
        ]);

        $article = Article::create([
            'program_category_id' => $category->id,
            'title' => 'Penyaluran Bantuan Sembako',
            'slug' => 'penyaluran-bantuan-sembako',
            'excerpt' => 'Dokumentasi penyaluran sembako.',
            'content' => '<p>Konten lengkap artikel.</p>',
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/xml', $response->headers->get('Content-Type') ?? '');
        $response->assertSee(route('home'), false);
        $response->assertSee(route('artikel.index'), false);
        $response->assertSee(route('galeri.index'), false);
        $response->assertSee(route('artikel.show', $article->slug), false);
    }

    public function test_homepage_contains_essential_seo_tags_and_organization_schema(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<meta name="robots" content="index, follow">', false);
        $response->assertSee('<meta property="og:type" content="website">', false);
        $response->assertSee('<meta property="og:title"', false);
        $response->assertSee('<meta name="twitter:card"', false);
        $response->assertSee('https://schema.org', false);
        $response->assertSee('"NGO"', false);
    }

    public function test_article_detail_contains_og_tags_and_json_ld_schemas(): void
    {
        $category = ProgramCategory::create([
            'name' => 'Pendidikan',
            'slug' => 'pendidikan',
        ]);

        $article = Article::create([
            'program_category_id' => $category->id,
            'title' => 'Beasiswa Santri Yabun',
            'slug' => 'beasiswa-santri-yabun',
            'excerpt' => 'Program beasiswa santri dhuafa.',
            'content' => '<p>Detail beasiswa pendidikan santri.</p>',
            'published_at' => now()->subHours(2),
            'is_published' => true,
        ]);

        $response = $this->get(route('artikel.show', $article->slug));

        $response->assertStatus(200);
        $response->assertSee('<meta property="og:type" content="article">', false);
        $response->assertSee('<link rel="canonical" href="'.route('artikel.show', $article->slug).'">', false);
        $response->assertSee('Beasiswa Santri Yabun — Yayasan Bakti Umat Nusantara Cabang Kubu Raya', false);
        $response->assertSee('"NewsArticle"', false);
        $response->assertSee('"BreadcrumbList"', false);
    }

    public function test_robots_txt_has_valid_absolute_sitemap_and_rules(): void
    {
        $this->assertFileExists(public_path('robots.txt'));
        $content = (string) file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Disallow: /admin', $content);
        $this->assertStringContainsString('Sitemap: https://yabunkuburaya.org/sitemap.xml', $content);
    }
}
