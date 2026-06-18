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

            <div class="flex items-center gap-2">

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

                        <tr id="row-{{ $d->id }}"
                            class="hover:bg-indigo-50/30 transition group">

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
                                {{ $d->teacher->name ?? '<span class="text-gray-400">—</span>' }}
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
                                <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation()">

                                    @if($d->slug)
                                        <!-- Edit Button - Persis seperti Admin -->
                                        <a href="{{ route('extracurricular.edit', $d->slug) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        <!-- Arsipkan Button - Persis seperti Admin -->
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
                        <!-- Empty state tetap sama -->
                        <tr>
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

    // Klik Row → Detail
    document.addEventListener('click', function (e) {
        let row = e.target.closest('.ekskul-show');
        if (row) {
            const url = row.dataset.url;
            if (url && url !== '#') {
                window.location.href = url;
            }
        }
    });

    // Tombol Arsipkan
    document.addEventListener('click', function (e) {
        let btn = e.target.closest('.btn-archive');
        if (!btn) return;

        const url = btn.dataset.url;
        const id  = btn.dataset.id;

        if (typeof deleteData === 'function') {
            deleteData(
                url,
                'Yakin ingin memindahkan data ke arsip?',
                {
                    onSuccess: function () {
                        document.getElementById('row-' + id)?.remove();
                    }
                }
            );
        }
    });

});
</script>
@endpush