@props([
    'options' => [],
    'selected' => null,
    'selectedKey' => null,
    'id' => 'dropdown',
])

<div x-data="{ open: false }" class="relative">
    <button
        @click="open = !open"
        @click.away="open = false"
        @keydown.escape="open = false"
        :aria-expanded="open.toString()"
        aria-haspopup="listbox"
        class="flex items-center gap-2 px-3 py-1.5 text-sm text-[#475569] dark:text-slate-200 bg-white dark:bg-[#1E293B] border border-[#E2E8F0] dark:border-slate-700 rounded-lg hover:border-[#CBD5E1] dark:hover:border-slate-600 focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2 transition-colors cursor-pointer"
    >
        {{ $trigger ?? '' }}
        <span class="font-medium text-[#1E293B] dark:text-white">{{ $selected ?? ($options[0] ?? '') }}</span>
        <svg class="w-3.5 h-3.5 text-[#94A3B8] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-1 w-44 bg-white dark:bg-[#1E293B] border border-[#E2E8F0] dark:border-slate-700 rounded-lg shadow-xl py-1 z-20"
        role="listbox"
        x-cloak
    >
        @foreach($options as $key => $label)
            @php
                $optKey = is_numeric($key) ? $label : $key;
                $optLabel = $label;
                $isSelected = ($selectedKey && $selectedKey === $optKey) || ($selected && ($selected === $optLabel || $selected === $optKey));
                $url = route('admin.dashboard', ['periode' => $optKey]);
            @endphp
            <a
                href="{{ $url }}"
                class="block w-full text-left px-3 py-1.5 text-sm transition-colors {{ $isSelected ? 'bg-[#F1F5F9] dark:bg-slate-800 text-[#065F46] dark:text-emerald-400 font-semibold' : 'text-[#475569] dark:text-slate-300 hover:bg-[#F8FAFC] dark:hover:bg-slate-800/80' }}"
                role="option"
                aria-selected="{{ $isSelected ? 'true' : 'false' }}"
            >
                {{ $optLabel }}
            </a>
        @endforeach
    </div>
</div>
