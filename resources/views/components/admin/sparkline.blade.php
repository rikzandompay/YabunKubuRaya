@props([
    'path',
    'areaPath',
    'positive' => true,
])

@php
    $color = $positive ? '#059669' : '#EF4444';
@endphp

<svg
    class="w-20 h-8 shrink-0"
    viewBox="0 0 80 32"
    fill="none"
    role="img"
    aria-label="{{ $positive ? 'Tren naik' : 'Tren turun' }}"
>
    <path d="{{ $areaPath }}" fill="{{ $color }}" fill-opacity="0.12" />
    <path d="{{ $path }}" stroke="{{ $color }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
</svg>
