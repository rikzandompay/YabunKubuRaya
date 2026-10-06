@extends('layouts.app')

@php
    $articleExcerpt = Str::limit(strip_tags($article->excerpt ?? $article->content), 150);
@endphp

@section('title', $article->title . ' — Yayasan BUM Pontianak')
@section('meta_description', $articleExcerpt)
@section('canonical_url', route('artikel.show', $article->slug))
@section('og_type', 'article')
@section('og_title', $article->title . ' — Yayasan BUM Pontianak')
@section('og_description', $articleExcerpt)
@section('og_image', $article->header_image_url)

@section('content')
<div class="bg-[#F8F9FB] min-h-screen py-10 md:py-16">
    <div class="max-w-4xl mx-auto px-6">
        
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-xs md:text-sm text-gray-500 font-medium">
            <a href="/" class="hover:text-[#00843D]">Beranda</a>
            <span>/</span>
            <a href="{{ route('artikel.index') }}" class="hover:text-[#00843D]">Artikel</a>
            <span>/</span>
            <a href="{{ route('artikel.index', ['kategori' => $article->programCategory?->slug]) }}" class="hover:text-[#00843D]">
                {{ $article->programCategory?->name ?? 'Program' }}
            </a>
        </nav>

        <!-- Main Article Container -->
        <article class="bg-white rounded-3xl p-6 md:p-10 shadow-xs border border-gray-100 mb-12">
            
            <!-- Category Badge & Date -->
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="bg-[#00843D] text-white text-xs font-semibold px-3.5 py-1 rounded-full shadow-xs">
                    {{ $article->programCategory?->name ?? 'Program' }}
                </span>
                <span class="text-xs text-gray-500 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    {{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : 'Baru' }}
                </span>
            </div>

            <!-- Title -->
            <h1 class="text-2xl md:text-4xl font-extrabold text-[#111111] leading-tight mb-6">
                {{ $article->title }}
            </h1>

            <!-- Header Image -->
            <div class="relative w-full rounded-2xl overflow-hidden mb-8 bg-gray-50 border border-gray-100 shadow-xs flex justify-center items-center">
                <img src="{{ $article->header_image_url }}" 
                     alt="{{ $article->title }}" 
                     loading="lazy"
                     class="w-full h-auto max-h-[650px] object-contain rounded-2xl" />
            </div>

            <!-- Content Area -->
            <div class="prose prose-lg max-w-none text-[#374151] leading-relaxed space-y-4 font-normal">
                {!! $article->content !!}
            </div>

            <!-- Share / Kembali CTA -->
            <div class="mt-10 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('artikel.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-gray-300 text-sm font-semibold text-[#374151] hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Daftar Artikel</span>
                </a>

                <button type="button" 
                        onclick="copyToClipboard(window.location.href, 'Tautan Artikel')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#00843D] text-white text-sm font-semibold hover:bg-[#006B31] transition-colors shadow-xs cursor-pointer">
                    <svg class="w-4 h-4 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                        <polyline points="16 6 12 2 8 6"></polyline>
                        <line x1="12" y1="2" x2="12" y2="15"></line>
                    </svg>
                    <span>Bagikan Artikel</span>
                </button>
            </div>

        </article>

        <!-- Related Articles Section -->
        @if(isset($relatedArticles) && $relatedArticles->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-[#111111] mb-6">Artikel Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $rel)
                        <article class="bg-white rounded-2xl overflow-hidden shadow-xs border border-gray-100 flex flex-col h-full group hover:shadow-md transition-shadow">
                            <div class="relative w-full aspect-[16/9] overflow-hidden bg-gray-100">
                                <img src="{{ $rel->header_image_url }}" alt="{{ $rel->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="text-base font-bold text-[#111111] group-hover:text-[#00843D] transition-colors line-clamp-2 mb-2">
                                    <a href="{{ route('artikel.show', $rel->slug) }}">{{ $rel->title }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 line-clamp-2 mb-4 font-normal">{{ $rel->excerpt }}</p>
                                <div class="mt-auto">
                                    <a href="{{ route('artikel.show', $rel->slug) }}" class="text-xs font-semibold text-[#00843D] hover:underline">Baca selengkapnya &rarr;</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@'.'context' => 'https://schema.org',
    '@'.'type' => 'NewsArticle',
    'headline' => $article->title,
    'description' => $articleExcerpt,
    'image' => [
        $article->header_image_url,
    ],
    'datePublished' => $article->published_at?->toIso8601String() ?? now()->toIso8601String(),
    'dateModified' => $article->updated_at?->toIso8601String() ?? now()->toIso8601String(),
    'mainEntityOfPage' => [
        '@'.'type' => 'WebPage',
        '@'.'id' => route('artikel.show', $article->slug),
    ],
    'author' => [
        '@'.'type' => 'Organization',
        'name' => 'Yayasan Bakti Umat Nusantara Cabang Pontianak',
        'url' => url('/'),
    ],
    'publisher' => [
        '@'.'type' => 'Organization',
        'name' => 'Yayasan Bakti Umat Nusantara Cabang Pontianak',
        'logo' => [
            '@'.'type' => 'ImageObject',
            'url' => asset('images/logo-bun.webp'),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@'.'context' => 'https://schema.org',
    '@'.'type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@'.'type' => 'ListItem',
            'position' => 1,
            'name' => 'Beranda',
            'item' => url('/'),
        ],
        [
            '@'.'type' => 'ListItem',
            'position' => 2,
            'name' => 'Artikel',
            'item' => route('artikel.index'),
        ],
        [
            '@'.'type' => 'ListItem',
            'position' => 3,
            'name' => $article->title,
            'item' => route('artikel.show', $article->slug),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush
