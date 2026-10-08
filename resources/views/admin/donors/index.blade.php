@extends('layouts.admin')

@section('title', 'Manajemen Donatur — Admin YABUN Cabang Kubu Raya')
@section('breadcrumb', 'Donatur')

@section('content')
<div class="space-y-6">
    {{-- Header & Action --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#1E293B]">Manajemen Donatur</h1>
            <p class="text-sm text-[#64748B] mt-0.5">Pendataan donatur yayasan, kontak, dan riwayat kontribusi donasi.</p>
        </div>
        <button
            type="button"
            x-data
            @click="$dispatch('open-modal-donatur')"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-[#065F46] hover:bg-[#047857] shadow-sm transition-colors focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2 shrink-0"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Donatur Baru</span>
        </button>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-[#065F46] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Search & Filter Form --}}
    <div class="bg-white p-4 rounded-xl border border-[#E2E8F0] shadow-sm">
        <form method="GET" action="{{ route('admin.donatur.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative flex-1">
                <svg class="w-4 h-4 text-[#94A3B8] absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    type="text"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Cari nama donatur atau nomor handphone..."
                    class="w-full pl-9 pr-4 py-2 text-sm bg-[#F8FAFC] border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                >
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="px-4 py-2 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg transition-colors flex items-center justify-center gap-1.5 shrink-0"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Cari</span>
                </button>

                {{-- Filter Button & Popover --}}
                <div class="relative shrink-0" x-data="{ openFilter: false }">
                    <button
                        type="button"
                        @click="openFilter = !openFilter"
                        @click.away="openFilter = false"
                        class="px-3.5 py-2 text-xs font-semibold rounded-lg border transition-all flex items-center gap-1.5 {{ ($categoryFilter || $typeFilter) ? 'bg-emerald-50 border-[#065F46] text-[#065F46] shadow-sm' : 'bg-white border-[#CBD5E1] text-[#475569] hover:bg-[#F8FAFC]' }}"
                        aria-label="Filter donatur"
                    >
                        <svg class="w-3.5 h-3.5 {{ ($categoryFilter || $typeFilter) ? 'text-[#065F46]' : 'text-[#64748B]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>
                        <span>Filter</span>
                        @if($categoryFilter || $typeFilter)
                            <span class="w-2 h-2 rounded-full bg-[#065F46]"></span>
                        @endif
                    </button>

                    {{-- Dropdown Filter Menu --}}
                    <div
                        x-show="openFilter"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        x-cloak
                        class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-[#E2E8F0] p-4 z-30 space-y-3.5"
                    >
                        <div class="flex items-center justify-between pb-2 border-b border-[#E2E8F0]">
                            <h4 class="text-xs font-bold text-[#1E293B] uppercase tracking-wider">Filter Data Donatur</h4>
                            @if($categoryFilter || $typeFilter)
                                <a
                                    href="{{ route('admin.donatur.index', array_filter(['q' => $search])) }}"
                                    class="text-[11px] font-medium text-red-600 hover:underline"
                                >
                                    Hapus Filter
                                </a>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#1E293B] mb-1">Kategori Donasi</label>
                            <select
                                name="kategori"
                                class="w-full px-2.5 py-1.5 text-xs bg-[#F8FAFC] border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                            >
                                <option value="">Semua Kategori</option>
                                <option value="jumat_berkah" {{ $categoryFilter === 'jumat_berkah' ? 'selected' : '' }}>Jumat Berkah</option>
                                <option value="donasi_bantuan" {{ $categoryFilter === 'donasi_bantuan' ? 'selected' : '' }}>Donasi Bantuan</option>
                                <option value="pembangunan_pondok_tahfidz" {{ $categoryFilter === 'pembangunan_pondok_tahfidz' ? 'selected' : '' }}>Pembangunan Tahfidz</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#1E293B] mb-1">Tipe Donatur</label>
                            <select
                                name="tipe"
                                class="w-full px-2.5 py-1.5 text-xs bg-[#F8FAFC] border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                            >
                                <option value="">Semua Tipe</option>
                                <option value="individu" {{ $typeFilter === 'individu' ? 'selected' : '' }}>Individu</option>
                                <option value="lembaga" {{ $typeFilter === 'lembaga' ? 'selected' : '' }}>Lembaga</option>
                                <option value="anonim" {{ $typeFilter === 'anonim' ? 'selected' : '' }}>Anonim</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#E2E8F0]">
                            <button
                                type="button"
                                @click="openFilter = false"
                                class="px-3 py-1.5 text-xs text-[#64748B] hover:bg-[#F1F5F9] rounded-lg transition-colors"
                            >
                                Tutup
                            </button>
                            <button
                                type="submit"
                                class="px-3 py-1.5 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg transition-colors"
                            >
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>

                @if($search || $categoryFilter || $typeFilter)
                    <a
                        href="{{ route('admin.donatur.index') }}"
                        class="px-3 py-2 text-xs text-[#64748B] hover:text-[#1E293B] hover:bg-[#F1F5F9] rounded-lg transition-colors shrink-0"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table of Donors --}}
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[640px]">
                <thead class="bg-[#F8FAFC] border-b border-[#E2E8F0] text-[#64748B] text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">Nama Donatur</th>
                        <th class="px-4 py-3 whitespace-nowrap">Tipe Donatur</th>
                        <th class="px-4 py-3 whitespace-nowrap">Nomor Kontak (WhatsApp)</th>
                        <th class="px-4 py-3 whitespace-nowrap">Kategori Donasi</th>
                        <th class="px-4 py-3 text-right whitespace-nowrap">Total Donasi</th>
                        <th class="px-4 py-3 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($donors as $donor)
                        <tr class="hover:bg-[#F8FAFC]/50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-[#1E293B]">
                                {{ $donor->nama_donatur }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $donor->tipe_donatur === 'lembaga' ? 'bg-blue-50 text-blue-700 border border-blue-200' : ($donor->tipe_donatur === 'anonim' ? 'bg-slate-100 text-slate-600' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                    {{ ucfirst($donor->tipe_donatur) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-[#64748B] whitespace-nowrap">
                                {{ $donor->nomor_hp ?? 'Tidak dicatat' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $categories = $donor->financialTransactions->pluck('category')->unique()->filter();
                                @endphp
                                @if($categories->isNotEmpty())
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        @foreach($categories as $category)
                                            @if($category === 'jumat_berkah')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                                    Jumat Berkah
                                                </span>
                                            @elseif($category === 'donasi_bantuan')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                                    Donasi Bantuan
                                                </span>
                                            @elseif($category === 'pembangunan_pondok_tahfidz')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-teal-50 text-teal-700 border border-teal-200 whitespace-nowrap">
                                                    Pembangunan Tahfidz
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-slate-100 text-slate-700 whitespace-nowrap">
                                                    {{ ucwords(str_replace('_', ' ', $category)) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-[#94A3B8] italic">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap font-semibold text-[#065F46]">
                                {{ format_rupiah($donor->financial_transactions_sum_amount ?? 0) }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                @php
                                    $latestTx = $donor->financialTransactions->first();
                                    $totalAmount = $donor->financial_transactions_sum_amount ?: ($latestTx?->amount ?? 0);
                                    $category = $latestTx?->category ?? 'donasi_bantuan';
                                    $date = $latestTx?->transaction_date ? \Carbon\Carbon::parse($latestTx->transaction_date)->format('Y-m-d') : date('Y-m-d');
                                @endphp
                                <button
                                    type="button"
                                    @click="$dispatch('open-modal-edit-donatur', {
                                        id: {{ $donor->id }},
                                        nama_donatur: '{{ addslashes($donor->nama_donatur) }}',
                                        tipe_donatur: '{{ $donor->tipe_donatur }}',
                                        nomor_hp: '{{ addslashes($donor->nomor_hp ?? '') }}',
                                        kategori: '{{ $category }}',
                                        total_donasi: '{{ (int) $totalAmount }}',
                                        tanggal_donasi: '{{ $date }}'
                                    })"
                                    class="p-1.5 text-[#065F46] hover:bg-emerald-50 rounded-md transition-colors inline-block"
                                    title="Edit Data Donatur"
                                    aria-label="Edit {{ $donor->nama_donatur }}"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>

                                <form method="POST" action="{{ route('admin.donatur.destroy', $donor->id) }}" onsubmit="return confirm('Hapus data donatur ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors"
                                        title="Hapus Donatur"
                                        aria-label="Hapus {{ $donor->nama_donatur }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-[#64748B]">
                                Belum ada data donatur tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($donors->hasPages())
            <div class="px-4 py-3 border-t border-[#E2E8F0]">
                {{ $donors->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah Donatur --}}
    <div
        x-data="{
            show: false,
            rawAmount: '',
            formattedAmount: '',
            updateAmount(val) {
                let clean = (val || '').toString().replace(/[^0-9]/g, '');
                this.rawAmount = clean;
                this.formattedAmount = clean ? 'Rp ' + new Intl.NumberFormat('id-ID').format(clean) : '';
            },
            resetModal() {
                this.rawAmount = '';
                this.formattedAmount = '';
                this.show = true;
            }
        }"
        @open-modal-donatur.window="resetModal()"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
        @keydown.escape.window="show = false"
    >
        <div
            @click.away="show = false"
            class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl border border-[#E2E8F0] space-y-4"
        >
            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                <h3 class="text-base font-semibold text-[#1E293B]">Tambah Data Donatur Baru</h3>
                <button @click="show = false" class="text-[#94A3B8] hover:text-[#1E293B] p-1 rounded">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.donatur.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Nama Lengkap Donatur <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="nama_donatur"
                        required
                        placeholder="Contoh: H. Ahmad Subarjo atau Hamba Allah"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Tipe Donatur <span class="text-red-500">*</span></label>
                    <select
                        name="tipe_donatur"
                        required
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                        <option value="individu">Individu / Perorangan</option>
                        <option value="lembaga">Lembaga / Komunitas / Korporat</option>
                        <option value="anonim">Anonim (Hamba Allah)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Nomor WhatsApp / HP (Opsional)</label>
                    <input
                        type="text"
                        name="nomor_hp"
                        placeholder="Contoh: 081234567890"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div class="border-t border-[#E2E8F0] pt-3 space-y-3">
                    <p class="text-xs font-semibold text-[#065F46]">Informasi Donasi</p>
                    
                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Kategori Program <span class="text-red-500">*</span></label>
                        <select
                            name="kategori"
                            required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                        >
                            <option value="jumat_berkah">Jumat Berkah</option>
                            <option value="donasi_bantuan" selected>Donasi Bantuan</option>
                            <option value="pembangunan_pondok_tahfidz">Pembangunan Pondok Tahfidz</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Total Donasi (Rp) <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            inputmode="numeric"
                            x-model="formattedAmount"
                            @input="updateAmount($event.target.value)"
                            required
                            placeholder="Contoh: Rp 50.000"
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none font-medium"
                        >
                        <input type="hidden" name="total_donasi" :value="rawAmount">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Tanggal Donasi <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            name="tanggal_donasi"
                            required
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E2E8F0]">
                    <button
                        type="button"
                        @click="show = false"
                        class="px-4 py-2 text-xs font-medium text-[#64748B] hover:bg-[#F1F5F9] rounded-lg transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg shadow-sm transition-colors"
                    >
                        Simpan Donatur
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Donatur --}}
    <div
        x-data="{
            show: false,
            item: {
                id: null,
                nama_donatur: '',
                tipe_donatur: 'individu',
                nomor_hp: '',
                kategori: 'donasi_bantuan',
                total_donasi: '',
                tanggal_donasi: '{{ date('Y-m-d') }}'
            },
            formattedAmount: '',
            updateAmount(val) {
                let clean = (val || '').toString().replace(/[^0-9]/g, '');
                this.item.total_donasi = clean;
                this.formattedAmount = clean ? 'Rp ' + new Intl.NumberFormat('id-ID').format(clean) : '';
            },
            initEdit(detail) {
                this.item = { ...detail };
                let clean = (detail.total_donasi || '').toString().replace(/[^0-9]/g, '');
                this.item.total_donasi = clean;
                this.formattedAmount = clean ? 'Rp ' + new Intl.NumberFormat('id-ID').format(clean) : '';
                this.show = true;
            }
        }"
        @open-modal-edit-donatur.window="initEdit($event.detail)"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
        @keydown.escape.window="show = false"
    >
        <div
            @click.away="show = false"
            class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl border border-[#E2E8F0] space-y-4"
        >
            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                <h3 class="text-base font-semibold text-[#1E293B]">Edit Data Donatur</h3>
                <button @click="show = false" class="text-[#94A3B8] hover:text-[#1E293B] p-1 rounded">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" :action="`/admin/donatur/${item.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Nama Lengkap Donatur <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="nama_donatur"
                        x-model="item.nama_donatur"
                        required
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Tipe Donatur <span class="text-red-500">*</span></label>
                    <select
                        name="tipe_donatur"
                        x-model="item.tipe_donatur"
                        required
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                        <option value="individu">Individu / Perorangan</option>
                        <option value="lembaga">Lembaga / Komunitas / Korporat</option>
                        <option value="anonim">Anonim (Hamba Allah)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Nomor WhatsApp / HP (Opsional)</label>
                    <input
                        type="text"
                        name="nomor_hp"
                        x-model="item.nomor_hp"
                        placeholder="Contoh: 081234567890"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div class="border-t border-[#E2E8F0] pt-3 space-y-3">
                    <p class="text-xs font-semibold text-[#065F46]">Informasi Donasi</p>
                    
                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Kategori Program <span class="text-red-500">*</span></label>
                        <select
                            name="kategori"
                            x-model="item.kategori"
                            required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                        >
                            <option value="jumat_berkah">Jumat Berkah</option>
                            <option value="donasi_bantuan">Donasi Bantuan</option>
                            <option value="pembangunan_pondok_tahfidz">Pembangunan Pondok Tahfidz</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Total Donasi (Rp) <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            inputmode="numeric"
                            x-model="formattedAmount"
                            @input="updateAmount($event.target.value)"
                            required
                            placeholder="Contoh: Rp 50.000"
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none font-medium"
                        >
                        <input type="hidden" name="total_donasi" :value="item.total_donasi">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Tanggal Donasi <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            name="tanggal_donasi"
                            x-model="item.tanggal_donasi"
                            required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E2E8F0]">
                    <button
                        type="button"
                        @click="show = false"
                        class="px-4 py-2 text-xs font-medium text-[#64748B] hover:bg-[#F1F5F9] rounded-lg transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg shadow-sm transition-colors"
                    >
                        Perbarui Donatur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
