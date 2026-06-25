@extends('layouts.app')

@section('title', 'Ekstrakurikuler')

@section('content')

<div class="min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="max-w-6xl mx-auto">

        <!-- BREADCRUMB -->
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            <span class="text-gray-700 font-medium">Ekstrakurikuler</span>
        </div>

        <!-- ALERT -->
        <div id="alertBox" class="hidden mb-4 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">

            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Ekstrakurikuler</h1>
                <p class="text-sm text-gray-400 mt-0.5">Kelola data kegiatan ekstrakurikuler sekolah.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">

                <!-- SEARCH -->
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Cari nama ekskul atau pembina…"
                        class="pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300 w-60 transition"
                    >
                </div>

                <a href="{{ route('extracurricular.archived') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/>
                    </svg>
                    Arsip
                </a>

                <a href="{{ route('extracurricular.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Tambah Ekskul
                </a>

            </div>

        </div>

        <!-- INFO HASIL PENCARIAN -->
        <div id="searchInfo" class="hidden mb-3 text-sm text-gray-500">
            Menampilkan <span id="searchCount" class="font-medium text-gray-700"></span> hasil
            untuk "<span id="searchKeyword" class="text-indigo-600"></span>"
        </div>

        <!-- TABLE CARD -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[700px]">

                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/70">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Ekstrakurikuler</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Pembina</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Jumlah Siswa</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">

                        @forelse($data as $d)
                        <tr id="row-{{ $d->id }}" class="hover:bg-indigo-50/30 transition group">

                            <!-- NAMA EKSKUL -->
                            <td class="px-6 py-4 cursor-pointer ekskul-show"
                                data-url="{{ $d->slug ? route('extracurricular.show', $d->slug) : '#' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 text-xl">
                                        🏅
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 group-hover:text-indigo-600 transition">
                                            {{ $d->name }}
                                        </p>
                                        @if(!$d->slug)
                                            <p class="text-amber-500 text-xs mt-0.5">Slug kosong</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- PEMBINA -->
                            <td class="px-6 py-4 cursor-pointer ekskul-show text-gray-600"
                                data-url="{{ $d->slug ? route('extracurricular.show', $d->slug) : '#' }}">
                                {{ $d->teacher->name ?? '—' }}
                            </td>

                            <!-- JUMLAH SISWA -->
                            <td class="px-6 py-4 cursor-pointer ekskul-show text-center"
                                data-url="{{ $d->slug ? route('extracurricular.show', $d->slug) : '#' }}">
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 px-3 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                    {{ $d->students->count() }} Siswa
                                </span>
                            </td>

                            <!-- AKSI -->
                            <td class="px-6 py-4 text-right">
                                {{-- ⛔ onclick="event.stopPropagation()" DIHAPUS.
                                     Sebelumnya ini menghentikan event sebelum sampai ke
                                     document-level listener (.btn-archive), sehingga
                                     tombol Arsipkan tidak pernah ter-trigger.
                                     Pencegahan redirect klik-baris kini ditangani di JS
                                     dengan mengecualikan area aksi dari listener .ekskul-show. --}}
                                <div class="flex items-center justify-end gap-2 action-cell">
                                    @if($d->slug)
                                        <a href="{{ route('extracurricular.edit', $d->slug) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100 transition btn-archive"
                                            data-id="{{ $d->id }}"
                                            data-url="{{ route('extracurricular.destroy', $d->slug) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/>
                                            </svg>
                                            Arsipkan
                                        </button>
                                    @endif
                                </div>
                            </td>

                        </tr>

                        @empty
                        <tr id="emptyRow">
                            <td colspan="4" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada ekstrakurikuler terdaftar</p>
                                    <a href="{{ route('extracurricular.create') }}"
                                       class="text-xs text-indigo-600 hover:underline">+ Tambah ekstrakurikuler pertama</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                        <!-- BARIS KOSONG HASIL PENCARIAN -->
                        <tr id="noResultRow" style="display:none;">
                            <td colspan="4" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Tidak ada ekskul yang cocok dengan pencarian</p>
                                    <button onclick="document.getElementById('searchInput').value=''; document.getElementById('searchInput').dispatchEvent(new Event('input'));"
                                            class="text-xs text-indigo-600 hover:underline">Hapus pencarian</button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    =====================
    DETAIL — klik baris ke halaman show
    Dikecualikan: klik yang berasal dari area aksi (.action-cell),
    supaya tombol Edit / Arsipkan tidak ikut memicu redirect.
    =====================
    */
    document.addEventListener('click', function (e) {
        if (e.target.closest('.action-cell')) return;

        let row = e.target.closest('.ekskul-show');
        if (row) {
            const url = row.dataset.url;
            if (url && url !== '#') {
                window.location.href = url;
            }
        }
    });

    /*
    =====================
    ARCHIVE — soft delete via deleteData() dari layout
    =====================
    */
    document.addEventListener('click', function (e) {
        let btn = e.target.closest('.btn-archive');
        if (!btn) return;

        const url = btn.dataset.url;
        const id  = btn.dataset.id;

        deleteData(
            url,
            'Yakin ingin memindahkan data ke arsip?',
            {
                onSuccess: function () {
                    // Hapus baris dari tabel
                    let removedRow = document.getElementById('row-' + id);
                    if (removedRow) removedRow.remove();

                    // Tampilkan empty state jika tidak ada data tersisa
                    let remaining = document.querySelectorAll('tbody tr[id^="row-"]');
                    if (remaining.length === 0) {
                        let input = document.getElementById('searchInput');
                        if (input) input.value = '';
                        document.getElementById('searchInfo').classList.add('hidden');
                        document.getElementById('noResultRow').style.display = 'none';
                        let emptyRow = document.getElementById('emptyRow');
                        if (emptyRow) emptyRow.style.display = '';
                    }
                }
            }
        );
    });

    /*
    =====================
    SEARCH
    =====================
    */
    (function () {
        var input     = document.getElementById('searchInput');
        var info      = document.getElementById('searchInfo');
        var countEl   = document.getElementById('searchCount');
        var keywordEl = document.getElementById('searchKeyword');
        var tbody     = document.querySelector('tbody');
        var allRows   = Array.from(tbody.querySelectorAll('tr[id^="row-"]'));
        var noResult  = document.getElementById('noResultRow');

        if (!input) return;

        input.addEventListener('input', function () {
            var q       = this.value.trim().toLowerCase();
            var visible = 0;

            allRows.forEach(function (row) {
                var match = q === '' || row.innerText.toLowerCase().includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            if (noResult) {
                noResult.style.display = (q !== '' && visible === 0) ? '' : 'none';
            }

            if (q === '') {
                info.classList.add('hidden');
            } else {
                info.classList.remove('hidden');
                countEl.textContent   = visible;
                keywordEl.textContent = q;
            }
        });
    })();

});
</script>
@endpush