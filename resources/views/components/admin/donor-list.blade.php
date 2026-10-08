@props(['donors' => [], 'totalDonorsCount' => null])

<div class="bg-[#F8FAFC] dark:bg-[#1E293B] rounded-xl border border-[#E2E8F0] dark:border-slate-800 flex flex-col h-full transition-colors duration-200" x-data="{ activeTab: 'bulan-ini', donorSearch: '' }">
    {{-- Header --}}
    <div
        class="px-4 py-2.5 flex items-center justify-between border-b border-[#E2E8F0]/60 dark:border-slate-800/80"
        style="background: repeating-linear-gradient(135deg, transparent, transparent 4px, rgba(0,0,0,0.02) 4px, rgba(0,0,0,0.02) 5px)"
    >
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#94A3B8] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            <span class="text-sm font-semibold text-[#1E293B] dark:text-white">Donatur Terbanyak</span>
        </div>
    </div>

    {{-- Body --}}
    <div class="bg-white dark:bg-[#0F172A] rounded-lg mx-3 my-3 p-4 flex-1 flex flex-col border border-transparent dark:border-slate-800/80 transition-colors duration-200">
        {{-- Tab pills --}}
        <div class="flex items-center gap-1 mb-3">
            <button
                @click="activeTab = 'bulan-ini'"
                :class="activeTab === 'bulan-ini' ? 'bg-[#1E293B] dark:bg-emerald-600 text-white' : 'text-[#64748B] dark:text-slate-400 hover:bg-[#F1F5F9] dark:hover:bg-slate-800'"
                class="px-3 py-1 rounded-full text-xs font-medium transition-colors focus-visible:ring-2 focus-visible:ring-[#065F46] cursor-pointer"
            >Bulan Ini</button>
            <button
                @click="activeTab = 'tahun-ini'"
                :class="activeTab === 'tahun-ini' ? 'bg-[#1E293B] dark:bg-emerald-600 text-white' : 'text-[#64748B] dark:text-slate-400 hover:bg-[#F1F5F9] dark:hover:bg-slate-800'"
                class="px-3 py-1 rounded-full text-xs font-medium transition-colors focus-visible:ring-2 focus-visible:ring-[#065F46] cursor-pointer"
            >Tahun Ini</button>
            <button
                @click="activeTab = 'semua'"
                :class="activeTab === 'semua' ? 'bg-[#1E293B] dark:bg-emerald-600 text-white' : 'text-[#64748B] dark:text-slate-400 hover:bg-[#F1F5F9] dark:hover:bg-slate-800'"
                class="px-3 py-1 rounded-full text-xs font-medium transition-colors focus-visible:ring-2 focus-visible:ring-[#065F46] cursor-pointer"
            >Semua</button>
        </div>

        {{-- Search --}}
        <div class="relative mb-3">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-[#94A3B8] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input
                type="text"
                x-model="donorSearch"
                placeholder="Cari donatur"
                class="w-full pl-8 pr-3 py-1.5 text-sm bg-[#F8FAFC] dark:bg-slate-800/80 border border-[#E2E8F0] dark:border-slate-700 text-[#1E293B] dark:text-white rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none placeholder-[#94A3B8] dark:placeholder-slate-500"
                aria-label="Cari donatur"
            >
        </div>

        {{-- Meta --}}
        <p class="text-xs text-[#94A3B8] dark:text-slate-500 mb-3">{{ ($totalDonorsCount ?? count($donors)) }} donatur terdaftar</p>

        {{-- List --}}
        <div class="flex-1 space-y-1 overflow-y-auto">
            @forelse($donors as $index => $donor)
                <div
                    class="flex items-center gap-3 p-2 rounded-lg hover:bg-[#F8FAFC] dark:hover:bg-slate-800/50 transition-colors"
                    x-show="!donorSearch || '{{ strtolower($donor['name']) }}'.includes(donorSearch.toLowerCase())"
                >
                    {{-- Rank badge --}}
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 text-xs font-bold
                        {{ $index === 0 ? 'bg-[#D97706]/10 dark:bg-amber-950/40 border border-[#D97706]/30 dark:border-amber-700/50 text-[#D97706] dark:text-amber-400' : 'bg-[#F8FAFC] dark:bg-slate-800 border border-[#E2E8F0] dark:border-slate-700 text-[#1E293B] dark:text-slate-200' }}
                    ">
                        #{{ $index + 1 }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <p class="text-sm font-medium text-[#1E293B] dark:text-white truncate">{{ $donor['name'] }}</p>
                            @if($donor['is_anonymous'])
                                <span class="text-[10px] bg-[#FEF3C7] dark:bg-amber-950/60 text-[#92400E] dark:text-amber-300 px-1.5 py-0.5 rounded font-medium shrink-0">Anonim</span>
                            @endif
                        </div>
                        <p class="text-xs text-[#94A3B8] dark:text-slate-500">{{ $donor['donations_count'] }} donasi</p>
                    </div>

                    {{-- Amount --}}
                    <p class="text-sm font-semibold text-[#1E293B] dark:text-white shrink-0">{{ format_rupiah($donor['total']) }}</p>
                </div>
            @empty
                <div class="py-12 text-center text-xs text-[#94A3B8] dark:text-slate-500">
                    Belum ada donatur yang tercatat.
                </div>
            @endforelse
        </div>
    </div>
</div>
