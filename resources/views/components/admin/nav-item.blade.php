@props(['label', 'icon', 'badge' => null, 'items' => []])

@php
    $hasActiveItem = collect($items)->contains(function ($i) {
        $target = $i['href'] ?? '';
        return $target !== '#' && (request()->fullUrl() === $target || request()->url() === $target);
    });
@endphp

<div x-data="{ expanded: {{ $hasActiveItem ? 'true' : 'false' }} }" x-show="matchesSearch('{{ $label }}')" class="mb-0.5">
    <button
        @click="expanded = !expanded"
        :aria-expanded="expanded.toString()"
        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ $hasActiveItem ? 'bg-white dark:bg-slate-800 border border-[#E2E8F0] dark:border-slate-700 shadow-sm text-[#065F46] dark:text-emerald-400 font-semibold' : 'text-[#475569] dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800/70 hover:shadow-sm hover:border hover:border-[#E2E8F0] dark:hover:border-slate-700' }} transition-colors cursor-pointer"
    >
        <span class="shrink-0 {{ $hasActiveItem ? 'text-[#065F46] dark:text-emerald-400' : 'text-[#64748B] dark:text-slate-400' }}">{!! $icon !!}</span>
        <span class="flex-1 text-left">{{ $label }}</span>
        @if($badge)
            <span class="bg-[#F1F5F9] dark:bg-slate-700 text-[#64748B] dark:text-slate-300 text-[11px] font-medium px-1.5 py-0.5 rounded-full">{{ $badge }}</span>
        @endif
        <svg
            class="w-3.5 h-3.5 text-[#94A3B8] dark:text-slate-400 transition-transform shrink-0"
            :class="expanded && 'rotate-90'"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
        ><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>

    {{-- Submenu with tree line --}}
    <div x-show="expanded" x-collapse class="ml-[22px] pl-3 border-l-2 border-[#E2E8F0] dark:border-slate-700 mt-0.5 mb-1">
        @foreach($items as $item)
            @php
                $isActive = $item['href'] !== '#' && (request()->fullUrl() === $item['href'] || (count(request()->query()) === 0 && request()->url() === $item['href']));
            @endphp
            <a
                href="{{ $item['href'] }}"
                class="relative flex items-center gap-2 px-2 py-1.5 text-sm {{ $isActive ? 'text-[#065F46] dark:text-emerald-400 font-semibold bg-emerald-50/60 dark:bg-emerald-950/40' : 'text-[#64748B] dark:text-slate-400 hover:text-[#1E293B] dark:hover:text-white hover:bg-[#F1F5F9] dark:hover:bg-slate-800/60' }} rounded-md transition-colors"
            >
                <span class="absolute -left-[15px] w-2 h-2 {{ $isActive ? 'bg-[#065F46] dark:bg-emerald-400 ring-2 ring-emerald-200 dark:ring-emerald-800' : 'bg-[#E2E8F0] dark:bg-slate-700' }} rounded-full"></span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
</div>
