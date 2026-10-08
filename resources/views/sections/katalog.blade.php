@php
    $defaultKatalogItems = [
        [
            'id' => 0,
            'title' => 'Jumat Berkah',
            'desc' => 'Berbagi makanan dan kebaikan setiap Jumat untuk masyarakat yang membutuhkan.',
            'image' => asset('images/santri-yabun.webp'),
            'image_fallback' => asset('images/santri-yabun.jpeg'),
            'link' => '#donasi',
        ],
        [
            'id' => 1,
            'title' => 'Tahsin & Tahfiz',
            'desc' => 'Akses pembelajaran Al-Qur\'an, perbaikan bacaan, serta pembinaan hafalan santri secara gratis.',
            'image' => asset('images/PembangunanRmgtahfidz.webp'),
            'image_fallback' => asset('images/PembangunanRmgtahfidz.jpeg'),
            'link' => '#donasi',
        ],
        [
            'id' => 2,
            'title' => 'Santunan Yatim',
            'desc' => 'Santunan rutin dan pendampingan kasih sayang untuk masa depan anak-anak yatim dhuafa.',
            'image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=1200',
            'image_fallback' =>
                'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&q=80&w=1200',
            'link' => '#donasi',
        ],
        [
            'id' => 3,
            'title' => 'Zakat Maal & Fitrah',
            'desc' => 'Layanan penerimaan dan penyaluran zakat secara amanah, transparan, dan tepat sasaran.',
            'image' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?auto=format&fit=crop&q=80&w=1200',
            'image_fallback' =>
                'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&q=80&w=1200',
            'link' => '#donasi',
        ],
        [
            'id' => 4,
            'title' => 'Tebar Kurban',
            'desc' => 'Fasilitas ibadah kurban dengan distribusi menyasar warga pelosok dan pedalaman Kalbar.',
            'image' => 'https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?auto=format&fit=crop&q=80&w=1200',
            'image_fallback' => asset('images/visi-misi.webp'),
            'link' => '#donasi',
        ],
    ];

    $katalogItems = (!empty($katalogItems) && is_array($katalogItems)) ? $katalogItems : $defaultKatalogItems;
@endphp

<style>
    :root {
        --color-primary: #00843D;
        --color-primary-dark: #006B31;
        --color-accent: #E60000;
        --color-bg: #FFFFFF;
        --color-text: #111111;
        --font: "Plus Jakarta Sans", "Manrope", sans-serif;
    }

    .katalog-section {
        font-family: var(--font);
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    /* Common Viewport & Track */
    .katalog-carousel-viewport {
        width: 100%;
        overflow: hidden;
        position: relative;
        padding: 20px 0 25px 0;
    }

    .katalog-carousel-track {
        display: flex;
        gap: 24px;
        transition: transform 0.45s cubic-bezier(0.25, 1, 0.5, 1);
        will-change: transform;
    }

    /* Cards Base Styling */
    .katalog-card {
        background-color: #FFFFFF;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        text-decoration: none;
        color: var(--color-text);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transition: background-color 0.35s ease, color 0.35s ease, transform 0.3s ease, box-shadow 0.35s ease;
        outline: none;
        box-sizing: border-box;
        cursor: pointer;
        user-select: none;
    }

    .katalog-card:focus-visible {
        outline: 3px solid var(--color-primary);
        outline-offset: 4px;
    }

    .katalog-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.12);
    }

    .katalog-card:hover .katalog-arrow-icon {
        transform: translate(3px, -3px);
    }

    .katalog-card-header {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .katalog-card-title {
        font-size: 26px;
        font-weight: 700;
        line-height: 1.22;
        margin: 0;
        color: inherit;
        transition: color 0.35s ease;
    }

    .katalog-accent-bar {
        width: 38px;
        height: 4px;
        border-radius: 2px;
        background-color: var(--color-accent);
        transition: background-color 0.35s ease;
    }

    .katalog-card-desc {
        font-size: 15px;
        line-height: 1.6;
        margin: 0;
        color: #4B5563;
        transition: color 0.35s ease, opacity 0.35s ease;
    }

    .katalog-card-footer {
        display: flex;
        justify-content: flex-end;
        align-items: flex-end;
        margin-top: auto;
    }

    .katalog-arrow-icon {
        width: 30px;
        height: 30px;
        stroke: currentColor;
        stroke-width: 2.4;
        fill: none;
        transition: transform 0.3s ease, stroke 0.35s ease;
    }

    /* ACTIVE CARD STATE (Hijau Sesuai Mockup) */
    .katalog-card.active {
        background-color: var(--color-primary) !important;
        color: #FFFFFF !important;
        box-shadow: 0 16px 36px -4px rgba(0, 132, 61, 0.4) !important;
    }

    .katalog-card.active .katalog-accent-bar {
        background-color: #FFFFFF !important;
    }

    .katalog-card.active .katalog-card-title,
    .katalog-card.active .katalog-card-desc,
    .katalog-card.active .katalog-arrow-icon {
        color: #FFFFFF !important;
        stroke: #FFFFFF !important;
        opacity: 1 !important;
    }

    /* Dynamic Photo Images (Stacked with smooth cross-fade) */
    .katalog-photo-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: opacity 0.6s cubic-bezier(0.25, 1, 0.5, 1), transform 0.75s cubic-bezier(0.25, 1, 0.5, 1);
        will-change: opacity, transform;
    }

    /* NAVIGATION BUTTONS (Sesuai Mockup Referensi: [←] [→]) */
    .katalog-nav-btn {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        border: 1px solid #E5E7EB;
        background-color: #FFFFFF;
        color: #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: border-color 0.25s ease, color 0.25s ease, background-color 0.25s ease, transform 0.15s ease;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .katalog-nav-btn:hover {
        border-color: var(--color-primary);
        color: var(--color-primary);
        background-color: #F0FDF4;
    }

    .katalog-nav-btn:active {
        transform: scale(0.92);
    }

    .katalog-nav-btn:focus-visible {
        outline: 3px solid var(--color-primary);
        outline-offset: 3px;
    }

    .katalog-nav-btn svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        stroke-width: 2.2;
        fill: none;
    }

    /* ========================================================= */
    /* DESKTOP STYLES (min-width: 1024px) (Persis Gambar Mockup)  */
    /* ========================================================= */
    @media (min-width: 1024px) {
        .katalog-section {
            background-color: #FFFFFF;
            padding: 56px 0 72px 0;
            border-bottom: 1px solid #E5E7EB;
        }

        /* Header Teks di Sebelah Kiri */
        .katalog-header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 48px 36px 48px;
            text-align: left;
        }

        .katalog-header-tag {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--color-primary);
            margin-bottom: 8px;
            display: inline-block;
        }

        .katalog-header-title {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.2;
            color: #111827;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .katalog-header-desc {
            font-size: 16px;
            color: #4B5563;
            margin-top: 8px;
            max-width: 640px;
            line-height: 1.6;
        }

        .katalog-header-line {
            width: 48px;
            height: 3px;
            background-color: var(--color-primary);
            border-radius: 2px;
            margin-top: 14px;
        }

        .katalog-container {
            width: 100%;
            height: 580px;
            position: relative;
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        /* Foto Dokumentasi di Sisi Kiri (38% lebar) */
        .katalog-photo-col {
            width: 38%;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100%;
            z-index: 1;
            background-color: #0D423C;
            overflow: hidden;
        }

        .katalog-photo-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.65) 0%, rgba(0, 0, 0, 0.15) 50%, transparent 100%);
            pointer-events: none;
            z-index: 15;
        }

        .katalog-photo-badge {
            position: absolute;
            bottom: 28px;
            left: 28px;
            right: 28px;
            color: #FFFFFF;
            pointer-events: none;
            z-index: 20;
        }

        .katalog-badge-tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            background-color: var(--color-primary);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 8px;
            color: #FFFFFF;
        }

        .katalog-badge-title {
            font-size: 19px;
            font-weight: 700;
            line-height: 1.3;
            margin: 0;
            color: #FFFFFF;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            display: block;
        }

        /* Kolom Kartu Menumpuk (Overlap) Separuh ke Atas Foto */
        .katalog-content-col {
            width: 100%;
            height: 100%;
            padding: 25px 0 25px calc(38% - 155px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            box-sizing: border-box;
            z-index: 10;
        }

        .katalog-card {
            flex: 0 0 310px;
            width: 310px;
            height: 405px;
            padding: 42px 34px;
        }

        .katalog-nav-wrapper {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 20px;
            z-index: 10;
            padding-right: 6%;
        }
    }

    /* ========================================================= */
    /* MOBILE & TABLET LAYOUT (< 1024px) (Sesuai Mockup Gambar HP)*/
    /* ========================================================= */
    @media (max-width: 1023px) {
        .katalog-section {
            background-color: #FFFFFF;
            padding: 24px 0 36px 0;
            border-bottom: 1px solid #F3F4F6;
        }

        /* Header Teks di Sebelah Kiri pada Mobile */
        .katalog-header-container {
            width: calc(100% - 32px);
            max-width: 520px;
            margin: 0 auto 20px auto;
            padding: 0 4px;
            text-align: left;
        }

        .katalog-header-tag {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--color-primary);
            margin-bottom: 6px;
            display: inline-block;
        }

        .katalog-header-title {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.25;
            color: #111827;
            margin: 0;
        }

        .katalog-header-desc {
            font-size: 14px;
            color: #6B7280;
            margin-top: 6px;
            line-height: 1.5;
        }

        .katalog-header-line {
            width: 40px;
            height: 3px;
            background-color: var(--color-primary);
            border-radius: 2px;
            margin-top: 10px;
        }

        .katalog-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* 1. Foto Header Bersudut Membulat (Sesuai Mockup Gambar HP) */
        .katalog-photo-col {
            width: calc(100% - 32px);
            max-width: 520px;
            height: 380px;
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            background-color: #0D423C;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
            margin: 0 auto;
        }

        .katalog-photo-overlay {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 0%, transparent 40%, rgba(0, 0, 0, 0.55) 100%);
        }

        /* Badge KATALOG PROGRAM di Pojok Kiri Atas Foto */
        .katalog-photo-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            bottom: auto;
            right: auto;
            z-index: 20;
        }

        .katalog-badge-tag {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 6px;
            background-color: var(--color-primary);
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            box-shadow: 0 2px 8px rgba(0, 132, 61, 0.4);
            margin: 0;
        }

        .katalog-badge-title {
            display: none;
        }

        /* 2. Kartu Carousel Menumpuk (Overlap) Setengah Bagian Bawah Foto */
        .katalog-content-col {
            width: 100%;
            margin-top: -180px;
            padding: 0;
            position: relative;
            z-index: 25;
            display: flex;
            flex-direction: column;
        }

        .katalog-carousel-viewport {
            width: 100%;
            overflow: hidden;
            padding: 12px 0 20px 0;
        }

        /* Kartu di Mobile */
        .katalog-card {
            flex: 0 0 260px;
            width: 260px;
            height: 360px;
            padding: 30px 24px;
            border-radius: 18px;
        }

        .katalog-card-title {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.18;
        }

        .katalog-accent-bar {
            width: 36px;
            height: 4px;
            border-radius: 2px;
        }

        .katalog-card-desc {
            font-size: 14.5px;
            line-height: 1.55;
        }

        .katalog-arrow-icon {
            width: 28px;
            height: 28px;
        }

        /* Tombol Navigasi Prev/Next di Bawah Kanan */
        .katalog-nav-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
            padding-right: 24px;
            gap: 10px;
            z-index: 10;
        }

        .katalog-nav-btn {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }
    }

    @media (max-width: 380px) {
        .katalog-photo-col {
            width: calc(100% - 24px);
            height: 340px;
            border-radius: 18px;
        }

        .katalog-content-col {
            margin-top: -160px;
        }

        .katalog-card {
            flex: 0 0 240px;
            width: 240px;
            height: 340px;
            padding: 26px 20px;
        }

        .katalog-card-title {
            font-size: 23px;
        }

        .katalog-card-desc {
            font-size: 13.5px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .katalog-card,
        .katalog-carousel-track,
        .katalog-nav-btn,
        .katalog-photo-img {
            transition: none !important;
        }
    }
</style>

<!-- SECTION KATALOG PROGRAM YABUN CABANG KUBU RAYA -->
<section id="katalog" class="katalog-section" aria-label="Katalog Program Yayasan Bakti Umat Nusantara">

    <!-- HEADER TEKS DI SEBELAH KIRI (Sesuai Permintaan User) -->
    <div class="katalog-header-container">
        <p class="katalog-header-tag">Katalog Program</p>
        <h2 class="katalog-header-title">Program Kebaikan & Dakwah</h2>
        <p class="katalog-header-desc">
        </p>
        <div class="katalog-header-line" aria-hidden="true"></div>
    </div>

    <!-- SHOWCASE CONTAINER: FOTO DI KIRI & KARTU OVERLAP DI KANAN -->
    <div class="katalog-container">

        <!-- Foto Dokumentasi Program: Berganti Otomatis Sesuai Kartu Aktif -->
        <div class="katalog-photo-col" id="katalogPhotoContainer">
            @foreach ($katalogItems as $idx => $item)
                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}"
                    width="500" height="600"
                    class="katalog-photo-img {{ $idx === 0 ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-105 pointer-events-none z-0' }}"
                    data-photo-id="{{ $item['id'] }}"
                    onerror="this.onerror=null; this.src='{{ $item['image_fallback'] }}';"
                    loading="{{ $idx === 0 ? 'eager' : 'lazy' }}" decoding="async" />
            @endforeach
            <div class="katalog-photo-overlay"></div>
            <div class="katalog-photo-badge">
                <span class="katalog-badge-tag">Katalog Program</span> <br>
                <h3 class="katalog-badge-title">Yayasan Bakti Umat Nusantara</h3>
            </div>
        </div>

        <!-- Konten Kartu Carousel & Tombol Navigasi -->
        <div class="katalog-content-col">

            <div class="katalog-carousel-viewport" id="katalogViewport">
                <div class="katalog-carousel-track" id="katalogCarouselTrack">

                    {{-- Render 3 Sets untuk Seamless Infinite Loop Carousel --}}
                    @for ($set = 0; $set < 3; $set++)
                        @foreach ($katalogItems as $item)
                            <a href="{{ $item['link'] }}" class="katalog-card" data-original-id="{{ $item['id'] }}"
                                aria-label="Program {{ $item['title'] }}">
                                <div class="katalog-card-header">
                                    <h3 class="katalog-card-title">{{ $item['title'] }}</h3>
                                    <div class="katalog-accent-bar"></div>
                                </div>
                                <p class="katalog-card-desc">
                                    {{ $item['desc'] }}
                                </p>
                                <div class="katalog-card-footer">
                                    <svg class="katalog-arrow-icon" viewBox="0 0 24 24">
                                        <line x1="7" y1="17" x2="17" y2="7"></line>
                                        <polyline points="7 7 17 7 17 17"></polyline>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    @endfor

                </div>
            </div>

            <!-- Tombol Navigasi Prev / Next (Sesuai Mockup Referensi: [←] [→]) -->
            <div class="katalog-nav-wrapper">
                <button type="button" class="katalog-nav-btn" id="katalogPrevBtn" aria-label="Kartu sebelumnya">
                    <svg viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                </button>
                <button type="button" class="katalog-nav-btn" id="katalogNextBtn" aria-label="Kartu berikutnya">
                    <svg viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </div>

        </div>

    </div>
</section>

<!-- JAVASCRIPT CAROUSEL LOGIC: RESPONSIF DENGAN GANTI FOTO DINAMIS & TOUCH SWIPE -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('katalogCarouselTrack');
        const prevBtn = document.getElementById('katalogPrevBtn');
        const nextBtn = document.getElementById('katalogNextBtn');
        if (!track) return;

        const cards = track.querySelectorAll('.katalog-card');
        const totalOriginal = {{ count($katalogItems) }};
        let currentGlobalIndex = totalOriginal; // Mulai dari set ke-2 (kartu pertama: Jumat Berkah)
        let isTransitioning = false;

        function getCardStride() {
            if (!cards[0]) return 284;
            const cardWidth = cards[0].offsetWidth;
            const gap = 24;
            return cardWidth + gap;
        }

        // Update kartu aktif dan ganti foto latar belakang secara sinkron
        function updateActiveCardsAndPhoto() {
            let activeOriginalId = 0;

            cards.forEach((card, idx) => {
                if (idx === currentGlobalIndex) {
                    card.classList.add('active');
                    activeOriginalId = parseInt(card.getAttribute('data-original-id')) || 0;
                } else {
                    card.classList.remove('active');
                }
            });

            // Ganti foto secara halus (cross-fade) sesuai kartu yang aktif
            const photoImgs = document.querySelectorAll('.katalog-photo-img');
            photoImgs.forEach((img) => {
                const imgId = parseInt(img.getAttribute('data-photo-id'));
                if (imgId === activeOriginalId) {
                    img.classList.add('opacity-100', 'scale-100', 'z-10');
                    img.classList.remove('opacity-0', 'scale-105', 'pointer-events-none', 'z-0');
                } else {
                    img.classList.remove('opacity-100', 'scale-100', 'z-10');
                    img.classList.add('opacity-0', 'scale-105', 'pointer-events-none', 'z-0');
                }
            });
        }

        function moveTo(index, animate = true) {
            if (!animate) {
                track.style.transition = 'none';
            } else {
                track.style.transition = 'transform 0.45s cubic-bezier(0.25, 1, 0.5, 1)';
                isTransitioning = true;
            }

            currentGlobalIndex = index;
            updateActiveCardsAndPhoto();

            const stride = getCardStride();
            const viewport = track.parentElement;
            const viewportWidth = viewport ? viewport.offsetWidth : window.innerWidth;
            const cardWidth = cards[0] ? cards[0].offsetWidth : 260;

            let shift;
            // Di Mobile / Tablet (< 1024px), posisikan kartu aktif tepat di tengah agar sisi kiri & kanan mengintip (peek)
            if (window.innerWidth < 1024) {
                const centerOffset = (viewportWidth - cardWidth) / 2;
                shift = (currentGlobalIndex * stride) - centerOffset;
            } else {
                shift = currentGlobalIndex * stride;
            }

            track.style.transform = `translateX(-${shift}px)`;
        }

        // Loop tak terhingga (Infinite Reset) saat transisi selesai
        track.addEventListener('transitionend', function() {
            isTransitioning = false;

            // Jika bergeser ke set 3, loncat kembali ke set 2 tanpa animasi
            if (currentGlobalIndex >= totalOriginal * 2) {
                const normalizedIndex = currentGlobalIndex - totalOriginal;
                moveTo(normalizedIndex, false);
            }
            // Jika mundur ke set 1, loncat ke set 2 tanpa animasi
            else if (currentGlobalIndex < totalOriginal) {
                const normalizedIndex = currentGlobalIndex + totalOriginal;
                moveTo(normalizedIndex, false);
            }
        });

        // Tombol Next & Prev
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                if (isTransitioning) return;
                moveTo(currentGlobalIndex + 1, true);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (isTransitioning) return;
                moveTo(currentGlobalIndex - 1, true);
            });
        }

        // Klik pada kartu manapun untuk memilih kartu dan menjadikannya hijau
        cards.forEach((card, idx) => {
            card.addEventListener('click', function(e) {
                if (idx !== currentGlobalIndex) {
                    e.preventDefault();
                    if (isTransitioning) return;
                    moveTo(idx, true);
                }
            });
        });

        // Dukungan Touch Gesture (Swipe Kiri & Kanan di HP)
        let touchStartX = 0;
        let touchDiffX = 0;
        let isTouching = false;

        track.addEventListener('touchstart', function(e) {
            if (isTransitioning) return;
            isTouching = true;
            touchStartX = e.touches[0].clientX;
            touchDiffX = 0;
        }, {
            passive: true
        });

        track.addEventListener('touchmove', function(e) {
            if (!isTouching) return;
            touchDiffX = e.touches[0].clientX - touchStartX;
        }, {
            passive: true
        });

        track.addEventListener('touchend', function() {
            if (!isTouching) return;
            isTouching = false;
            const threshold = 40; // minimum geser 40px
            if (touchDiffX < -threshold) {
                if (!isTransitioning) moveTo(currentGlobalIndex + 1, true);
            } else if (touchDiffX > threshold) {
                if (!isTransitioning) moveTo(currentGlobalIndex - 1, true);
            }
        });

        // Responsif saat rotasi HP atau Resize Window
        window.addEventListener('resize', function() {
            moveTo(currentGlobalIndex, false);
        });

        // Inisialisasi awal
        moveTo(currentGlobalIndex, false);
    });
</script>
