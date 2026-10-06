<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ProgramCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Tampilkan halaman daftar artikel dengan filter & pencarian (FR-PUB-03).
     */
    public function index(Request $request): View
    {
        $categories = ProgramCategory::all();

        $kategori = $request->query('kategori', 'semua');
        $q = $request->query('q');
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $articles = Article::with('programCategory')
            ->published()
            ->category($kategori)
            ->search($q)
            ->dateRange($dari, $sampai)
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('articles.index', compact('articles', 'categories', 'kategori', 'q', 'dari', 'sampai'));
    }

    /**
     * Tampilkan halaman detail artikel berdasarkan slug.
     */
    public function show(string $slug): View
    {
        $article = Article::with('programCategory')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $relatedArticles = Article::with('programCategory')
            ->published()
            ->where('program_category_id', $article->program_category_id)
            ->where('id', '!=', $article->id)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
