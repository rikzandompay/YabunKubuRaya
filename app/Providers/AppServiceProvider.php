<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Donatur;
use App\Models\FinancialTransaction;
use App\Models\GalleryPhoto;
use App\Models\Katalog;
use App\Models\Pengaturan;
use App\Models\RekeningBank;
use App\Policies\ArticlePolicy;
use App\Policies\DonaturPolicy;
use App\Policies\FinancialTransactionPolicy;
use App\Policies\GalleryPhotoPolicy;
use App\Policies\KatalogPolicy;
use App\Policies\RekeningBankPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS URL generation when on production or behind SSL reverse proxy / tunnel
        if ($this->app->environment('production') || request()->header('x-forwarded-proto') === 'https' || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }

        // Register policies untuk otorisasi admin
        Gate::policy(FinancialTransaction::class, FinancialTransactionPolicy::class);
        Gate::policy(Donatur::class, DonaturPolicy::class);
        Gate::policy(GalleryPhoto::class, GalleryPhotoPolicy::class);
        Gate::policy(Article::class, ArticlePolicy::class);
        Gate::policy(Katalog::class, KatalogPolicy::class);
        Gate::policy(RekeningBank::class, RekeningBankPolicy::class);

        // SECURITY: Rate limit login attempts untuk mencegah brute force
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Bagikan data pengaturan profil yayasan dan rekening bank aktif ke landing page, components & footer
        view()->composer(['layouts.app', 'welcome', 'components.*', 'sections.*'], function ($view) {
            try {
                $settings = Pengaturan::all()->pluck('nilai', 'kunci')->toArray();
                $bankAccounts = RekeningBank::where('status_aktif', true)->orderBy('id')->get();
            } catch (\Throwable $e) {
                $settings = [];
                $bankAccounts = collect();
            }

            $view->with('siteSettings', $settings);
            $view->with('bankAccounts', $bankAccounts);
        });
    }
}
