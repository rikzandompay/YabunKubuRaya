@props(['donations' => []])

@php
    $typeColors = [
        'Jumat Berkah' => 'bg-[#065F46]',
        'Donasi Bantuan' => 'bg-[#D97706]',
        'Pembangunan Pondok Tahfidz' => 'bg-[#3B82F6]',
        'Uang Donasi' => 'bg-[#D97706]',
        'Uang Pembangunan' => 'bg-[#3B82F6]',
    ];
    $statusConfig = [
        'Terverifikasi' => ['dot' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
        'Menunggu' => ['dot' => 'bg-amber-400', 'text' => 'text-amber-700'],
    ];
@endphp

<div
    class="bg-[#F8FAFC] rounded-xl border border-[#E2E8F0]"
    x-data="donationTable()"
>
    {{-- Header --}}
    <div
        class="px-4 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2"
        style="background: repeating-linear-gradient(135deg, transparent, transparent 4px, rgba(0,0,0,0.02) 4px, rgba(0,0,0,0.02) 5px)"
    >
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 0v.375"/></svg>
            <span class="text-sm font-semibold text-[#1E293B]">Monitoring Donasi Masuk</span>
        </div>

        <div class="flex items-center gap-2">
            {{-- Search --}}
            <div class="relative">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    type="text"
                    x-model="search"
                    placeholder="Cari donatur / ID"
                    class="w-36 sm:w-48 pl-8 pr-3 py-1.5 text-sm bg-white border border-[#E2E8F0] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none placeholder-[#94A3B8]"
                    aria-label="Cari donatur atau ID donasi"
                >
            </div>

            {{-- Filter button --}}
            <div class="relative">
                <button
                    @click="filterOpen = !filterOpen"
                    :aria-expanded="filterOpen.toString()"
                    class="flex items-center gap-1.5 px-3 py-1.5 text-sm text-[#64748B] border border-[#E2E8F0] rounded-lg hover:bg-white hover:border-[#CBD5E1] transition-colors focus-visible:ring-2 focus-visible:ring-[#065F46]"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                    Filter
                </button>

                {{-- Filter popover --}}
                <div
                    x-show="filterOpen"
                    @click.away="filterOpen = false"
                    x-transition
                    class="absolute right-0 mt-1 w-56 bg-white border border-[#E2E8F0] rounded-lg shadow-lg p-4 z-20"
                    x-cloak
                >
                    <p class="text-xs font-medium text-[#1E293B] mb-2">Jenis Donasi</p>
                    <label class="flex items-center gap-2 mb-1.5 text-sm text-[#475569] cursor-pointer">
                        <input type="checkbox" x-model="filters.types" value="Jumat Berkah" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                        Jumat Berkah
                    </label>
                    <label class="flex items-center gap-2 mb-1.5 text-sm text-[#475569] cursor-pointer">
                        <input type="checkbox" x-model="filters.types" value="Donasi Bantuan" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                        Donasi Bantuan
                    </label>
                    <label class="flex items-center gap-2 mb-3 text-sm text-[#475569] cursor-pointer">
                        <input type="checkbox" x-model="filters.types" value="Pembangunan Pondok Tahfidz" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                        Pembangunan Pondok Tahfidz
                    </label>

                    <p class="text-xs font-medium text-[#1E293B] mb-2">Status</p>
                    <label class="flex items-center gap-2 mb-1.5 text-sm text-[#475569] cursor-pointer">
                        <input type="checkbox" x-model="filters.statuses" value="Terverifikasi" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                        Terverifikasi
                    </label>
                    <label class="flex items-center gap-2 mb-3 text-sm text-[#475569] cursor-pointer">
                        <input type="checkbox" x-model="filters.statuses" value="Menunggu" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                        Menunggu
                    </label>

                    <div class="flex gap-2">
                        <button @click="applyFilters()" class="flex-1 px-3 py-1.5 text-xs font-medium text-white bg-[#065F46] rounded-lg hover:bg-[#047857] transition-colors">Terapkan</button>
                        <button @click="resetFilters()" class="flex-1 px-3 py-1.5 text-xs font-medium text-[#64748B] border border-[#E2E8F0] rounded-lg hover:bg-[#F1F5F9] transition-colors">Reset</button>
                    </div>
                </div>
            </div>

            {{-- Kebab menu --}}
            <div class="relative" x-data="{ kebabOpen: false }">
                <button
                    @click="kebabOpen = !kebabOpen"
                    @click.away="kebabOpen = false"
                    class="p-1.5 rounded-lg hover:bg-white hover:border hover:border-[#E2E8F0] focus-visible:ring-2 focus-visible:ring-[#065F46]"
                    aria-label="Opsi tabel lainnya"
                    :aria-expanded="kebabOpen.toString()"
                >
                    <svg class="w-4 h-4 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/></svg>
                </button>
                <div x-show="kebabOpen" x-transition class="absolute right-0 mt-1 w-36 bg-white border border-[#E2E8F0] rounded-lg shadow-lg py-1 z-20" role="menu" x-cloak>
                    {{-- TODO: implementasi cetak dan ekspor --}}
                    <button onclick="alert('Segera hadir')" class="w-full text-left px-3 py-1.5 text-sm text-[#475569] hover:bg-[#F1F5F9]" role="menuitem">Cetak</button>
                    <button onclick="alert('Segera hadir')" class="w-full text-left px-3 py-1.5 text-sm text-[#475569] hover:bg-[#F1F5F9]" role="menuitem">Ekspor</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg mx-3 mb-3 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[#E2E8F0]">
                    <th class="w-10 px-3 py-3">
                        <label class="sr-only">Pilih semua donasi</label>
                        <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-[#64748B] uppercase tracking-wide cursor-pointer hover:text-[#1E293B]" @click="sortBy('id')" :aria-sort="getSortAria('id')">
                        <span class="inline-flex items-center gap-1">ID Donasi <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/></svg></span>
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-[#64748B] uppercase tracking-wide cursor-pointer hover:text-[#1E293B]" @click="sortBy('donor_name')" :aria-sort="getSortAria('donor_name')">
                        <span class="inline-flex items-center gap-1">Nama Donatur <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/></svg></span>
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-[#64748B] uppercase tracking-wide cursor-pointer hover:text-[#1E293B]" @click="sortBy('type')" :aria-sort="getSortAria('type')">
                        <span class="inline-flex items-center gap-1">Jenis <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/></svg></span>
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-[#64748B] uppercase tracking-wide cursor-pointer hover:text-[#1E293B]" @click="sortBy('amount')" :aria-sort="getSortAria('amount')">
                        <span class="inline-flex items-center gap-1">Nominal <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/></svg></span>
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-[#64748B] uppercase tracking-wide cursor-pointer hover:text-[#1E293B]" @click="sortBy('status')" :aria-sort="getSortAria('status')">
                        <span class="inline-flex items-center gap-1">Status <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/></svg></span>
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-[#64748B] uppercase tracking-wide hidden lg:table-cell">Tanggal</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-[#64748B] uppercase tracking-wide hidden lg:table-cell">Metode</th>
                    <th scope="col" class="w-10 px-3 py-3"><span class="sr-only">Aksi</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($donations as $index => $donation)
                    @php
                        $typeColor = $typeColors[$donation['type']] ?? 'bg-[#94A3B8]';
                        $status = $statusConfig[$donation['status']] ?? ['dot' => 'bg-gray-400', 'text' => 'text-gray-600'];
                        $initial = mb_substr($donation['donor_name'], 0, 1);
                        $formattedDate = \Carbon\Carbon::parse($donation['date'])->translatedFormat('d M Y');
                    @endphp
                    <tr
                        class="border-b border-[#F1F5F9] transition-colors"
                        :class="selected.includes('{{ $donation['id'] }}') ? 'bg-[#065F46]/5 border-l-2 border-l-[#065F46]' : 'hover:bg-[#F8FAFC]'"
                        x-show="matchesFilters('{{ $donation['id'] }}', '{{ $donation['donor_name'] }}', '{{ $donation['type'] }}', '{{ $donation['status'] }}')"
                    >
                        <td class="px-3 py-3">
                            <label class="sr-only">Pilih donasi #{{ $donation['id'] }}</label>
                            <input type="checkbox" value="{{ $donation['id'] }}" x-model="selected" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                        </td>
                        <td class="px-3 py-3 font-medium text-[#1E293B] whitespace-nowrap">#{{ $donation['id'] }}</td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 bg-[#065F46]/10 text-[#065F46] rounded-full flex items-center justify-center text-xs font-bold shrink-0">{{ $initial }}</div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[#1E293B]">{{ $donation['donor_name'] }}</span>
                                    @if($donation['is_anonymous'])
                                        <span class="text-[10px] bg-[#FEF3C7] text-[#92400E] px-1.5 py-0.5 rounded font-medium">Anonim</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-4 rounded-full {{ $typeColor }}"></span>
                                <span class="text-[#475569]">{{ $donation['type'] }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-3 font-semibold text-[#1E293B] whitespace-nowrap">{{ format_rupiah($donation['amount']) }}</td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full {{ $status['dot'] }}"></span>
                                <span class="{{ $status['text'] }} text-sm">{{ $donation['status'] }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-[#64748B] whitespace-nowrap hidden lg:table-cell">{{ $formattedDate }}</td>
                        <td class="px-3 py-3 text-[#64748B] whitespace-nowrap hidden lg:table-cell">{{ $donation['method'] }}</td>
                        <td class="px-3 py-3">
                            <div class="relative" x-data="{ rowMenu: false }">
                                <button
                                    @click="rowMenu = !rowMenu"
                                    @click.away="rowMenu = false"
                                    class="p-1 rounded hover:bg-[#F1F5F9] focus-visible:ring-2 focus-visible:ring-[#065F46]"
                                    aria-label="Aksi untuk donasi #{{ $donation['id'] }}"
                                    :aria-expanded="rowMenu.toString()"
                                >
                                    <svg class="w-4 h-4 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/></svg>
                                </button>
                                <div x-show="rowMenu" x-transition class="absolute right-0 mt-1 w-32 bg-white border border-[#E2E8F0] rounded-lg shadow-lg py-1 z-20" role="menu" x-cloak>
                                    {{-- TODO: implementasi detail, edit, hapus --}}
                                    <button onclick="alert('Segera hadir')" class="w-full text-left px-3 py-1.5 text-sm text-[#475569] hover:bg-[#F1F5F9]" role="menuitem">Detail</button>
                                    <button onclick="alert('Segera hadir')" class="w-full text-left px-3 py-1.5 text-sm text-[#475569] hover:bg-[#F1F5F9]" role="menuitem">Edit</button>
                                    <button onclick="alert('Segera hadir')" class="w-full text-left px-3 py-1.5 text-sm text-red-600 hover:bg-red-50" role="menuitem">Hapus</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function donationTable() {
        return {
            search: '',
            filterOpen: false,
            selectAll: false,
            selected: ['DN-0231'],
            sortCol: null,
            sortAsc: true,
            filters: {
                types: ['Jumat Berkah', 'Donasi Bantuan', 'Pembangunan Pondok Tahfidz'],
                statuses: ['Terverifikasi', 'Menunggu'],
            },
            activeFilters: {
                types: ['Jumat Berkah', 'Donasi Bantuan', 'Pembangunan Pondok Tahfidz'],
                statuses: ['Terverifikasi', 'Menunggu'],
            },

            toggleSelectAll() {
                const checkboxes = this.$el.querySelectorAll('tbody input[type="checkbox"]');
                if (this.selectAll) {
                    this.selected = Array.from(checkboxes).map(cb => cb.value);
                } else {
                    this.selected = [];
                }
            },

            sortBy(col) {
                if (this.sortCol === col) {
                    this.sortAsc = !this.sortAsc;
                } else {
                    this.sortCol = col;
                    this.sortAsc = true;
                }
            },

            getSortAria(col) {
                if (this.sortCol !== col) return 'none';
                return this.sortAsc ? 'ascending' : 'descending';
            },

            applyFilters() {
                this.activeFilters = JSON.parse(JSON.stringify(this.filters));
                this.filterOpen = false;
            },

            resetFilters() {
                this.filters = {
                    types: ['Jumat Berkah', 'Donasi Bantuan', 'Pembangunan Pondok Tahfidz'],
                    statuses: ['Terverifikasi', 'Menunggu'],
                };
                this.activeFilters = JSON.parse(JSON.stringify(this.filters));
                this.filterOpen = false;
            },

            matchesFilters(id, name, type, status) {
                if (this.search) {
                    const q = this.search.toLowerCase();
                    if (!id.toLowerCase().includes(q) && !name.toLowerCase().includes(q)) return false;
                }
                if (this.activeFilters.types.length > 0) {
                    const normType = (type || '').toLowerCase();
                    const matchesType = this.activeFilters.types.some(t => {
                        const normFilter = t.toLowerCase();
                        return normType === normFilter ||
                            (normFilter.includes('bantuan') && normType.includes('bantuan')) ||
                            (normFilter.includes('pembangunan') && normType.includes('pembangunan')) ||
                            (normFilter.includes('jumat') && normType.includes('jumat'));
                    });
                    if (!matchesType) return false;
                }
                if (this.activeFilters.statuses.length > 0 && !this.activeFilters.statuses.includes(status)) return false;
                return true;
            },
        };
    }
</script>
