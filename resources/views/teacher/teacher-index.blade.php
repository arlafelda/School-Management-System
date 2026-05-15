@extends('layouts.app')

@section('title', 'Teachers Management')

@section('content')

<div class="space-y-6">

    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li>
                <span class="text-gray-700 font-medium">Dashboard</span>
            </li>
            <li>/</li>
            <li class="text-gray-700 font-medium">Guru</li>
        </ol>
    </nav>

    <div id="alertBox"></div>

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold">Daftar Guru</h1>
            <p class="text-gray-500 text-sm">Kelola data guru</p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('teacher.archived') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                Arsip
            </a>

            <a href="{{ route('teacher.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Guru
            </a>

        </div>

    </div>

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
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($teachers as $teacher)

                <tr id="row-{{ $teacher->id }}"
                    class="hover:bg-gray-50 cursor-pointer"
                    data-url="{{ route('teacher.show', $teacher->slug) }}">

                    <td class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>

                        <div class="font-semibold">
                            {{ $teacher->name }}
                        </div>
                    </td>

                    <td class="p-4 text-center">{{ $teacher->nip }}</td>
                    <td class="p-4 text-center">{{ $teacher->subject }}</td>

                    <td class="p-4 text-center">
                        @if($teacher->position == 'wali_kelas')
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                Wali Kelas
                            </span>
                        @else
                            <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded text-xs">
                                Guru
                            </span>
                        @endif
                    </td>

                    <td class="p-4 text-center">{{ $teacher->phone }}</td>
                    <td class="p-4 text-center">{{ $teacher->user->email ?? '-' }}</td>

                    <td class="p-4 text-center space-x-2">

                        <a href="{{ route('teacher.edit', $teacher->slug) }}"
                           class="text-blue-600 text-sm"
                           onclick="event.stopPropagation()">
                            Edit
                        </a>

                        <button
                            class="text-red-500 text-sm btn-delete"
                            data-id="{{ $teacher->id }}"
                            data-url="{{ route('teacher.delete', $teacher->slug) }}"
                            onclick="event.stopPropagation()">
                            Arsipkan
                        </button>

                    </td>

                </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center p-6 text-gray-500">
                            Data guru tidak tersedia
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
        let row = e.target.closest('tr[data-url]');

        if (row) {
            let url = row.dataset.url;
            if (url) {
                window.location.href = url;
            }
        }
    });


    document.addEventListener('click', function (e) {

        let btn = e.target.closest('.btn-delete');
        if (!btn) return;

        e.preventDefault();

        let url = btn.dataset.url;
        let id = btn.dataset.id;
        let row = document.getElementById('row-' + id);

        if (!confirm('Yakin ingin memindahkan data ke arsip?')) {
            return;
        }

        if (typeof deleteData !== 'undefined') {

            deleteData(url, function () {

                if (row) row.remove();

                let alertBox = document.getElementById('alertBox');

                alertBox.innerHTML = `
                    <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                        Guru berhasil dipindahkan ke arsip
                    </div>
                `;

                setTimeout(() => {
                    alertBox.innerHTML = '';
                }, 3000);

            });

        } else {
            console.error('deleteData belum tersedia');
        }

    });

});
</script>
@endpush