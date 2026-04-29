@extends('layouts.app')

@section('title', 'Students Management')

@section('content')

<div class="space-y-6">

    <!-- ALERT AJAX -->
    <div id="alertBox"></div>

    <!-- SUCCESS SESSION -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <h1 class="text-2xl font-bold">Daftar Siswa</h1>

        <a href="{{ route('students.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow">
            + Tambah Siswa
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow border overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-4 text-left">Siswa</th>
                    <th class="p-4 text-center">NISN</th>
                    <th class="p-4 text-center">NIS</th>
                    <th class="p-4 text-center">Kelas</th>
                    <th class="p-4 text-center">Jurusan</th>
                    <th class="p-4 text-center">No HP</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($students as $student)
                <tr id="row-{{ $student->id }}" class="hover:bg-gray-50">

                    <td class="p-4">
                        <a href="{{ route('students.show', $student->id) }}"
                           class="flex items-center gap-3">

                            <div class="w-10 h-10 bg-green-600 text-white flex items-center justify-center rounded-full text-sm font-bold">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>

                            <span class="font-semibold">
                                {{ $student->name }}
                            </span>
                        </a>
                    </td>

                    <td class="p-4 text-center">{{ $student->nisn }}</td>
                    <td class="p-4 text-center">{{ $student->nis }}</td>
                    <td class="p-4 text-center">{{ $student->class->name ?? '-' }}</td>
                    <td class="p-4 text-center">{{ $student->major }}</td>
                    <td class="p-4 text-center">{{ $student->phone }}</td>

                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $student->status == 'aktif' ? 'bg-green-100 text-green-700' :
                           ($student->status == 'lulus' ? 'bg-blue-100 text-blue-700' :
                           'bg-red-100 text-red-700') }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </td>

                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-3">

                            <a href="{{ route('students.edit', $student->id) }}"
                               class="text-blue-600 text-sm">
                                Edit
                            </a>

                            <!-- AJAX DELETE (PAKAI ajax.js) -->
                            <form class="formDelete"
                                  action="{{ route('students.delete', $student->id) }}"
                                  method="POST"
                                  onclick="event.stopPropagation()">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="text-red-500 text-sm">
                                    Hapus
                                </button>

                            </form>

                        </div>
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
   AJAX DELETE (PAKAI ajax.js)
========================= */
$('.formDelete').on('submit', function (e) {
    e.preventDefault();

    let url = this.action;
    let row = $(this).closest('tr');

    deleteData(url, function () {

        row.remove();

        $('#alertBox').html(`
            <div class="mb-4 bg-green-100 text-green-700 p-3 rounded-lg">
                Data berhasil dihapus
            </div>
        `);

    });
});
</script>

@endpush