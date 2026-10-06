<!-- ============================================================== -->
<!-- REKENING DONASI & KAMPANYE PEMBANGUNAN RUMAH TAHFIDZ           -->
<!-- ============================================================== -->
<section id="donasi" class="bg-white py-16 lg:py-24 border-t border-[#E5E7EB]">
    <div class="max-w-7xl mx-auto px-6">

        <!-- SECTION HEADER (Sama Persis Letak & Tipografinya dengan Transparansi Donasi) -->
        <div class="max-w-2xl mb-12 lg:mb-16">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-[#111111]">
                Rekening Resmi Donasi
            </h2>
            <p class="text-base md:text-lg text-[#4B5563] mt-2 leading-relaxed text-justify">
                Setiap donasi dan infaq yang Anda titipkan menjadi amal jariyah untuk operasional santri tahfidz,
                santunan dhuafa, dan program dakwah.
            </p>
        </div>

        <!-- DAFTAR REKENING BANK RESMI YAYASAN -->
        <div class="divide-y divide-gray-200 border-y border-gray-200">
            @forelse($bankAccounts as $index => $bank)
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
                    $cleanNumber = preg_replace('/[^0-9]/', '', $bank->nomor_rekening);
                @endphp
                <div
                    class="py-6 flex flex-col md:flex-row md:items-center justify-between gap-5 {{ $index === 0 ? 'bg-emerald-50/40 px-5 -mx-5 sm:mx-0 sm:px-6 sm:rounded-lg' : 'px-5 -mx-5 sm:mx-0 sm:px-6' }}">
                    <div class="flex items-center gap-6 sm:gap-8">
                        <div class="h-14 md:h-16 w-36 sm:w-44 md:w-48 shrink-0 flex items-center">
                            <img src="{{ $logo }}" alt="{{ $bank->nama_bank }}"
                                class="h-10 sm:h-11 md:h-12 w-auto max-w-[140px] md:max-w-[160px] object-contain"
                                onerror="this.src='{{ asset('images/logo-bun.webp') }}'" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <h3 class="text-sm font-bold text-gray-900">{{ $bank->nama_bank }}</h3>
                            </div>
                            <p class="text-xl font-extrabold text-gray-900 font-mono tracking-wide">
                                {{ $bank->nomor_rekening }}
                            </p>
                            <p class="text-xs text-gray-500">
                                a.n {{ $bank->atas_nama }}
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="copyToClipboard('{{ $cleanNumber }}', '{{ $bank->nama_bank }}')"
                        class="shrink-0 inline-flex items-center justify-center gap-2 border border-gray-300 hover:border-[#007A4D] hover:text-[#007A4D] text-gray-700 font-semibold text-xs sm:text-sm px-5 py-2 rounded-md transition-colors cursor-pointer bg-white">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span>Salin No. Rekening</span>
                    </button>
                </div>
            @empty
                <div class="py-8 px-5 text-center text-sm text-gray-500">
                    Belum ada rekening donasi aktif yang terdaftar.
                </div>
            @endforelse
        </div>

        <!-- WHATSAPP CONFIRMATION STRIP -->
        @php
            $waRaw = !empty($siteSettings['kontak_wa']) ? preg_replace('/[^0-9]/', '', $siteSettings['kontak_wa']) : '6281930942890';
            if (str_starts_with($waRaw, '0')) {
                $waRaw = '62' . substr($waRaw, 1);
            }
        @endphp
        <div
            class="mt-12 lg:mt-16 pt-8 border-t border-[#E5E7EB] flex flex-col sm:flex-row items-center justify-between gap-5 text-center sm:text-left">
            <div>
                <h3 class="text-base font-bold text-gray-900">Sudah Melakukan Transfer Donasi?</h3>
                <p class="text-sm text-gray-600 mt-0.5">
                    Kirimkan bukti transfer untuk pencatatan dan penerbitan bukti donasi resmi.
                </p>
            </div>
            <a href="https://wa.me/{{ $waRaw }}?text={{ urlencode('Assalamu\'alaikum Admin YABUN, saya ingin konfirmasi donasi') }}"
                target="_blank" rel="noopener noreferrer"
                class="shrink-0 inline-flex items-center gap-2 bg-[#007A4D] hover:bg-[#046A42] text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>Konfirmasi via WhatsApp</span>
            </a>
        </div>

    </div>
</section>
