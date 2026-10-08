@extends('layouts.app')

@section('title', 'Yayasan Bakti Umat Nusantara Cabang Kubu Raya — Bersama Membangun Kebaikan')
@section('meta_description', 'Website Resmi Yayasan Bakti Umat Nusantara (YABUN) Cabang Kubu Raya. Menghadirkan program sosial, santunan dhuafa, pendidikan tahfidz Al-Qur\'an, dan keagamaan.')
@section('canonical_url', route('home'))

@section('content')
    {{-- Hero Section (Above the fold - Rendered immediately for optimal LCP & FCP) --}}
    <div>
        @include('sections.hero')
    </div>

    {{-- Profil Yabun Section (Sejarah & Santri Mockup) --}}
    <div data-reveal data-reveal-duration="600">
        @include('sections.profile')
    </div>

    {{-- Dampak Nyata Section (3 Cards: Santri Qur'an, Anak Yatim, Kaum Dhuafa) --}}
    <div data-reveal data-reveal-duration="600">
        @include('sections.dampak-nyata')
    </div>

    {{-- Visi & Misi Section (Kegiatan Mockup & 4 Pilar Misi) --}}
    <div data-reveal data-reveal-duration="600">
        @include('sections.visi-misi')
    </div>

    {{-- Terverifikasi Oleh Lembaga (Notaris PPAT, Dinsos Kubu Raya, BAZNAS Kubu Raya) --}}
    <div data-reveal data-reveal-duration="600">
        @include('sections.legalitas')
    </div>

    {{-- Katalog Program Section (Jumat Berkah, Tahsin & Tahfiz, Santunan & Zakat) --}}
    <div data-reveal data-reveal-duration="600">
        @include('sections.katalog')
    </div>

    {{-- Program Unggulan Section (Pembangunan Rumah Tahfidz) --}}
    <div data-reveal data-reveal-duration="600">
        @include('sections.program-unggulan')
    </div>

    {{-- Galeri Kegiatan (Bento Grid 5 Foto & 3 Filter Kategori) --}}
    <div data-reveal data-reveal-duration="600">
        <x-gallery-section :photos="$photos ?? []" :category="$category ?? 'jumat_berkah'" :totalCount="$totalCount ?? 0" />
    </div>

    {{-- Program Artikel Section (3 Kartu Flat: Keagamaan, Pendidikan, Sosial) --}}
    <div data-reveal data-reveal-duration="600">
        <x-program-article-section :categories="$categories ?? []" />
    </div>

    {{-- Transparansi Donasi Section (Flat Stat Typography, Live Synced dengan DB) --}}
    <div data-reveal data-reveal-duration="600">
        <x-donation-transparency-section :summary="$transparencySummary ?? null" />
    </div>

    {{-- Info Donasi & Rekening Bank (BSI & BRI) --}}
    <div data-reveal data-reveal-duration="600">
        @include('sections.donasi')
    </div>
@endsection
