@props(['photos' => [], 'category' => 'jumat_berkah', 'totalCount' => 0])

<section id="galeri" class="bg-white py-16 md:py-24 border-b border-gray-100" x-data="galleryComponent({{ json_encode($photos) }}, '{{ $category }}', {{ $totalCount }})">
    <div class="mx-auto max-w-[1400px] px-6 lg:px-12">

        <!-- HEADER SECTION: Judul + Subjudul + Link Lihat Semua (Desktop) -->
        <div class="mb-8 md:mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#111827]">
                    Galeri Kegiatan
                </h2>
                <p class="mt-2 text-base md:text-lg font-normal text-[#6B7280]">
                    Dokumentasi kegiatan sosial Yayasan Bakti Umat Nusantara Cabang Kubu Raya
                </p>
            </div>
            <a href="{{ route('galeri.index') }}"
                aria-label="Lihat semua foto galeri kegiatan"
                class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-[#00843D] hover:text-[#006B31] transition-colors group">
                <span>Lihat Semua Foto</span>
                <svg class="w-4 h-4 stroke-current stroke-2 fill-none transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <!-- FILTER TAB PILLS (3 Jenis: Jumat Berkah, Donasi, Dzikir) -->
        <div class="mb-8 flex items-center gap-3 overflow-x-auto snap-x scrollbar-none pb-2 select-none" role="tablist"
            aria-label="Filter Galeri Kegiatan">

            <!-- Tab 1: Jumat Berkah -->
            <button type="button" role="tab" id="tab-jumat_berkah" aria-controls="panel-galeri"
                :aria-selected="activeCategory === 'jumat_berkah'" @click="setCategory('jumat_berkah')"
                class="shrink-0 snap-start px-5 py-2 rounded-full font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] cursor-pointer"
                :class="activeCategory === 'jumat_berkah'
                    ?
                    'bg-[#00843D] text-white shadow-md' :
                    'bg-[#F1F3F5] text-[#495057] hover:bg-[#E9ECEF]'">
                Jumat Berkah
            </button>

            <!-- Tab 2: Donasi -->
            <button type="button" role="tab" id="tab-donasi" aria-controls="panel-galeri"
                :aria-selected="activeCategory === 'donasi'" @click="setCategory('donasi')"
                class="shrink-0 snap-start px-5 py-2 rounded-full font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] cursor-pointer"
                :class="activeCategory === 'donasi'
                    ?
                    'bg-[#00843D] text-white shadow-md' :
                    'bg-[#F1F3F5] text-[#495057] hover:bg-[#E9ECEF]'">
                Donasi
            </button>

            <!-- Tab 3: Dzikir -->
            <button type="button" role="tab" id="tab-dzikir" aria-controls="panel-galeri"
                :aria-selected="activeCategory === 'dzikir'" @click="setCategory('dzikir')"
                class="shrink-0 snap-start px-5 py-2 rounded-full font-medium text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] cursor-pointer"
                :class="activeCategory === 'dzikir'
                    ?
                    'bg-[#00843D] text-white shadow-md' :
                    'bg-[#F1F3F5] text-[#495057] hover:bg-[#E9ECEF]'">
                Dzikir
            </button>
        </div>

        <!-- BENTO GRID CONTAINING PHOTOS -->
        <div id="panel-galeri" role="tabpanel" aria-labelledby="tab-jumat_berkah" class="relative min-h-[400px]">

            <!-- Loading Spinner Indicator -->
            <div x-show="isLoading" x-transition:enter="transition opacity ease-out duration-150"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="absolute inset-0 z-20 flex items-center justify-center bg-white/70 backdrop-blur-xs rounded-xl">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-[#00843D] animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-xs font-medium text-[#6B7280]">Memuat galeri...</span>
                </div>
            </div>

            <!-- Photos Grid Layout -->
            <div class="transition-all duration-300 transform"
                :class="isFading ? 'opacity-0 scale-98' : 'opacity-100 scale-100'">

                <template x-if="photos.length > 0">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1.35fr_1fr_1fr] lg:grid-rows-2 gap-4 lg:gap-[18px] lg:h-[710px]">
                        <template x-for="(photo, index) in photos.slice(0, 5)" :key="photo.id">
                            <div class="group relative overflow-hidden rounded-xl bg-gray-100 cursor-pointer shadow-xs border border-gray-100 focus-within:ring-2 focus-within:ring-[#00843D]"
                                :class="{
                                    'lg:row-span-2 aspect-video md:aspect-auto h-full': index === 0,
                                    'aspect-[4/3] lg:aspect-auto h-full': index > 0
                                }"
                                @click="openLightbox(index)">

                                <!-- Foto Image -->
                                <img :src="photo.image_url" :alt="photo.title" loading="lazy"
                                    class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105" />

                                <!-- Hover Overlay Hijau Tipis + Icon Expand (Tanpa Teks) -->
                                <div
                                    class="absolute inset-0 bg-[#00843D]/0 group-hover:bg-[#00843D]/25 transition-colors duration-300 flex items-center justify-center pointer-events-none">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white/90 shadow-lg text-[#00843D] flex items-center justify-center opacity-0 transform scale-75 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300">
                                        <svg class="w-6 h-6 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <polyline points="9 21 3 21 3 15"></polyline>
                                            <line x1="21" y1="3" x2="14" y2="10"></line>
                                            <line x1="3" y1="21" x2="10" y2="14"></line>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Empty State jika data kosong -->
                <template x-if="photos.length === 0 && !isLoading">
                    <div class="py-16 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <p class="text-sm text-gray-500 font-medium">Belum ada foto untuk kategori ini.</p>
                    </div>
                </template>

            </div>

        </div>

        <!-- TOMBOL LIHAT SEMUA GALERI -->
        <div class="mt-10 md:mt-12 text-center">
            <a href="{{ route('galeri.index') }}"
                aria-label="Lihat semua galeri kegiatan dokumentasi"
                class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-[#00843D] text-white font-semibold text-sm hover:bg-[#006B31] shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] focus-visible:ring-offset-2 group cursor-pointer">
                <span>Lihat Semua Galeri</span>
                <svg class="w-4 h-4 stroke-current stroke-2 fill-none transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

    </div>

    <!-- LIGHTBOX MODAL FULLSCREEN -->
    <template x-teleport="body">
        <div x-show="lightboxOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @keydown.window.escape="closeLightbox()"
            class="fixed inset-0 z-[200] flex items-center justify-center bg-black/95 p-4 select-none" role="dialog"
            aria-modal="true" aria-label="Tampilan foto galeri fullscreen">

            <!-- Dark Backdrop Dismiss Click -->
            <div class="absolute inset-0" @click="closeLightbox()"></div>

            <!-- Tombol Tutup (X) -->
            <button type="button" @click="closeLightbox()" aria-label="Tutup Tampilan Foto"
                class="absolute top-5 right-5 z-30 p-3 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] cursor-pointer">
                <svg class="w-6 h-6 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <!-- Navigation Left Arrow -->
            <button type="button" x-show="photos.length > 1" @click.stop="prevPhoto()" aria-label="Foto Sebelumnya"
                class="absolute left-4 md:left-8 z-30 p-3 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] cursor-pointer">
                <svg class="w-6 h-6 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <!-- Main Image Container in Lightbox -->
            <div class="relative z-10 max-w-5xl max-h-[85vh] flex items-center justify-center pointer-events-none">
                <template x-if="currentPhoto">
                    <img :src="currentPhoto.image_url" :alt="currentPhoto.title"
                        class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl transition-all duration-300 pointer-events-auto" />
                </template>
            </div>

            <!-- Navigation Right Arrow -->
            <button type="button" x-show="photos.length > 1" @click.stop="nextPhoto()" aria-label="Foto Berikutnya"
                class="absolute right-4 md:right-8 z-30 p-3 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] cursor-pointer">
                <svg class="w-6 h-6 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

        </div>
    </template>
</section>

<!-- ALPINE.JS COMPONENT LOGIC -->
@push('scripts')
    <script>
        function galleryComponent(initialPhotos, initialCategory, initialTotal) {
            return {
                photos: initialPhotos || [],
                activeCategory: initialCategory || 'jumat_berkah',
                totalCount: initialTotal || 0,
                isLoading: false,
                isFading: false,
                lightboxOpen: false,
                lightboxIndex: 0,

                get currentPhoto() {
                    return this.photos[this.lightboxIndex] || null;
                },

                setCategory(cat) {
                    if (this.activeCategory === cat && this.photos.length > 0) return;

                    this.isFading = true;
                    this.isLoading = true;

                    fetch(`/galeri?kategori=${cat}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(res => {
                            setTimeout(() => {
                                this.activeCategory = cat;
                                this.photos = res.data || [];
                                this.totalCount = res.total || 0;
                                this.isLoading = false;
                                this.isFading = false;
                            }, 200);
                        })
                        .catch(err => {
                            console.error('Gagal mengambil data galeri:', err);
                            this.isLoading = false;
                            this.isFading = false;
                        });
                },

                openLightbox(idx) {
                    this.lightboxIndex = idx;
                    this.lightboxOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeLightbox() {
                    this.lightboxOpen = false;
                    document.body.style.overflow = '';
                },

                prevPhoto() {
                    if (this.photos.length === 0) return;
                    this.lightboxIndex = (this.lightboxIndex - 1 + this.photos.length) % this.photos.length;
                },

                nextPhoto() {
                    if (this.photos.length === 0) return;
                    this.lightboxIndex = (this.lightboxIndex + 1) % this.photos.length;
                }
            };
        }
    </script>
@endpush
