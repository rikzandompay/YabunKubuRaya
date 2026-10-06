@extends('layouts.admin')

@section('title', 'Manajemen Katalog Program — Admin YABUN Pontianak')
@section('breadcrumb', 'Katalog Program')

@section('content')
    <div class="space-y-6">
        {{-- Header & Action --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-[#1E293B]">Manajemen Katalog Program</h1>
                <p class="text-sm text-[#64748B] mt-0.5">Kelola paket dan item program kerja seperti Jumat Berkah, Tahsin,
                    Santunan, dan Kurban.</p>
            </div>
            <button type="button" x-data @click="$dispatch('open-modal-katalog')"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-[#065F46] hover:bg-[#047857] shadow-sm transition-colors focus-visible:ring-2 focus-visible:ring-[#065F46] focus-visible:ring-offset-2 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Item Katalog</span>
            </button>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div
                class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-[#065F46] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Filter Tabs Program --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-[#E2E8F0]">
            <a href="{{ route('admin.katalog.index', ['program' => 'jumat-berkah']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ $programFilter === 'jumat-berkah' ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}">
                Jumat Berkah
            </a>
            <a href="{{ route('admin.katalog.index', ['program' => 'tahsin-tahfiz']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ $programFilter === 'tahsin-tahfiz' ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}">
                Tahsin & Tahfiz
            </a>
            <a href="{{ route('admin.katalog.index', ['program' => 'santunan-anak-yatim']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ $programFilter === 'santunan-anak-yatim' ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}">
                Santunan Anak Yatim
            </a>
            <a href="{{ route('admin.katalog.index', ['program' => 'zakat-mal-fitrah']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ $programFilter === 'zakat-mal-fitrah' ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}">
                Zakat Mal dan Fitrah
            </a>
            <a href="{{ route('admin.katalog.index', ['program' => 'kurban']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shrink-0 {{ $programFilter === 'kurban' ? 'bg-[#065F46] text-white shadow-sm' : 'text-[#64748B] hover:text-[#1E293B] hover:bg-white' }}">
                Kurban
            </a>
        </div>

        {{-- Items Table --}}
        <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm min-w-[720px]">
                    <thead
                        class="bg-[#F8FAFC] border-b border-[#E2E8F0] text-[#64748B] text-xs font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 whitespace-nowrap">Gambar</th>
                            <th class="px-4 py-3 whitespace-nowrap">Program</th>
                            <th class="px-4 py-3 whitespace-nowrap">Judul Katalog</th>
                            <th class="px-4 py-3 whitespace-nowrap">Deskripsi</th>
                            <th class="px-4 py-3 whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @forelse($items as $item)
                            <tr class="hover:bg-[#F8FAFC]/50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-12 h-12 rounded-lg object-cover border border-[#CBD5E1]">
                                </td>
                                <td class="px-4 py-3 font-medium text-[#065F46] whitespace-nowrap">
                                    {{ $item->program?->nama_program ?? 'Program Umum' }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-[#1E293B]">
                                    {{ $item->judul }}
                                </td>
                                <td class="px-4 py-3 text-[#64748B] max-w-xs truncate">
                                    {{ $item->deskripsi }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full {{ $item->status === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                    <button type="button"
                                        @click="$dispatch('open-modal-edit-katalog', {
                                            id: {{ $item->id }},
                                            program_id: {{ $item->program_id }},
                                            judul: '{{ addslashes($item->judul) }}',
                                            deskripsi: '{{ addslashes($item->deskripsi) }}',
                                            status: '{{ $item->status }}',
                                            gambar_url: '{{ $item->gambar_url }}'
                                        })"
                                        class="p-1.5 text-[#065F46] hover:bg-emerald-50 rounded-md transition-colors inline-block"
                                        title="Edit Item Katalog">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('admin.katalog.destroy', $item->id) }}"
                                        onsubmit="return confirm('Hapus item katalog ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors"
                                            title="Hapus" aria-label="Hapus {{ $item->judul }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-[#64748B]">
                                    Belum ada item katalog terdaftar untuk kriteria ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($items->hasPages())
                <div class="px-4 py-3 border-t border-[#E2E8F0]">
                    {{ $items->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Tambah Katalog --}}
        <div x-data="{ show: false }" @open-modal-katalog.window="show = true" x-show="show" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
            @keydown.escape.window="show = false">
            <div @click.away="show = false"
                class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl border border-[#E2E8F0] space-y-4">
                <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                    <h3 class="text-base font-semibold text-[#1E293B]">Tambah Item Katalog Program</h3>
                    <button @click="show = false" class="text-[#94A3B8] hover:text-[#1E293B] p-1 rounded">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.katalog.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Pilih Program Induk</label>
                        <select name="program_id" required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                            @foreach ($programs as $prog)
                                <option value="{{ $prog->id }}">{{ $prog->nama_program }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Judul Katalog</label>
                        <input type="text" name="judul" required
                            placeholder="Contoh: Paket Nasi Kotak Jumat Berkah Santri"
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Upload Gambar Katalog</label>
                        <input type="file" name="gambar" accept="image/*"
                            class="w-full px-3 py-1.5 text-xs text-[#64748B] bg-white border border-[#CBD5E1] rounded-lg file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-[#065F46] hover:file:bg-emerald-100">
                        <p class="text-[11px] text-[#94A3B8] mt-1">Format: JPG, PNG, WEBP. Maks: 5MB.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Deskripsi Lengkap</label>
                        <textarea name="deskripsi" rows="3" required
                            placeholder="Uraikan detail paket, rincian donasi, dan manfaat program..."
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Status Keaktifan</label>
                        <select name="status" required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E2E8F0]">
                        <button type="button" @click="show = false"
                            class="px-4 py-2 text-xs font-medium text-[#64748B] hover:bg-[#F1F5F9] rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg shadow-sm transition-colors">
                            Simpan Item Katalog
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit Katalog --}}
        <div x-data="{ show: false, item: {} }"
            @open-modal-edit-katalog.window="show = true; item = $event.detail"
            x-show="show" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
            @keydown.escape.window="show = false">
            <div @click.away="show = false"
                class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl border border-[#E2E8F0] space-y-4">
                <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                    <h3 class="text-base font-semibold text-[#1E293B]">Edit Item Katalog Program</h3>
                    <button @click="show = false" class="text-[#94A3B8] hover:text-[#1E293B] p-1 rounded">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" :action="`/admin/katalog/${item.id}`" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Pilih Program Induk</label>
                        <select name="program_id" x-model="item.program_id" required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                            @foreach ($programs as $prog)
                                <option value="{{ $prog->id }}">{{ $prog->nama_program }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Judul Katalog</label>
                        <input type="text" name="judul" x-model="item.judul" required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Gambar Saat Ini</label>
                        <template x-if="item.gambar_url">
                            <img :src="item.gambar_url" class="w-20 h-20 rounded-lg object-cover border border-[#CBD5E1] mb-2" alt="Gambar">
                        </template>
                        <input type="file" name="gambar" accept="image/*"
                            class="w-full px-3 py-1.5 text-xs text-[#64748B] bg-white border border-[#CBD5E1] rounded-lg file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-[#065F46] hover:file:bg-emerald-100">
                        <p class="text-[11px] text-[#94A3B8] mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Deskripsi Lengkap</label>
                        <textarea name="deskripsi" rows="3" x-model="item.deskripsi" required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#1E293B] mb-1">Status Keaktifan</label>
                        <select name="status" x-model="item.status" required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#065F46] focus:border-transparent outline-none">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E2E8F0]">
                        <button type="button" @click="show = false"
                            class="px-4 py-2 text-xs font-medium text-[#64748B] hover:bg-[#F1F5F9] rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-[#065F46] hover:bg-[#047857] rounded-lg shadow-sm transition-colors">
                            Perbarui Katalog
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
