@extends('layouts.app')

@section('title', 'Archived Classes')

@section('content')

@php
    $user = auth()->user();
@endphp

<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('class.index') }}" class="text-blue-600 hover:underline">
                    Kelas
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
            <h1 class="text-2xl font-bold">Kelas Diarsipkan</h1>
            <p class="text-sm text-gray-500">Daftar kelas yang telah diarsipkan</p>
        </div>

        <a href="{{ route('class.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            ← Kembali
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow border overflow-x-auto">

        <table class="w-full text-sm min-w-[800px]">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-4 text-left">Nama Kelas</th>
                    <th class="p-4 text-center">Tingkat</th>
                    <th class="p-4 text-center">Jurusan</th>
                    <th class="p-4 text-left">Wali Kelas</th>
                    <th class="p-4 text-center">Jumlah Siswa</th>
                    <th class="p-4 text-center">Status</th>

                    @if(in_array($user->role, ['super_admin', 'admin']))
                        <th class="p-4 text-center">Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>

                @forelse($classes as $class)

                <tr id="row-{{ $class->slug }}" class="border-b hover:bg-gray-50">

                    <td class="p-4 font-semibold text-gray-700">
                        {{ $class->name }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $class->level }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $class->major ?? '-' }}
                    </td>

                    <td class="p-4">
                        {{ $class->teacher->name ?? 'Belum ada wali kelas' }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $class->students->count() }}
                    </td>

                    <td class="p-4 text-center">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Archived
                        </span>
                    </td>

                    @if(in_array($user->role, ['super_admin', 'admin']))
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">

                            <!-- RESTORE -->
                            <button
                                class="btn-restore bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
                                data-slug="{{ $class->slug }}"
                                data-url="{{ route('class.restore', $class->slug) }}">
                                Restore
                            </button>

                            <!-- HAPUS PERMANEN -->
                            <button
                                class="btn-force-delete bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                                data-slug="{{ $class->slug }}"
                                data-url="{{ route('class.forceDelete', $class->slug) }}">
                                Hapus Permanen
                            </button>

                        </div>
                    </td>
                    @endif

                </tr>

                @empty
                <tr>
                    <td colspan="7" class="text-center p-6 text-gray-500">
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
            let row = document.getElementById('row-' + slug);

            if (!confirm('Restore kelas ini?')) return;

            fetch(url, {
                method: 'PUT',
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
                        <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                            ${data.message}
                        </div>
                    `;

                    setTimeout(() => {
                        document.getElementById('alertBox').innerHTML = '';
                    }, 3000);
                }
            })
            .catch(err => console.error(err));

            return;
        }

        // =====================
        // HAPUS PERMANEN
        // =====================
        let btnDelete = e.target.closest('.btn-force-delete');
        if (btnDelete) {

            let url = btnDelete.dataset.url;
            let slug = btnDelete.dataset.slug;
            let row = document.getElementById('row-' + slug);

            if (!confirm('Hapus permanen kelas ini? Tindakan ini tidak bisa dibatalkan!')) return;

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
                            ${data.message}
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