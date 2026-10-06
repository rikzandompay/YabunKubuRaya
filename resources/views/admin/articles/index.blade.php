@extends('layouts.admin')

@section('title', 'Manajemen Artikel Program — Admin YABUN Pontianak')
@section('breadcrumb', 'Artikel Program')

@section('content')
<div class="space-y-6">
    {{-- Header & Action --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#1E293B]">Manajemen Artikel Program</h1>
            <p class="text-sm text-[#64748B] mt-0.5">Kelola berita, liputan kegiatan, dan artikel edukasi yayasan.</p>
        </div>
        <button
            type="button"
            x-data
            @click="$dispatch('open-modal-artikel')"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-[#065F46] hover:bg-[#047857] shadow-sm transition-colors focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2 shrink-0"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tulis Artikel Baru</span>
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

    {{-- Filter Kategori --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-[#E2E8F0]">
        <a
            href="{{ route('admin.artikel.index') }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ !$kategoriSlug || $kategoriSlug === 'semua' ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}"
        >
            Semua Kategori
        </a>
        @foreach($categories as $category)
            <a
                href="{{ route('admin.artikel.index', ['kategori' => $category->slug]) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ $kategoriSlug === $category->slug ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}"
            >
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    {{-- Table of Articles --}}
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[640px]">
                <thead class="bg-[#F8FAFC] border-b border-[#E2E8F0] text-[#64748B] text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">Artikel</th>
                        <th class="px-4 py-3 whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3 whitespace-nowrap">Tanggal Terbit</th>
                        <th class="px-4 py-3 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($articles as $article)
                        <tr class="hover:bg-[#F8FAFC]/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img
                                        src="{{ $article->header_image_url }}"
                                        alt="{{ $article->title }}"
                                        class="w-12 h-12 rounded-lg object-cover bg-slate-100 shrink-0"
                                    >
                                    <div class="min-w-0">
                                        <p class="font-semibold text-[#1E293B] line-clamp-1">{{ $article->title }}</p>
                                        <p class="text-xs text-[#64748B] line-clamp-1">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 80) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-slate-100 text-slate-700">
                                    {{ $article->programCategory?->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-[#64748B] text-xs whitespace-nowrap">
                                {{ $article->published_at ? $article->published_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $article->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $article->is_published ? 'Terbit' : 'Draf' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <a
                                        href="{{ route('artikel.show', $article->slug) }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="p-1.5 text-[#64748B] hover:text-[#065F46] hover:bg-[#F1F5F9] rounded-md transition-colors"
                                        title="Lihat Pratinjau Publik"
                                        aria-label="Lihat {{ $article->title }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                    </a>

                                    <button
                                        type="button"
                                        @click="$dispatch('open-modal-edit-artikel', {{ json_encode([
                                            'id' => $article->id,
                                            'program_category_id' => $article->program_category_id,
                                            'title' => $article->title,
                                            'excerpt' => $article->excerpt ?? '',
                                            'content' => $article->content,
                                            'is_published' => (bool) $article->is_published,
                                            'header_image_url' => $article->header_image_url,
                                        ]) }})"
                                        class="p-1.5 text-[#065F46] hover:bg-emerald-50 rounded-md transition-colors"
                                        title="Edit Artikel"
                                        aria-label="Edit {{ $article->title }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('admin.artikel.destroy', $article->id) }}" onsubmit="return confirm('Hapus artikel ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors"
                                            title="Hapus"
                                            aria-label="Hapus {{ $article->title }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-[#64748B]">
                                Belum ada artikel terbit untuk kriteria ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($articles->hasPages())
            <div class="px-4 py-3 border-t border-[#E2E8F0]">
                {{ $articles->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah Artikel --}}
    <div
        x-data="{ show: false }"
        @open-modal-artikel.window="show = true"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
        @keydown.escape.window="show = false"
    >
        <div
            @click.away="show = false"
            class="bg-white rounded-xl max-w-2xl w-full p-6 shadow-xl border border-[#E2E8F0] space-y-4 max-h-[90vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                <h3 class="text-base font-semibold text-[#1E293B]">Tulis Artikel Program Baru</h3>
                <button @click="show = false" class="text-[#94A3B8] hover:text-[#1E293B] p-1 rounded">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.artikel.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Judul Artikel</label>
                    <input
                        type="text"
                        name="title"
                        required
                        placeholder="Contoh: Distribusi Paket Jumat Berkah di Pontianak Timur"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Kategori Program</label>
                    <select
                        name="program_category_id"
                        required
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Foto Sampul Header (Opsional)</label>
                    <input
                        type="file"
                        name="header_image"
                        accept="image/jpeg,image/png,image/webp"
                        class="w-full text-xs text-[#64748B] file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#065F46] file:text-white hover:file:bg-[#047857]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Ringkasan Singkat (Excerpt)</label>
                    <input
                        type="text"
                        name="excerpt"
                        placeholder="Ringkasan 1-2 kalimat untuk pratinjau kartu berita..."
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Isi Artikel Lengkap</label>
                    <textarea
                        name="content"
                        rows="6"
                        required
                        placeholder="Tulis narasi berita kegiatan selengkapnya..."
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    ></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_published" id="is_published" value="1" checked class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                    <label for="is_published" class="text-xs text-[#475569]">Terbitkan langsung ke publik</label>
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
                        Terbitkan Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Artikel --}}
    <div
        x-data="{ show: false, item: { id: '', program_category_id: '', title: '', excerpt: '', content: '', is_published: true, header_image_url: '' } }"
        @open-modal-edit-artikel.window="show = true; item = $event.detail"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
        @keydown.escape.window="show = false"
    >
        <div
            @click.away="show = false"
            class="bg-white rounded-xl max-w-2xl w-full p-6 shadow-xl border border-[#E2E8F0] space-y-4 max-h-[90vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                <h3 class="text-base font-semibold text-[#1E293B]">Edit Artikel Program</h3>
                <button @click="show = false" class="text-[#94A3B8] hover:text-[#1E293B] p-1 rounded">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" :action="`/admin/artikel/${item.id}`" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Judul Artikel</label>
                    <input
                        type="text"
                        name="title"
                        x-model="item.title"
                        required
                        placeholder="Contoh: Distribusi Paket Jumat Berkah di Pontianak Timur"
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Kategori Program</label>
                    <select
                        name="program_category_id"
                        x-model="item.program_category_id"
                        required
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Foto Sampul Header Saat Ini</label>
                    <template x-if="item.header_image_url">
                        <div class="mb-2">
                            <img :src="item.header_image_url" class="w-32 h-20 rounded-lg object-cover border border-[#CBD5E1]" alt="Preview">
                        </div>
                    </template>
                    <input
                        type="file"
                        name="header_image"
                        accept="image/jpeg,image/png,image/webp"
                        class="w-full text-xs text-[#64748B] file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#065F46] file:text-white hover:file:bg-[#047857]"
                    >
                    <p class="text-[11px] text-[#94A3B8] mt-1">Kosongkan jika tidak ingin mengubah foto sampul.</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Ringkasan Singkat (Excerpt)</label>
                    <input
                        type="text"
                        name="excerpt"
                        x-model="item.excerpt"
                        placeholder="Ringkasan 1-2 kalimat untuk pratinjau kartu berita..."
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#1E293B] mb-1">Isi Artikel Lengkap</label>
                    <textarea
                        name="content"
                        x-model="item.content"
                        rows="6"
                        required
                        placeholder="Tulis narasi berita kegiatan selengkapnya..."
                        class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"
                    ></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_published" id="edit_is_published" value="1" :checked="item.is_published" class="rounded border-[#CBD5E1] text-[#065F46] focus:ring-[#065F46]">
                    <label for="edit_is_published" class="text-xs text-[#475569]">Terbitkan langsung ke publik</label>
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
