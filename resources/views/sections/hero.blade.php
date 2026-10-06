<!-- ============================================================== -->
<!-- HERO SECTION (Sama Persis dengan OT - Tanpa Background Image) -->
<!-- ============================================================== -->
<section
    id="home"
    class="relative flex min-h-screen items-center overflow-hidden bg-gradient-to-br from-[#FAFCFA] via-[#F6FAF7] to-[#EEF7F2]"
>
    <!-- ── BACKGROUND VECTOR ARTWORK (Islamic Pattern on Right & Waves on Left/Bottom) ── -->
    <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
        <svg
            class="absolute inset-0 w-full h-full"
            xmlns="http://www.w3.org/2000/svg"
            preserveAspectRatio="none"
        >
            <defs>
                <!-- ── Islamic 8-Point Star Pattern Definition ── -->
                <pattern
                    id="islamic-star-tile"
                    width="110"
                    height="110"
                    patternUnits="userSpaceOnUse"
                >
                    <g stroke="#007A4D" stroke-width="1.2" fill="none" stroke-opacity="0.45">
                        <!-- Center 8-pointed star unit -->
                        <path d="M 55 15 L 66 38 L 93 38 L 74 55 L 82 82 L 55 66 L 28 82 L 36 55 L 17 38 L 44 38 Z" />
                        <path d="M 55 25 L 62 42 L 80 42 L 66 55 L 72 73 L 55 62 L 38 73 L 44 55 L 30 42 L 48 42 Z" stroke-width="0.8" stroke-opacity="0.3" />
                        
                        <!-- Rotated intersecting geometric star lines -->
                        <rect x="28" y="28" width="54" height="54" transform="rotate(0 55 55)" stroke-width="0.9" />
                        <rect x="28" y="28" width="54" height="54" transform="rotate(45 55 55)" stroke-width="0.9" />
                        <circle cx="55" cy="55" r="38" stroke-width="0.7" stroke-dasharray="3 3" stroke-opacity="0.35" />
                        
                        <!-- Corner stars -->
                        <path d="M 0 0 L 11 18 L 28 18 L 17 28 L 22 45 L 0 33 L -22 45 L -17 28 L -28 18 L -11 18 Z" />
                        <rect x="-28" y="-28" width="56" height="56" transform="rotate(45 0 0)" stroke-width="0.8" />
                        
                        <path d="M 110 0 L 121 18 L 138 18 L 127 28 L 132 45 L 110 33 L 88 45 L 93 28 L 82 18 L 99 18 Z" />
                        <rect x="82" y="-28" width="56" height="56" transform="rotate(45 110 0)" stroke-width="0.8" />

                        <path d="M 0 110 L 11 128 L 28 128 L 17 138 L 22 155 L 0 143 L -22 155 L -17 138 L -28 128 L -11 128 Z" />
                        <rect x="-28" y="82" width="56" height="56" transform="rotate(45 0 110)" stroke-width="0.8" />

                        <path d="M 110 110 L 121 128 L 138 128 L 127 138 L 132 155 L 110 143 L 88 155 L 93 138 L 82 128 L 99 128 Z" />
                        <rect x="82" y="82" width="56" height="56" transform="rotate(45 110 110)" stroke-width="0.8" />
                    </g>
                </pattern>

                <!-- ── Halftone Dots Pattern ── -->
                <pattern
                    id="dot-halftone"
                    width="16"
                    height="16"
                    patternUnits="userSpaceOnUse"
                >
                    <circle cx="8" cy="8" r="1.5" fill="#007A4D" fill-opacity="0.22" />
                </pattern>

                <!-- ── Top-Right Radial Fade Mask ── -->
                <radialGradient id="topRightFade" cx="100%" cy="0%" r="75%" fx="100%" fy="0%">
                    <stop offset="0%" stop-color="#ffffff" stop-opacity="1" />
                    <stop offset="55%" stop-color="#ffffff" stop-opacity="0.75" />
                    <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
                </radialGradient>

                <mask id="patternMaskRight">
                    <rect x="0" y="0" width="100%" height="100%" fill="url(#topRightFade)" />
                </mask>

                <!-- ── Emerald Wave Gradients ── -->
                <linearGradient id="waveGradLeft" x1="0" y1="1" x2="1" y2="0">
                    <stop offset="0%" stop-color="#007A4D" stop-opacity="0.35" />
                    <stop offset="60%" stop-color="#10B981" stop-opacity="0.15" />
                    <stop offset="100%" stop-color="#A7F3D0" stop-opacity="0" />
                </linearGradient>

                <linearGradient id="waveGradRightBottom" x1="1" y1="1" x2="0.3" y2="0.3">
                    <stop offset="0%" stop-color="#046A42" stop-opacity="0.45" />
                    <stop offset="70%" stop-color="#059669" stop-opacity="0.15" />
                    <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0" />
                </linearGradient>
            </defs>

            <!-- Top-Right Halftone Dots Layer -->
            <rect
                x="30%"
                y="0"
                width="70%"
                height="85%"
                fill="url(#dot-halftone)"
                mask="url(#patternMaskRight)"
            />

            <!-- Top-Right Islamic Geometric Star Layer (clear of left-side text) -->
            <rect
                x="25%"
                y="0"
                width="75%"
                height="90%"
                fill="url(#islamic-star-tile)"
                mask="url(#patternMaskRight)"
            />

            <!-- Bottom-Right Organic Wave Accent -->
            <path
                d="M 750 1000 C 900 860, 1100 810, 1300 840 C 1420 858, 1470 790, 1500 730 L 1500 1000 Z"
                fill="url(#waveGradRightBottom)"
            />
            <path
                d="M 770 1000 C 920 875, 1110 825, 1310 855 C 1430 870, 1480 800, 1500 745"
                fill="none"
                stroke="#007A4D"
                stroke-width="1.5"
                stroke-opacity="0.4"
            />

            <!-- Bottom-Left Subtle Wave Layer -->
            <path
                d="M 0 1000 L 0 880 C 150 850, 300 920, 500 900 C 650 885, 750 940, 850 1000 Z"
                fill="url(#waveGradLeft)"
            />
        </svg>
    </div>

    <!-- ── HERO CONTENT (Clean readability on left, pattern visible on right) ── -->
    <div class="relative z-10 mx-auto w-full max-w-[1400px] px-6 py-40 lg:px-12">
        <div class="max-w-2xl">
            <!-- Main Headline -->
            <h1
                class="heading mb-8 text-[#111111] font-extrabold"
                style="font-size: clamp(2.25rem, 5vw, 3.5rem); line-height: 1.1;"
            >
                Bersama Masyarakat
                <br />
                Membangun Kebaikan
            </h1>

            <!-- Supporting text -->
            <p
                class="mb-10 max-w-xl text-[#4B5563] font-sans text-justify"
                style="font-size: 1.0625rem; line-height: 1.75;"
            >
                Yayasan Bakti Umat Nusantara (YABUN) hadir mewujudkan kebaikan melalui
                program sosial, pendidikan, dan keagamaan. Bersama kita membangun masa
                depan yang lebih baik bagi sesama.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap items-center gap-4">
                <a
                    href="#profile"
                    class="bg-[#007A4D] px-8 py-3.5 text-sm font-semibold text-white transition-all hover:bg-[#046A42] shadow-md hover:shadow-lg rounded-sm"
                >
                    Profil Yabun
                </a>
                <a
                    href="https://wa.me/6281234567890?text=Assalamu%27alaikum%20YABUN%20Cabang%20Pontianak"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="border border-[#D6D2C8] bg-white px-8 py-3.5 text-sm font-semibold text-[#111] transition-all hover:border-[#007A4D] hover:text-[#007A4D] rounded-sm shadow-sm"
                >
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>
