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
use Illuminate\Support\Facades\Gate;
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
        // Force HTTPS URL generation when behind SSL reverse proxy / tunnel or on production domain
        $isLocalHost = in_array(request()->getHost(), ['127.0.0.1', 'localhost']);
        if (request()->isSecure() || ($this->app->environment('production') && ! $isLocalHost)) {
            URL::forceScheme('https');
        }

        // Register policies untuk otorisasi admin
        Gate::policy(FinancialTransaction::class, FinancialTransactionPolicy::class);
        Gate::policy(Donatur::class, DonaturPolicy::class);
        Gate::policy(GalleryPhoto::class, GalleryPhotoPolicy::class);
        Gate::policy(Article::class, ArticlePolicy::class);
        Gate::policy(Katalog::class, KatalogPolicy::class);
        Gate::policy(RekeningBank::class, RekeningBankPolicy::class);

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
