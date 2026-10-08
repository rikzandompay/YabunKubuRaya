<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Donatur;
use App\Models\FinancialTransaction;
use App\Models\Pengaturan;
use App\Services\FinanceSummaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialTransactionController extends Controller
{
    /**
     * Tampilkan halaman manajemen keuangan admin dengan filter dan ringkasan.
     */
    public function index(Request $request): View
    {
        $categoryFilter = $request->query('kategori');
        $validCategories = ['jumat_berkah', 'donasi_bantuan', 'pembangunan_pondok_tahfidz'];

        $query = FinancialTransaction::whereHas('donor')
            ->with('donor')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id');

        if ($categoryFilter && in_array($categoryFilter, $validCategories, true)) {
            $query->where('category', $categoryFilter);
        }

        $transactions = $query->paginate(15)->withQueryString();
        $summary = FinanceSummaryService::totalsByCategory(fresh: true);
        $donors = Donatur::orderBy('nama_donatur')->get();
        $totalTransactionsCount = FinancialTransaction::whereHas('donor')->pemasukan()->count();
        $totalDonorsCount = Donatur::count();

        return view('admin.finance.index', compact(
            'transactions',
            'summary',
            'donors',
            'categoryFilter',
            'totalTransactionsCount',
            'totalDonorsCount'
        ));
    }

    /**
     * Simpan transaksi keuangan baru dan sinkronkan statistik.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', FinancialTransaction::class);

        if ($request->filled('amount')) {
            $request->merge([
                'amount' => preg_replace('/[^0-9]/', '', (string) $request->input('amount')),
            ]);
        }

        $validated = $request->validate([
            'type' => 'required|in:pemasukan,pengeluaran',
            'category' => 'required|in:jumat_berkah,donasi_bantuan,pembangunan_pondok_tahfidz',
            'amount' => 'required|numeric|min:1',
            'donor_id' => 'required|exists:donatur,id',
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ], [
            'donor_id.required' => 'Donatur wajib dipilih untuk setiap transaksi donasi.',
            'donor_id.exists' => 'Data donatur tidak valid.',
        ]);

        FinancialTransaction::create($validated);

        return redirect()->route('admin.keuangan.index')->with('success', 'Transaksi berhasil disimpan. Statistik transparansi donasi telah diperbarui secara otomatis.');
    }

    /**
     * Hapus transaksi keuangan.
     */
    public function destroy(int $id): RedirectResponse
    {
        $transaction = FinancialTransaction::findOrFail($id);

        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('admin.keuangan.index')->with('success', 'Transaksi berhasil dihapus dan statistik telah disinkronkan kembali.');
    }

    /**
     * Cetak atau unduh laporan transparansi keuangan ke format PDF.
     */
    public function exportPdf(Request $request): View
    {
        $categoryFilter = $request->query('kategori');
        $validCategories = ['jumat_berkah', 'donasi_bantuan', 'pembangunan_pondok_tahfidz'];

        $periodFilter = $request->query('periode');

        $query = FinancialTransaction::whereHas('donor')
            ->with('donor')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id');

        if ($categoryFilter && in_array($categoryFilter, $validCategories, true)) {
            $query->where('category', $categoryFilter);
        }

        if ($periodFilter && $periodFilter !== 'semua') {
            $range = match ($periodFilter) {
                'hari_ini' => [now()->startOfDay(), now()->endOfDay()],
                'minggu_ini' => [now()->startOfWeek(), now()->endOfWeek()],
                'minggu_lalu' => [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()],
                'bulan_ini' => [now()->startOfMonth(), now()->endOfMonth()],
                'tahun_ini' => [now()->startOfYear(), now()->endOfYear()],
                default => null,
            };

            if ($range) {
                $query->whereDate('transaction_date', '>=', $range[0]->toDateString())
                    ->whereDate('transaction_date', '<=', $range[1]->toDateString());
            }
        }

        $transactions = $query->get();
        $summary = FinanceSummaryService::totalsByCategory(fresh: true);
        $settings = Pengaturan::all()->pluck('nilai', 'kunci')->toArray();
        $admin = auth()->user();

        return view('admin.finance.pdf', compact(
            'transactions',
            'summary',
            'settings',
            'admin',
            'categoryFilter',
            'periodFilter'
        ));
    }
}
