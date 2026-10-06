@extends('layouts.admin')

@section('title', 'Manajemen Kegiatan Foto — Admin YABUN Pontianak')
@section('breadcrumb', 'Kegiatan Foto')

@section('content')
<div class="space-y-6">
    {{-- Header & Action --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#1E293B]">Manajemen Kegiatan Foto</h1>
            <p class="text-sm text-[#64748B] mt-0.5">Kelola dokumentasi galeri kegiatan sosial dan keagamaan yayasan.</p>
        </div>
        <button
            type="button"
            x-data
            @click="$dispatch('open-modal-foto')"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-[#065F46] hover:bg-[#047857] shadow-sm transition-colors focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2 shrink-0"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Unggah Foto Baru</span>
        </button>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-[#065F46] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-[#E2E8F0]">
        <a
            href="{{ route('admin.galeri.index') }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ !$kategori ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}"
        >
            Semua Foto ({{ $totalCount }})
        </a>
        <a
            href="{{ route('admin.galeri.index', ['kategori' => 'jumat_berkah']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ $kategori === 'jumat_berkah' ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}"
        >
            Jumat Berkah
        </a>
        <a
            href="{{ route('admin.galeri.index', ['kategori' => 'donasi']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ $kategori === 'donasi' ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}"
        >
            Donasi
        </a>
        <a
            href="{{ route('admin.galeri.index', ['kategori' => 'dzikir']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ $kategori === 'dzikir' ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}"
        >
            Dzikir
        </a>
    </div>

    {{-- Photo Grid --}}
    @if($photos->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($photos as $photo)
                <div class="bg-white rounded-xl border border-[#E2E8F0] overflow-hidden shadow-sm hover:shadow transition-all group flex flex-col">
                    <div class="relative aspect-video bg-[#F1F5F9] overflow-hidden">
                        <img
                            src="{{ $photo->image_url }}"
                            alt="{{ $photo->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        >
                        <span class="absolute top-2 left-2 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-md bg-white/90 backdrop-blur-sm text-[#1E293B]">
                            {{ str_replace('_', ' ', $photo->category) }}
                        </span>
                        @if($photo->is_featured)
                            <span class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-500 text-white">
                                Unggulan
                            </span>
                        @endif
                    </div>
                    <div class="p-3.5 flex-1 flex flex-col justify-between gap-3">
                        <p class="text-sm font-medium text-[#1E293B] line-clamp-2">{{ $photo->title }}</p>
                        <div class="flex items-center justify-between pt-2 border-t border-[#F1F5F9]">
                            <span class="text-[11px] text-[#94A3B8]">
                                {{ $photo->activity_date ? $photo->activity_date->format('d M Y') : ($photo->created_at ? $photo->created_at->format('d M Y') : 'Baru') }}
                            </span>
                            <div class="flex items-center gap-1">
                                {{-- Tombol Edit --}}
                                <button
                                    type="button"
                                    x-data
                                    @click="$dispatch('open-modal-edit', {
                                        id: {{ $photo->id }},
                                        title: {{ json_encode($photo->title) }},
                                        category: {{ json_encode($photo->category) }},
                                        activity_date: '{{ $photo->activity_date ? $photo->activity_date->format('Y-m-d') : date('Y-m-d') }}',
                                        is_featured: {{ $photo->is_featured ? 'true' : 'false' }},
                                        image_url: {{ json_encode($photo->image_url) }}
                                    })"
                                    class="p-1.5 text-[#065F46] hover:bg-emerald-50 rounded-md transition-colors"
                                    title="Edit Foto"
                                    aria-label="Edit Foto {{ $photo->title }}"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form method="POST" action="{{ route('admin.galeri.destroy', $photo->id) }}" onsubmit="return confirm('Hapus foto ini dari galeri?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors"
                                        title="Hapus Foto"
                                        aria-label="Hapus Foto {{ $photo->title }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $photos->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-xl border border-dashed border-[#CBD5E1] p-10 text-center">
            <div class="w-12 h-12 bg-[#F1F5F9] text-[#64748B] rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <h2 class="text-base font-semibold text-[#1E293B]">Belum ada foto kegiatan</h2>
            <p class="text-xs text-[#64748B] mt-1 max-w-sm mx-auto">Mulai tambahkan foto dokumentasi kegiatan untuk memperkaya transparansi dan publikasi yayasan.</p>
        </div>
    @endif

    {{-- Modal Upload Foto --}}
    <div
        x-data="{ show: false }"
        @open-modal-foto.window="show = true"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
        @keydown.escape.window="show = false"
    >
        <div
            @click.away="show = false"
            class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl border border-[#E2E8F0] space-y-4"
        >
            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                <h3 class="text-base font-semibold text-[#1E293B]">Unggah Foto Kegiatan</h3>
                <button @click="show = false" class="text-[#94A3B8] hover:text-[#1E293B] p-1 rounded">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Judul Foto / Kegiatan</label>
                    <input
                        type="text"
                        name="title"
                        required
                        placeholder="Contoh: Pembagian Nasi Jumat Berkah Santri"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Kategori Kegiatan</label>
                    <select
                        name="category"
                        required
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                        <option value="jumat_berkah">Jumat Berkah</option>
                        <option value="donasi">Donasi</option>
                        <option value="dzikir">Dzikir</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Tanggal Kegiatan</label>
                    <input
                        type="date"
                        name="activity_date"
                        value="{{ date('Y-m-d') }}"
                        required
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">File Foto (JPG, PNG, WebP max 5MB)</label>
                    <input
                        type="file"
                        name="image"
                        required
                        accept="image/jpeg,image/png,image/webp"
                        class="w-full text-xs text-[#64748B] file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#065F46] file:text-white hover:file:bg-[#047857]"
                    >
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                    <label for="is_featured" class="text-xs text-[#475569]">Tampilkan sebagai foto unggulan di beranda</label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E2E8F0]">
                    <button
                        type="button"
                        @click="show = false"
                        class="px-4 py-2 text-xs font-medium text-[#64748B] hover:bg-[#F1F5F9] rounded-lg transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg shadow-sm transition-colors"
                    >
                        Simpan Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Foto --}}
    <div
        x-data="{ show: false, photo: {} }"
        @open-modal-edit.window="show = true; photo = $event.detail"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
        @keydown.escape.window="show = false"
    >
        <div
            @click.away="show = false"
            class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl border border-[#E2E8F0] space-y-4"
        >
            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                <h3 class="text-base font-semibold text-[#1E293B]">Edit Foto Kegiatan</h3>
                <button @click="show = false" class="text-[#94A3B8] hover:text-[#1E293B] p-1 rounded">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" :action="'/admin/galeri/' + photo.id" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Judul Foto / Kegiatan</label>
                    <input
                        type="text"
                        name="title"
                        required
                        x-model="photo.title"
                        placeholder="Contoh: Pembagian Nasi Jumat Berkah Santri"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Kategori Kegiatan</label>
                    <select
                        name="category"
                        required
                        x-model="photo.category"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                        <option value="jumat_berkah">Jumat Berkah</option>
                        <option value="donasi">Donasi</option>
                        <option value="dzikir">Dzikir</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Tanggal Kegiatan</label>
                    <input
                        type="date"
                        name="activity_date"
                        x-model="photo.activity_date"
                        required
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Ganti File Foto (Kosongkan jika tidak diganti)</label>
                    <div class="flex items-center gap-3 mb-2" x-show="photo.image_url">
                        <img :src="photo.image_url" alt="Pratinjau" class="w-14 h-14 object-cover rounded-lg border border-[#E2E8F0]">
                        <span class="text-[11px] text-[#64748B]">Foto saat ini</span>
                    </div>
                    <input
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/webp"
                        class="w-full text-xs text-[#64748B] file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#065F46] file:text-white hover:file:bg-[#047857]"
                    >
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_featured" id="is_featured_edit" value="1" x-model="photo.is_featured" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                    <label for="is_featured_edit" class="text-xs text-[#475569]">Tampilkan sebagai foto unggulan di beranda</label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E2E8F0]">
                    <button
                        type="button"
                        @click="show = false"
                        class="px-4 py-2 text-xs font-medium text-[#64748B] hover:bg-[#F1F5F9] rounded-lg transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg shadow-sm transition-colors"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
