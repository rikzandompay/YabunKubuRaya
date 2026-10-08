<!-- ============================================================== -->
<!-- LEGALITAS & VERIFIKASI LEMBAGA SECTION                       -->
<!-- ============================================================== -->
<section id="legalitas" class="bg-white py-16 md:py-28 border-b border-gray-100">
    <div class="mx-auto max-w-[1400px] px-6 lg:px-12">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-12 md:mb-14">
            <h2 class="text-3xl font-extrabold tracking-tight text-[#1F2937] lg:text-4xl mb-4">
                Terverifikasi Oleh Lembaga
            </h2>
            <p class="text-[15px] text-[#4B5563] leading-relaxed max-w-lg mx-auto">
                Yayasan Bakti Umat Nusantara beroperasi dengan legalitas resmi dan bermitra aktif dengan institusi
                terpercaya.
            </p>
        </div>

        <!-- 3 Logo Cards: Segitiga di Mobile (1 Atas Tengah, 2 Bawah), Sejajar Horizontal di Desktop -->
        <div class="grid grid-cols-2 gap-y-8 gap-x-4 sm:gap-x-6 md:flex md:flex-wrap md:items-center md:justify-center md:gap-12 max-w-4xl mx-auto">

            <!-- 1. Notaris / PPAT (Puncak Segitiga di Mobile) -->
            <div class="col-span-2 flex flex-col items-center gap-2.5 sm:gap-3 text-center md:col-auto" data-reveal="up" data-reveal-delay="100">
                <div
                    class="flex h-28 w-28 sm:h-32 sm:w-32 md:h-40 md:w-40 items-center justify-center overflow-hidden rounded-2xl bg-white p-3.5 sm:p-4 border border-gray-200 hover:border-[#007A4D] transition-all shadow-xs hover:shadow-md">
                    <img src="{{ asset('images/logoppat.webp') }}" alt="Notaris / PPAT"
                        width="160" height="160"
                        class="h-full w-full object-contain" onerror="this.src='{{ asset('images/logoppat.png') }}'"
                        loading="lazy" decoding="async" />
                </div>
                <p class="text-[12px] sm:text-[13px] md:text-[14px] text-gray-700 max-w-[150px] sm:max-w-[180px] leading-snug font-semibold">
                    Notaris / PPAT
                    <span class="block text-[11px] sm:text-xs font-normal text-gray-500 mt-0.5">SK: AHU-426.AH.02.01.2008</span>
                </p>
            </div>

            <!-- 2. Dinas Sosial Kubu Raya (Kiri Bawah di Mobile) -->
            <div class="col-span-1 flex flex-col items-center gap-2.5 sm:gap-3 text-center md:col-auto" data-reveal="up" data-reveal-delay="200">
                <div
                    class="flex h-28 w-28 sm:h-32 sm:w-32 md:h-40 md:w-40 items-center justify-center overflow-hidden rounded-2xl bg-white p-3.5 sm:p-4 border border-gray-200 hover:border-[#007A4D] transition-all shadow-xs hover:shadow-md">
                    <img src="{{ asset('images/logo-kuburaya.webp') }}" alt="Dinas Sosial Kubu Raya"
                        width="160" height="160"
                        class="h-full w-full object-contain" onerror="this.src='{{ asset('images/logo-kuburaya.png') }}'"
                        loading="lazy" decoding="async" />
                </div>
                <p class="text-[12px] sm:text-[13px] md:text-[14px] text-gray-700 max-w-[150px] sm:max-w-[180px] leading-snug font-semibold">
                    Dinas Sosial Kubu Raya
                    <span class="block text-[11px] sm:text-xs font-normal text-gray-500 mt-0.5">SK: 400.9.12/420/DINSOS-B/2024</span>
                </p>
            </div>

            <!-- 3. BAZNAS Kabupaten Kubu Raya (Kanan Bawah di Mobile) -->
            <div class="col-span-1 flex flex-col items-center gap-2.5 sm:gap-3 text-center md:col-auto" data-reveal="up" data-reveal-delay="300">
                <div
                    class="flex h-28 w-28 sm:h-32 sm:w-32 md:h-40 md:w-40 items-center justify-center overflow-hidden rounded-2xl bg-white p-3.5 sm:p-4 border border-gray-200 hover:border-[#007A4D] transition-all shadow-xs hover:shadow-md">
                    <img src="{{ asset('images/logo-baznas.webp') }}" alt="BAZNAS Kabupaten Kubu Raya"
                        width="160" height="160"
                        class="h-full w-full object-contain" onerror="this.src='{{ asset('images/logo-baznas.jpeg') }}'"
                        loading="lazy" decoding="async" />
                </div>
                <p class="text-[12px] sm:text-[13px] md:text-[14px] text-gray-700 max-w-[150px] sm:max-w-[180px] leading-snug font-semibold">
                    BAZNAS Kubu Raya
                    <span class="block text-[11px] sm:text-xs font-normal text-gray-500 mt-0.5">SK: 033/BAZNAS/KKR/2025</span>
                </p>
            </div>

        </div>
    </div>
</section>
