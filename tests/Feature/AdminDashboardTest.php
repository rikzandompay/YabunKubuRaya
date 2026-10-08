<?php

namespace Tests\Feature;

use App\Models\Donatur;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_view_dashboard(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin Yayasan',
            'peran' => 'admin',
        ]);

        $donor = Donatur::create([
            'nama_donatur' => 'Hamba Allah',
            'nomor_hp' => '081234567890',
            'tipe_donatur' => 'individu',
        ]);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'pembangunan_pondok_tahfidz',
            'amount' => 5000000,
            'donor_id' => $donor->id,
            'description' => 'Infaq Pembangunan',
            'transaction_date' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Admin Yayasan');
        $response->assertSee('Total Donasi Masuk');
        $response->assertSee('Uang Pembangunan');
        $response->assertSee('Uang Jumat Berkah');
        $response->assertSee('Uang Donasi');
        $response->assertSee('Donatur Terbanyak');
        $response->assertSee('Statistik Donasi Masuk');
        $response->assertSee('Monitoring Donasi Masuk');
        $response->assertSee('Rp 5.000.000');
    }

    public function test_admin_url_redirects_to_native_dashboard(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin Yayasan',
            'peran' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_chart_data_includes_rolling_seven_days_and_periods(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin Yayasan',
            'peran' => 'admin',
        ]);

        $donor = Donatur::create([
            'nama_donatur' => 'Donatur Rolling Test',
            'nomor_hp' => '081234567899',
            'tipe_donatur' => 'individu',
        ]);

        // Transaction 3 days ago (falls in rolling 7 days)
        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 3000000,
            'donor_id' => $donor->id,
            'description' => 'Sedekah Jumat Berkah',
            'transaction_date' => now()->startOfWeek(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $chartData = $response->viewData('chartData');

        $this->assertArrayHasKey('harian', $chartData);
        $this->assertArrayHasKey('mingguan', $chartData);
        $this->assertArrayHasKey('bulanan', $chartData);
        $this->assertArrayHasKey('tahunan', $chartData);

        $this->assertEquals('7 Hari Terakhir', $chartData['harian']['period_title']);
        $this->assertCount(7, $chartData['harian']['values']);
        $this->assertContains(3000000.0, $chartData['harian']['values']);
        $this->assertContains(3000000.0, $chartData['mingguan']['values']);
    }

    public function test_dashboard_shows_flat_sparkline_and_zero_delta_when_no_income(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin Yayasan',
            'peran' => 'admin',
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard', ['periode' => 'minggu_lalu']));

        $response->assertStatus(200);
        $stats = $response->viewData('stats');

        $this->assertNotEmpty($stats);
        foreach ($stats as $stat) {
            $this->assertEquals(0, $stat['value']);
            $this->assertEquals(0.0, $stat['delta']);
            $this->assertTrue($stat['is_neutral']);
            $this->assertEquals('M0,26 L80,26', $stat['sparkline']);
            $this->assertEquals('M0,26 L80,26 L80,32 L0,32 Z', $stat['sparkline_area']);
        }
    }

    public function test_dashboard_supports_period_filtering(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin Yayasan',
            'peran' => 'admin',
        ]);

        $donor = Donatur::create([
            'nama_donatur' => 'Donatur Periode',
            'nomor_hp' => '081234567891',
            'tipe_donatur' => 'individu',
        ]);

        // Transaksi kemarin (bukan hari ini)
        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 1500000,
            'donor_id' => $donor->id,
            'description' => 'Donasi kemarin',
            'transaction_date' => now()->subDay(),
        ]);

        // Akses dengan filter hari ini -> nilai seharusnya 0
        $responseToday = $this->actingAs($user)->get(route('admin.dashboard', ['periode' => 'hari_ini']));
        $responseToday->assertStatus(200);
        $todayStats = $responseToday->viewData('stats');
        $this->assertEquals(0, $todayStats[0]['value']);

        // Akses dengan filter semua -> nilai terhitung
        $responseAll = $this->actingAs($user)->get(route('admin.dashboard', ['periode' => 'semua']));
        $responseAll->assertStatus(200);
        $allStats = $responseAll->viewData('stats');
        $this->assertEquals(1500000, $allStats[0]['value']);
    }

    public function test_dashboard_export_streams_csv(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin Yayasan',
            'peran' => 'admin',
        ]);

        $donor = Donatur::create([
            'nama_donatur' => 'Donatur Ekspor',
            'nomor_hp' => '081234567892',
            'tipe_donatur' => 'individu',
        ]);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'donasi_bantuan',
            'amount' => 750000,
            'donor_id' => $donor->id,
            'description' => 'Infaq Bantuan Sosial',
            'transaction_date' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard.export', ['periode' => 'semua']));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename=laporan-donasi-yabun-kubu-raya-semua-', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_dashboard_provides_donut_chart_data_by_category(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin Yayasan',
            'peran' => 'admin',
        ]);

        $donor = Donatur::create([
            'nama_donatur' => 'Donatur Donut Test',
            'nomor_hp' => '081234567893',
            'tipe_donatur' => 'individu',
        ]);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'pembangunan_pondok_tahfidz',
            'amount' => 6000000,
            'donor_id' => $donor->id,
            'description' => 'Wakaf Pembangunan',
            'transaction_date' => now(),
        ]);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 4000000,
            'donor_id' => $donor->id,
            'description' => 'Paket Berkah Jumat',
            'transaction_date' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard', ['periode' => 'semua']));

        $response->assertStatus(200);
        $response->assertSee('Proporsi Kategori Donasi');
        $response->assertSee('Pembangunan Tahfidz');
        $response->assertSee('Jumat Berkah');

        $donutData = $response->viewData('donutData');
        $this->assertIsArray($donutData);
        $this->assertEquals(10000000.0, $donutData['total']);
        $this->assertCount(3, $donutData['categories']);

        $pembangunan = collect($donutData['categories'])->firstWhere('key', 'pembangunan_pondok_tahfidz');
        $this->assertNotNull($pembangunan);
        $this->assertEquals(6000000.0, $pembangunan['total']);
        $this->assertEquals(60.0, $pembangunan['percentage']);

        $jumat = collect($donutData['categories'])->firstWhere('key', 'jumat_berkah');
        $this->assertNotNull($jumat);
        $this->assertEquals(4000000.0, $jumat['total']);
        $this->assertEquals(40.0, $jumat['percentage']);
    }
}
