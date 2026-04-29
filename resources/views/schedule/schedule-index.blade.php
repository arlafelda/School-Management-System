@extends('layouts.app')

@section('title', 'Kelola Jadwal')

@section('content')

<div class="space-y-6">

    <!-- ALERT -->
    <div id="alertBox"></div>

    <!-- HEADER -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold">Kelola Jadwal</h1>
            <p class="text-gray-500 text-sm">Manajemen jadwal pelajaran sekolah</p>
        </div>

        <a href="{{ route('schedule.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Jadwal
        </a>
    </div>

    <!-- FILTER -->
    <div class="bg-white p-4 rounded-lg shadow">

        <form method="GET" class="flex flex-wrap gap-3 items-center">

            <select name="class_id"
                class="border rounded-lg px-3 py-2 text-sm"
                onchange="this.form.submit()">

                <option value="">Semua Kelas</option>

                @foreach($classes as $class)
                    <option value="{{ $class->id }}"
                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach

            </select>

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari jadwal..."
                class="border rounded-lg px-3 py-2 text-sm w-64">

            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                🔍 Cari
            </button>

            <a href="{{ route('schedule.index') }}"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                Reset
            </a>

        </form>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Hari</th>
                    <th class="p-4 text-center">Jam</th>
                    <th class="p-4 text-center">Kelas</th>
                    <th class="p-4 text-center">Jurusan</th>
                    <th class="p-4 text-center">Mapel</th>
                    <th class="p-4 text-center">Guru</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($schedules as $schedule)

                <!-- ROW BISA DIKLIK -->
                <tr data-url="{{ route('schedule.show', $schedule->id) }}"
                    class="schedule-row hover:bg-gray-50 transition cursor-pointer">

                    <td class="p-4 font-semibold text-blue-600">
                        {{ $schedule->day }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $schedule->start_time }} - {{ $schedule->end_time }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $schedule->class->name ?? '-' }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $schedule->class->major ?? '-' }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $schedule->teacher->subject ?? '-' }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $schedule->teacher->name ?? '-' }}
                    </td>

                    <!-- ACTION -->
                    <td class="p-4 text-center space-x-2">

                        <!-- EDIT -->
                        <a href="{{ route('schedule.edit', $schedule->id) }}"
                           onclick="event.stopPropagation()"
                           class="text-blue-600 hover:underline">
                            Edit
                        </a>

                        <!-- DELETE -->
                        <form action="{{ route('schedule.delete', $schedule->id) }}"
                              method="POST"
                              class="inline formDelete"
                              onclick="event.stopPropagation()">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-red-500 hover:underline">
                                Hapus
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center p-4 text-gray-500">
                        Data jadwal belum tersedia
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
$(document).ready(function () {

    // =====================
    // CLICK ROW → DETAIL
    // =====================
    $('.schedule-row').on('click', function () {
        let url = $(this).data('url');
        window.location.href = url;
    });

    // =====================
    // DELETE AJAX
    // =====================
    $('.formDelete').on('submit', function (e) {
        e.preventDefault();

        let url = this.action;
        let row = $(this).closest('tr');

        deleteData(url, function () {

            row.remove();

            $('#alertBox').html(`
                <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                    Data jadwal berhasil dihapus
                </div>
            `);

        });
    });

});
</script>

@endpush