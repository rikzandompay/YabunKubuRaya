<?php

use App\Http\Controllers\Admin\Articles\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Donors\DonorController;
use App\Http\Controllers\Admin\Finance\FinancialTransactionController;
use App\Http\Controllers\Admin\Gallery\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\Katalog\KatalogController as AdminKatalogController;
use App\Http\Controllers\Admin\Settings\SettingController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/galeri', [GalleryController::class, 'index'])->name('galeri.index');

Route::get('/artikel', [ArticleController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('artikel.show');

Route::get('/googlecc04ffb1e40ce9fe.html', function () {
    return response("google-site-verification: googlecc04ffb1e40ce9fe.html\n", 200, [
        'Content-Type' => 'text/html; charset=utf-8',
    ]);
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/robots.txt', function () {
    $sitemapUrl = url('/sitemap.xml');
    $content = "User-agent: *\nDisallow: /admin/\nDisallow: /admin\nAllow: /\n\nSitemap: {$sitemapUrl}\n";

    return response($content, 200, [
        'Content-Type' => 'text/plain; charset=utf-8',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
    ]);
});

Route::redirect('/login', '/admin/login')->name('login');
Route::redirect('/admin', '/admin/dashboard');

// Admin Panel Modules (FR-ADM-02, 03, 04, 05, 06, 07)
// SECURITY: Semua route admin wajib login (auth) + role admin/superadmin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

    // FR-ADM-03 Manajemen Kegiatan Foto
    Route::get('/galeri', [AdminGalleryController::class, 'index'])->name('galeri.index');
    Route::post('/galeri', [AdminGalleryController::class, 'store'])->name('galeri.store');
    Route::put('/galeri/{id}', [AdminGalleryController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{id}', [AdminGalleryController::class, 'destroy'])->name('galeri.destroy');

    // FR-ADM-04 Manajemen Katalog
    Route::get('/katalog', [AdminKatalogController::class, 'index'])->name('katalog.index');
    Route::post('/katalog', [AdminKatalogController::class, 'store'])->name('katalog.store');
    Route::put('/katalog/{id}', [AdminKatalogController::class, 'update'])->name('katalog.update');
    Route::delete('/katalog/{id}', [AdminKatalogController::class, 'destroy'])->name('katalog.destroy');

    // FR-ADM-05 Manajemen Artikel Program
    Route::get('/artikel', [AdminArticleController::class, 'index'])->name('artikel.index');
    Route::post('/artikel', [AdminArticleController::class, 'store'])->name('artikel.store');
    Route::put('/artikel/{id}', [AdminArticleController::class, 'update'])->name('artikel.update');
    Route::delete('/artikel/{id}', [AdminArticleController::class, 'destroy'])->name('artikel.destroy');

    // FR-ADM-06 Manajemen Donatur
    Route::get('/donatur', [DonorController::class, 'index'])->name('donatur.index');
    Route::post('/donatur', [DonorController::class, 'store'])->name('donatur.store');
    Route::put('/donatur/{id}', [DonorController::class, 'update'])->name('donatur.update');
    Route::delete('/donatur/{id}', [DonorController::class, 'destroy'])->name('donatur.destroy');

    // FR-ADM-07 Manajemen Keuangan & Transparansi
    Route::get('/keuangan', [FinancialTransactionController::class, 'index'])->name('keuangan.index');
    Route::get('/keuangan/pdf', [FinancialTransactionController::class, 'exportPdf'])->name('keuangan.pdf');
    Route::post('/keuangan', [FinancialTransactionController::class, 'store'])->name('keuangan.store');
    Route::delete('/keuangan/{id}', [FinancialTransactionController::class, 'destroy'])->name('keuangan.destroy');

    // Pengaturan Sistem & Profil Yayasan
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/general', [SettingController::class, 'updateGeneral'])->name('setting.general.update');
    Route::post('/setting/bank', [SettingController::class, 'storeBank'])->name('setting.bank.store');
    Route::delete('/setting/bank/{id}', [SettingController::class, 'destroyBank'])->name('setting.bank.destroy');
    Route::put('/setting/profile', [SettingController::class, 'updateProfile'])->name('setting.profile.update');
});
