@extends('layouts.app')

@section('title', 'Arsip Guru')

@section('content')

<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('teacher.index') }}" class="text-blue-600 hover:underline">
                    Guru
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
            <h1 class="text-2xl font-bold">Arsip Guru</h1>
            <p class="text-gray-500 text-sm">Data guru yang telah diarsipkan</p>
        </div>

        <a href="{{ route('teacher.index') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            ← Kembali
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow border overflow-x-auto">

        <table class="w-full text-sm min-w-[900px]">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-4 text-left">Guru</th>
                    <th class="p-4 text-center">NIP</th>
                    <th class="p-4 text-center">Mapel</th>
                    <th class="p-4 text-center">Jabatan</th>
                    <th class="p-4 text-center">No HP</th>
                    <th class="p-4 text-center">Email</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($teachers as $teacher)

                <tr id="row-{{ $teacher->slug }}" class="border-b hover:bg-gray-50">

                    <!-- NAME -->
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-500 text-white flex items-center justify-center font-bold shrink-0">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-gray-700">{{ $teacher->name }}</span>
                        </div>
                    </td>

                    <!-- NIP -->
                    <td class="p-4 text-center">
                        {{ $teacher->nip ?? '-' }}
                    </td>

                    <!-- SUBJECT -->
                    <td class="p-4 text-center">
                        @if($teacher->subjects->isNotEmpty())
                            {{ $teacher->subjects->pluck('name')->join(', ') }}
                        @else
                            -
                        @endif
                    </td>

                    <!-- POSITION -->
                    <td class="p-4 text-center">
                        {{ ucfirst($teacher->position ?? '-') }}
                    </td>

                    <!-- PHONE -->
                    <td class="p-4 text-center">
                        {{ $teacher->phone ?? '-' }}
                    </td>

                    <!-- EMAIL -->
                    <td class="p-4 text-center">
                        {{ $teacher->user->email ?? '-' }}
                    </td>

                    <!-- STATUS -->
                    <td class="p-4 text-center">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Archived
                        </span>
                    </td>

                    <!-- ACTION -->
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">

                            <!-- RESTORE -->
                            <button
                                class="btn-restore bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
                                data-slug="{{ $teacher->slug }}"
                                data-url="{{ route('teacher.restore', $teacher->slug) }}">
                                Restore
                            </button>

                            <!-- HAPUS PERMANEN -->
                            <button
                                class="btn-force-delete bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                                data-slug="{{ $teacher->slug }}"
                                data-url="{{ route('teacher.forceDelete', $teacher->slug) }}">
                                Hapus Permanen
                            </button>

                        </div>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center p-6 text-gray-500">
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
        document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-restore');
        if (!btn) return;

        const id  = btn.dataset.id;
        const url = btn.dataset.url;

        if (typeof restoreData === 'undefined') {
            console.error('restoreData belum tersedia');
            return;
        }

        restoreData(
            url,
            'Restore data ini?',
            {
                onSuccess: function () {
                    document.getElementById('row-' + id)?.remove();
                }
            }
        );
    });

        // =====================
        // HAPUS PERMANEN
        // =====================
        let btnDelete = e.target.closest('.btn-force-delete');
        if (btnDelete) {

            let url = btnDelete.dataset.url;
            let slug = btnDelete.dataset.slug;
            let row = document.getElementById('row-' + slug);

            if (!confirm('Hapus permanen data guru ini? Tindakan ini tidak bisa dibatalkan!')) return;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (row) row.remove();

                    document.getElementById('alertBox').innerHTML = `
                        <div class="p-3 bg-red-100 text-red-700 rounded-lg">
                            Guru berhasil dihapus permanen
                        </div>
                    `;

                    setTimeout(() => {
                        document.getElementById('alertBox').innerHTML = '';
                    }, 3000);
                }
            })
            .catch(err => console.error(err));

        }

    });

});
</script>
@endpush