<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Yayasan Bakti Umat Nusantara (YABUN) Kubu Raya & Pontianak — Lembaga Sosial & Yatim Dhuafa')</title>
    <meta name="description" content="@yield('meta_description', 'Website Resmi Yayasan Bakti Umat Nusantara (YABUN) Cabang Kubu Raya & Pontianak, Kalimantan Barat. Menghadirkan program sosial, santunan yatim dhuafa, dan pendidikan tahfidz Al-Qur\'an di Kubu Raya dan Pontianak.')">
    <meta name="keywords" content="Yayasan di kubu raya, yayasan di pontianak, yayasan bakti umat nusantara, yabun kubu raya, yabun pontianak, lembaga sosial kubu raya, donasi pontianak, rumah tahfidz kubu raya, santunan anak yatim pontianak">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Yayasan Bakti Umat Nusantara Cabang Kubu Raya">
    <meta property="og:locale" content="id_ID">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', View::yieldContent('title', 'Yayasan Bakti Umat Nusantara (YABUN) Kubu Raya & Pontianak — Lembaga Sosial & Yatim Dhuafa'))">
    <meta property="og:description" content="@yield('og_description', View::yieldContent('meta_description', 'Website Resmi Yayasan Bakti Umat Nusantara (YABUN) Cabang Kubu Raya & Pontianak, Kalimantan Barat. Menghadirkan program sosial, santunan yatim dhuafa, dan pendidikan tahfidz Al-Qur\'an di Kubu Raya dan Pontianak.'))">
    <meta property="og:image" content="@yield('og_image', asset('favicon-512x512.png'))">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:url" content="@yield('canonical_url', url()->current())">
    <meta name="twitter:title" content="@yield('og_title', View::yieldContent('title', 'Yayasan Bakti Umat Nusantara (YABUN) Kubu Raya & Pontianak — Lembaga Sosial & Yatim Dhuafa'))">
    <meta name="twitter:description" content="@yield('og_description', View::yieldContent('meta_description', 'Website Resmi Yayasan Bakti Umat Nusantara (YABUN) Cabang Kubu Raya & Pontianak, Kalimantan Barat. Menghadirkan program sosial, santunan yatim dhuafa, dan pendidikan tahfidz Al-Qur\'an di Kubu Raya dan Pontianak.'))">
    <meta name="twitter:image" content="@yield('og_image', asset('favicon-512x512.png'))">

    <!-- Favicon & Touch Icons (Google Search Compliant: Multiples of 48px square) -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans (Non-blocking with print-swap fallback) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" rel="stylesheet">
    </noscript>

    <!-- Structured Data (JSON-LD Organization & LocalBusiness for Google Search Knowledge Graph) -->
    <script type="application/ld+json">
    {!! json_encode([
        '@'.'context' => 'https://schema.org',
        '@'.'type' => ['NGO', 'NonprofitOrganization', 'LocalBusiness'],
        'name' => 'Yayasan Bakti Umat Nusantara Cabang Kubu Raya',
        'alternateName' => [
            'YABUN Kubu Raya',
            'YABUN Pontianak',
            'Yayasan Bakti Umat Nusantara Pontianak',
            'Bakti Umat Nusantara Kubu Raya',
            'Yayasan di Kubu Raya',
            'Yayasan di Pontianak'
        ],
        'url' => 'https://yabunkuburaya.org',
        'logo' => asset('favicon-512x512.png'),
        'image' => asset('images/logoyabun.jpeg'),
        'description' => 'Website Resmi Yayasan Bakti Umat Nusantara (YABUN) Cabang Kubu Raya & Pontianak. Mewujudkan kebaikan melalui program sosial, santunan yatim dhuafa, dan pendidikan tahfidz Al-Qur\'an di Kubu Raya dan Pontianak.',
        'telephone' => '+6285349836076',
        'address' => [
            '@'.'type' => 'PostalAddress',
            'streetAddress' => 'Komplek Pondok Indah Lestari, Jln. Harmoni III Blok H4 No. 2, RT. 005/RW. 011, Desa Parit Baru, Kec. Sungai Raya',
            'addressLocality' => 'Kubu Raya',
            'addressRegion' => 'Kalimantan Barat',
            'postalCode' => '78123',
            'addressCountry' => 'ID',
        ],
        'areaServed' => [
            [
                '@'.'type' => 'City',
                'name' => 'Kubu Raya',
            ],
            [
                '@'.'type' => 'City',
                'name' => 'Pontianak',
            ],
            [
                '@'.'type' => 'AdministrativeArea',
                'name' => 'Kalimantan Barat',
            ],
        ],
        'sameAs' => [
            'https://www.instagram.com/yabunpontianak/',
            'https://yayasanbaktiumatnusantara.org',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @stack('schema')

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#111827] antialiased selection:bg-[#007A4D] selection:text-white font-sans">

    <!-- Toast Notification for Copy Rekening -->
    <div id="copy-toast" class="fixed top-20 left-1/2 -translate-x-1/2 z-[120] hidden items-center gap-2 bg-[#065F46] text-white px-5 py-2.5 rounded-full shadow-xl border border-white/20 text-xs md:text-sm font-semibold pointer-events-none transition-all duration-300">
        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <span id="copy-toast-msg">Nomor rekening berhasil disalin!</span>
    </div>

    <!-- Navigation Header -->
    @include('components.navbar')

    <!-- Main Content Container -->
    <main class="pt-[68px]">
        @yield('content')
    </main>

    <!-- Footer Component -->
    <div data-reveal data-reveal-duration="600">
        @include('components.footer')
    </div>

    <!-- Global Interactive Script -->
    <script>
        function copyToClipboard(text, label) {
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.getElementById('copy-toast');
                const toastMsg = document.getElementById('copy-toast-msg');
                if (toast && toastMsg) {
                    toastMsg.textContent = label ? `Nomor ${label} (${text}) berhasil disalin!` : `Nomor rekening (${text}) berhasil disalin!`;
                    toast.classList.remove('hidden');
                    toast.classList.add('flex', 'toast-in');
                    setTimeout(() => {
                        toast.classList.remove('flex', 'toast-in');
                        toast.classList.add('hidden');
                    }, 2500);
                }
            }).catch(err => {
                console.error('Gagal menyalin:', err);
            });
        }
        // Lock zoom on mobile (Pinch-zoom & double tap disable)
        document.addEventListener('gesturestart', function (e) {
            e.preventDefault();
        });
        document.addEventListener('touchmove', function (e) {
            if (e.touches.length > 1) {
                e.preventDefault();
            }
        }, { passive: false });
    </script>
    @stack('scripts')
</body>
</html>
