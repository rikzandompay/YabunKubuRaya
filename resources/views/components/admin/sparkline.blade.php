@props([
    'path',
    'areaPath',
    'positive' => true,
    'isNeutral' => false,
])

@php
    if ($isNeutral) {
        $color = '#94A3B8';
        $fillOpacity = '0.04';
    } else {
        $color = $positive ? '#059669' : '#EF4444';
        $fillOpacity = '0.12';
    }
@endphp

<svg
    {{ $attributes->merge(['class' => 'w-14 h-5 sm:w-20 sm:h-8 shrink-0']) }}
    viewBox="0 0 80 32"
    fill="none"
    role="img"
    aria-label="{{ $isNeutral ? 'Tren stabil' : ($positive ? 'Tren naik' : 'Tren turun') }}"
>
    <path d="{{ $areaPath }}" fill="{{ $color }}" fill-opacity="{{ $fillOpacity }}" />
    <path d="{{ $path }}" stroke="{{ $color }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
</svg>
