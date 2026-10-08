@props([
    'title',
    'icon',
    'value',
    'delta' => 0,
    'positive' => true,
    'isNeutral' => false,
    'comparison' => 'vs minggu lalu',
    'sparkline',
    'sparklineArea',
])

<div class="bg-[#F8FAFC] dark:bg-[#1E293B] rounded-xl border border-[#E2E8F0] dark:border-slate-800 transition-colors duration-200">
    {{-- Header with diagonal stripe pattern --}}
    <div
        class="px-4 py-2.5 flex items-center justify-between border-b border-[#E2E8F0]/60 dark:border-slate-800/80"
        style="background: repeating-linear-gradient(135deg, transparent, transparent 4px, rgba(0,0,0,0.02) 4px, rgba(0,0,0,0.02) 5px)"
    >
        <span class="text-xs font-medium text-[#64748B] dark:text-slate-400 uppercase tracking-wide">{{ $title }}</span>
        @if($icon === 'banknotes')
            <svg class="w-4 h-4 text-[#94A3B8] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
        @elseif($icon === 'building')
            <svg class="w-4 h-4 text-[#94A3B8] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/></svg>
        @elseif($icon === 'mosque')
            <svg class="w-4 h-4 text-[#94A3B8] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5M12 3c-2 2-4 3.5-4 6a4 4 0 008 0c0-2.5-2-4-4-6zM6.5 10.5C4.5 11.5 3 13 3 15v6h18v-6c0-2-1.5-3.5-3.5-4.5M9 21v-3a3 3 0 016 0v3"/></svg>
        @elseif($icon === 'heart')
            <svg class="w-4 h-4 text-[#94A3B8] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
        @endif
    </div>

    {{-- Inner card --}}
    <div class="bg-white dark:bg-[#0F172A] rounded-lg mx-3 my-3 p-4 flex items-end justify-between gap-3 border border-transparent dark:border-slate-800/80 transition-colors duration-200">
        <div>
            <p class="text-2xl font-bold text-[#1E293B] dark:text-white">{{ format_rupiah($value) }}</p>
            <p class="mt-1 text-xs flex items-center gap-1.5">
                @if($isNeutral || $delta == 0)
                    <span class="text-[#64748B] dark:text-slate-400 font-medium">0%</span>
                @else
                    <span class="{{ $positive ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500 dark:text-rose-400' }} font-medium">
                        {{ $positive ? '↑ +' : '↓ -' }}{{ $delta }}%
                    </span>
                @endif
                <span class="text-[#94A3B8] dark:text-slate-500">{{ $comparison }}</span>
            </p>
        </div>

        {{-- Sparkline SVG --}}
        <x-admin.sparkline :path="$sparkline" :area-path="$sparklineArea" :positive="$positive" :is-neutral="$isNeutral" />
    </div>
</div>
