<!-- ============================================================== -->
<!-- PROGRAM UNGGULAN SECTION (Pembangunan Rumah Tahfidz)          -->
<!-- Diambil dari OT / VisualBreak.tsx                              -->
<!-- ============================================================== -->
<section id="program-unggulan" class="bg-white py-16 md:py-20 border-t border-[#E5E5E5]">
    <div class="mx-auto max-w-[1400px] px-6 lg:px-12">
        <!-- === Program Pembangunan Highlight === -->
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            
            <!-- Left: Text -->
            <div data-reveal="left" data-reveal-duration="600">
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-[#999999]">
                    Program Unggulan
                </p>
                <h2 class="heading mb-5 text-[#111111] underline decoration-[#10B981] underline-offset-4" style="font-size: clamp(1.5rem, 2.5vw, 2.25rem);">
                    Pembangunan Rumah Tahfidz
                </h2>
                <div class="space-y-3 text-[#555555] text-justify" style="font-size: 0.9375rem; lineHeight: 1.75;">
                    <p>
                        {{ !empty($siteSettings['program_unggulan']) ? $siteSettings['program_unggulan'] : (!empty($siteSettings['misi']) ? $siteSettings['misi'] : 'Di kota Pontianak dan sekitarnya, terbitlah cahaya harapan dalam bentuk pembangunan dan pembinaan Rumah Tahfidz serta santunan sosial. Proyek mulia ini menjadi bukti kebersamaan dan tekad kuat masyarakat untuk memberikan pendidikan agama yang berkualitas kepada generasi penerus.') }}
                    </p>
                </div>
            </div>

            <!-- Right: Promo Card (Browser Mockup) -->
            <div data-reveal="right" data-reveal-duration="600" data-reveal-delay="200">
                <div class="relative overflow-hidden border border-[#E5E5E5] bg-[#F8F8F8] rounded-[8px]">
                    <!-- Browser chrome header -->
                    <div class="flex items-center gap-2 border-b border-[#E5E5E5] bg-white px-4 py-3">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#E5E5E5]"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-[#E5E5E5]"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-[#E5E5E5]"></span>
                    </div>
                    <!-- Image area -->
                    <div class="relative p-4">
                        <div class="aspect-[16/9] w-full overflow-hidden bg-[#EEEEEE] rounded-[4px]">
                            <img
                                src="{{ asset('images/PembangunanRmgtahfidz.webp') }}"
                                alt="Pembangunan Rumah Tahfidz Ulul Albab"
                                class="w-full h-full object-contain opacity-100 bg-white"
                                onerror="this.onerror=null; this.src='{{ asset('images/PembangunanRmgtahfidz.jpeg') }}';"
                                loading="lazy"
                            />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
