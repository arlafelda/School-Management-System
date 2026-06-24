@extends('layouts.app')

@section('title', 'Daftar Admin')

@section('content')

<div class="min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="max-w-6xl mx-auto">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard.super_admin') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span class="text-gray-700 font-medium">Kelola Admin</span>
        </div>

        {{-- ALERT --}}
        <div id="alertBox" class="hidden mb-4 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">

            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Daftar Admin</h1>
                <p class="text-sm text-gray-400 mt-0.5">Kelola akun admin yang terdaftar di sistem.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">

                {{-- SEARCH --}}
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Cari nama atau email…"
                        class="pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300 w-56 transition"
                    >
                </div>

                {{-- ARSIP --}}
                <a href="{{ route('admin.archived') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
                    Arsip
                </a>

                {{-- TAMBAH --}}
                <a href="{{ route('admin.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Admin
                </a>

            </div>

        </div>

        {{-- INFO HASIL PENCARIAN --}}
        <div id="searchInfo" class="hidden mb-3 text-sm text-gray-500">
            Menampilkan <span id="searchCount" class="font-medium text-gray-700"></span> hasil
            untuk "<span id="searchKeyword" class="text-indigo-600"></span>"
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm min-w-[680px]">

                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/70">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">

                        @forelse($admins as $user)

                        <tr id="row-{{ $user->slug }}"
                            class="hover:bg-indigo-50/30 transition group">

                            {{-- USER --}}
                            <td class="px-6 py-4 cursor-pointer admin-show"
                                data-url="{{ route('admin.show', $user->slug) }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-indigo-600 font-semibold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 group-hover:text-indigo-600 transition">
                                            {{ $user->name }}
                                        </p>
                                        <p class="text-gray-400 text-xs mt-0.5">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- ROLE --}}
                            <td class="px-6 py-4 cursor-pointer admin-show text-gray-600"
                                data-url="{{ route('admin.show', $user->slug) }}">
                                {{ ucfirst($user->role) }}
                            </td>

                            {{-- ID --}}
                            <td class="px-6 py-4 cursor-pointer admin-show"
                                data-url="{{ route('admin.show', $user->slug) }}">
                                <span class="text-gray-400 font-mono text-xs">#{{ $user->id }}</span>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4 cursor-pointer admin-show"
                                data-url="{{ route('admin.show', $user->slug) }}">
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 border border-green-100 px-2.5 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                    Aktif
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('admin.edit', $user->slug) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100 transition btn-delete"
                                        data-url="{{ route('admin.delete', $user->slug) }}"
                                        data-row="row-{{ $user->slug }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
                                        Arsipkan
                                    </button>

                                </div>
                            </td>

                        </tr>

                        @empty

                        <tr id="emptyRow">
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada admin terdaftar</p>
                                    <a href="{{ route('admin.create') }}" class="text-xs text-indigo-600 hover:underline">+ Tambah admin pertama</a>
                                </div>
                            </td>
                        </tr>

                        @endforelse

                        {{-- BARIS KOSONG HASIL PENCARIAN (ditampilkan via JS) --}}
                        <tr id="noResultRow" style="display:none;">
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                                    <p class="text-sm font-medium text-gray-500">Tidak ada admin yang cocok dengan pencarian</p>
                                    <button onclick="document.getElementById('searchInput').value=''; document.getElementById('searchInput').dispatchEvent(new Event('input'));" class="text-xs text-indigo-600 hover:underline">Hapus pencarian</button>
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
@verbatim
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    =====================
    DETAIL
    =====================
    */
    document.addEventListener('click', function (e) {
        let row = e.target.closest('.admin-show');
        if (row) window.location.href = row.dataset.url;
    });


    /*
    =====================
    ARCHIVE
    =====================
    */
    document.addEventListener('click', function (e) {

        let btn = e.target.closest('.btn-delete');
        if (!btn) return;

        let url   = btn.dataset.url;
        let rowId = btn.dataset.row;

        if (typeof deleteData === 'undefined') {
            console.error('deleteData belum tersedia');
            return;
        }

        deleteData(
            url,
            'Yakin ingin memindahkan data ke arsip?',
            {
                onSuccess: function () {
                    let removedRow = document.getElementById(rowId);
                    if (removedRow) removedRow.remove();

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
        const input     = document.getElementById('searchInput');
        const info      = document.getElementById('searchInfo');
        const countEl   = document.getElementById('searchCount');
        const keywordEl = document.getElementById('searchKeyword');
        const tbody     = document.querySelector('tbody');
        const allRows   = Array.from(tbody.querySelectorAll('tr[id^="row-"]'));
        const noResult  = document.getElementById('noResultRow');

        if (!input) return;

        input.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            let visible = 0;

            allRows.forEach(function (row) {
                const match = q === '' || row.innerText.toLowerCase().includes(q);
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
@endverbatim
@endpush