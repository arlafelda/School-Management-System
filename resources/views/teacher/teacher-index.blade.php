@extends('layouts.app')

@section('title', 'Teachers Management')

@section('content')

<div class="space-y-6">

    <!-- ALERT -->
    <div id="alertBox"></div>

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Daftar Guru</h1>
            <p class="text-gray-500 text-sm">Kelola data guru</p>
        </div>

        <a href="{{ route('teacher.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            + Tambah Guru
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
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($teachers as $teacher)

                <tr id="row-{{ $teacher->id }}"
                    class="hover:bg-gray-50 cursor-pointer"
                    data-url="{{ route('teacher.show', $teacher->id) }}">

                    <!-- NAME -->
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

                    <!-- ACTION -->
                    <td class="p-4 text-center space-x-2">

                        <a href="{{ route('teacher.edit', $teacher->id) }}"
                           class="text-blue-600 text-sm"
                           onclick="event.stopPropagation()">
                            Edit
                        </a>

                        <button
                            class="text-red-500 text-sm btn-delete"
                            data-id="{{ $teacher->id }}"
                            data-url="{{ route('teacher.delete', $teacher->id) }}"
                            onclick="event.stopPropagation()">
                            Hapus
                        </button>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection


@push('scripts')

<script>
/* =========================
   CLICK ROW DETAIL
========================= */
$(document).on('click', 'tr[data-url]', function () {
    window.location = $(this).data('url');
});


/* =========================
   DELETE AJAX (PAKAI ajax.js)
========================= */
$(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();

    let url = $(this).data('url');
    let id = $(this).data('id');
    let row = $('#row-' + id);

    if (!confirm('Yakin ingin menghapus data ini?')) return;

    deleteData(url, function () {

        row.remove();

        $('#alertBox').html(`
            <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                Data berhasil dihapus
            </div>
        `);

    });
});
</script>

@endpush