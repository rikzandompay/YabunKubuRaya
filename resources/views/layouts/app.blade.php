<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Yayasan Bakti Umat Nusantara Cabang Pontianak — Bersama Membangun Kebaikan')</title>
    <meta name="description" content="@yield('meta_description', 'Website Resmi Yayasan Bakti Umat Nusantara (YABUN) Cabang Pontianak. Mewujudkan kebaikan melalui program sosial, pendidikan, dan keagamaan.')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Yayasan Bakti Umat Nusantara Cabang Pontianak">
    <meta property="og:locale" content="id_ID">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', View::yieldContent('title', 'Yayasan Bakti Umat Nusantara Cabang Pontianak — Bersama Membangun Kebaikan'))">
    <meta property="og:description" content="@yield('og_description', View::yieldContent('meta_description', 'Website Resmi Yayasan Bakti Umat Nusantara (YABUN) Cabang Pontianak. Mewujudkan kebaikan melalui program sosial, pendidikan, dan keagamaan.'))">
    <meta property="og:image" content="@yield('og_image', asset('images/logo-bun.webp'))">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:url" content="@yield('canonical_url', url()->current())">
    <meta name="twitter:title" content="@yield('og_title', View::yieldContent('title', 'Yayasan Bakti Umat Nusantara Cabang Pontianak — Bersama Membangun Kebaikan'))">
    <meta name="twitter:description" content="@yield('og_description', View::yieldContent('meta_description', 'Website Resmi Yayasan Bakti Umat Nusantara (YABUN) Cabang Pontianak. Mewujudkan kebaikan melalui program sosial, pendidikan, dan keagamaan.'))">
    <meta name="twitter:image" content="@yield('og_image', asset('images/logo-bun.webp'))">

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/logo-bun.webp') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Structured Data (JSON-LD Organization) -->
    <script type="application/ld+json">
    {!! json_encode([
        '@'.'context' => 'https://schema.org',
        '@'.'type' => 'NGO',
        'name' => 'Yayasan Bakti Umat Nusantara Cabang Pontianak',
        'alternateName' => ['YABUN Pontianak', 'Bakti Umat Nusantara Pontianak'],
        'url' => url('/'),
        'logo' => asset('images/logo-bun.webp'),
        'description' => 'Website Resmi Yayasan Bakti Umat Nusantara (YABUN) Cabang Pontianak. Mewujudkan kebaikan melalui program sosial, pendidikan, dan keagamaan.',
        'address' => [
            '@'.'type' => 'PostalAddress',
            'addressLocality' => 'Pontianak',
            'addressRegion' => 'Kalimantan Barat',
            'addressCountry' => 'ID',
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
