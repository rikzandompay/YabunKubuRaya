@props([
    'summary' => null,
])

@php
    // Fallback jika data summary belum di-inject dari controller
    if (! $summary) {
        $summary = \App\Services\FinanceSummaryService::totalsByCategory();
    }

    $categories = $summary['categories'] ?? [
        'jumat_berkah' => [
            'label' => 'JUMAT BERKAH',
            'amount' => 0,
            'formatted' => 'Rp 0',
            'unit' => 'Rupiah',
            'description' => 'Total dana yang terkumpul dari program Jumat Berkah.',
        ],
        'donasi_bantuan' => [
            'label' => 'DONASI BANTUAN',
            'amount' => 0,
            'formatted' => 'Rp 0',
            'unit' => 'Rupiah',
            'description' => 'Total dana donasi bantuan yang diterima dan disalurkan.',
        ],
        'pembangunan_pondok_tahfidz' => [
            'label' => 'DONASI PEMBANGUNAN PONDOK TAHFIDZ',
            'amount' => 0,
            'formatted' => 'Rp 0',
            'unit' => 'Rupiah',
            'description' => 'Total dana yang terkumpul untuk pembangunan pondok tahfidz.',
        ],
    ];

    $latestFormatted = $summary['latest_date_formatted'] ?? null;
@endphp

<!-- SECTION TRANSPARANSI DONASI (SESUAI PRD & REFERENSI TIPOGRAFI ANGKA BESAR) -->
<section id="transparansi-donasi" class="bg-white py-16 lg:py-24 border-t border-[#E5E7EB] scroll-mt-20 relative" aria-labelledby="transparansi-heading">
    <div id="transparansi" class="absolute -top-20"></div>
    <div class="max-w-7xl mx-auto px-6">

        <!-- Header Section Rata Kiri -->
        <div class="max-w-2xl">
            <h2 id="transparansi-heading" class="text-3xl md:text-4xl font-bold tracking-tight text-[#111111]">
                Transparansi Donasi
            </h2>
            <p class="text-base md:text-lg text-[#4B5563] mt-2 leading-relaxed">
                Setiap donasi tercatat dan dilaporkan secara terbuka.
            </p>
        </div>

        <!-- Deretan 3 Blok Statistik Sejajar (Flat, Tanpa Kotak / Border / Shadow) -->
        <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 md:gap-12 mt-12 lg:mt-16" id="transparency-stats-grid">
            @foreach ($categories as $cat)
                <div class="flex flex-col border-t border-[#E5E7EB] pt-8 md:border-t-0 md:pt-0 lg:border-l lg:border-[#E5E7EB] lg:pl-10 first:border-t-0 first:pt-0 first:border-l-0 first:pl-0">
                    
                    <!-- a. Label kecil uppercase warna hijau #00843D -->
                    <dt class="order-1 text-xs sm:text-sm font-semibold uppercase tracking-wider text-[#00843D] mb-4">
                        {{ $cat['label'] }}
                    </dt>

                    <!-- b. Angka besar font-normal, text-5xl md:text-6xl lg:text-7xl, leading-none, tabular-nums -->
                    <dd class="order-2 flex flex-col">
                        <span
                            class="transparency-stat-number text-5xl md:text-6xl lg:text-7xl font-normal text-[#111111] leading-none tabular-nums"
                            aria-label="{{ $cat['formatted'] }} {{ $cat['unit'] }}"
                            data-target="{{ (float) $cat['amount'] }}"
                            data-formatted="{{ $cat['formatted'] }}"
                        >
                            {{ $cat['formatted'] }}
                        </span>

                        <!-- c. Satuan / Keterangan nominal di bawah angka (Rupiah, Juta, Miliar) -->
                        <span class="text-3xl md:text-4xl font-normal text-[#111111] mt-3 block leading-tight">
                            {{ $cat['unit'] }}
                        </span>

                        <!-- d. Deskripsi text-base/lg warna #374151 -->
                        <p class="text-base lg:text-lg text-[#374151] leading-relaxed mt-4">
                            {{ $cat['description'] }}
                        </p>
                    </dd>

                </div>
            @endforeach
        </dl>

        <!-- Baris Terakhir Diperbarui & Tombol Pill Outline -->
        <div class="mt-12 lg:mt-16 pt-8 border-t border-[#E5E7EB] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            
            <!-- Tanggal Pembaruan -->
            <div class="flex items-center gap-2 text-sm text-[#4B5563]">
                <svg class="w-4 h-4 text-[#00843D] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>
                    @if ($latestFormatted)
                        Terakhir diperbarui: <strong class="font-medium text-[#111111]">{{ $latestFormatted }}</strong>
                    @else
                        Belum ada transaksi tercatat
                    @endif
                </span>
            </div>

            <!-- Tombol Pill Outline "Lihat Info Donasi →" -->
            <div>
                <a
                    href="#donasi"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border border-[#00843D] text-[#00843D] font-semibold text-sm hover:bg-[#00843D] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#00843D] focus:ring-offset-2 transition-all duration-200 group"
                    aria-label="Lihat informasi nomor rekening resmi donasi"
                >
                    <span>Lihat Info Donasi</span>
                    <span class="transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                </a>
            </div>

        </div>

    </div>
</section>

<!-- SCRIPT COUNT-UP DENGAN EASING EXPO & INTERSECTION OBSERVER -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const section = document.getElementById('transparansi-donasi');
        if (!section) return;

        const statElements = section.querySelectorAll('.transparency-stat-number');
        if (!statElements.length) return;

        // Cek prefers-reduced-motion
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) {
            // Langsung tampilkan angka final
            statElements.forEach(function (el) {
                const formatted = el.getAttribute('data-formatted') || 'Rp 0';
                el.textContent = formatted;
            });
            return;
        }

        const formatter = new Intl.NumberFormat('id-ID');

        // Fungsi Easing easeOutExpo
        function easeOutExpo(x) {
            return x === 1 ? 1 : 1 - Math.pow(2, -10 * x);
        }

        function animateCountUp() {
            statElements.forEach(function (el) {
                const target = parseFloat(el.getAttribute('data-target')) || 0;
                const formattedFinal = el.getAttribute('data-formatted') || 'Rp 0';

                // Jika nilai target 0, tetap tampilkan "Rp 0" tanpa animasi
                if (target <= 0) {
                    el.textContent = 'Rp 0';
                    return;
                }

                const duration = 1800; // 1.8 detik
                let startTime = null;

                function step(currentTime) {
                    if (!startTime) startTime = currentTime;
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const easedProgress = easeOutExpo(progress);
                    const currentVal = Math.floor(easedProgress * target);

                    el.textContent = 'Rp ' + formatter.format(currentVal);

                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = formattedFinal;
                    }
                }

                requestAnimationFrame(step);
            });
        }

        // Jalankan animasi saat section masuk viewport
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        animateCountUp();
                        obs.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.25,
                rootMargin: '0px 0px -50px 0px'
            });

            observer.observe(section);
        } else {
            animateCountUp();
        }
    });
</script>
