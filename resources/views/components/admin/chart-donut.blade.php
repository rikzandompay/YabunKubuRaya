@props(['donutData' => []])

@php
    $total = (float) ($donutData['total'] ?? 0);
    $categories = $donutData['categories'] ?? [];
    $periodLabel = $donutData['period_label'] ?? 'Semua Periode';
    $jsonCategories = json_encode($categories);
@endphp

<div
    class="bg-[#F8FAFC] dark:bg-[#1E293B] rounded-xl border border-[#E2E8F0] dark:border-slate-800 flex flex-col h-full transition-colors duration-200"
    x-data="{
        categories: {{ $jsonCategories }},
        total: {{ $total }},
        hoveredKey: null,
        activeItem: null,
        formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }
    }"
>
    {{-- Header --}}
    <div
        class="px-4 py-2.5 flex items-center justify-between border-b border-[#E2E8F0] dark:border-slate-800/80"
        style="background: repeating-linear-gradient(135deg, transparent, transparent 4px, rgba(0,0,0,0.02) 4px, rgba(0,0,0,0.02) 5px)"
    >
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#00843D] dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
            </svg>
            <span class="text-sm font-semibold text-[#1E293B] dark:text-white">Proporsi Kategori Donasi</span>
        </div>
        <span class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-emerald-50 dark:bg-emerald-950/60 text-[#00843D] dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
            {{ $periodLabel }}
        </span>
    </div>

    {{-- Body --}}
    <div class="bg-white dark:bg-[#0F172A] rounded-lg mx-3 mb-3 mt-3 p-4 sm:p-5 flex-1 flex flex-col justify-between transition-colors duration-200">
        {{-- Donut Chart Visual --}}
        <div class="relative flex items-center justify-center my-3">
            <svg class="w-56 h-56 sm:w-60 sm:h-60 xl:w-64 xl:h-64 transform transition-transform" viewBox="0 0 160 160">
                {{-- Base background track --}}
                <circle
                    cx="80"
                    cy="80"
                    r="60"
                    fill="none"
                    class="stroke-slate-100 dark:stroke-slate-800"
                    stroke-width="20"
                />

                @if($total > 0)
                    {{-- Category segments --}}
                    @foreach($categories as $cat)
                        @if($cat['percentage'] > 0)
                            <circle
                                cx="80"
                                cy="80"
                                r="60"
                                fill="none"
                                stroke="{{ $cat['color'] }}"
                                stroke-width="20"
                                stroke-dasharray="{{ $cat['stroke_dasharray'] }}"
                                stroke-dashoffset="{{ $cat['stroke_dashoffset'] }}"
                                transform="rotate(-90 80 80)"
                                stroke-linecap="butt"
                                class="transition-all duration-300 cursor-pointer"
                                :class="{
                                    '!opacity-40': hoveredKey && hoveredKey !== '{{ $cat['key'] }}',
                                    'stroke-[24] filter drop-shadow-sm': hoveredKey === '{{ $cat['key'] }}'
                                }"
                                @mouseenter="hoveredKey = '{{ $cat['key'] }}'; activeItem = categories.find(c => c.key === '{{ $cat['key'] }}')"
                                @mouseleave="hoveredKey = null; activeItem = null"
                            />
                        @endif
                    @endforeach
                @endif
            </svg>

            {{-- Center hole display --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none px-4">
                <template x-if="activeItem">
                    <div class="transition-opacity duration-150">
                        <span class="text-xs font-semibold text-[#64748B] dark:text-slate-400 block truncate max-w-[140px]" x-text="activeItem.name"></span>
                        <p class="text-lg sm:text-xl font-extrabold text-[#1E293B] dark:text-white mt-0.5 leading-tight tracking-tight" x-text="formatRupiah(activeItem.total)"></p>
                        <span class="inline-block mt-1 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-0.5 rounded-full" x-text="activeItem.percentage + '%'"></span>
                    </div>
                </template>

                <template x-if="!activeItem">
                    <div>
                        <span class="text-[11px] sm:text-xs font-semibold text-[#64748B] dark:text-slate-400 uppercase tracking-wider block">Total Donasi</span>
                        <p class="text-lg sm:text-xl font-extrabold text-[#1E293B] dark:text-white mt-0.5 leading-tight tracking-tight">{{ format_rupiah($total) }}</p>
                        @if($total == 0)
                            <span class="text-xs text-[#94A3B8] dark:text-slate-500 mt-1 block">Belum ada pemasukan</span>
                        @else
                            <span class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 font-semibold block">100% Terverifikasi</span>
                        @endif
                    </div>
                </template>
            </div>
        </div>

        {{-- Category Breakdown List --}}
        <div class="mt-4 pt-3 border-t border-[#E2E8F0] dark:border-slate-800 space-y-2">
            @forelse($categories as $cat)
                <div
                    class="p-2 rounded-lg transition-colors cursor-pointer flex flex-col gap-1.5"
                    :class="hoveredKey === '{{ $cat['key'] }}' ? 'bg-[#F8FAFC] dark:bg-slate-800/80 shadow-xs' : 'hover:bg-[#F8FAFC] dark:hover:bg-slate-800/40'"
                    @mouseenter="hoveredKey = '{{ $cat['key'] }}'; activeItem = categories.find(c => c.key === '{{ $cat['key'] }}')"
                    @mouseleave="hoveredKey = null; activeItem = null"
                >
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $cat['color'] }}"></span>
                            <span class="font-medium text-[#1E293B] dark:text-slate-200 truncate">{{ $cat['name'] }}</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="font-semibold text-[#1E293B] dark:text-white">{{ format_rupiah($cat['total']) }}</span>
                            <span class="text-[11px] font-bold text-[#64748B] dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded">
                                {{ $cat['percentage'] }}%
                            </span>
                        </div>
                    </div>
                    {{-- Progress Bar --}}
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                        <div
                            class="h-full rounded-full transition-all duration-500"
                            style="width: {{ $cat['percentage'] }}%; background-color: {{ $cat['color'] }}"
                        ></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-center text-[#94A3B8] dark:text-slate-500 py-2">Tidak ada kategori data.</p>
            @endforelse
        </div>
    </div>
</div>
