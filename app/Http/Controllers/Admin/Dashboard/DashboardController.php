<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Donatur;
use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * Opsi periode filtering yang didukung di Dashboard.
     *
     * @var array<string, string>
     */
    public const PERIOD_OPTIONS = [
        'semua' => 'Semua Periode',
        'hari_ini' => 'Hari Ini',
        'minggu_ini' => 'Minggu Ini',
        'minggu_lalu' => 'Minggu Lalu',
        'bulan_ini' => 'Bulan Ini',
        'tahun_ini' => 'Tahun Ini',
    ];

    public function index(Request $request)
    {
        $periodKey = $this->resolvePeriod((string) $request->query('periode', 'semua'));
        $periodRange = $this->getPeriodRange($periodKey);

        // 1. Hitung statistik dinamis dan sparkline untuk 4 Card KPI
        $stats = [
            $this->buildStatCard(
                title: 'Total Donasi Masuk',
                icon: 'banknotes',
                category: 'total',
                periodKey: $periodKey,
                range: $periodRange
            ),
            $this->buildStatCard(
                title: 'Uang Pembangunan',
                icon: 'building',
                category: 'pembangunan_pondok_tahfidz',
                periodKey: $periodKey,
                range: $periodRange
            ),
            $this->buildStatCard(
                title: 'Uang Jumat Berkah',
                icon: 'mosque',
                category: 'jumat_berkah',
                periodKey: $periodKey,
                range: $periodRange
            ),
            $this->buildStatCard(
                title: 'Uang Donasi',
                icon: 'heart',
                category: 'donasi_bantuan',
                periodKey: $periodKey,
                range: $periodRange
            ),
        ];

        // 2. Dynamic Top Donors sesuai periode (fallback ke keseluruhan bila periode kosong)
        $totalDonorsCount = Donatur::count();

        $topDonorsQuery = Donatur::withCount(['financialTransactions' => function ($q) use ($periodRange) {
            $q->where('type', 'pemasukan');
            if ($periodRange['current_start'] && $periodRange['current_end']) {
                $q->whereDate('transaction_date', '>=', $periodRange['current_start']->toDateString())
                    ->whereDate('transaction_date', '<=', $periodRange['current_end']->toDateString());
            }
        }])
            ->withSum(['financialTransactions' => function ($q) use ($periodRange) {
                $q->where('type', 'pemasukan');
                if ($periodRange['current_start'] && $periodRange['current_end']) {
                    $q->whereDate('transaction_date', '>=', $periodRange['current_start']->toDateString())
                        ->whereDate('transaction_date', '<=', $periodRange['current_end']->toDateString());
                }
            }], 'amount')
            ->whereHas('financialTransactions', function ($q) use ($periodRange) {
                $q->where('type', 'pemasukan');
                if ($periodRange['current_start'] && $periodRange['current_end']) {
                    $q->whereDate('transaction_date', '>=', $periodRange['current_start']->toDateString())
                        ->whereDate('transaction_date', '<=', $periodRange['current_end']->toDateString());
                }
            })
            ->orderByDesc('financial_transactions_sum_amount')
            ->orderByDesc('financial_transactions_count')
            ->take(6);

        $dbTopDonors = $topDonorsQuery->get();

        // Jika tidak ada transaksi di periode tersebut, tampilkan donor keseluruhan agar UI tetap terisi
        if ($dbTopDonors->isEmpty() && $periodKey !== 'semua') {
            $dbTopDonors = Donatur::withCount('financialTransactions')
                ->withSum(['financialTransactions' => fn ($q) => $q->where('type', 'pemasukan')], 'amount')
                ->whereHas('financialTransactions', fn ($q) => $q->where('type', 'pemasukan'))
                ->orderByDesc('financial_transactions_sum_amount')
                ->orderByDesc('financial_transactions_count')
                ->take(6)
                ->get();
        }

        $topDonors = $dbTopDonors->map(function ($donor) {
            return [
                'name' => $donor->nama_donatur,
                'donations_count' => (int) $donor->financial_transactions_count,
                'total' => (float) ($donor->financial_transactions_sum_amount ?? 0),
                'is_anonymous' => ($donor->tipe_donatur === 'anonim'),
            ];
        })->toArray();

        // 3. Dynamic Recent Transactions sesuai periode
        $txQuery = FinancialTransaction::whereHas('donor')
            ->with('donor')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id');

        if ($periodRange['current_start'] && $periodRange['current_end']) {
            $txQuery->whereDate('transaction_date', '>=', $periodRange['current_start']->toDateString())
                ->whereDate('transaction_date', '<=', $periodRange['current_end']->toDateString());
        }

        $dbTransactions = $txQuery->take(15)->get();

        // Jika tidak ada di periode yang difilter, ambil transaksi terbaru keseluruhan
        if ($dbTransactions->isEmpty() && $periodKey !== 'semua') {
            $dbTransactions = FinancialTransaction::whereHas('donor')
                ->with('donor')
                ->orderByDesc('transaction_date')
                ->orderByDesc('id')
                ->take(15)
                ->get();
        }

        $categoryLabels = [
            'jumat_berkah' => 'Jumat Berkah',
            'donasi_bantuan' => 'Donasi Bantuan',
            'pembangunan_pondok_tahfidz' => 'Pembangunan Pondok Tahfidz',
        ];

        $donations = $dbTransactions->map(function ($t) use ($categoryLabels) {
            return [
                'id' => 'DN-'.str_pad((string) $t->id, 4, '0', STR_PAD_LEFT),
                'donor_name' => $t->donor?->nama_donatur ?? 'Hamba Allah',
                'type' => $categoryLabels[$t->category] ?? ucwords(str_replace('_', ' ', (string) $t->category)),
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

        // A. Harian: 7 hari dalam minggu berjalan (Senin s/d Minggu)
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

        // B. Mingguan: 4 minggu terakhir
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

        $periodOptions = self::PERIOD_OPTIONS;
        $currentPeriodKey = $periodKey;
        $currentPeriodLabel = self::PERIOD_OPTIONS[$periodKey] ?? 'Semua Periode';

        // 5. Data Donut Chart: Proporsi Kategori Pemasukan Donasi
        $categoriesMeta = [
            'pembangunan_pondok_tahfidz' => [
                'name' => 'Pembangunan Tahfidz',
                'color' => '#0284C7',
                'dark_color' => '#38BDF8',
            ],
            'jumat_berkah' => [
                'name' => 'Jumat Berkah',
                'color' => '#059669',
                'dark_color' => '#34D399',
            ],
            'donasi_bantuan' => [
                'name' => 'Donasi Bantuan',
                'color' => '#D97706',
                'dark_color' => '#FBBF24',
            ],
        ];

        $donutQuery = FinancialTransaction::whereHas('donor')->pemasukan();
        if ($periodRange['current_start'] && $periodRange['current_end']) {
            $donutQuery->whereDate('transaction_date', '>=', $periodRange['current_start']->toDateString())
                ->whereDate('transaction_date', '<=', $periodRange['current_end']->toDateString());
        }

        $allPemasukan = $donutQuery->get();
        $grandTotalDonasi = (float) $allPemasukan->sum('amount');

        $circumference = 376.991; // 2 * pi * 60 (enlarged circle radius)
        $currentOffset = 0.0;
        $donutCategories = [];

        foreach ($categoriesMeta as $catKey => $meta) {
            $catTxs = $allPemasukan->where('category', $catKey);
            $catTotal = (float) $catTxs->sum('amount');
            $catCount = $catTxs->count();
            $percentage = $grandTotalDonasi > 0 ? round(($catTotal / $grandTotalDonasi) * 100, 1) : 0.0;
            $dash = ($percentage / 100) * $circumference;

            $donutCategories[] = [
                'key' => $catKey,
                'name' => $meta['name'],
                'total' => $catTotal,
                'count' => $catCount,
                'percentage' => $percentage,
                'color' => $meta['color'],
                'dark_color' => $meta['dark_color'],
                'stroke_dasharray' => sprintf('%.2f %.2f', $dash, max(0.0, $circumference - $dash)),
                'stroke_dashoffset' => sprintf('%.2f', -$currentOffset),
            ];

            $currentOffset += $dash;
        }

        $donutData = [
            'total' => $grandTotalDonasi,
            'period_label' => $currentPeriodLabel,
            'categories' => $donutCategories,
        ];

        return view('admin.dashboard.index', compact(
            'stats',
            'topDonors',
            'donations',
            'chartData',
            'totalDonorsCount',
            'periodOptions',
            'currentPeriodKey',
            'currentPeriodLabel',
            'donutData'
        ));
    }

    /**
     * Ekspor data donasi ke CSV untuk periode terpilih.
     */
    public function export(Request $request): StreamedResponse
    {
        $periodKey = $this->resolvePeriod((string) $request->query('periode', 'semua'));
        $range = $this->getPeriodRange($periodKey);

        $query = FinancialTransaction::whereHas('donor')
            ->with('donor')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id');

        if ($range['current_start'] && $range['current_end']) {
            $query->whereDate('transaction_date', '>=', $range['current_start']->toDateString())
                ->whereDate('transaction_date', '<=', $range['current_end']->toDateString());
        }

        $transactions = $query->get();

        $categoryLabels = [
            'jumat_berkah' => 'Jumat Berkah',
            'donasi_bantuan' => 'Donasi Bantuan',
            'pembangunan_pondok_tahfidz' => 'Pembangunan Pondok Tahfidz',
        ];

        $filename = 'laporan-donasi-yabun-kubu-raya-'.$periodKey.'-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($transactions, $categoryLabels) {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            // UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($handle, [
                'No',
                'ID Transaksi',
                'Tanggal',
                'Nama Donatur',
                'Tipe Donatur',
                'Program',
                'Tipe',
                'Nominal (Rp)',
                'Status',
                'Keterangan',
            ], ';');

            foreach ($transactions as $index => $tx) {
                fputcsv($handle, [
                    $index + 1,
                    'DN-'.str_pad((string) $tx->id, 4, '0', STR_PAD_LEFT),
                    $tx->transaction_date?->format('d/m/Y') ?? '-',
                    $tx->donor?->nama_donatur ?? 'Hamba Allah',
                    ucfirst((string) ($tx->donor?->tipe_donatur ?? 'Individu')),
                    $categoryLabels[$tx->category] ?? (string) $tx->category,
                    ucfirst((string) $tx->type),
                    (float) $tx->amount,
                    'Terverifikasi',
                    $tx->description ?? '-',
                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Membangun payload card stat beserta delta akurat dan sparkline SVG.
     *
     * @param  array{current_start: ?Carbon, current_end: ?Carbon, prev_start: ?Carbon, prev_end: ?Carbon, comparison: string, label: string}  $range
     * @return array{title: string, icon: string, value: float, delta: float, positive: bool, is_neutral: bool, comparison: string, sparkline: string, sparkline_area: string}
     */
    private function buildStatCard(string $title, string $icon, string $category, string $periodKey, array $range): array
    {
        // 1. Nilai periode berjalan
        $currQuery = FinancialTransaction::whereHas('donor')->pemasukan();
        if ($range['current_start'] && $range['current_end']) {
            $currQuery->whereDate('transaction_date', '>=', $range['current_start']->toDateString())
                ->whereDate('transaction_date', '<=', $range['current_end']->toDateString());
        }
        if ($category !== 'total') {
            $currQuery->kategori($category);
        }
        $currSum = (float) $currQuery->sum('amount');

        // 2. Nilai periode pembanding sebelumnya
        $prevSum = 0.0;
        if ($range['prev_start'] && $range['prev_end']) {
            $prevQuery = FinancialTransaction::whereHas('donor')->pemasukan();
            $prevQuery->whereDate('transaction_date', '>=', $range['prev_start']->toDateString())
                ->whereDate('transaction_date', '<=', $range['prev_end']->toDateString());
            if ($category !== 'total') {
                $prevQuery->kategori($category);
            }
            $prevSum = (float) $prevQuery->sum('amount');
        }

        // 3. Delta kalkulasi riil
        if ($currSum == 0.0 && $prevSum == 0.0) {
            $delta = 0.0;
            $positive = true;
            $isNeutral = true;
        } elseif ($prevSum == 0.0 && $currSum > 0.0) {
            $delta = 100.0;
            $positive = true;
            $isNeutral = false;
        } elseif ($prevSum > 0.0) {
            $diff = (($currSum - $prevSum) / $prevSum) * 100;
            $delta = round(abs($diff), 1);
            $positive = $diff >= 0;
            $isNeutral = ($delta == 0.0);
        } else {
            $delta = 0.0;
            $positive = true;
            $isNeutral = true;
        }

        // 4. Hitung buckets data sparkline
        $buckets = $this->calculateSparklineBuckets($category, $periodKey, $range);
        $sparkline = $this->generateSparkline($buckets);

        return [
            'title' => $title,
            'icon' => $icon,
            'value' => $currSum,
            'delta' => $delta,
            'positive' => $positive,
            'is_neutral' => $isNeutral,
            'comparison' => $range['comparison'],
            'sparkline' => $sparkline['path'],
            'sparkline_area' => $sparkline['area'],
        ];
    }

    /**
     * Hitung array interval data untuk sparkline.
     *
     * @param  array{current_start: ?Carbon, current_end: ?Carbon}  $range
     * @return array<int, float>
     */
    private function calculateSparklineBuckets(string $category, string $periodKey, array $range): array
    {
        $baseQuery = FinancialTransaction::whereHas('donor')->pemasukan();
        if ($category !== 'total') {
            $baseQuery->kategori($category);
        }

        switch ($periodKey) {
            case 'hari_ini':
                // 6 interval dalam 24 jam hari ini
                $buckets = array_fill(0, 6, 0.0);
                $todayTxs = (clone $baseQuery)
                    ->whereDate('transaction_date', now()->toDateString())
                    ->get();

                if ($todayTxs->isNotEmpty()) {
                    foreach ($todayTxs as $tx) {
                        $hour = $tx->created_at ? Carbon::parse($tx->created_at)->hour : 12;
                        $slot = min(5, (int) floor($hour / 4));
                        $buckets[$slot] += (float) $tx->amount;
                    }
                }

                return $buckets;

            case 'minggu_ini':
            case 'minggu_lalu':
                // 7 hari (Senin s/d Minggu)
                $buckets = array_fill(0, 7, 0.0);
                $start = $range['current_start'] ?? now()->startOfWeek();
                $end = $range['current_end'] ?? now()->endOfWeek();

                $txs = (clone $baseQuery)
                    ->whereDate('transaction_date', '>=', $start->toDateString())
                    ->whereDate('transaction_date', '<=', $end->toDateString())
                    ->get();

                for ($i = 0; $i < 7; $i++) {
                    $d = $start->copy()->addDays($i)->toDateString();
                    $buckets[$i] = (float) $txs->filter(fn ($t) => Carbon::parse($t->transaction_date)->toDateString() === $d)->sum('amount');
                }

                return $buckets;

            case 'bulan_ini':
                // 4 interval dalam bulan ini
                $buckets = array_fill(0, 4, 0.0);
                $start = $range['current_start'] ?? now()->startOfMonth();
                $end = $range['current_end'] ?? now()->endOfMonth();

                $txs = (clone $baseQuery)
                    ->whereDate('transaction_date', '>=', $start->toDateString())
                    ->whereDate('transaction_date', '<=', $end->toDateString())
                    ->get();

                $daysInMonth = max(28, (int) $start->daysInMonth);
                $step = ceil($daysInMonth / 4);

                foreach ($txs as $tx) {
                    $day = Carbon::parse($tx->transaction_date)->day;
                    $slot = min(3, (int) floor(($day - 1) / $step));
                    $buckets[$slot] += (float) $tx->amount;
                }

                return $buckets;

            case 'tahun_ini':
                // 12 bulan dalam tahun ini
                $buckets = array_fill(0, 12, 0.0);
                $txs = (clone $baseQuery)
                    ->whereYear('transaction_date', now()->year)
                    ->get();

                foreach ($txs as $tx) {
                    $m = Carbon::parse($tx->transaction_date)->month - 1;
                    if ($m >= 0 && $m < 12) {
                        $buckets[$m] += (float) $tx->amount;
                    }
                }

                return $buckets;

            case 'semua':
            default:
                // 6 bulan terakhir
                $buckets = array_fill(0, 6, 0.0);
                $sixMonthsAgo = now()->subMonths(5)->startOfMonth();

                $txs = (clone $baseQuery)
                    ->whereDate('transaction_date', '>=', $sixMonthsAgo->toDateString())
                    ->get();

                for ($i = 0; $i < 6; $i++) {
                    $mDate = now()->subMonths(5 - $i);
                    $mYear = $mDate->year;
                    $mMonth = $mDate->month;

                    $buckets[$i] = (float) $txs->filter(function ($t) use ($mYear, $mMonth) {
                        $parsed = Carbon::parse($t->transaction_date);

                        return $parsed->year === $mYear && $parsed->month === $mMonth;
                    })->sum('amount');
                }

                return $buckets;
        }
    }

    /**
     * Menghasilkan path SVG sparkline.
     * JIKA nilai 0 (tidak ada pemasukan), menghasilkan garis datar lurus horizontal sempurna (bukan gelombang naik palsu).
     *
     * @param  array<int, float>  $buckets
     * @return array{path: string, area: string}
     */
    private function generateSparkline(array $buckets): array
    {
        $maxVal = max($buckets);

        // Jika tidak ada data atau semua 0: kembalikan garis datar sempurna di baseline Y=26
        if ($maxVal <= 0.0) {
            return [
                'path' => 'M0,26 L80,26',
                'area' => 'M0,26 L80,26 L80,32 L0,32 Z',
            ];
        }

        $n = count($buckets);
        $stepX = 80 / max(1, $n - 1);
        $points = [];

        foreach ($buckets as $i => $val) {
            $x = round($i * $stepX, 1);
            // Normalisasi Y dari 26 (0/min) ke 6 (puncak)
            $y = round(26 - (($val / $maxVal) * 20), 1);
            $points[] = [$x, $y];
        }

        // Bangun path kurva halus dengan cubic bezier
        $path = 'M'.$points[0][0].','.$points[0][1];
        for ($i = 0; $i < $n - 1; $i++) {
            $p0 = $points[$i];
            $p1 = $points[$i + 1];
            $cp1x = round($p0[0] + ($p1[0] - $p0[0]) / 2, 1);
            $cp1y = $p0[1];
            $cp2x = round($p0[0] + ($p1[0] - $p0[0]) / 2, 1);
            $cp2y = $p1[1];
            $path .= " C{$cp1x},{$cp1y} {$cp2x},{$cp2y} {$p1[0]},{$p1[1]}";
        }

        $area = $path.' L80,32 L0,32 Z';

        return [
            'path' => $path,
            'area' => $area,
        ];
    }

    /**
     * Menormalkan query parameter periode menjadi kunci kanonikal.
     */
    private function resolvePeriod(string $raw): string
    {
        $normalized = strtolower(str_replace(' ', '_', trim($raw)));

        return array_key_exists($normalized, self::PERIOD_OPTIONS) ? $normalized : 'semua';
    }

    /**
     * Mengembalikan rentang tanggal dan label perbandingan untuk suatu periode.
     *
     * @return array{current_start: ?Carbon, current_end: ?Carbon, prev_start: ?Carbon, prev_end: ?Carbon, comparison: string, label: string}
     */
    private function getPeriodRange(string $period): array
    {
        return match ($period) {
            'hari_ini' => [
                'current_start' => now()->startOfDay(),
                'current_end' => now()->endOfDay(),
                'prev_start' => now()->subDay()->startOfDay(),
                'prev_end' => now()->subDay()->endOfDay(),
                'comparison' => 'vs kemarin',
                'label' => 'Hari Ini',
            ],
            'minggu_ini' => [
                'current_start' => now()->startOfWeek(),
                'current_end' => now()->endOfWeek(),
                'prev_start' => now()->subWeek()->startOfWeek(),
                'prev_end' => now()->subWeek()->endOfWeek(),
                'comparison' => 'vs minggu lalu',
                'label' => 'Minggu Ini',
            ],
            'minggu_lalu' => [
                'current_start' => now()->subWeek()->startOfWeek(),
                'current_end' => now()->subWeek()->endOfWeek(),
                'prev_start' => now()->subWeeks(2)->startOfWeek(),
                'prev_end' => now()->subWeeks(2)->endOfWeek(),
                'comparison' => 'vs 2 minggu lalu',
                'label' => 'Minggu Lalu',
            ],
            'bulan_ini' => [
                'current_start' => now()->startOfMonth(),
                'current_end' => now()->endOfMonth(),
                'prev_start' => now()->subMonth()->startOfMonth(),
                'prev_end' => now()->subMonth()->endOfMonth(),
                'comparison' => 'vs bulan lalu',
                'label' => 'Bulan Ini',
            ],
            'tahun_ini' => [
                'current_start' => now()->startOfYear(),
                'current_end' => now()->endOfYear(),
                'prev_start' => now()->subYear()->startOfYear(),
                'prev_end' => now()->subYear()->endOfYear(),
                'comparison' => 'vs tahun lalu',
                'label' => 'Tahun Ini',
            ],
            default => [
                'current_start' => null,
                'current_end' => null,
                'prev_start' => null,
                'prev_end' => null,
                'comparison' => 'total kumulatif',
                'label' => 'Semua Periode',
            ],
        };
    }
}
