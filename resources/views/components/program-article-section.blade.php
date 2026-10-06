@props(['categories' => []])

<!-- SECTION PROGRAM ARTIKEL -->
<section id="program-artikel" class="bg-[#F8F9FB] py-16 lg:py-24 border-b border-gray-100 select-none">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Header Section: Judul & Subjudul -->
        <div class="mb-12">
            <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-[#111111]">
                Program Artikel
            </h2>
            <p class="mt-2 text-base lg:text-lg text-[#4B5563] font-normal">
                Berita dan cerita kegiatan Yayasan BUM Pontianak
            </p>
        </div>

        <!-- Grid 3 Kartu Flat -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 lg:gap-12" id="articleCategoryGrid">
            @forelse($categories as $index => $cat)
                <div class="article-card-item group flex flex-col h-full opacity-0 translate-y-4 transition-all duration-700 ease-out"
                     style="transition-delay: {{ $index * 100 }}ms;">
                    
                    <!-- Link Pembungkus Utama -->
                    <a href="{{ route('artikel.index', ['kategori' => $cat->slug]) }}" 
                       class="flex flex-col h-full rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] focus-visible:ring-offset-2">
                        
                        <!-- a. Gambar Header (16:9, rounded-xl 12px, zoom hover) -->
                        <div class="relative w-full aspect-[16/9] lg:h-[250px] overflow-hidden rounded-xl bg-gray-200">
                            <img src="{{ $cat->cover_image_url }}" 
                                 alt="Gambar Kategori {{ $cat->name }}"
                                 width="450"
                                 height="250"
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105" />
                        </div>

                        <!-- b. Judul Kategori (text-2xl, font-semibold, #111111) -->
                        <h3 class="text-2xl font-semibold text-[#111111] mt-5 group-hover:text-[#00843D] transition-colors duration-300">
                            {{ $cat->name }}
                        </h3>

                        <!-- c. Deskripsi Singkat (line-clamp-4) -->
                        <p class="text-base lg:text-lg text-[#4B5563] leading-[1.6] line-clamp-4 mt-3 mb-6 font-normal">
                            {{ $cat->description }}
                        </p>

                        <!-- d. Tombol "Selengkapnya" + Ikon Panah (Position: mt-auto) -->
                        <div class="mt-auto pt-2">
                            <div class="group/btn inline-flex items-center justify-center gap-3 w-full sm:w-auto px-7 py-4 rounded-full border border-[#374151] bg-transparent text-[#111111] font-medium text-sm lg:text-base hover:bg-[#00843D] hover:text-white hover:border-[#00843D] transition-all duration-300">
                                <span>Selengkapnya</span>
                                <svg class="w-5 h-5 stroke-current stroke-2 fill-none transform group-hover/btn:translate-x-1 transition-transform duration-300" viewBox="0 0 24 24">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                        </div>

                    </a>
                </div>
            @empty
                <!-- Fallback Static Cards jika database kosong -->
                <div class="article-card-item group flex flex-col h-full opacity-0 translate-y-4 transition-all duration-700 ease-out">
                    <a href="{{ route('artikel.index', ['kategori' => 'keagamaan']) }}" class="flex flex-col h-full rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] focus-visible:ring-offset-2">
                        <div class="relative w-full aspect-[16/9] lg:h-[250px] overflow-hidden rounded-xl bg-gray-200">
                            <img src="{{ asset('images/program/keagamaan.jpeg') }}" 
                                 alt="Gambar Kategori Keagamaan" width="450" height="250" loading="lazy" 
                                 class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105" />
                        </div>
                        <h3 class="text-2xl font-semibold text-[#111111] mt-5 group-hover:text-[#00843D] transition-colors duration-300">Keagamaan</h3>
                        <p class="text-base lg:text-lg text-[#4B5563] leading-[1.6] line-clamp-4 mt-3 mb-6">
                            Kumpulan artikel kegiatan keagamaan seperti Jumat Berkah, Tahsin & Tahfiz, dan dzikir bersama yang kami selenggarakan.
                        </p>
                        <div class="mt-auto pt-2">
                            <div class="group/btn inline-flex items-center justify-center gap-3 w-full sm:w-auto px-7 py-4 rounded-full border border-[#374151] bg-transparent text-[#111111] font-medium text-sm lg:text-base hover:bg-[#00843D] hover:text-white hover:border-[#00843D] transition-all duration-300">
                                <span>Selengkapnya</span>
                                <svg class="w-5 h-5 stroke-current stroke-2 fill-none transform group-hover/btn:translate-x-1 transition-transform duration-300" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="article-card-item group flex flex-col h-full opacity-0 translate-y-4 transition-all duration-700 ease-out" style="transition-delay: 100ms;">
                    <a href="{{ route('artikel.index', ['kategori' => 'pendidikan']) }}" class="flex flex-col h-full rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] focus-visible:ring-offset-2">
                        <div class="relative w-full aspect-[16/9] lg:h-[250px] overflow-hidden rounded-xl bg-gray-200">
                            <img src="{{ asset('images/program/pendidikan.jpeg') }}" 
                                 alt="Gambar Kategori Pendidikan" width="450" height="250" loading="lazy" 
                                 class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105" />
                        </div>
                        <h3 class="text-2xl font-semibold text-[#111111] mt-5 group-hover:text-[#00843D] transition-colors duration-300">Pendidikan</h3>
                        <p class="text-base lg:text-lg text-[#4B5563] leading-[1.6] line-clamp-4 mt-3 mb-6">
                            Ikuti kisah dan kabar program pendidikan yayasan, mulai dari pembinaan Al-Qur'an hingga dukungan belajar bagi generasi penerus.
                        </p>
                        <div class="mt-auto pt-2">
                            <div class="group/btn inline-flex items-center justify-center gap-3 w-full sm:w-auto px-7 py-4 rounded-full border border-[#374151] bg-transparent text-[#111111] font-medium text-sm lg:text-base hover:bg-[#00843D] hover:text-white hover:border-[#00843D] transition-all duration-300">
                                <span>Selengkapnya</span>
                                <svg class="w-5 h-5 stroke-current stroke-2 fill-none transform group-hover/btn:translate-x-1 transition-transform duration-300" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="article-card-item group flex flex-col h-full opacity-0 translate-y-4 transition-all duration-700 ease-out" style="transition-delay: 200ms;">
                    <a href="{{ route('artikel.index', ['kategori' => 'sosial']) }}" class="flex flex-col h-full rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00843D] focus-visible:ring-offset-2">
                        <div class="relative w-full aspect-[16/9] lg:h-[250px] overflow-hidden rounded-xl bg-gray-200">
                            <img src="{{ asset('images/program/sosial.jpeg') }}" 
                                 alt="Gambar Kategori Sosial" width="450" height="250" loading="lazy" 
                                 class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105" />
                        </div>
                        <h3 class="text-2xl font-semibold text-[#111111] mt-5 group-hover:text-[#00843D] transition-colors duration-300">Sosial</h3>
                        <p class="text-base lg:text-lg text-[#4B5563] leading-[1.6] line-clamp-4 mt-3 mb-6">
                            Telusuri berita santunan, zakat, dan aksi peduli sesama yang menunjukkan komitmen kami terhadap kemaslahatan umat.
                        </p>
                        <div class="mt-auto pt-2">
                            <div class="group/btn inline-flex items-center justify-center gap-3 w-full sm:w-auto px-7 py-4 rounded-full border border-[#374151] bg-transparent text-[#111111] font-medium text-sm lg:text-base hover:bg-[#00843D] hover:text-white hover:border-[#00843D] transition-all duration-300">
                                <span>Selengkapnya</span>
                                <svg class="w-5 h-5 stroke-current stroke-2 fill-none transform group-hover/btn:translate-x-1 transition-transform duration-300" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </div>
                        </div>
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</section>

<!-- SCROLL ANIMATION OBSERVER -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const items = document.querySelectorAll('.article-card-item');
        if (!items.length) return;

        // Respect prefers-reduced-motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            items.forEach(el => {
                el.classList.remove('opacity-0', 'translate-y-4');
                el.classList.add('opacity-100', 'translate-y-0');
            });
            return;
        }

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('opacity-0', 'translate-y-4');
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        items.forEach(item => observer.observe(item));
    });
</script>
@endpush
