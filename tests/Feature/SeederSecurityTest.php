<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\E2ETestSeeder;
use Database\Seeders\GalleryPhotoSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class SeederSecurityTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('restrictedEnvironments')]
    public function test_database_seeder_does_not_create_demo_admin_in_restricted_environments(string $environment): void
    {
        $this->app['env'] = $environment;
        $this->partialMock(GalleryPhotoSeeder::class)->shouldReceive('run')->once();

        $this->runSeeder(DatabaseSeeder::class);

        $this->assertDatabaseEmpty('pengguna');
        $this->assertDatabaseHas('program', ['slug' => 'jumat-berkah']);
    }

    #[DataProvider('restrictedEnvironments')]
    public function test_database_seeder_leaves_existing_user_unchanged_in_restricted_environments(string $environment): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'admin@example.com',
            'kata_sandi' => Hash::make('existing-secure-password'),
            'peran' => 'pengurus',
        ]);
        $originalAttributes = $user->refresh()->getAttributes();
        $this->app['env'] = $environment;
        $this->partialMock(GalleryPhotoSeeder::class)->shouldReceive('run')->once();

        $this->runSeeder(DatabaseSeeder::class);

        $this->assertSame($originalAttributes, $user->fresh()->getAttributes());
        $this->assertDatabaseCount('pengguna', 1);
    }

    #[DataProvider('restrictedEnvironments')]
    public function test_e2e_seeder_is_blocked_before_any_database_access_in_restricted_environments(string $environment): void
    {
        $this->app['env'] = $environment;
        DB::enableQueryLog();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('E2ETestSeeder may only run in local or testing environments.');

        try {
            $this->runSeeder(E2ETestSeeder::class);
        } finally {
            $queries = DB::getQueryLog();
            DB::disableQueryLog();
            $this->assertSame([], $queries);
            $this->assertDatabaseEmpty('pengguna');
        }
    }

    #[DataProvider('restrictedEnvironments')]
    public function test_e2e_seeder_leaves_existing_user_unchanged_in_restricted_environments(string $environment): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'admin@example.com',
            'kata_sandi' => Hash::make('existing-secure-password'),
            'peran' => 'pengurus',
        ]);
        $originalAttributes = $user->refresh()->getAttributes();
        $this->app['env'] = $environment;
        DB::enableQueryLog();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('E2ETestSeeder may only run in local or testing environments.');

        try {
            $this->runSeeder(E2ETestSeeder::class);
        } finally {
            $queries = DB::getQueryLog();
            DB::disableQueryLog();
            $this->assertSame([], $queries);
            $this->assertSame($originalAttributes, $user->fresh()->getAttributes());
            $this->assertDatabaseCount('pengguna', 1);
        }
    }

    #[DataProvider('demoSeederEnvironments')]
    public function test_seeders_create_demo_login_in_allowed_environments(string $seederClass, string $environment): void
    {
        $this->app['env'] = $environment;
        $this->partialMock(GalleryPhotoSeeder::class)->shouldReceive('run')->times($seederClass === DatabaseSeeder::class ? 1 : 0);

        $this->runSeeder($seederClass);

        $this->assertDatabaseHas('pengguna', [
            'email' => 'admin@example.com',
            'peran' => 'superadmin',
        ]);
        $user = User::where('email', 'admin@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('password', $user->kata_sandi));
        $this->assertNotNull($user->email_diverifikasi_pada);
    }

    #[DataProvider('allowedEnvironments')]
    public function test_e2e_seeder_resets_existing_demo_login_in_allowed_environments(string $environment): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'admin@example.com',
            'kata_sandi' => Hash::make('existing-secure-password'),
            'peran' => 'pengurus',
        ]);
        $this->app['env'] = $environment;

        $this->runSeeder(E2ETestSeeder::class);

        $user->refresh();
        $this->assertSame('Admin E2E Playwright', $user->nama);
        $this->assertSame('superadmin', $user->peran);
        $this->assertTrue(Hash::check('password', $user->kata_sandi));
        $this->assertNotNull($user->email_diverifikasi_pada);
        $this->assertDatabaseCount('pengguna', 1);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function restrictedEnvironments(): array
    {
        return [
            'production' => ['production'],
            'staging' => ['staging'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function allowedEnvironments(): array
    {
        return [
            'local' => ['local'],
            'testing' => ['testing'],
        ];
    }

    /**
     * @return array<string, array{class-string<Seeder>, string}>
     */
    public static function demoSeederEnvironments(): array
    {
        return [
            'database local' => [DatabaseSeeder::class, 'local'],
            'database testing' => [DatabaseSeeder::class, 'testing'],
            'e2e local' => [E2ETestSeeder::class, 'local'],
            'e2e testing' => [E2ETestSeeder::class, 'testing'],
        ];
    }

    /**
     * @param  class-string<Seeder>  $seederClass
     */
    private function runSeeder(string $seederClass): void
    {
        $seeder = $this->app->make($seederClass)->setContainer($this->app);

        User::unguarded(fn () => $seeder());
    }
}
