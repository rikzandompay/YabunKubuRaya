@extends('layouts.admin')

@section('title', 'Pengaturan Sistem & Yayasan — Admin YABUN Cabang Kubu Raya')
@section('breadcrumb', 'Pengaturan')

@section('content')
    <div class="space-y-6" x-data="{ activeTab: 'profil' }">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-[#1E293B]">Pengaturan Sistem & Yayasan</h1>
                <p class="text-sm text-[#64748B] mt-0.5">Kelola identitas yayasan, nomor rekening bank donasi resmi, dan akun
                    administrator.</p>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div
                class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-[#065F46] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tabs Navigation --}}
        <div class="flex items-center gap-2 border-b border-[#E2E8F0]">
            <button type="button" @click="activeTab = 'profil'"
                :class="activeTab === 'profil' ? 'border-[#065F46] text-[#065F46] font-semibold' :
                    'border-transparent text-[#64748B] hover:text-[#1E293B]'"
                class="px-4 py-2.5 text-sm border-b-2 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Profil Yayasan</span>
            </button>

            <button type="button" @click="activeTab = 'rekening'"
                :class="activeTab === 'rekening' ? 'border-[#065F46] text-[#065F46] font-semibold' :
                    'border-transparent text-[#64748B] hover:text-[#1E293B]'"
                class="px-4 py-2.5 text-sm border-b-2 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Rekening Donasi</span>
            </button>

            <button type="button" @click="activeTab = 'akun'"
                :class="activeTab === 'akun' ? 'border-[#065F46] text-[#065F46] font-semibold' :
                    'border-transparent text-[#64748B] hover:text-[#1E293B]'"
                class="px-4 py-2.5 text-sm border-b-2 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Akun & Keamanan</span>
            </button>
        </div>

        {{-- Tab 1: Profil & Identitas Yayasan --}}
        <div x-show="activeTab === 'profil'" class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6">
            <form method="POST" action="{{ route('admin.setting.general.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#1E293B] mb-1">Nama Resmi Lembaga / Yayasan</label>
                        <input type="text" name="nama_yayasan" required
                            value="{{ old('nama_yayasan', $settings['nama_yayasan'] ?? 'Yayasan Bakti Umat Nusantara Cabang Kubu Raya') }}"
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1E293B] mb-1">Kontak WhatsApp Resmi</label>
                        <input type="text" name="kontak_wa" required
                            value="{{ old('kontak_wa', $settings['kontak_wa'] ?? '081234567890') }}"
                            placeholder="Contoh: 081234567890"
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1E293B] mb-1">Alamat Email Resmi</label>
                        <input type="email" name="email" required
                            value="{{ old('email', $settings['email'] ?? 'kontak@yabunkuburaya.org') }}"
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1E293B] mb-1">Alamat Kantor / Sekretariat</label>
                        <input type="text" name="alamat" required
                            value="{{ old('alamat', $settings['alamat'] ?? 'Kota Pontianak, Kalimantan Barat') }}"
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1E293B] mb-1">Visi Lembaga</label>
                    <textarea name="visi" rows="2"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">{{ old('visi', $settings['visi'] ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1E293B] mb-1">Deskripsi Program Unggulan</label>
                    <textarea name="program_unggulan" rows="4"
                        placeholder="Deskripsi program unggulan yang akan ditampilkan di Landing Page..."
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">{{ old('program_unggulan', $settings['program_unggulan'] ?? $settings['misi'] ?? '') }}</textarea>
                </div>

                <div class="flex items-center justify-end pt-3 border-t border-[#E2E8F0]">
                    <button type="submit"
                        class="px-5 py-2.5 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg shadow-sm transition-colors">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab 2: Rekening Bank Donasi --}}
        <div x-show="activeTab === 'rekening'" x-cloak class="space-y-6">
            {{-- List of Current Accounts --}}
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6">
                <h2 class="text-base font-bold text-[#1E293B] mb-4">Rekening Bank Donasi Terdaftar</h2>

                <div class="divide-y divide-[#E2E8F0] border border-[#E2E8F0] rounded-lg overflow-hidden">
                    @forelse($bankAccounts as $bank)
                        <div class="p-4 flex items-center justify-between gap-4 hover:bg-[#F8FAFC]">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-[#1E293B]">{{ $bank->nama_bank }}</span>
                                    <span
                                        class="px-2 py-0.5 text-[11px] font-medium rounded-full {{ $bank->status_aktif ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $bank->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <p class="text-xs text-[#065F46] font-mono font-semibold">{{ $bank->nomor_rekening }}</p>
                                <p class="text-xs text-[#64748B]">a.n. {{ $bank->atas_nama }}</p>
                            </div>

                            <form method="POST" action="{{ route('admin.setting.bank.destroy', $bank->id) }}"
                                onsubmit="return confirm('Hapus rekening bank ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors"
                                    title="Hapus Rekening">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-[#94A3B8]">
                            Belum ada rekening donasi yang tersimpan.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Add New Bank Account --}}
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6">
                <h2 class="text-base font-bold text-[#1E293B] mb-4">Tambah Rekening Bank Baru</h2>
                <form method="POST" action="{{ route('admin.setting.bank.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#1E293B] mb-1">Nama Bank</label>
                            <input type="text" name="nama_bank" required
                                placeholder="Contoh: Bank Syariah Indonesia (BSI)"
                                class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#1E293B] mb-1">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" required placeholder="Contoh: 7123456789"
                                class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#1E293B] mb-1">Atas Nama Rekening</label>
                            <input type="text" name="atas_nama" required
                                placeholder="Contoh: Yayasan Bakti Umat Nusantara"
                                class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="status_aktif" id="status_aktif" checked
                            class="rounded text-[#065F46] focus:ring-[#065F46]">
                        <label for="status_aktif" class="text-xs text-[#1E293B] font-medium">Jadikan rekening aktif untuk
                            ditampilkan di publik</label>
                    </div>

                    <div class="flex items-center justify-end pt-3 border-t border-[#E2E8F0]">
                        <button type="submit"
                            class="px-5 py-2.5 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg shadow-sm transition-colors">
                            Tambah Rekening Bank
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tab 3: Akun & Keamanan --}}
        <div x-show="activeTab === 'akun'" x-cloak class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6">
            <form method="POST" action="{{ route('admin.setting.profile.update') }}" class="space-y-4 max-w-xl">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-[#1E293B] mb-1">Nama Lengkap Administrator</label>
                    <input type="text" name="nama" required
                        value="{{ old('nama', $user->nama ?? 'Admin Bakti Umat Nusantara Cabang Kubu Raya') }}"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1E293B] mb-1">Alamat Email Login</label>
                    <input type="email" name="email" required
                        value="{{ old('email', $user->email ?? 'admin@yabunkuburaya.org') }}"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                </div>

                <div class="border-t border-[#E2E8F0] pt-4 mt-6 space-y-4">
                    <p class="text-xs font-bold text-[#1E293B] uppercase tracking-wider">Ubah Kata Sandi (Opsional)</p>
                    <p class="text-xs text-[#64748B]">Biarkan kosong jika tidak ingin mengganti kata sandi login Anda.</p>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" name="password_saat_ini" autocomplete="current-password"
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-[#1E293B] mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password_baru" autocomplete="new-password"
                                class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#1E293B] mb-1">Ulangi Kata Sandi Baru</label>
                            <input type="password" name="password_baru_confirmation" autocomplete="new-password"
                                class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-[#E2E8F0]">
                    <button type="submit"
                        class="px-5 py-2.5 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg shadow-sm transition-colors">
                        Perbarui Profil & Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
