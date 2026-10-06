<?php

namespace Tests\Feature;

use App\Models\RekeningBank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_view_setting_page(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin YABUN',
            'peran' => 'superadmin',
        ]);

        $response = $this->actingAs($user)->get(route('admin.setting.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Sistem');
        $response->assertSee('Profil Yayasan');
        $response->assertSee('Rekening Donasi');
    }

    public function test_admin_can_update_general_settings(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.setting.general.update'), [
            'nama_yayasan' => 'Yayasan Bakti Umat Nusantara Baru',
            'email' => 'info@yabun.org',
            'kontak_wa' => '081299998888',
            'alamat' => 'Jl. Khatulistiwa No. 10 Pontianak',
            'visi' => 'Menjadi pelopor kemandirian umat',
            'misi' => 'Pemberdayaan kaum dhuafa',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pengaturan', [
            'kunci' => 'nama_yayasan',
            'nilai' => 'Yayasan Bakti Umat Nusantara Baru',
        ]);
    }

    public function test_admin_can_manage_bank_accounts(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.setting.bank.store'), [
            'nama_bank' => 'Bank Mandiri Syariah',
            'nomor_rekening' => '1234567890',
            'atas_nama' => 'Yayasan Bakti Umat',
            'status_aktif' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('rekening_bank', [
            'nomor_rekening' => '1234567890',
        ]);

        $bank = RekeningBank::where('nomor_rekening', '1234567890')->first();

        $deleteResponse = $this->actingAs($user)->delete(route('admin.setting.bank.destroy', $bank->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('rekening_bank', [
            'id' => $bank->id,
        ]);
    }

    public function test_admin_can_update_program_unggulan_and_it_syncs_to_landing_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.setting.general.update'), [
            'nama_yayasan' => 'Yayasan Bakti Umat Nusantara Baru',
            'email' => 'info@yabun.org',
            'kontak_wa' => '081299998888',
            'alamat' => 'Jl. Khatulistiwa No. 10 Pontianak',
            'visi' => 'Menjadi pelopor kemandirian umat',
            'program_unggulan' => 'Program Unggulan Terintegrasi Berkah',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pengaturan', [
            'kunci' => 'program_unggulan',
            'nilai' => 'Program Unggulan Terintegrasi Berkah',
        ]);

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Program Unggulan Terintegrasi Berkah');
    }

    public function test_landing_page_renders_multiple_active_bank_accounts(): void
    {
        RekeningBank::create([
            'nama_bank' => 'Bank Syariah Indonesia (BSI)',
            'nomor_rekening' => '7123456789',
            'atas_nama' => 'Yayasan Bakti Umat Nusantara',
            'status_aktif' => true,
        ]);

        RekeningBank::create([
            'nama_bank' => 'Bank BRI',
            'nomor_rekening' => '771901013298537',
            'atas_nama' => 'Yayasan Bakti Umat Nusantara',
            'status_aktif' => true,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('7123456789');
        $response->assertSee('771901013298537');
    }
}
