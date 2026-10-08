@extends('layouts.app')

@section('title', 'Program Artikel & Berita — Yayasan Bakti Umat Nusantara Cabang Kubu Raya')
@section('meta_description',
    'Kumpulan berita, artikel, dan dokumentasi kegiatan sosial, pendidikan Al-Qur\'an, dan
    keagamaan Yayasan Bakti Umat Nusantara Cabang Kubu Raya.')
@section('canonical_url', route('artikel.index'))

@section('content')
    <div class="bg-[#F8F9FB] min-h-screen py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Header Page -->
            <div class="mb-10 text-center max-w-3xl mx-auto">
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-[#111111]">
                    Program Artikel & Berita
                </h1>
                <p class="mt-3 text-base md:text-lg text-[#4B5563]">
                    Informasi dan kabar terbaru seputar kegiatan keagamaan, pendidikan, dan aksi sosial Yayasan Bakti Umat
                    Nusantara Cabang Kubu Raya.
                </p>
            </div>

            <!-- Filter Pill Categories (Semua, Keagamaan, Pendidikan, Sosial) -->
            <div class="mb-12 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('artikel.index') }}"
                    class="px-6 py-2.5 rounded-full font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] {{ $kategori === 'semua' || !$kategori ? 'bg-[#00843D] text-white shadow-md' : 'bg-[#F1F3F5] text-[#374151] hover:bg-gray-200' }}">
                    Semua
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('artikel.index', ['kategori' => $cat->slug]) }}"
                        class="px-6 py-2.5 rounded-full font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] {{ $kategori === $cat->slug ? 'bg-[#00843D] text-white shadow-md' : 'bg-[#F1F3F5] text-[#374151] hover:bg-gray-200' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Grid Artikel 3 Kolom -->
            @if ($articles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach ($articles as $article)
                        <article
                            class="bg-white rounded-2xl overflow-hidden shadow-xs border border-gray-100 flex flex-col h-full group transition-all duration-300 hover:shadow-md">
                            <!-- Foto Header -->
                            <div class="relative w-full aspect-[4/3] sm:aspect-[16/10] overflow-hidden bg-gray-100">
                                <img src="{{ $article->header_image_url }}" alt="{{ $article->title }}" loading="lazy"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" />
                                <!-- Badge Kategori -->
                                <span
                                    class="absolute top-4 left-4 bg-[#00843D] text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                    {{ $article->programCategory?->name ?? 'Program' }}
                                </span>
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 flex flex-col flex-1">
                                <!-- Tanggal Publikasi -->
                                <div class="text-xs text-gray-500 font-medium mb-2 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <span>{{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : 'Baru' }}</span>
                                </div>

                                <!-- Judul Artikel -->
                                <h2
                                    class="text-xl font-bold text-[#111111] group-hover:text-[#00843D] transition-colors line-clamp-2 mb-3">
                                    <a href="{{ route('artikel.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h2>

                                <!-- Kutipan / Excerpt -->
                                <p class="text-sm text-[#4B5563] line-clamp-3 leading-relaxed mb-6 font-normal">
                                    {{ $article->excerpt }}
                                </p>

                                <!-- Link Baca Selengkapnya -->
                                <div class="mt-auto pt-2">
                                    <a href="{{ route('artikel.show', $article->slug) }}"
                                        class="inline-flex items-center gap-2 text-sm font-semibold text-[#00843D] hover:text-[#006B31] transition-colors group/link">
                                        <span>Baca selengkapnya</span>
                                        <svg class="w-4 h-4 stroke-current stroke-2 fill-none transform group-hover/link:translate-x-1 transition-transform"
                                            viewBox="0 0 24 24">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination Link -->
                <div class="mt-8 flex justify-center">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="py-16 text-center bg-white rounded-2xl border border-dashed border-gray-200 max-w-2xl mx-auto">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">Artikel tidak ditemukan</h3>
                    <p class="text-sm text-gray-500 mt-1">Coba gunakan kata kunci lain atau ubah filter kategori/tanggal
                        Anda.</p>
                    <a href="{{ route('artikel.index') }}"
                        class="inline-block mt-4 text-sm font-semibold text-[#00843D] hover:underline">Reset Filter</a>
                </div>
            @endif

        </div>
    </div>
@endsection
