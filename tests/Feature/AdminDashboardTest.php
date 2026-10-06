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
            'nama' => 'Admin BUM',
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
        $response->assertSee('Admin BUM');
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
            'nama' => 'Admin BUM',
            'peran' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_chart_data_includes_rolling_seven_days_and_periods(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin BUM',
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
}
