@extends('layouts.app')

@section('title', 'Archived Schedule')

@section('content')

<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">

            <li>
                <span class="text-gray-700 font-medium">
                    Dashboard
                </span>
            </li>

            <li>/</li>

            <li>
                <a href="{{ route('schedule.index') }}"
                   class="hover:text-blue-600">
                    Jadwal
                </a>
            </li>

            <li>/</li>

            <li class="text-gray-700 font-medium">
                Arsip Jadwal
            </li>

        </ol>
    </nav>

    <!-- HEADER -->
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold">
                Arsip Jadwal
            </h1>

            <p class="text-gray-500 text-sm">
                Data jadwal yang telah diarsipkan
            </p>
        </div>

        <a href="{{ route('schedule.index') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Kembali
        </a>

    </div>

    <!-- ALERT -->
    <div id="alertBox"></div>

    <!-- TABLE -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4">Hari</th>
                    <th class="p-4">Jam</th>
                    <th class="p-4">Kelas</th>
                    <th class="p-4">Guru</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($schedules as $schedule)

                <tr id="row-{{ $schedule->id }}">

                    <td class="p-4">
                        {{ $schedule->day }}
                    </td>

                    <td class="p-4">
                        {{ $schedule->start_time }} - {{ $schedule->end_time }}
                    </td>

                    <td class="p-4">
                        {{ $schedule->class->name ?? '-' }}
                    </td>

                    <td class="p-4">
                        {{ $schedule->teacher->name ?? '-' }}
                    </td>

                    <td class="p-4">

                        <div class="flex gap-3">

                            <!-- Restore -->
                            <form action="{{ route('schedule.restore', $schedule->id) }}"
                                  method="POST"
                                  class="restoreForm inline"
                                  data-id="{{ $schedule->id }}">

                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                        class="text-green-600 hover:underline">
                                    Restore
                                </button>

                            </form>

                            <!-- Delete -->
                            <form action="{{ route('schedule.delete', $schedule->id) }}"
                                  method="POST"
                                  class="deleteForm inline"
                                  data-id="{{ $schedule->id }}">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="text-red-600 hover:underline">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5"
                        class="text-center p-6 text-gray-500">
                        Tidak ada data archive
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
document.addEventListener('DOMContentLoaded', function () {

    // RESTORE
    const restoreForms = document.querySelectorAll('.restoreForm');

    restoreForms.forEach(form => {
        form.addEventListener('submit', function(e) {

            e.preventDefault();
            e.stopPropagation();

            if (!confirm('Restore data ini?')) return;

            const url = this.action;
            const id = this.dataset.id;
            const row = document.getElementById('row-' + id);

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(res => {

                if (res.success) {

                    if (row) row.remove();

                    document.getElementById('alertBox').innerHTML = `
                        <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                            ${res.message}
                        </div>
                    `;

                } else {
                    throw new Error('Restore gagal');
                }

            })
            .catch(error => {

                console.error(error);

                document.getElementById('alertBox').innerHTML = `
                    <div class="p-3 bg-red-100 text-red-700 rounded-lg">
                        Restore gagal
                    </div>
                `;
            });

        });
    });


    // DELETE
    const deleteForms = document.querySelectorAll('.deleteForm');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {

            e.preventDefault();
            e.stopPropagation();

            if (!confirm('Hapus permanen data ini?')) return;

            const url = this.action;
            const id = this.dataset.id;
            const row = document.getElementById('row-' + id);

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(res => {

                if (res.success) {

                    if (row) row.remove();

                    document.getElementById('alertBox').innerHTML = `
                        <div class="p-3 bg-red-100 text-red-700 rounded-lg">
                            ${res.message}
                        </div>
                    `;

                } else {
                    throw new Error('Delete gagal');
                }

            })
            .catch(error => {

                console.error(error);

                document.getElementById('alertBox').innerHTML = `
                    <div class="p-3 bg-red-100 text-red-700 rounded-lg">
                        Delete gagal
                    </div>
                `;
            });

        });
    });

});
</script>
@endpush