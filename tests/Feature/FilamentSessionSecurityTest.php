<?php

namespace Tests\Feature;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use App\Models\ProgramCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentSessionSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_article_livewire_update_succeeds_with_the_same_valid_session(): void
    {
        $admin = User::factory()->create(['peran' => 'admin']);
        $snapshot = $this->openArticleCreatePage($admin);
        $sessionId = session()->getId();
        $sessionPasswordHash = session('password_hash_web');
        $this->assertNotEmpty($sessionPasswordHash);

        $response = $this->updateArticle($snapshot, ['data.title' => 'Still authorized']);

        $response->assertOk()->assertJsonStructure(['components' => [['snapshot']]]);
        $updatedSnapshot = json_decode($response->json('components.0.snapshot'), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('Still authorized', $updatedSnapshot['data']['data'][0]['title']);
        $this->assertSame($sessionId, session()->getId());
        $response->assertSessionHas('password_hash_web', $sessionPasswordHash);
        $this->assertAuthenticatedAs($admin, 'web');
    }

    public function test_create_article_livewire_noop_returns_401_after_password_changes_with_the_old_session_hash(): void
    {
        $admin = User::factory()->create(['peran' => 'admin']);
        $snapshot = $this->openArticleCreatePage($admin);
        $sessionId = session()->getId();
        $sessionPasswordHash = session('password_hash_web');
        $this->assertNotEmpty($sessionPasswordHash);

        User::whereKey($admin->id)->update(['kata_sandi' => Hash::make('a-different-password')]);
        $this->assertNotSame($admin->getAuthPassword(), $admin->fresh()->getAuthPassword());
        $this->assertSame($sessionId, session()->getId());
        $this->assertSame($sessionPasswordHash, session('password_hash_web'));

        $this->updateArticle($snapshot)
            ->assertUnauthorized()
            ->assertSessionMissing('password_hash_web');

        $this->assertGuest('web');
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_livewire_update_returns_401_for_an_existing_session_after_password_changes(): void
    {
        $admin = User::factory()->create(['peran' => 'admin']);
        $snapshot = $this->openArticleEditor($admin);

        $this->saveArticle($snapshot, 'Allowed before password change')->assertOk();
        $this->assertDatabaseHas('articles', ['title' => 'Allowed before password change']);

        $sessionPasswordHash = session('password_hash_web');
        $this->assertNotEmpty($sessionPasswordHash);
        $admin->update(['kata_sandi' => 'a-different-password']);
        $this->assertSame($sessionPasswordHash, session('password_hash_web'));

        $this->saveArticle($snapshot, 'Blocked after password change')
            ->assertUnauthorized()
            ->assertSessionMissing('password_hash_web');

        $this->assertGuest('web');
        $this->assertDatabaseHas('articles', ['title' => 'Allowed before password change']);
        $this->assertDatabaseMissing('articles', ['title' => 'Blocked after password change']);
    }

    public function test_livewire_update_returns_403_after_admin_role_is_revoked(): void
    {
        $admin = User::factory()->create(['peran' => 'admin']);
        $snapshot = $this->openArticleEditor($admin);

        $this->saveArticle($snapshot, 'Allowed before role revocation')->assertOk();
        $this->assertDatabaseHas('articles', ['title' => 'Allowed before role revocation']);

        $admin->update(['peran' => 'pengurus']);

        $this->saveArticle($snapshot, 'Blocked after role revocation')->assertForbidden();

        $this->assertDatabaseHas('articles', ['title' => 'Allowed before role revocation']);
        $this->assertDatabaseMissing('articles', ['title' => 'Blocked after role revocation']);
    }

    private function openArticleCreatePage(User $admin): string
    {
        $response = $this->withSession([Auth::guard('web')->getName() => $admin->id])
            ->get(ArticleResource::getUrl('create', panel: 'admin'));
        $response->assertOk();

        return $this->articleSnapshot($response, '.create-article');
    }

    private function openArticleEditor(User $admin): string
    {
        $category = ProgramCategory::create(['name' => 'Session security', 'slug' => 'session-security']);
        $article = Article::create([
            'program_category_id' => $category->id,
            'title' => 'Original article',
            'slug' => 'original-article',
            'content' => '<p>Session security test.</p>',
        ]);

        $response = $this->withSession([Auth::guard('web')->getName() => $admin->id])
            ->get(ArticleResource::getUrl('edit', ['record' => $article], panel: 'admin'));
        $response->assertOk();

        return $this->articleSnapshot($response, '.edit-article');
    }

    private function articleSnapshot(TestResponse $response, string $componentSuffix): string
    {
        preg_match_all('/wire:snapshot="([^"]+)"/', $response->getContent(), $matches);

        foreach ($matches[1] as $encodedSnapshot) {
            $snapshot = html_entity_decode($encodedSnapshot, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $component = json_decode($snapshot, true, flags: JSON_THROW_ON_ERROR);

            if (str_ends_with($component['memo']['name'], $componentSuffix)) {
                return $snapshot;
            }
        }

        $this->fail('The real Filament page did not render an article snapshot for '.$componentSuffix);
    }

    private function saveArticle(string $snapshot, string $title): TestResponse
    {
        return $this->updateArticle($snapshot, ['data.title' => $title], [
            ['path' => '', 'method' => 'save', 'params' => []],
        ]);
    }

    /**
     * @param  array<string, mixed>  $updates
     * @param  list<array{path: string, method: string, params: list<mixed>}>  $calls
     */
    private function updateArticle(string $snapshot, array $updates = [], array $calls = []): TestResponse
    {
        // A new HTTP request must reload the user rather than reuse the test process's cached guard.
        Auth::forgetGuards();
        Livewire::flushState();

        return $this->withCredentials()
            ->withCookie(config('session.cookie'), session()->getId())
            ->postJson('/livewire/update', [
                'components' => [[
                    'snapshot' => $snapshot,
                    'updates' => $updates,
                    'calls' => $calls,
                ]],
            ], ['X-Livewire' => 'true']);
    }
}
