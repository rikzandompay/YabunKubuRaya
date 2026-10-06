@extends('layouts.app')

@section('title', 'Galeri Dokumentasi Kegiatan — Yayasan BUM Pontianak')
@section('meta_description', 'Kumpulan dokumentasi foto penyaluran bantuan sosial, santunan dhuafa, beasiswa santri, dan kegiatan keagamaan Yayasan Bakti Umat Nusantara Cabang Pontianak.')
@section('canonical_url', route('galeri.index'))

@section('content')
<div class="bg-[#F8F9FB] min-h-screen py-12 md:py-20" x-data="fullGalleryComponent({{ json_encode($photos->items()) }})">
    <div class="max-w-7xl mx-auto px-6">
        
        <!-- Breadcrumb Navigation -->
        <nav class="mb-6 flex items-center gap-2 text-xs md:text-sm text-gray-500 font-medium">
            <a href="{{ route('home') }}" class="hover:text-[#00843D] transition-colors">Beranda</a>
            <svg class="w-3.5 h-3.5 text-gray-400 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
            <span class="text-[#00843D] font-semibold">Galeri Kegiatan</span>
        </nav>

        <!-- Header Page -->
        <div class="mb-10 text-center max-w-3xl mx-auto">
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-[#111111]">
                Galeri Dokumentasi Kegiatan
            </h1>
            <p class="mt-3 text-base md:text-lg text-[#4B5563]">
                Dokumentasi nyata aksi kemanusiaan, penyaluran amanah donatur, serta kegiatan sosial dan dakwah Yayasan Bakti Umat Nusantara Cabang Pontianak.
            </p>
        </div>

        <!-- Filter Pill Categories -->
        <div class="mb-12 flex flex-wrap items-center justify-center gap-3">
            <!-- Semua -->
            <a href="{{ route('galeri.index', ['kategori' => 'semua']) }}"
               class="px-6 py-2.5 rounded-full font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] {{ $category === 'semua' || !$category ? 'bg-[#00843D] text-white shadow-md' : 'bg-white text-[#374151] hover:bg-gray-100 border border-gray-200' }}">
                Semua ({{ $categoryCounts['semua'] ?? 0 }})
            </a>

            <!-- Jumat Berkah -->
            <a href="{{ route('galeri.index', ['kategori' => 'jumat_berkah']) }}"
               class="px-6 py-2.5 rounded-full font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] {{ $category === 'jumat_berkah' ? 'bg-[#00843D] text-white shadow-md' : 'bg-white text-[#374151] hover:bg-gray-100 border border-gray-200' }}">
                Jumat Berkah ({{ $categoryCounts['jumat_berkah'] ?? 0 }})
            </a>

            <!-- Donasi -->
            <a href="{{ route('galeri.index', ['kategori' => 'donasi']) }}"
               class="px-6 py-2.5 rounded-full font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] {{ $category === 'donasi' ? 'bg-[#00843D] text-white shadow-md' : 'bg-white text-[#374151] hover:bg-gray-100 border border-gray-200' }}">
                Donasi & Santunan ({{ $categoryCounts['donasi'] ?? 0 }})
            </a>

            <!-- Dzikir -->
            <a href="{{ route('galeri.index', ['kategori' => 'dzikir']) }}"
               class="px-6 py-2.5 rounded-full font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] {{ $category === 'dzikir' ? 'bg-[#00843D] text-white shadow-md' : 'bg-white text-[#374151] hover:bg-gray-100 border border-gray-200' }}">
                Dzikir & Doa ({{ $categoryCounts['dzikir'] ?? 0 }})
            </a>
        </div>

        <!-- Grid Foto Galeri -->
        @if($photos->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                @foreach($photos as $index => $photo)
                    <div class="group relative overflow-hidden rounded-2xl bg-white shadow-xs border border-gray-100 cursor-pointer transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                         @click="openLightbox({{ $index }})">
                        <!-- Foto Image -->
                        <div class="relative aspect-4/3 overflow-hidden bg-gray-100">
                            <img src="{{ $photo->image_url }}" 
                                 alt="{{ $photo->title }}" 
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105" />
                            
                            <!-- Category Badge -->
                            <span class="absolute top-3 left-3 bg-black/60 backdrop-blur-xs text-white text-[11px] font-medium px-2.5 py-1 rounded-full uppercase tracking-wider">
                                {{ str_replace('_', ' ', $photo->category) }}
                            </span>

                            <!-- Hover Overlay with Magnify / Zoom Icon -->
                            <div class="absolute inset-0 bg-[#00843D]/0 group-hover:bg-[#00843D]/30 transition-colors duration-300 flex items-center justify-center">
                                <div class="w-11 h-11 rounded-full bg-white/90 shadow-lg text-[#00843D] flex items-center justify-center opacity-0 transform scale-75 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300">
                                    <svg class="w-5 h-5 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <polyline points="9 21 3 21 3 15"></polyline>
                                        <line x1="21" y1="3" x2="14" y2="10"></line>
                                        <line x1="3" y1="21" x2="10" y2="14"></line>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Caption Title -->
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 group-hover:text-[#00843D] transition-colors">
                                {{ $photo->title }}
                            </h3>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $photos->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="py-20 text-center bg-white rounded-2xl border border-dashed border-gray-200 max-w-lg mx-auto p-8 shadow-xs">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-[#00843D] mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Foto</h3>
                <p class="text-sm text-gray-500 mb-6">Belum ada foto dokumentasi yang tersedia untuk kategori ini.</p>
                <a href="{{ route('galeri.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#00843D] text-white text-xs font-semibold hover:bg-[#006B31] transition-colors">
                    Lihat Semua Kategori
                </a>
            </div>
        @endif

        <!-- Back to Home link -->
        <div class="mt-14 text-center">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-[#00843D] transition-colors">
                <svg class="w-4 h-4 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

    </div>

    <!-- FULLSCREEN LIGHTBOX MODAL -->
    <template x-teleport="body">
        <div x-show="lightboxOpen" x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0" 
             @keydown.window.escape="closeLightbox()"
             class="fixed inset-0 z-[200] flex items-center justify-center bg-black/95 p-4 select-none" 
             role="dialog"
             aria-modal="true">

            <!-- Backdrop Click -->
            <div class="absolute inset-0" @click="closeLightbox()"></div>

            <!-- Close Button (X) -->
            <button type="button" @click="closeLightbox()" 
                    class="absolute top-5 right-5 z-30 p-3 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] cursor-pointer"
                    aria-label="Tutup Tampilan">
                <svg class="w-6 h-6 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <!-- Previous Button -->
            <button type="button" x-show="items.length > 1" @click.stop="prevPhoto()" 
                    class="absolute left-4 md:left-8 z-30 p-3 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] cursor-pointer"
                    aria-label="Foto Sebelumnya">
                <svg class="w-6 h-6 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <!-- Main Image & Title Container -->
            <div class="relative z-10 max-w-5xl max-h-[85vh] flex flex-col items-center justify-center pointer-events-none">
                <template x-if="currentPhoto">
                    <div class="flex flex-col items-center pointer-events-auto">
                        <img :src="currentPhoto.image_url" 
                             :alt="currentPhoto.title"
                             class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl transition-all duration-300" />
                        <p class="mt-4 text-center text-sm md:text-base font-medium text-white/90 max-w-2xl px-4" x-text="currentPhoto.title"></p>
                    </div>
                </template>
            </div>

            <!-- Next Button -->
            <button type="button" x-show="items.length > 1" @click.stop="nextPhoto()" 
                    class="absolute right-4 md:right-8 z-30 p-3 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] cursor-pointer"
                    aria-label="Foto Berikutnya">
                <svg class="w-6 h-6 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
    function fullGalleryComponent(initialItems) {
        return {
            items: initialItems || [],
            lightboxOpen: false,
            lightboxIndex: 0,

            get currentPhoto() {
                return this.items[this.lightboxIndex] || null;
            },

            openLightbox(index) {
                this.lightboxIndex = index;
                this.lightboxOpen = true;
                document.body.style.overflow = 'hidden';
            },

            closeLightbox() {
                this.lightboxOpen = false;
                document.body.style.overflow = '';
            },

            prevPhoto() {
                if (this.items.length === 0) return;
                this.lightboxIndex = (this.lightboxIndex - 1 + this.items.length) % this.items.length;
            },

            nextPhoto() {
                if (this.items.length === 0) return;
                this.lightboxIndex = (this.lightboxIndex + 1) % this.items.length;
            }
        };
    }
</script>
@endpush
