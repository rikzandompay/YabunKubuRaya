<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use App\Models\Katalog;
use App\Models\ProgramCategory;
use App\Services\FinanceSummaryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Tampilkan landing page utama lengkap dengan galeri, katalog, dan transparansi donasi.
     */
    public function index(Request $request): View
    {
        $validCategories = ['jumat_berkah', 'donasi', 'dzikir'];
        $category = $request->query('kategori', 'jumat_berkah');

        if (! in_array($category, $validCategories, true)) {
            $category = 'jumat_berkah';
        }

        $photos = GalleryPhoto::where('category', $category)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'image_url' => $photo->image_url,
                    'category' => $photo->category,
                    'is_featured' => $photo->is_featured,
                    'sort_order' => $photo->sort_order,
                ];
            });

        $totalCount = GalleryPhoto::where('category', $category)->count();
        $categories = ProgramCategory::all();

        // Data Dynamic Katalog dari Database
        $dbKatalogs = Katalog::with('program')
            ->where('status', 'aktif')
            ->orderBy('id')
            ->get();

        $katalogItems = $dbKatalogs->map(function ($k, $idx) {
            return [
                'id' => $idx,
                'title' => $k->judul,
                'desc' => $k->deskripsi,
                'image' => $k->gambar_url,
                'image_fallback' => asset('images/santri-yabun.jpeg'),
                'link' => '#donasi',
            ];
        })->toArray();

        // Data Transparansi Donasi Sesuai FR-ADM-07 & FR-ADM-02
        $transparencySummary = FinanceSummaryService::totalsByCategory();

        return view('welcome', compact(
            'photos',
            'category',
            'totalCount',
            'categories',
            'katalogItems',
            'transparencySummary'
        ));
    }
}
