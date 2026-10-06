<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Donatur;
use App\Models\FinancialTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Dynamic Stats from FinancialTransaction (Kas Manajemen)
        $totalDonasi = (float) FinancialTransaction::whereHas('donor')->pemasukan()->sum('amount');
        $totalPembangunan = (float) FinancialTransaction::whereHas('donor')->pemasukan()->kategori('pembangunan_pondok_tahfidz')->sum('amount');
        $totalJumatBerkah = (float) FinancialTransaction::whereHas('donor')->pemasukan()->kategori('jumat_berkah')->sum('amount');
        $totalDonasiBantuan = (float) FinancialTransaction::whereHas('donor')->pemasukan()->kategori('donasi_bantuan')->sum('amount');

        $stats = [
            [
                'title' => 'Total Donasi Masuk',
                'icon' => 'banknotes',
                'value' => $totalDonasi,
                'delta' => 7.1,
                'positive' => true,
                'sparkline' => 'M0,24 C8,20 16,28 24,16 32,20 40,12 48,18 56,8 64,14 72,6 80,10',
                'sparkline_area' => 'M0,24 C8,20 16,28 24,16 32,20 40,12 48,18 56,8 64,14 72,6 80,10 L80,32 L0,32 Z',
            ],
            [
                'title' => 'Uang Pembangunan',
                'icon' => 'building',
                'value' => $totalPembangunan,
                'delta' => 2.0,
                'positive' => true,
                'sparkline' => 'M0,20 C10,22 20,18 30,20 40,16 50,14 60,18 70,12 80,14',
                'sparkline_area' => 'M0,20 C10,22 20,18 30,20 40,16 50,14 60,18 70,12 80,14 L80,32 L0,32 Z',
            ],
            [
                'title' => 'Uang Jumat Berkah',
                'icon' => 'mosque',
                'value' => $totalJumatBerkah,
                'delta' => 1.3,
                'positive' => true,
                'sparkline' => 'M0,10 C10,12 20,8 30,14 40,16 50,12 60,20 70,18 80,24',
                'sparkline_area' => 'M0,10 C10,12 20,8 30,14 40,16 50,12 60,20 70,18 80,24 L80,32 L0,32 Z',
            ],
            [
                'title' => 'Uang Donasi',
                'icon' => 'heart',
                'value' => $totalDonasiBantuan,
                'delta' => 0.0,
                'positive' => true,
                'sparkline' => 'M0,18 C10,14 20,20 30,12 40,16 50,10 60,14 70,8 80,12',
                'sparkline_area' => 'M0,18 C10,14 20,20 30,12 40,16 50,10 60,14 70,8 80,12 L80,32 L0,32 Z',
            ],
        ];

        // 2. Dynamic Top Donors from database
        $totalDonorsCount = Donatur::count();

        $dbTopDonors = Donatur::withCount('financialTransactions')
            ->withSum(['financialTransactions' => fn ($q) => $q->where('type', 'pemasukan')], 'amount')
            ->whereHas('financialTransactions', fn ($q) => $q->where('type', 'pemasukan'))
            ->orderByDesc('financial_transactions_sum_amount')
            ->orderByDesc('financial_transactions_count')
            ->take(6)
            ->get();

        $topDonors = $dbTopDonors->map(function ($donor) {
            return [
                'name' => $donor->nama_donatur,
                'donations_count' => (int) $donor->financial_transactions_count,
                'total' => (float) ($donor->financial_transactions_sum_amount ?? 0),
                'is_anonymous' => ($donor->tipe_donatur === 'anonim'),
            ];
        })->toArray();

        // 3. Dynamic Recent Transactions
        $dbTransactions = FinancialTransaction::whereHas('donor')
            ->with('donor')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->take(15)
            ->get();

        $categoryLabels = [
            'jumat_berkah' => 'Jumat Berkah',
            'donasi_bantuan' => 'Donasi Bantuan',
            'pembangunan_pondok_tahfidz' => 'Pembangunan Pondok Tahfidz',
        ];

        $donations = $dbTransactions->map(function ($t) use ($categoryLabels) {
            return [
                'id' => 'DN-'.str_pad($t->id, 4, '0', STR_PAD_LEFT),
                'donor_name' => $t->donor?->nama_donatur ?? 'Hamba Allah',
                'type' => $categoryLabels[$t->category] ?? ucwords(str_replace('_', ' ', $t->category)),
                'amount' => (float) $t->amount,
                'status' => 'Terverifikasi',
                'date' => $t->transaction_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
                'method' => 'Transfer Bank',
                'is_anonymous' => ($t->donor?->tipe_donatur === 'anonim'),
            ];
        })->toArray();

        // 4. Dynamic Chart Data from Kas Manajemen
        $dayMap = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min'];
        $monthMap = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        // A. Harian: 7 hari dalam minggu berjalan (dimulai dari Senin di ujung kiri hingga Minggu)
        $harianLabels = [];
        $harianSubtitles = [];
        $harianValues = [];
        $harianStart = now()->startOfWeek()->startOfDay();
        $harianEnd = now()->endOfWeek()->endOfDay();

        $dailyTransactions = FinancialTransaction::whereHas('donor')
            ->pemasukan()
            ->whereDate('transaction_date', '>=', $harianStart->toDateString())
            ->whereDate('transaction_date', '<=', $harianEnd->toDateString())
            ->get();

        for ($i = 0; $i < 7; $i++) {
            $date = $harianStart->copy()->addDays($i);
            $dateStr = $date->toDateString();
            $harianLabels[] = $dayMap[$date->dayOfWeekIso];
            $harianSubtitles[] = $date->isToday() ? 'Hari Ini' : $date->day.' '.$monthMap[$date->month];

            $sumDay = (float) $dailyTransactions->filter(function ($tx) use ($dateStr) {
                return Carbon::parse($tx->transaction_date)->toDateString() === $dateStr;
            })->sum('amount');

            $harianValues[] = $sumDay;
        }

        // B. Mingguan: 4 minggu terakhir (rolling 4 weeks)
        $mingguanLabels = [];
        $mingguanSubtitles = [];
        $mingguanValues = [];
        $fourWeeksAgo = now()->subWeeks(3)->startOfWeek();
        $thisWeekEnd = now()->endOfWeek();

        $weeklyTransactions = FinancialTransaction::whereHas('donor')
            ->pemasukan()
            ->whereDate('transaction_date', '>=', $fourWeeksAgo->toDateString())
            ->whereDate('transaction_date', '<=', $thisWeekEnd->toDateString())
            ->get();

        for ($i = 3; $i >= 0; $i--) {
            $wStart = now()->subWeeks($i)->startOfWeek();
            $wEnd = now()->subWeeks($i)->endOfWeek();

            $wStartStr = $wStart->toDateString();
            $wEndStr = $wEnd->toDateString();

            $mingguanLabels[] = 'Mgg '.(4 - $i);
            $mingguanSubtitles[] = $i === 0 ? 'Minggu Ini' : $wStart->day.' '.$monthMap[$wStart->month].' - '.$wEnd->day.' '.$monthMap[$wEnd->month];

            $sumWeek = (float) $weeklyTransactions->filter(function ($tx) use ($wStartStr, $wEndStr) {
                $txDate = Carbon::parse($tx->transaction_date)->toDateString();

                return $txDate >= $wStartStr && $txDate <= $wEndStr;
            })->sum('amount');

            $mingguanValues[] = $sumWeek;
        }

        // C. Bulanan: 12 bulan dalam tahun berjalan
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();
        $monthlyTransactions = FinancialTransaction::whereHas('donor')
            ->pemasukan()
            ->whereDate('transaction_date', '>=', $startOfYear->toDateString())
            ->whereDate('transaction_date', '<=', $endOfYear->toDateString())
            ->get();

        $bulananValues = array_fill(0, 12, 0);
        foreach ($monthlyTransactions as $tx) {
            $monthIndex = Carbon::parse($tx->transaction_date)->month - 1;
            if ($monthIndex >= 0 && $monthIndex <= 11) {
                $bulananValues[$monthIndex] += (float) $tx->amount;
            }
        }
        $bulananLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $bulananSubtitles = array_map(fn ($m) => $m.' '.now()->year, $bulananLabels);

        // D. Tahunan: 5 tahun terakhir
        $currentYear = (int) now()->year;
        $years = [
            $currentYear - 4,
            $currentYear - 3,
            $currentYear - 2,
            $currentYear - 1,
            $currentYear,
        ];
        $tahunanLabels = array_map('strval', $years);

        $yearlyTransactions = FinancialTransaction::whereHas('donor')
            ->pemasukan()
            ->whereYear('transaction_date', '>=', $years[0])
            ->get();

        $tahunanValues = array_fill(0, 5, 0);
        foreach ($yearlyTransactions as $tx) {
            $txYear = Carbon::parse($tx->transaction_date)->year;
            $yIdx = array_search($txYear, $years, true);
            if ($yIdx !== false) {
                $tahunanValues[$yIdx] += (float) $tx->amount;
            }
        }

        $chartData = [
            'harian' => [
                'period_title' => '7 Hari Terakhir',
                'labels' => $harianLabels,
                'subtitles' => $harianSubtitles,
                'values' => $harianValues,
            ],
            'mingguan' => [
                'period_title' => '4 Minggu Terakhir',
                'labels' => $mingguanLabels,
                'subtitles' => $mingguanSubtitles,
                'values' => $mingguanValues,
            ],
            'bulanan' => [
                'period_title' => 'Tahun '.now()->year,
                'labels' => $bulananLabels,
                'subtitles' => $bulananSubtitles,
                'values' => $bulananValues,
            ],
            'tahunan' => [
                'period_title' => '5 Tahun Terakhir',
                'labels' => $tahunanLabels,
                'subtitles' => $tahunanLabels,
                'values' => $tahunanValues,
            ],
        ];

        return view('admin.dashboard.index', compact('stats', 'topDonors', 'donations', 'chartData', 'totalDonorsCount'));
    }
}
