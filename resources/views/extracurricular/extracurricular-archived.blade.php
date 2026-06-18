@extends('layouts.app')

@section('title', 'Arsip Ekstrakurikuler')

@section('content')

<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('extracurricular.index') }}" class="text-blue-600 hover:underline">
                    Ekstrakurikuler
                </a>
            </li>
            <li>/</li>
            <li class="text-gray-700 font-medium">Arsip</li>
        </ol>
    </nav>

    <!-- ALERT -->
    <div id="alertBox"></div>

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Arsip Ekstrakurikuler</h1>
            <p class="text-sm text-gray-500">Data ekstrakurikuler yang telah diarsipkan</p>
        </div>

        <a href="{{ route('extracurricular.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            ← Kembali
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow border overflow-x-auto">

        <table class="w-full text-sm min-w-[600px]">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-4 text-left">Nama Ekskul</th>
                    <th class="p-4 text-center">Pembina</th>
                    <th class="p-4 text-center">Jumlah Siswa</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $d)

                <tr id="row-{{ $d->slug }}" class="border-b hover:bg-gray-50">

                    <td class="p-4 font-medium">
                        {{ $d->name }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $d->teacher->name ?? '-' }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $d->students->count() }}
                    </td>

                    <td class="p-4 text-center">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Archived
                        </span>
                    </td>

                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">

                            <!-- RESTORE -->
                            <button
                                class="btn-restore bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
                                data-slug="{{ $d->slug }}"
                                data-url="{{ route('extracurricular.restore', $d->slug) }}">
                                Restore
                            </button>

                            <!-- HAPUS PERMANEN -->
                            <button
                                class="btn-force-delete bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                                data-slug="{{ $d->slug }}"
                                data-url="{{ route('extracurricular.forceDelete', $d->slug) }}">
                                Hapus Permanen
                            </button>

                        </div>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="5" class="text-center p-6 text-gray-500">
                        Tidak ada data arsip
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.addEventListener('click', function (e) {

        // =====================
        // RESTORE
        // =====================
        let btnRestore = e.target.closest('.btn-restore');
        if (btnRestore) {

            let url = btnRestore.dataset.url;
            let slug = btnRestore.dataset.slug;

            restoreData(
                url,
                'Restore ekstrakurikuler ini?',
                {
                    onSuccess: function () {
                        let row = document.getElementById('row-' + slug);
                        if (row) row.remove();
                    }
                }
            );

            return;
        }

        // =====================
        // HAPUS PERMANEN
        // =====================
        let btnDelete = e.target.closest('.btn-force-delete');
        if (btnDelete) {

            let url = btnDelete.dataset.url;
            let slug = btnDelete.dataset.slug;

            deleteData(
                url,
                'Hapus permanen ekstrakurikuler ini? Tindakan ini tidak bisa dibatalkan!',
                {
                    onSuccess: function () {
                        let row = document.getElementById('row-' + slug);
                        if (row) row.remove();
                    }
                }
            );

        }

    });

});
</script>
@endpush