<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Tampilkan sitemap XML dinamis untuk mesin pencari (SEO).
     */
    public function index(): Response
    {
        $articles = Article::published()
            ->select('id', 'slug', 'updated_at', 'published_at')
            ->orderByDesc('published_at')
            ->get();

        $content = view('sitemap', compact('articles'))->render();

        return response($content, 200, [
            'Content-Type' => 'text/xml; charset=utf-8',
        ]);
    }
}
