@props(['options' => [], 'selected' => null, 'id' => 'dropdown'])

<div x-data="{ open: false, selected: '{{ $selected ?? ($options[0] ?? '') }}' }" class="relative">
    <button
        @click="open = !open"
        @click.away="open = false"
        @keydown.escape="open = false"
        :aria-expanded="open.toString()"
        aria-haspopup="listbox"
        class="flex items-center gap-2 px-3 py-1.5 text-sm text-[#475569] bg-white border border-[#E2E8F0] rounded-lg hover:border-[#CBD5E1] focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2 transition-colors"
    >
        {{ $trigger ?? '' }}
        <span x-text="selected"></span>
        <svg class="w-3.5 h-3.5 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-1 w-40 bg-white border border-[#E2E8F0] rounded-lg shadow-lg py-1 z-20"
        role="listbox"
        x-cloak
    >
        @foreach($options as $option)
            <button
                @click="selected = '{{ $option }}'; open = false; $dispatch('period-change', { value: '{{ $option }}' })"
                :class="selected === '{{ $option }}' ? 'bg-[#F1F5F9] text-[#1E293B] font-medium' : 'text-[#475569]'"
                class="w-full text-left px-3 py-1.5 text-sm hover:bg-[#F1F5F9] transition-colors"
                role="option"
                :aria-selected="(selected === '{{ $option }}').toString()"
            >
                {{ $option }}
            </button>
        @endforeach
    </div>
</div>
