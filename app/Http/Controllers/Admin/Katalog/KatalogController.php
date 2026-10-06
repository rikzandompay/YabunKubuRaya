<?php

namespace App\Http\Controllers\Admin\Katalog;

use App\Http\Controllers\Controller;
use App\Models\Katalog;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KatalogController extends Controller
{
    /**
     * Tampilkan daftar item katalog dengan filter program.
     */
    public function index(Request $request): View
    {
        $programFilter = $request->query('program');

        $programs = Program::orderBy('nama_program')->get();

        $query = Katalog::with('program')->orderByDesc('id');

        if ($programFilter) {
            $query->whereHas('program', function ($q) use ($programFilter) {
                $q->where('slug', $programFilter);
            });
        }

        $items = $query->paginate(10)->withQueryString();

        return view('admin.katalog.index', compact('items', 'programs', 'programFilter'));
    }

    /**
     * Simpan item katalog baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Katalog::class);

        $validated = $request->validate([
            'program_id' => 'required|exists:program,id',
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'target_penerima' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['judul']).'-'.Str::random(5);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('katalog', 'public');
        }

        Katalog::create($validated);

        return redirect()->route('admin.katalog.index')
            ->with('success', 'Item katalog berhasil ditambahkan.');
    }

    /**
     * Perbarui item katalog.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $item = Katalog::findOrFail($id);

        $this->authorize('update', $item);

        $validated = $request->validate([
            'program_id' => 'required|exists:program,id',
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'target_penerima' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('gambar')) {
            if ($item->gambar && ! str_starts_with($item->gambar, 'http') && ! str_starts_with($item->gambar, 'images/')) {
                Storage::disk('public')->delete($item->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('katalog', 'public');
        }

        $item->update($validated);

        return redirect()->route('admin.katalog.index')
            ->with('success', 'Item katalog berhasil diperbarui.');
    }

    /**
     * Hapus item katalog.
     */
    public function destroy(int $id): RedirectResponse
    {
        $item = Katalog::findOrFail($id);

        $this->authorize('delete', $item);

        if ($item->gambar && ! str_starts_with($item->gambar, 'http') && ! str_starts_with($item->gambar, 'images/')) {
            Storage::disk('public')->delete($item->gambar);
        }

        $item->delete();

        return back()->with('success', 'Item katalog berhasil dihapus.');
    }
}
