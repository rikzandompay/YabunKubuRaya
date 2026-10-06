<?php

namespace Tests\Feature;

use App\Models\Donatur;
use App\Models\FinancialTransaction;
use App\Models\User;
use App\Services\FinanceSummaryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationTransparencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_donation_transparency_section_with_zero_by_default(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Transparansi Donasi');
        $response->assertSee('Setiap donasi tercatat dan dilaporkan secara terbuka.');
        $response->assertSee('JUMAT BERKAH');
        $response->assertSee('DONASI BANTUAN');
        $response->assertSee('DONASI PEMBANGUNAN PONDOK TAHFIDZ');
        $response->assertSee('Belum ada transaksi tercatat');
        $response->assertSee('Lihat Info Donasi');
    }

    public function test_finance_summary_service_calculates_totals_and_invalidates_cache(): void
    {
        FinanceSummaryService::clearCache();

        $initial = FinanceSummaryService::totalsByCategory();
        $this->assertEquals(0, $initial['categories']['jumat_berkah']['amount']);
        $this->assertNull($initial['latest_transaction_date']);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'jumat_berkah',
            'amount' => 5000000.00,
            'description' => 'Infaq Jumat Berkah',
            'transaction_date' => '2026-10-01',
        ]);

        FinancialTransaction::create([
            'type' => 'pemasukan',
            'category' => 'donasi_bantuan',
            'amount' => 12500000.00,
            'description' => 'Donasi Seragam Santri',
            'transaction_date' => '2026-10-02',
        ]);

        // Transaksi pengeluaran tidak boleh dihitung ke total pemasukan
        FinancialTransaction::create([
            'type' => 'pengeluaran',
            'category' => 'jumat_berkah',
            'amount' => 2000000.00,
            'description' => 'Beli beras jumat berkah',
            'transaction_date' => '2026-10-03',
        ]);

        $updated = FinanceSummaryService::totalsByCategory();

        $this->assertEquals(5000000.00, $updated['categories']['jumat_berkah']['amount']);
        $this->assertEquals(12500000.00, $updated['categories']['donasi_bantuan']['amount']);
        $this->assertEquals(0, $updated['categories']['pembangunan_pondok_tahfidz']['amount']);
        $this->assertEquals('2026-10-03', $updated['latest_transaction_date']);
        $this->assertNotNull($updated['latest_date_formatted']);
    }

    public function test_admin_can_manage_financial_transactions(): void
    {
        $admin = User::factory()->create(['peran' => 'admin']);

        $indexResponse = $this->actingAs($admin)->get(route('admin.keuangan.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Manajemen Keuangan &amp; Transparansi Donasi', false);

        $donor = Donatur::create([
            'nama_donatur' => 'H. Muhsin',
            'tipe_donatur' => 'individu',
            'nomor_hp' => '08123456789',
        ]);

        $storeResponse = $this->actingAs($admin)->post(route('admin.keuangan.store'), [
            'type' => 'pemasukan',
            'category' => 'pembangunan_pondok_tahfidz',
            'amount' => 25000000,
            'donor_id' => $donor->id,
            'transaction_date' => '2026-10-01',
            'description' => 'Wakaf Pembangunan Asrama',
        ]);

        $storeResponse->assertRedirect(route('admin.keuangan.index'));

        $this->assertDatabaseHas('financial_transactions', [
            'category' => 'pembangunan_pondok_tahfidz',
            'amount' => 25000000,
        ]);

        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Rp 25.000.000');
    }

    public function test_guest_cannot_access_admin_finance(): void
    {
        $response = $this->get(route('admin.keuangan.index'));
        $response->assertRedirect(route('login'));
    }
}
