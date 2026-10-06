<?php

namespace App\Http\Controllers\Admin\Donors;

use App\Http\Controllers\Controller;
use App\Models\Donatur;
use App\Models\FinancialTransaction;
use App\Services\FinanceSummaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonorController extends Controller
{
    /**
     * Tampilkan daftar donatur dan riwayat kontribusi.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $categoryFilter = $request->query('kategori');
        $typeFilter = $request->query('tipe');

        $query = Donatur::withCount('financialTransactions')
            ->withSum(['financialTransactions' => fn ($q) => $q->where('type', 'pemasukan')], 'amount')
            ->with(['financialTransactions' => fn ($q) => $q->where('type', 'pemasukan')->orderByDesc('id')])
            ->orderByDesc('id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_donatur', 'like', "%{$search}%")
                    ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        if ($categoryFilter && in_array($categoryFilter, ['jumat_berkah', 'donasi_bantuan', 'pembangunan_pondok_tahfidz'], true)) {
            $query->whereHas('financialTransactions', function ($q) use ($categoryFilter) {
                $q->where('type', 'pemasukan')->where('category', $categoryFilter);
            });
        }

        if ($typeFilter && in_array($typeFilter, ['individu', 'lembaga', 'anonim'], true)) {
            $query->where('tipe_donatur', $typeFilter);
        }

        $donors = $query->paginate(15)->withQueryString();

        return view('admin.donors.index', compact('donors', 'search', 'categoryFilter', 'typeFilter'));
    }

    /**
     * Simpan data donatur baru beserta transaksi donasi awal.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Donatur::class);

        if ($request->filled('total_donasi')) {
            $request->merge([
                'total_donasi' => preg_replace('/[^0-9]/', '', (string) $request->input('total_donasi')),
            ]);
        }

        $validated = $request->validate([
            'nama_donatur' => 'required|string|max:150',
            'nomor_hp' => 'nullable|string|max:30',
            'tipe_donatur' => 'required|in:individu,lembaga,anonim',
            'kategori' => 'required|in:jumat_berkah,donasi_bantuan,pembangunan_pondok_tahfidz',
            'total_donasi' => 'required|numeric|min:1000',
            'tanggal_donasi' => 'required|date',
        ], [
            'nama_donatur.required' => 'Nama donatur wajib diisi.',
            'tipe_donatur.required' => 'Tipe donatur wajib dipilih.',
            'kategori.required' => 'Kategori donasi wajib dipilih.',
            'total_donasi.required' => 'Total donasi wajib diisi dan tidak boleh kosong.',
            'total_donasi.min' => 'Total donasi minimal Rp 1.000.',
            'tanggal_donasi.required' => 'Tanggal donasi wajib diisi.',
        ]);

        $donatur = Donatur::create([
            'nama_donatur' => $validated['nama_donatur'],
            'nomor_hp' => $validated['nomor_hp'] ?? null,
            'tipe_donatur' => $validated['tipe_donatur'],
        ]);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => $validated['kategori'],
            'amount' => $validated['total_donasi'],
            'donor_id' => $donatur->id,
            'description' => 'Pencatatan donasi donatur '.$donatur->nama_donatur,
            'transaction_date' => $validated['tanggal_donasi'],
        ]);

        FinanceSummaryService::clearCache();

        return redirect()->route('admin.donatur.index')
            ->with('success', 'Data donatur dan donasi berhasil ditambahkan.');
    }

    /**
     * Perbarui data donatur beserta transaksi donasinya.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $donor = Donatur::findOrFail($id);

        $this->authorize('update', $donor);

        if ($request->filled('total_donasi')) {
            $request->merge([
                'total_donasi' => preg_replace('/[^0-9]/', '', (string) $request->input('total_donasi')),
            ]);
        }

        $validated = $request->validate([
            'nama_donatur' => 'required|string|max:150',
            'nomor_hp' => 'nullable|string|max:30',
            'tipe_donatur' => 'required|in:individu,lembaga,anonim',
            'kategori' => 'required|in:jumat_berkah,donasi_bantuan,pembangunan_pondok_tahfidz',
            'total_donasi' => 'required|numeric|min:1000',
            'tanggal_donasi' => 'required|date',
        ], [
            'nama_donatur.required' => 'Nama donatur wajib diisi.',
            'tipe_donatur.required' => 'Tipe donatur wajib dipilih.',
            'kategori.required' => 'Kategori donasi wajib dipilih.',
            'total_donasi.required' => 'Total donasi wajib diisi dan tidak boleh kosong.',
            'total_donasi.min' => 'Total donasi minimal Rp 1.000.',
            'tanggal_donasi.required' => 'Tanggal donasi wajib diisi.',
        ]);

        $donor->update([
            'nama_donatur' => $validated['nama_donatur'],
            'nomor_hp' => $validated['nomor_hp'] ?? null,
            'tipe_donatur' => $validated['tipe_donatur'],
        ]);

        $transaction = $donor->financialTransactions()->where('type', 'pemasukan')->latest('id')->first();

        if ($transaction) {
            $transaction->update([
                'category' => $validated['kategori'],
                'amount' => $validated['total_donasi'],
                'transaction_date' => $validated['tanggal_donasi'],
            ]);
        } else {
            FinancialTransaction::create([
                'type' => 'pemasukan',
                'category' => $validated['kategori'],
                'amount' => $validated['total_donasi'],
                'donor_id' => $donor->id,
                'description' => 'Pencatatan donasi donatur '.$donor->nama_donatur,
                'transaction_date' => $validated['tanggal_donasi'],
            ]);
        }

        FinanceSummaryService::clearCache();

        return redirect()->route('admin.donatur.index')
            ->with('success', 'Data donatur dan donasi berhasil diperbarui.');
    }

    /**
     * Hapus data donatur.
     */
    public function destroy(int $id): RedirectResponse
    {
        $donor = Donatur::findOrFail($id);

        $this->authorize('delete', $donor);

        $donor->financialTransactions()->delete();
        $donor->delete();

        FinanceSummaryService::clearCache();

        return back()->with('success', 'Data donatur dan riwayat donasi terkait berhasil dihapus.');
    }
}
