<footer id="footer" class="w-full bg-[#0B192C] text-white overflow-hidden scroll-mt-20">
    <div id="kontak"></div>

    <!-- ===== Top: Brand Logos ===== -->
    <div class="mx-auto max-w-[1400px] px-6 lg:px-12 pt-14 pb-10">
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-3 md:gap-4 max-w-3xl">
            <!-- Logo 1: BUN -->
            <a href="{{ url('/') }}"
                class="flex items-center justify-center bg-white p-2 rounded-md h-12 md:h-14 px-3 hover:opacity-95 transition-opacity">
                <img src="{{ asset('images/logo-bun.webp') }}" alt="Logo Yayasan Bakti Umat Nusantara" width="200"
                    height="50" class="h-8 md:h-10 w-auto max-w-full object-contain"
                    onerror="this.src='{{ asset('images/logo-bun.jpeg') }}'" />
            </a>
            <!-- Logo 2: YABUN -->
            <a href="{{ url('/') }}"
                class="flex items-center justify-center bg-white p-2 rounded-md h-12 md:h-14 px-3 hover:opacity-95 transition-opacity">
                <img src="{{ asset('images/logoyabun.webp') }}" alt="Logo YABUN" width="200" height="50"
                    class="h-8 md:h-10 w-auto max-w-full object-contain"
                    onerror="this.src='{{ asset('images/logoyabun.jpeg') }}'" />
            </a>
            <!-- Logo 3: BAZNAS -->
            <div class="flex items-center justify-center bg-white p-2 rounded-md h-12 md:h-14 px-3">
                <img src="{{ asset('images/logo-baznas.webp') }}" alt="Logo BAZNAS" width="200" height="50"
                    class="h-8 md:h-10 w-auto max-w-full object-contain"
                    onerror="this.src='{{ asset('images/logo-baznas.jpeg') }}'" />
            </div>
            <!-- Logo 4: Notaris / PPAT -->
            <div class="flex items-center justify-center bg-white p-2 rounded-md h-12 md:h-14 px-3">
                <img src="{{ asset('images/logoppat.webp') }}" alt="Logo Notaris PPAT" width="200"
                    height="50" class="h-8 md:h-10 w-auto max-w-full object-contain"
                    onerror="this.src='{{ asset('images/logoppat.png') }}'" />
            </div>
            <!-- Logo 5: Kabupaten Kubu Raya -->
            <div class="col-span-2 sm:col-auto flex items-center justify-center bg-white p-2 rounded-md h-12 md:h-14 px-3">
                <img src="{{ asset('images/logo-kuburaya.webp') }}" alt="Logo Kabupaten Kubu Raya" width="200"
                    height="50" class="h-8 md:h-10 w-auto max-w-full object-contain"
                    onerror="this.src='{{ asset('images/logo-kuburaya.png') }}'" />
            </div>
        </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-white/20"></div>

    <!-- ===== Main Grid: 4 Columns Sesuai Web YABUN Pontianak ===== -->
    <div class="mx-auto max-w-[1400px] px-6 lg:px-12 py-12" id="donasi-footer">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">

            <!-- Column 1: Maps & Alamat Kantor -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white/80">Lokasi Kantor</h3>
                    <a href="https://www.google.com/maps/place/Yayasan+Bakti+Umat+Nusantara+Pontianak/@-0.086,109.3749989,857m/data=!3m2!1e3!4b1!4m6!3m5!1s0x2e1d5b434d03ddeb:0x2f9e3c0e0d20ab21!8m2!3d-0.086!4d109.3775738!16s%2Fg%2F11tk07pmzm?entry=ttu&g_ep=EgoyMDI2MDkyOC4wIKXMDSoASAFQAw%3D%3D"
                        target="_blank" rel="noopener noreferrer"
                        class="text-xs font-medium text-emerald-400 hover:text-emerald-300 underline underline-offset-2 transition-colors">
                        Buka di Maps ↗
                    </a>
                </div>
                <div class="relative overflow-hidden border border-white/20 rounded-md" style="position: relative; aspect-ratio: 4/3;">
                    <iframe
                        src="https://maps.google.com/maps?q=Yayasan+Bakti+Umat+Nusantara+Pontianak&t=&z=16&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="100%" style="border: 0; position: absolute; inset: 0;"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Yayasan Bakti Umat Nusantara Pontianak"></iframe>
                </div>
                <div class="text-xs text-white/70 space-y-1">
                    <p class="font-semibold text-white text-[11px] uppercase tracking-wide">Alamat Kantor:</p>
                    <p class="leading-relaxed">{{ !empty($siteSettings['alamat']) ? $siteSettings['alamat'] : 'Komplek Pondok Indah Lestari, Jln. Harmoni III Blok H4 No.2, RT.005/RW.011, Desa Parit Baru, Kec. Sungai Raya, Kabupaten Kubu Raya, Kalimantan Barat 78123' }}</p>
                </div>
            </div>

            <!-- Column 2: Navigasi / Menu Pilihan -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white/80">Menu Pilihan</h3>
                <ul class="space-y-2 text-sm text-white/80">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-emerald-400 transition-colors">Home</a>
                    </li>
                    <li>
                        <a href="{{ url('/#profile') }}" class="hover:text-emerald-400 transition-colors">Profile
                            Yabun</a>
                    </li>
                    <li>
                        <a href="{{ url('/#visi-misi') }}" class="hover:text-emerald-400 transition-colors">Visi &
                            Misi</a>
                    </li>
                    <li>
                        <a href="{{ url('/#legalitas') }}" class="hover:text-emerald-400 transition-colors">Legalitas
                            Lembaga</a>
                    </li>
                    <li>
                        <a href="{{ url('/#katalog') }}" class="hover:text-emerald-400 transition-colors">Katalog
                            Program</a>
                    </li>
                    <li>
                        <a href="{{ url('/#dampak-nyata') }}" class="hover:text-emerald-400 transition-colors">Dampak
                            Nyata</a>
                    </li>
                    <li>
                        <a href="{{ url('/#donasi') }}" class="hover:text-emerald-400 transition-colors">Info
                            Donasi</a>
                    </li>
                    <li>
                        <a href="{{ url('/#transparansi') }}"
                            class="hover:text-emerald-400 transition-colors">Transparansi Donasi</a>
                    </li>
                    <li>
                        <a href="{{ route('artikel.index') }}" class="hover:text-emerald-400 transition-colors">Artikel
                            Program</a>
                    </li>
                    <li>
                        <a href="{{ url('/#galeri') }}" class="hover:text-emerald-400 transition-colors">Galeri
                            Kegiatan</a>
                    </li>
                    <li>
                        <a href="{{ url('/#kontak') }}" class="hover:text-emerald-400 transition-colors">Kontak
                            Kami</a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Rekening Donasi Resmi -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white/80">Rekening Donasi</h3>
                <p class="text-xs text-white/60 leading-relaxed">
                    Salurkan donasi & sedekah Anda melalui rekening resmi yayasan:
                </p>

                <div class="space-y-3">
                    @forelse($bankAccounts as $bank)
                        @php
                            $bankLower = strtolower($bank->nama_bank);
                            $logo = asset('images/logo-bun.webp');
                            if (str_contains($bankLower, 'bsi') || str_contains($bankLower, 'syariah indonesia')) {
                                $logo = asset('images/logo-bsi.webp');
                            } elseif (str_contains($bankLower, 'bri')) {
                                $logo = asset('images/logo-bri.webp');
                            } elseif (str_contains($bankLower, 'bca')) {
                                $logo = asset('images/logo-bca.webp');
                            } elseif (str_contains($bankLower, 'mandiri')) {
                                $logo = asset('images/logo-mandiri.webp');
                            }
                            $cleanNum = preg_replace('/[^0-9]/', '', $bank->nomor_rekening);
                        @endphp
                        <div class="bg-white/10 rounded-md p-3 hover:bg-white/15 transition-colors border border-white/10">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="bg-white rounded px-2 py-1 flex items-center justify-center h-8">
                                    <img src="{{ $logo }}" alt="{{ $bank->nama_bank }}" width="80"
                                        height="24" class="object-contain h-6 w-auto max-w-[65px]"
                                        onerror="this.src='{{ asset('images/logo-bun.webp') }}'" />
                                </div>
                                <span class="text-[10px] text-white/50">
                                    {{ $bank->nama_bank }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between gap-2 pt-1 border-t border-white/10">
                                <div>
                                    <p class="text-[13px] font-bold text-white tracking-wider font-mono">
                                        {{ $bank->nomor_rekening }}
                                    </p>
                                    <p class="text-[10px] text-white/60">a.n. {{ $bank->atas_nama }}</p>
                                </div>
                                <button type="button" x-data="{ copied: false }"
                                    @click="
                                        navigator.clipboard.writeText('{{ $cleanNum }}');
                                        copied = true;
                                        if (typeof copyToClipboard === 'function') copyToClipboard('{{ $cleanNum }}', '{{ $bank->nama_bank }}');
                                        setTimeout(() => copied = false, 2000);
                                    "
                                    class="inline-flex items-center gap-1 text-xs text-gray-300 hover:text-white bg-white/10 hover:bg-white/20 px-2.5 py-1 rounded font-medium cursor-pointer transition-colors"
                                    title="Salin No Rekening" aria-label="Salin nomor {{ $bank->nomor_rekening }}">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[11px]" x-text="copied ? 'Tersalin' : 'Salin'">Salin</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-white/60 py-2">Belum ada rekening aktif terdaftar.</div>
                    @endforelse
                </div>
            </div>

            <!-- Column 4: Kontak & Social Media -->
            @php
                $waRaw = !empty($siteSettings['kontak_wa']) ? preg_replace('/[^0-9]/', '', $siteSettings['kontak_wa']) : '6281930942890';
                if (str_starts_with($waRaw, '0')) {
                    $waRaw = '62' . substr($waRaw, 1);
                }
            @endphp
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white/80">Kontak Kami</h3>
                <div class="space-y-3 text-sm text-white/80">
                    <div>
                        <p class="text-[11px] text-white/50 mb-0.5">Telepon / WhatsApp</p>
                        <a href="https://wa.me/{{ $waRaw }}?text={{ urlencode('Assalamu\'alaikum Admin YABUN Pontianak') }}"
                            target="_blank" rel="noopener noreferrer"
                            class="font-semibold text-white hover:text-emerald-400 transition-colors inline-flex items-center gap-1.5">
                            {{ !empty($siteSettings['kontak_wa']) ? $siteSettings['kontak_wa'] : '+62 819-3094-2890' }}
                        </a>
                    </div>
                    <div>
                        <p class="text-[11px] text-white/50 mb-0.5">Email Resmi</p>
                        <a href="mailto:{{ !empty($siteSettings['email']) ? $siteSettings['email'] : 'ybaktiumat@gmail.com' }}"
                            class="font-semibold text-white hover:text-emerald-400 transition-colors">
                            {{ !empty($siteSettings['email']) ? $siteSettings['email'] : 'ybaktiumat@gmail.com' }}
                        </a>
                    </div>
                </div>

                <!-- Join Us — Media Sosial -->
                <div class="pt-4 border-t border-white/20">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-white/60 mb-3">
                        Media Sosial
                    </p>
                    <div class="flex items-center gap-2">
                        <!-- YouTube -->
                        <a href="https://youtube.com/@yabun" target="_blank" rel="noopener noreferrer"
                            aria-label="YouTube"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white hover:bg-emerald-600 hover:border-emerald-600 transition-all">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/share/1DRSViYsTi/" target="_blank"
                            rel="noopener noreferrer" aria-label="Facebook"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white hover:bg-emerald-600 hover:border-emerald-600 transition-all">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/yabunpontianak/" target="_blank" rel="noopener noreferrer"
                            aria-label="Instagram"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white hover:bg-emerald-600 hover:border-emerald-600 transition-all">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-white/20"></div>

    <!-- ===== Bottom Bar ===== -->
    <div class="mx-auto max-w-[1400px] px-6 lg:px-12 py-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between text-[11px] text-white/50">
            <p>© {{ date('Y') }} Yayasan Bakti Umat Nusantara Cabang Pontianak. All rights reserved.</p>
            <div class="flex gap-5">
                <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
