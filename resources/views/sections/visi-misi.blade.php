<!-- ============================================================== -->
<!-- VISI & MISI SECTION (Kegiatan YABUN & 4 Pilar Program)       -->
<!-- ============================================================== -->
<section id="visi-misi" class="bg-white py-20 md:py-28 border-b border-gray-100">
    <div class="mx-auto max-w-[1400px] px-6 lg:px-12">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">

            <!-- Left Column: Visual Showcase (Layout Referensi: Main Image + Floating Badge + Arch Card) -->
            <div class="relative w-full max-w-[500px] lg:max-w-[530px] mx-auto order-2 lg:order-1 select-none">
                <!-- Relative Canvas with padding for floating elements -->
                <div class="relative pt-4 pr-4 pb-8 pl-2">

                    <!-- 1. Main Background Image Card -->
                    <div
                        class="relative w-full aspect-square overflow-hidden rounded-[2.5rem] bg-[#0d423c] shadow-xl border border-gray-100/80">
                        <img src="{{ asset('images/visi-misi.webp') }}"
                            alt="Dokumentasi Visi dan Misi Yayasan Bakti Umat Nusantara"
                            class="w-full h-full object-contain" onerror="this.src='{{ asset('images/visi-misi.png') }}'"
                            loading="lazy" />
                        <!-- Subtle Bottom Overlay -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent pointer-events-none">
                        </div>
                    </div>

                    <!-- 2. Floating Top-Right Badge (Tahun Pengabdian & Komitmen) -->
                    <div
                        class="absolute top-0 right-0 sm:-top-2 sm:-right-2 z-20 flex items-center gap-3 rounded-2xl bg-[#007A4D] px-4 py-2.5 sm:px-5 sm:py-3 shadow-md border border-white/20 text-white">
                        <span
                            class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-none text-white">2</span>
                        <div class="text-xs font-semibold leading-tight text-white">
                            <p>Tahun Pengabdian</p>
                            <p class="text-white font-normal">& Komitmen Masa Depan</p>
                        </div>
                    </div>

                    <!-- 3. Overlapping Bottom-Right Arch Image Card -->
                    <div
                        class="absolute -bottom-4 -right-2 sm:-bottom-6 sm:-right-4 z-20 w-[54%] sm:w-[56%] aspect-[4/5] overflow-hidden rounded-t-[3.5rem] rounded-b-[2rem] sm:rounded-t-[4.5rem] sm:rounded-b-[2.5rem] border-4 sm:border-[6px] border-white bg-white shadow-2xl">
                        <img src="{{ asset('images/santri-yabun.webp') }}" alt="Santri Yayasan Bakti Umat Nusantara"
                            class="w-full h-full object-cover"
                            onerror="this.src='{{ asset('images/santri-yabun.jpeg') }}'" loading="lazy" />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent pointer-events-none">
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column: Visi Teks & 2x2 Grid Misi -->
            <div class="order-1 lg:order-2">
                <h2 class="mb-6 text-3xl font-extrabold tracking-tight text-[#1F2937] lg:text-4xl">
                    Visi dan Misi
                </h2>

                <!-- Teks Visi -->
                <div class="mb-10">
                    <p class="text-[15px] font-normal leading-[1.8] text-[#4B5563] text-justify">
                        <strong class="text-[#1F2937] font-bold text-base">Visi:</strong> {{ !empty($siteSettings['visi']) ? $siteSettings['visi'] : 'Menjadi yayasan yang amanah dan terpercaya dalam mewujudkan kesejahteraan umat melalui program sosial, pendidikan, dan dakwah keagamaan yang berkelanjutan di seluruh Nusantara.' }}
                    </p>
                </div>

                <!-- Grid Misi 2x2 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
                    <!-- Item 1: Sosial (inline icon-left) -->
                    <div class="flex items-start gap-3.5">
                        <div
                            class="shrink-0 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#111827] mb-0.5">Kemanusiaan</h3>
                            <p class="text-[13.5px] leading-relaxed text-[#4B5563]">
                                Menyelenggarakan program bantuan sosial dan santunan bagi masyarakat dhuafa & anak
                                yatim.
                            </p>
                        </div>
                    </div>

                    <!-- Item 2: Pendidikan (color badge) -->
                    <div class="flex items-start gap-3.5">
                        <div
                            class="shrink-0 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#111827] mb-0.5">Pendidikan Al-Qur'an</h3>
                            <p class="text-[13.5px] leading-relaxed text-[#4B5563]">
                                Meningkatkan akses pendidikan tahfidz Al-Qur'an bagi anak-anak dan generasi muda.
                            </p>
                        </div>
                    </div>

                    <!-- Item 3: Keagamaan (inline icon-left) -->
                    <div class="flex items-start gap-3.5">
                        <div
                            class="shrink-0 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#111827] mb-0.5">Dakwah Keagamaan</h3>
                            <p class="text-[13.5px] leading-relaxed text-[#4B5563]">
                                Memfasilitasi kegiatan keagamaan, kajian, dan syiar Islam yang membangun kebersamaan
                                umat.
                            </p>
                        </div>
                    </div>

                    <!-- Item 4: Tanggap Bencana (color badge) -->
                    <div class="flex items-start gap-3.5">
                        <div
                            class="shrink-0 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#111827] mb-0.5">Tanggap Bencana</h3>
                            <p class="text-[13.5px] leading-relaxed text-[#4B5563]">
                                Mendukung aksi cepat tanggap darurat bencana dan sarana fasilitas keumatan.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
