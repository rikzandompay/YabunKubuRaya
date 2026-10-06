<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\RekeningBank;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan sistem dan profil yayasan.
     */
    public function index(): View
    {
        $settings = Pengaturan::all()->pluck('nilai', 'kunci')->toArray();
        $bankAccounts = RekeningBank::orderBy('id')->get();
        $user = auth()->user();

        return view('admin.settings.index', compact('settings', 'bankAccounts', 'user'));
    }

    /**
     * Perbarui informasi profil umum yayasan.
     */
    public function updateGeneral(Request $request): RedirectResponse
    {
        abort_unless(in_array(auth()->user()->peran, ['superadmin', 'admin'], true), 403);

        $validated = $request->validate([
            'nama_yayasan' => 'required|string|max:200',
            'email' => 'required|email|max:100',
            'kontak_wa' => 'required|string|max:30',
            'alamat' => 'required|string|max:500',
            'visi' => 'nullable|string|max:1000',
            'misi' => 'nullable|string|max:2000',
            'program_unggulan' => 'nullable|string|max:2000',
        ]);

        if (isset($validated['program_unggulan']) && ! isset($validated['misi'])) {
            $validated['misi'] = $validated['program_unggulan'];
        } elseif (isset($validated['misi']) && ! isset($validated['program_unggulan'])) {
            $validated['program_unggulan'] = $validated['misi'];
        }

        foreach ($validated as $key => $value) {
            Pengaturan::updateOrCreate(
                ['kunci' => $key],
                ['nilai' => $value, 'kelompok' => 'umum']
            );
        }

        return back()->with('success', 'Pengaturan profil yayasan berhasil disimpan.');
    }

    /**
     * Simpan rekening bank donasi baru.
     */
    public function storeBank(Request $request): RedirectResponse
    {
        $this->authorize('create', RekeningBank::class);

        $validated = $request->validate([
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50|unique:rekening_bank,nomor_rekening',
            'atas_nama' => 'required|string|max:150',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif');

        RekeningBank::create($validated);

        return back()->with('success', 'Rekening bank donasi berhasil ditambahkan.');
    }

    /**
     * Hapus rekening bank donasi.
     */
    public function destroyBank(int $id): RedirectResponse
    {
        $bank = RekeningBank::findOrFail($id);

        $this->authorize('delete', $bank);

        $bank->delete();

        return back()->with('success', 'Rekening bank donasi berhasil dihapus.');
    }

    /**
     * Perbarui profil akun admin yang sedang login.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            return back()->with('error', 'Sesi login tidak valid.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:pengguna,email,'.$user->id,
            'password_saat_ini' => 'nullable|required_with:password_baru|string',
            'password_baru' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];

        if (! empty($validated['password_baru'])) {
            if (! Hash::check($validated['password_saat_ini'], $user->kata_sandi)) {
                return back()->withErrors(['password_saat_ini' => 'Kata sandi saat ini tidak cocok.']);
            }
            $user->kata_sandi = Hash::make($validated['password_baru']);
        }

        $user->save();

        return back()->with('success', 'Profil admin berhasil diperbarui.');
    }
}
