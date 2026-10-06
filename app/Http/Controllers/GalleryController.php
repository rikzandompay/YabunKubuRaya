<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Tampilkan galeri foto kegiatan dengan filter kategori.
     */
    public function index(Request $request): View|JsonResponse
    {
        $validCategories = ['semua', 'jumat_berkah', 'donasi', 'dzikir'];
        $category = $request->query('kategori', 'semua');

        if (! in_array($category, $validCategories, true)) {
            $category = 'semua';
        }

        // AJAX / JSON request dari tab filter di Landing Page
        if ($request->wantsJson() || $request->ajax() || $request->has('json')) {
            $catFilter = in_array($category, ['jumat_berkah', 'donasi', 'dzikir'], true) ? $category : 'jumat_berkah';

            $photos = GalleryPhoto::where('category', $catFilter)
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($photo) => [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'image_url' => $photo->image_url,
                    'category' => $photo->category,
                    'is_featured' => $photo->is_featured,
                    'sort_order' => $photo->sort_order,
                ]);

            $totalCount = GalleryPhoto::where('category', $catFilter)->count();

            return response()->json([
                'success' => true,
                'category' => $catFilter,
                'total' => $totalCount,
                'data' => $photos,
            ]);
        }

        // Web view untuk Halaman Semua Galeri (/galeri)
        $query = GalleryPhoto::query();
        if ($category !== 'semua') {
            $query->where('category', $category);
        }

        $photos = $query->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $categoryCounts = [
            'semua' => GalleryPhoto::count(),
            'jumat_berkah' => GalleryPhoto::where('category', 'jumat_berkah')->count(),
            'donasi' => GalleryPhoto::where('category', 'donasi')->count(),
            'dzikir' => GalleryPhoto::where('category', 'dzikir')->count(),
        ];

        return view('galeri.index', compact('photos', 'category', 'categoryCounts'));
    }
}
