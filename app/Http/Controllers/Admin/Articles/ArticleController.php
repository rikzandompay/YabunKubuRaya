<?php

namespace App\Http\Controllers\Admin\Articles;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ProgramCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Tampilkan daftar artikel untuk admin panel.
     */
    public function index(Request $request): View
    {
        $kategoriSlug = $request->query('kategori');
        $categories = ProgramCategory::orderBy('name')->get();

        $query = Article::with('programCategory')->orderByDesc('id');

        if ($kategoriSlug && $kategoriSlug !== 'semua') {
            $query->whereHas('programCategory', function ($q) use ($kategoriSlug) {
                $q->where('slug', $kategoriSlug);
            });
        }

        $articles = $query->paginate(10)->withQueryString();

        return view('admin.articles.index', compact('articles', 'categories', 'kategoriSlug'));
    }

    /**
     * Simpan artikel baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Article::class);

        $validated = $request->validate([
            'program_category_id' => 'required|exists:program_categories,id',
            'title' => 'required|string|max:200',
            'excerpt' => 'nullable|string|max:300',
            'content' => 'required|string',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_published' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('header_image')) {
            $imagePath = $request->file('header_image')->store('articles', 'public');
        }

        $cleanContent = Str::sanitizeHtml($validated['content']);

        Article::create([
            'program_category_id' => $validated['program_category_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::random(5),
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($cleanContent), 150),
            'content' => $cleanContent,
            'header_image' => $imagePath,
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diterbitkan.');
    }

    /**
     * Perbarui artikel yang sudah ada.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);

        $this->authorize('update', $article);

        $validated = $request->validate([
            'program_category_id' => 'required|exists:program_categories,id',
            'title' => 'required|string|max:200',
            'excerpt' => 'nullable|string|max:300',
            'content' => 'required|string',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_published' => 'nullable|boolean',
        ]);

        $cleanContent = Str::sanitizeHtml($validated['content']);

        $updateData = [
            'program_category_id' => $validated['program_category_id'],
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($cleanContent), 150),
            'content' => $cleanContent,
            'is_published' => $request->has('is_published'),
        ];

        if ($request->hasFile('header_image')) {
            if ($article->header_image && ! str_starts_with($article->header_image, 'http') && ! str_starts_with($article->header_image, 'images/')) {
                Storage::disk('public')->delete($article->header_image);
            }
            $updateData['header_image'] = $request->file('header_image')->store('articles', 'public');
        }

        if ($article->title !== $validated['title']) {
            $updateData['slug'] = Str::slug($validated['title']).'-'.Str::random(5);
        }

        if ($updateData['is_published'] && ! $article->published_at) {
            $updateData['published_at'] = now();
        }

        $article->update($updateData);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Hapus artikel.
     */
    public function destroy(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);

        $this->authorize('delete', $article);

        if ($article->header_image && ! str_starts_with($article->header_image, 'http') && ! str_starts_with($article->header_image, 'images/')) {
            Storage::disk('public')->delete($article->header_image);
        }

        $article->delete();

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
