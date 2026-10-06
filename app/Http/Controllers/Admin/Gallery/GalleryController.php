<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Tampilkan daftar foto kegiatan galeri untuk admin.
     */
    public function index(Request $request): View
    {
        $kategori = $request->query('kategori');
        $validCategories = ['jumat_berkah', 'donasi', 'dzikir'];

        $query = GalleryPhoto::orderBy('sort_order')->orderByDesc('id');

        if ($kategori && in_array($kategori, $validCategories, true)) {
            $query->where('category', $kategori);
        }

        $photos = $query->paginate(12)->withQueryString();
        $totalCount = GalleryPhoto::count();

        return view('admin.gallery.index', compact('photos', 'kategori', 'totalCount'));
    }

    /**
     * Simpan foto kegiatan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', GalleryPhoto::class);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'category' => 'required|in:jumat_berkah,donasi,dzikir',
            'activity_date' => 'nullable|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_featured' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        GalleryPhoto::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'activity_date' => $validated['activity_date'] ?? now()->toDateString(),
            'image_path' => $path,
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => (GalleryPhoto::max('sort_order') ?? 0) + 1,
        ]);

        return redirect()->route('admin.galeri.index', ['kategori' => $validated['category']])
            ->with('success', 'Foto kegiatan berhasil diunggah.');
    }

    /**
     * Perbarui foto kegiatan.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $photo = GalleryPhoto::findOrFail($id);

        $this->authorize('update', $photo);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'category' => 'required|in:jumat_berkah,donasi,dzikir',
            'activity_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($photo->image_path && ! str_starts_with($photo->image_path, 'http')) {
                Storage::disk('public')->delete($photo->image_path);
            }
            $photo->image_path = $request->file('image')->store('gallery', 'public');
        }

        $photo->title = $validated['title'];
        $photo->category = $validated['category'];
        if (! empty($validated['activity_date'])) {
            $photo->activity_date = $validated['activity_date'];
        }
        $photo->is_featured = $request->boolean('is_featured');
        $photo->save();

        return redirect()->route('admin.galeri.index', ['kategori' => $photo->category])
            ->with('success', 'Foto kegiatan berhasil diperbarui.');
    }

    /**
     * Hapus foto kegiatan.
     */
    public function destroy(int $id): RedirectResponse
    {
        $photo = GalleryPhoto::findOrFail($id);

        $this->authorize('delete', $photo);

        if ($photo->image_path && ! str_starts_with($photo->image_path, 'http')) {
            Storage::disk('public')->delete($photo->image_path);
        }

        $photo->delete();

        return back()->with('success', 'Foto kegiatan berhasil dihapus.');
    }
}
