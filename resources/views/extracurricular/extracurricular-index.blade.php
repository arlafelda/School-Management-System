@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h1 class="text-xl font-bold text-blue-700">
                Ekstrakurikuler
            </h1>
            <p class="text-sm text-gray-500">
                Kelola data ekskul
            </p>
        </div>

        <a href="{{ route('extracurricular.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            + Tambah
        </a>

    </header>

    <!-- CONTENT -->
    <main class="p-6">

        <!-- ✅ ALERT -->
        <div id="alertBox" class="mb-4"></div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Nama Ekskul</th>
                        <th class="p-3 text-center">Pembina</th>
                        <th class="p-3 text-center">Jumlah Siswa</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($data as $d)

                        <tr
                            id="row-{{ $d->id }}"
                            class="border-t hover:bg-gray-50 cursor-pointer"
                            data-url="{{ route('extracurricular.show', $d->id) }}"
                        >

                            <!-- NAMA -->
                            <td class="p-3 font-medium">
                                {{ $d->name }}
                            </td>

                            <!-- PEMBINA -->
                            <td class="p-3 text-center">
                                {{ $d->teacher->name ?? '-' }}
                            </td>

                            <!-- JUMLAH SISWA -->
                            <td class="p-3 text-center">
                                {{ $d->students->count() }}
                            </td>

                            <!-- AKSI -->
                            <td class="p-3 text-center space-x-2">

                                <a href="{{ route('extracurricular.edit', $d->id) }}"
                                   onclick="event.stopPropagation()"
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">
                                    Edit
                                </a>

                                <!-- ✅ DELETE AJAX -->
                                <form action="{{ route('extracurricular.destroy', $d->id) }}"
                                      method="POST"
                                      class="inline formDelete"
                                      data-id="{{ $d->id }}"
                                      onclick="event.stopPropagation()">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400">
                                Tidak ada data ekstrakurikuler
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </main>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ✅ cek jQuery
    if (typeof window.$ === 'undefined') {
        console.error('jQuery belum load');
        return;
    }

    // =========================
    // CLICK ROW → DETAIL
    // =========================
    $(document).on('click', 'tr[data-url]', function (e) {

        if (
            e.target.closest('a') ||
            e.target.closest('button') ||
            e.target.closest('form')
        ) return;

        window.location.href = $(this).data('url');
    });

    // =========================
    // DELETE AJAX
    // =========================
    $(document).on('submit', '.formDelete', function (e) {
        e.preventDefault();

        let url = this.action;
        let id = $(this).data('id');
        let row = $('#row-' + id);

        // cek function ajax
        if (typeof window.deleteData === 'function') {

            window.deleteData(url, function () {

                row.remove();

                $('#alertBox').html(`
                    <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                        Data berhasil dihapus
                    </div>
                `);

            });

        } else {
            console.error('deleteData belum tersedia (ajax.js belum ke-load)');
        }

    });

});
</script>
@endpush